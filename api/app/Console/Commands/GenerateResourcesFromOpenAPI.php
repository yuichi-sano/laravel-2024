<?php

namespace App\Console\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Facades\File;
use Symfony\Component\Yaml\Yaml;
use OpenApi\Generator;

class GenerateResourcesFromOpenAPI extends GeneratorCommand
{
    protected $signature = 'generate:resources {path_to_openapi} {--tag=} {--force} {--withC}';
    protected $description = 'Generate FormRequest, ValueObject, and Resource from OpenAPI specification';

    protected array $openApiArr = [];
    protected array $controllers = [];

    /**
     * @return void
     */
    private function confirmation(): void
    {
        $confirmContents = "強制的にRequest,Resource群が書き換わりますがよろしいですか？";

        if ($this->hasOption('withC') && $this->option('withC')) {
            $confirmContents = '強制的にコントローラとRequest,Resource群が書き換わりますがよろしいですか？';
        }

        if (($this->hasOption('force') && $this->option('force')) &&
            !$this->confirm($confirmContents)
        ) {
            $this->info('stop the create');
            return;
        }
        if (
            ($this->hasOption('force') && $this->option('force')) &&
            !$this->confirm('本当によろしいですか？')
        ) {
            $this->info('stop the create');
        }
    }

    /**
     * yamlまたはjsonファイルからopenapiを解析
     * @return void
     */
    private function parseFile(): void
    {
        $file = $this->argument('path_to_openapi');
        $ext = substr($file, strrpos($file, '.') + 1);
        $pathToOpenAPI = resource_path() . '/openapi/'. $file;
        if($ext === 'json' || $ext === 'JSON'){
            $resource = file_get_contents($pathToOpenAPI);
            $json = mb_convert_encoding($resource, 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN');
            $this->openApiArr = json_decode($json, true);
        }else{
            $this->openApiArr = Yaml::parseFile($pathToOpenAPI);
        }
    }

    /**
     * @return void
     */
    public function handle()
    {
        $this->confirmation();
        $this->parseFile();
        $apiPaths = $this->openApiArr['paths'];

        foreach ($apiPaths as $uri => $apiEndPath) {
            $controllers = [];

            foreach ($apiEndPath as $method => $apiEnd) {
                $tag = end($apiEnd['tags']);
                if ($this->option('tag') && $tag != $this->option("tag")) {
                    continue;
                }
                //$controllers[$tag][$method]['security'] = $apiEnd['security'];
                //$controllers[$tag][$method]['description'] = $apiEnd['description'];

                if (array_key_exists("requestBody", $apiEnd)) {
                    $request = end($apiEnd['requestBody']['content']);
                    $uriName = ltrim(str_replace('/', '_', $uri), '_');
                    $requestName = $this->pascalize($uriName).$this->pascalize($method);
                    if (array_key_exists("schema", $request)) {
                        if (array_key_exists('$ref', $request['schema'])) {
                            $schemaName = $this->getDefinitionName($request['schema']['$ref']);
                            $schema = $this->openApiArr['components']['schemas'][$schemaName];
                            $requestVO = $schemaName;
                            //$this->makeDefinition($schemaName, $tag);
                        }else{
                            $schema = $request['schema'];
                            $requestVO = $schemaName = $requestName;
                        }
                        $this->makeDefinition($schemaName, $tag, $schema);
                        $ruleStr = $this->generateValidationRules($schema['properties'],$schema['required']);
                        $controllers[$tag][$method]['request'] = $this->makeRequest($requestName, $tag, $ruleStr, $requestVO);
                    }
                }elseif (array_key_exists('parameters', $apiEnd)){
                    $request = $apiEnd['parameters'];
                    $uriName = ltrim(str_replace('/', '_', $uri), '_');
                    $requestName = $this->pascalize($uriName).$this->pascalize($method);
                    $requestVO = $schemaName = $requestName;
                    $schema['properties'] = [];
                    $schema['required'] = [];
                    foreach ($request as $unit){
                        $schema['properties'][$unit['name']]['type'] = $unit['schema']['type'];
                        if(isset($unit['required']) && $unit['required']){
                            $schema['required'][] = $unit['name'];
                        }
                    }
                    $this->makeDefinition($schemaName, $tag, $schema);
                    $ruleStr = $this->generateValidationRules($schema['properties'],$schema['required']);
                    $controllers[$tag][$method]['request'] = $this->makeRequest($requestName, $tag, $ruleStr, $requestVO);
                }

                if (!array_key_exists("content", $apiEnd['responses']['200'])) {
                    continue;
                }
                $resultResource = end($apiEnd['responses']['200']['content']);
                if (array_key_exists("schema", $resultResource)) {
                    $uriName = ltrim(str_replace('/', '_', $uri), '_');
                    $resourceName = $this->pascalize($uriName).$this->pascalize($method);
                    if (array_key_exists('$ref', $resultResource['schema'])) {
                        $schemaName = $this->getDefinitionName($resultResource['schema']['$ref']);
                        $schema = $this->openApiArr['components']['schemas'][$schemaName];
                    }else{
                        $schema = $resultResource['schema'];
                    }
                    $resourceFields = $this->generateResourceFields($schema['properties']);
                    $controllers[$tag][$method]['resource'] = $this->makeResource($resourceName, $tag, $resourceFields);
                }
            }
            if (!empty($controllers) && $this->hasOption('withC') && $this->option('withC')) {
                $this->makeControllers($uri, $controllers);
            }
        }


        $this->info('FormRequest, ValueObject, and Resource created successfully.');
    }



    /**
     * cliとapiのIF定義生成
     * @param $name
     * @param $tag
     * @param $resourceType
     * @return string
     */
    public function makeDefinition($name, $tag, $properties = null): string
    {
        $resourceType = 'valueObject';
        if (str_contains($name, 'Abstract')) {
            echo 'Abstractと命名されたものは基底クラスとみなしここでは作成しません';
            return '';
        }
        $className = $this->qualifyClassCustom($name, $resourceType, $tag);
        $path = $this->getPath($className);
        if ((!$this->hasOption('force') ||
                !$this->option('force')) &&
            $this->alreadyExists($className) &&
            !$this->confirm($className . 'は既に存在しますが強制的に上書ますか？')
        ) {
            $this->error($className . ' already exists!');
            return $className;
        }
        $this->makeDirectory($path);
        $this->files->put($path, $this->buildClassOne($className, $name, $tag, $properties));
        $this->info($this->type . ' created successfully.');
        return $className;
    }


    public function makeRequest($name, $tag, $rule, $requestVO): string
    {
        if (strstr($name, 'Abstract')) {
            echo 'Abstractと命名されたものは基底クラスとみなしここでは作成しません';
            return '';
        }
        $className = $this->qualifyClassCustom($name, 'request', $tag);
        $path = $this->getPath($className);
        if ((!$this->hasOption('force') ||
                !$this->option('force')) &&
            $this->alreadyExists($className) &&
            !$this->confirm($className . 'は既に存在しますが強制的に上書ますか？')
        ) {
            $this->error($className . ' already exists!');
            return $className;
        }
        $this->makeDirectory($path);
        $this->files->put($path, $this->buildRequest($className, $rule, $tag, $requestVO));
        $this->info($this->type . ' created successfully.');
        return $className;
    }

    protected function buildRequest(string $name, $rule, $tag, $valueObject): string
    {
        $stub = $this->files->get($this->getStubCustom('request'));
        $vo = $this->getClassName($this->qualifyClassCustom($valueObject,'valueObject',$tag));
        $this->replaceRequest($stub, $rule, $vo, $tag);
        return $this->replaceNamespace($stub, $name)->replaceClass($stub, $name);
    }


    /**
     * cliとapiのIF定義生成
     * @param $name
     * @param $tag
     * @param $resourceType
     * @return string
     */
    public function makeResource($name, $tag, $fields): string
    {
        if (str_contains($name, 'Abstract')) {
            echo 'Abstractと命名されたものは基底クラスとみなしここでは作成しません';
            return '';
        }
        $className = $this->qualifyClassCustom($name, 'resultResource', $tag);
        $path = $this->getPath($className);
        if ((!$this->hasOption('force') ||
                !$this->option('force')) &&
            $this->alreadyExists($className) &&
            !$this->confirm($className . 'は既に存在しますが強制的に上書ますか？')
        ) {
            $this->error($className . ' already exists!');
            return $className;
        }
        $this->makeDirectory($path);
        $this->files->put($path, $this->buildResource($className, $fields, $tag));
        $this->info($this->type . ' created successfully.');
        return $className;
    }

    protected function buildResource(string $name, $fields, $tag): string
    {
        $stub = $this->files->get($this->getStubCustom('resultResource'));
        $this->replaceResource($stub, $fields, $tag);
        return $this->replaceNamespace($stub, $name)->replaceClass($stub, $name);
    }



    protected function generateValidationRules($properties, $requiredParams = []): string
    {
        $rules = [];

        foreach ($properties as $property => $details) {
            if(in_array($property, $requiredParams)) {
                $rule = "'{$property}' => 'required"; // ここでバリデーションルールを追加（例: 必須項目）
            }else{
                $rule = "'{$property}' => 'nullable";
            }

            // プロパティの型に応じてルールを追加

            if (array_key_exists('$ref', $details)) {
                $ref = $this->getDefinitionName($details['$ref']);
                $required = [];
                if (array_key_exists('required', $this->openApiArr['components']['schemas'][$ref])) {
                    $required = $this->openApiArr['components']['schemas'][$ref]['required'];
                }
                $nestedRules = $this->generateValidationRules(
                    $this->openApiArr['components']['schemas'][$ref]['properties'],
                    $required
                );
                $rule .= "|'.\Illuminate\Validation\Rule::array([{$nestedRules}])";
                $rule .= "";
                $rules[] = $rule;
                continue;
            }
            if ($details['type'] === 'array' &&  array_key_exists('$ref', $details['items'])) {
                $ref = $this->getDefinitionName($details['items']['$ref']);
                $required = [];
                if (array_key_exists('required', $this->openApiArr['components']['schemas'][$ref])) {
                    $required = $this->openApiArr['components']['schemas'][$ref]['required'];
                }
                $nestedRules = $this->generateValidationRules(
                    $this->openApiArr['components']['schemas'][$ref]['properties'],
                    $required
                );
                $rule .= "|array|'.\Illuminate\Validation\Rule::array([{$nestedRules}])";
                $rule .= "";
                $rules[] = $rule;
                continue;
            }
            if ($details['type'] === 'string') {
                $rule .= "|string|max:255";
            } elseif ($details['type'] === 'integer') {
                $rule .= "|integer";
            } elseif ($details['type'] === 'boolean') {
                $rule .= "|boolean";
            } elseif ($details['type'] === 'object' && isset($details['properties'])) {
                $nestedRules = $this->generateValidationRules($details['properties'],$details['required']);
                $rule .= "|\Illuminate\Validation\Rule::array({$nestedRules})";
            } elseif ($details['type'] === 'array' && $details['items']['type'] === 'object' && isset($details['items']['properties'])) {
                $nestedRules = $this->generateValidationRules($details['items']['properties'],$details['items']['required']);
                $rule .= "|array|\Illuminate\Validation\Rule::array({$nestedRules})";
            }

            $rule .= "'";
            $rules[] = $rule;
        }

        return implode(",\n            ", $rules);
    }


    protected function generateResourceFields($properties): string
    {
        $fields = '';

        foreach ($properties as $property => $details) {
            $fields .= "'{$property}' => \$this->{$property},\n            ";
        }

        return $fields;
    }

    /**
     * namespace付クラス名からnamespaceを排除して返却
     * @param $fullName
     * @return string
     */
    protected function getClassName($fullName): string
    {
        $classParts = explode('\\', $fullName);
        return end($classParts);
    }


    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub(): string
    {
        return __DIR__ . '/stubs/';
    }

    protected function getStubCustom($resourceType): string
    {
        $stubName = '';
        if ($resourceType == 'request') {
            $stubName = 'form-request.stub';
        } elseif ($resourceType == 'valueObject') {
            $stubName = 'value-object.stub';
        } elseif ($resourceType == 'resultResource') {
            $stubName = 'resource.stub';
        } elseif ($resourceType == 'controller') {
            $stubName = 'controller.stub';
        }
        return $this->getStub() . $stubName;
    }

    /**
     * Get the default namespace for the class.
     *
     * @param string $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace): string
    {
        return $rootNamespace . '\Http';
    }

    /**
     * Parse the class name and format according to the root namespace.
     *
     * @param string $name
     * @return string
     */
    protected function qualifyClassCustom(string $name, $resourceType, $tag): string
    {
        if ($resourceType == 'request') {
            $customName = 'Requests/' . $tag . '/' . $name . 'Request';
        } elseif ($resourceType == 'valueObject') {
            $customName = 'Requests/ValueObjects/' . $tag . '/' . $name . 'ValueObject';
        } elseif ($resourceType == 'resultResource') {
            $customName = 'Resources/' . $tag . '/' . $name . 'Resource';
        } elseif ($resourceType == 'controller') {
            $customName = 'Controllers/' . $tag . '/' . $name . 'Controller';
        }
        return $this->qualifyClass($customName);
    }

    public function getDefinitionName($name): string
    {
        return str_replace('#/components/schemas/', '', $name);
    }


    public function childGen(array $children, $name, $tag)
    {
        $className = $this->qualifyClassCustom($name, 'valueObject', $tag);
        //$name = $this->qualifyClass($this->getNameInput());
        $path = $this->getPath($className);
        if ((!$this->hasOption('force') ||
                !$this->option('force')) &&
            $this->alreadyExists($className) &&
            !$this->confirm($className . 'は既に存在しますが強制的に上書ますか？')
        ) {
            $this->error($className . ' already exists!');
            return false;
        }
        $this->makeDirectory($path);
        $this->files->put($path, $this->buildClassOne($className, $name, $tag, $children));
        $this->info($this->type . ' created children successfully.');
    }

    /**
     * Build the class with the given name.
     *
     * @param string $name
     * @return string
     */
    protected function buildClassOne(string $name, $planeName, $tag, array $children = null): string
    {
        $replaceProperties = [];
        $getters = [];
        $stub = $this->files->get($this->getStubCustom('valueObject'));
        $swaggerArray = $children;
        if (empty($children)) {
            $swaggerArray = $this->openApiArr['components']['schemas'][$planeName];
            if (!array_key_exists('properties', $swaggerArray)) {
                echo '$swaggerArray';
            }
        }
        foreach ($swaggerArray['properties'] as $propertyName => $property) {
            if (array_key_exists('$ref', $property)) {

                $schemaName = $this->getDefinitionName($property['$ref']);
                $child = $this->openApiArr['components']['schemas'][$schemaName];
                $childName = $planeName . self::pascalize($propertyName);
                $this->childGen($child, $childName, $tag);
                $propertyTypeName = $planeName . self::pascalize($propertyName);

                $fullClassName = $this->qualifyClassCustom($propertyTypeName, 'valueObject', $tag);
                $propertyType = $this->getClassName($fullClassName);
                $description = $property['description'] ?? '';
                $replaceProperties[] = $this->getPropertyStr($propertyName, $propertyType, $description);
                $getters[] = $this->getGetterMethodStr($propertyName);
                continue;
            }

            $propertyType = $property['type'];
            if (
                $property['type'] == 'object' ||
                (
                    $property['type'] == 'array'
                    && (array_key_exists('$ref', $property['items']) || $property['items']['type'] == 'object')
                )
            ) {

                $propertyTypeName = $planeName . self::pascalize($propertyName);
                $fullClassName = $this->qualifyClassCustom($propertyTypeName, 'valueObject', $tag);
                $propertyType = $this->getClassName($fullClassName);
            }
            $description = $property['description'] ?? '';

            $replaceProperties[] = $this->getPropertyStr($propertyName, $propertyType, $description);


            $getters[] = $this->getGetterMethodStr($propertyName);
            if ($property['type'] == 'object') {
                $childName = $planeName . self::pascalize($propertyName);
                if (array_key_exists('properties', $property) && $property['properties'] != '{}') {
                    $this->childGen($property, $childName, $tag);
                }
            } elseif ($property['type'] == 'array') {
                $childName = $planeName . self::pascalize($propertyName);

                if (array_key_exists('$ref', $property['items'])) {
                    $schemaName = $this->getDefinitionName($property['items']['$ref']);
                    $child = $this->openApiArr['components']['schemas'][$schemaName];
                }else{
                    $child = $property['items'];
                }
                $this->childGen($child, $childName, $tag);
            }
        }
        $this->replaceValueObject(
            $stub,
            implode("\n", $replaceProperties),
            implode("\n", $getters),
            $tag);
        return $this->replaceNamespace($stub, $name)->replaceClass($stub, $name);
    }

    protected function getPropertyStr($name, $type, $description): string
    {

        $description = str_replace(array("\r\n", "\r", "\n"), '', $description);
        if ($type === 'integer') {
            $type = 'int';
        } elseif ($type === 'boolean') {
            $type = 'bool';
        }

        $camel =  $this->camelize($name);
        return "
    // {$description}
    protected {$type} \${$camel};
       ";
    }

    /**
     * @param $name
     * @return string
     */
    protected function getGetterMethodStr($name): string
    {
        $pascal = $this->pascalize($name);
        $camel = $this->camelize($name);
        return "
    /**
     * @return mixed
     */
    public function get{$pascal}()
    {
        return \$this->{$camel};
    }
    ";
    }

    /**
     * @param $stub
     * @param $properties
     * @param $getters
     * @param string $tag
     * @return void
     */
    protected function replaceValueObject(&$stub, $properties, $getters, string $tag = ''): void
    {
        $nameTag = $tag != '' ? '\\'.$this->pascalize($tag) : '';
        $stub = str_replace(
            ['{{ properties }}', '{{ getters }}' , '{{ voClassTag }}'],
            [$properties, $getters, $nameTag ],
            $stub
        );
    }


    /**
     * プロパティ記載
     * @param string $stub
     * @param string $name
     * @return void
     */
    protected function replaceProperties(string &$stub, $names): void
    {
        $stub = str_replace(
            ['{{ properties }}'],
            [$names],
            $stub
        );
    }



    /**
     * getters
     * @param string $stub
     * @param string $name
     * @return void
     */
    protected function replaceGetters(string &$stub, $names): void
    {
        $stub = str_replace(
            ['{{ getters }}'],
            [$names],
            $stub
        );
    }


    /**
     * cliとapiのIF定義生成
     * @param $uri
     * @param $controllers
     * @return string
     */
    public function makeControllers($uri, $controllers)
    {
        foreach ($controllers as $tag => $controller) {
            $className = $this->makeController($uri, $controller, $tag);
            $this->addApiRoute($uri, $className, $controller);
        }
    }

    /**
     * @param $uri
     * @param $className
     * @param $controller
     * @return void
     * @throws FileNotFoundException
     */
    public function addApiRoute($uri, $className, $controller)
    {
        $this->replaceUseAddPoint($className);
        foreach ($controller as $method => $param) {
            $this->replaceRouteAddPoint($uri, $method, $className, $param);
        }
    }
    /**
     * @param $className
     * @return false|void
     * @throws FileNotFoundException
     */
    public function replaceUseAddPoint($className)
    {
        $apiRoute = $this->files->get($this->getRoutesPath('api'));


        $existsPattern = preg_quote($className, '/');
        $existsPattern = "/^.*$existsPattern.*\$/m";
        if (preg_match_all($existsPattern, $apiRoute, $matches)) {
            $this->info('すでにuse宣言されています');
            return false;
        }

        $searchStr = 'artisanUseAddPoint';
        $pattern = preg_quote($searchStr, '/');
        $pattern = "/^.*$pattern.*\$/m";

        if (strpos($className, '{id}') !== false) {
            $className = str_replace('{id}', 'id', $className);
        }
        file_put_contents($this->getRoutesPath('api'), preg_replace(
            $pattern,
            'use ' . $className . ";\n" . "\n" . '//' . $searchStr,
            $apiRoute
        ));
    }

    /**
     * @param $className
     * @return false|void
     * @throws FileNotFoundException
     */
    public function replaceRouteAddPoint($uri, $method, $className, $param)
    {
        $apiRoute = $this->files->get($this->getRoutesPath('api'));

        $existsPattern = preg_quote("Route::" . $method . "('" . $uri . "'", '/');
        $existsPattern = "/^.*$existsPattern.*\$/m";
        if (preg_match_all($existsPattern, $apiRoute, $matches)) {
            $this->info('すでに同一名、同一methodでroutes宣言されています');
            return false;
        }

        $searchStr = 'artisanRouteAddPoint';
        $pattern = preg_quote($searchStr, '/');
        $pattern = "/^.*$pattern.*\$/m";
        $class = str_replace($this->getNamespace($className) . '\\', '', $className);
        if (strpos($class, '{id}') !== false) {
            $class = str_replace('{id}', 'id', $class);
        }
        $route = "Route::" . $method . "('" . $uri . "', [" . $class . "::class,'" . self::convMethodStr($method) . "']);";
        $security = array_key_last($param['security']);



        if ($security) {
            $func = "Route::group(['middleware' => '" . $security . "'], function () {\n";
            $func .= "    " . $route . "\n";
            $func .= "});";
        } else {
            $func = $route;
        }
        file_put_contents($this->getRoutesPath('api'), preg_replace(
            $pattern,
            $func . "\n" . "\n" . '//' . $searchStr,
            $apiRoute
        ));
    }


    public function makeController($uri, $controller, $tag)
    {

        $name = self::pascalize(str_replace('/', '', $uri));

        $className = $this->qualifyClassCustom($name, 'controller', $tag);

        $path = $this->getPath($className);
        if ((!$this->hasOption('force') ||
                !$this->option('force')) &&
            $this->alreadyExists($className) &&
            !$this->confirm($className . 'は既に存在しますが強制的に上書ますか？')
        ) {
            $this->error($className . ' already exists!');
            return $className;
        }
        if (str_contains($className, '{id}')) {
            $className = str_replace('{id}', 'id', $className);
        }

        $this->makeDirectory($path);
        $this->files->put($path, $this->buildControllerClass($className, $controller));
        $this->info($className . ' created successfully.');
        return $className;
    }

    /**
     * Build the class with the given name.
     *
     * @param string $name
     * @return string
     */
    protected function buildControllerClass(string $name, $controller): string
    {
        $useInterfaces = [];
        $getMethodStrs = [];

        $stub = $this->files->get($this->getStubCustom('controller'));

        foreach ($controller as $method => $param) {
            if (array_key_exists('request', $controller)) {
                $useInterfaces[] = 'use ' . $param['request'];
            }

            $useInterfaces[] = 'use ' . $param['resource'];
            $getMethodStrs[] = $this->getHttpControllerMethodStr($method, $param);
        }

        $this->replaceUseInterfaces($stub, implode(";\n", array_unique($useInterfaces)));
        $this->replaceHttpMethods($stub, implode("\n", $getMethodStrs));
        return $this->replaceNamespace($stub, $name)->replaceClass($stub, $name);
    }

    /**
     * @param $method
     * @param $param
     * @return string
     */
    protected function getHttpControllerMethodStr($method, $param): string
    {
        $request = array_key_exists('request', $param) ? str_replace($this->getNamespace($param['request']) . '\\', '', $param['request']) : null;
        $response = str_replace($this->getNamespace($param['resource']) . '\\', '', $param['resource']);
        $methodStr =  self::convMethodStr($method);
        return "
    /**
     * {$param['description']}
     * @param mixed
     */
    public function {$methodStr}({$request} \$request): {$response}
    {
        return {$response}::buildResult(\$response);
    }
    ";
    }

    /**
     * @param string $method
     * @return string
     */
    public static function convMethodStr(string $method): string
    {
        $methods = array(
            'get' => 'index',
            'post' => 'store',
            'put' => 'update',
            'delete' => 'destroy',
        );
        return $methods[$method];
    }
    /**
     * IF記載
     * @param string $stub
     * @param string $names
     * @return void
     */
    protected function replaceUseInterfaces(string &$stub, string $names): void
    {
        $stub = str_replace(
            ['useInterfaces'],
            [$names],
            $stub
        );
    }

    /**
     * IF記載
     * @param string $stub
     * @param string $names
     * @return void
     */
    protected function replaceHttpMethods(string &$stub, string $names): void
    {
        $stub = str_replace(
            ['httpMethods'],
            [$names],
            $stub
        );
    }

    /**
     * RequestクラスStub置換
     * @param string $stub
     * @param string $rule
     * @param $valueObject
     * @param $tag
     * @return void
     */
    protected function replaceRequest(string &$stub, string $rule, $valueObject, $tag = '' ): void
    {
        $nameTag = $tag != '' ? $this->pascalize($tag).'\\' : '';

        $stub = str_replace(
            ['{{ voClassTag }}', '{{ voClassName }}','{{ rules }}'],
            [$nameTag, $valueObject, $rule],
            $stub
        );
    }

    /**
     * ResourceクラスStub置換
     * @param string $stub
     * @param string $fields
     * @param $tag
     * @return void
     */
    protected function replaceResource(string &$stub, string $fields, $tag = '' ): void
    {
        $stub = str_replace(
            ['{{ resourceFields }}'],
            [$fields],
            $stub
        );
    }




    /**
     * スネークからパスカルへ置換します。
     * @param string $str
     * @return string
     */
    public function pascalize(string $str): string
    {
        return ucfirst(strtr(ucwords(strtr($str, array('_' => ' '))), array(' ' => '')));
    }

    /**
     * スネークからキャメルへ置換します。
     * @param string $str
     * @return string
     */
    public function camelize(string $str): string
    {
        return lcfirst(strtr(ucwords(strtr($str, array('_' => ' '))), array(' ' => '')));
    }

}
