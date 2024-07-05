<?php

namespace App\Repositories\Eloquent;

use App\Repositories\BaseRepositoryInterface;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\View\FileViewFinder;

class EloquentRepository implements BaseRepositoryInterface
{
    /**
     * @var Model $model
     */
    protected Model $model;

    /**
     * Constructor
     * @param Model $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * @param array $columns
     * @return Collection
     */
    public function all(array $columns = array('*')): Collection
    {
        return $this->model->all($columns);
    }
    /**
     * @param mixed $id
     * @param array $columns
     * @return Builder|Builder[]|Collection|Model|null
     */
    public function findById($id, array $columns = array('*')): Model|Collection|Builder|array|null
    {
        return $this->model::query()->find($id, $columns);
    }

    /**
     * findWhere function
     * @param $conditions
     * @return Builder|Model|null
     */
    public function findWhere($conditions): Model|Builder|null
    {
        return $this->model::query()->where($conditions)->first();
    }

    /**
     * @param string $column
     * @param array $data
     * @return Collection|array
     */
    public function getWhereIn(string $column, array $data): Collection|array
    {
        return $this->model::query()->whereIn($column, $data)->get();
    }

    /**
     * @param array $data
     * @return Builder|Model
     */
    public function create(array $data): Model|Builder
    {
        return $this->model::query()->create($data);
    }

    /**
     * @param array $data
     * @return int
     */
    public function insertGetId(array $data): int
    {
        return $this->model::query()->insertGetId($data);
    }

    public function updateById($data, $id): bool|int
    {
        return $this->model::query()->findOrFail($id)->update($data);
    }


    /**
     * @param array $whereClause
     * @param array $data
     * @return bool
     */
    public function update(array $whereClause, array $data): bool
    {
        return $this->updateWhere($whereClause, $data);
    }

    /**
     * @param array $condition
     * @param array $data
     * @return int
     */
    public function updateWhere(array $condition, array $data): int
    {
        return $this->model::query()->where($condition)->update($data);
    }

    /**
     * @param int $id
     * @return bool|mixed|null
     */
    public function deleteById(int $id)
    {
        return $this->model::query()->findOrFail($id)->delete();
    }

    /**
     * @param array $condition
     * @return void
     */
    public function deleteWhere(array $condition): void
    {
        $this->model::query()->where($condition)->delete();
    }

    /**
     * @param array $condition
     * @return void
     */
    public function forceDeleteWhere(array $condition): void
    {
        $this->model::query()->where($condition)->forceDelete();
    }

    /**
     * @param string $column
     * @param array $condition
     * @return void
     */
    public function forceDeleteWhereIn(string $column, array $condition): void
    {
        $this->model::query()->whereIn($column, $condition)->forceDelete();
    }

    /**
     * @param array $uniqueData
     * @param array $normalData
     * @return Model|Builder
     */
    public function updateOrCreate(array $uniqueData, array $normalData): Model|Builder
    {
        return $this->model::query()->updateOrCreate($uniqueData, $normalData);
    }

    /**
     * @param array $uniqueData
     * @param array $normalData
     * @return Model|Builder
     */
    public function firstOrCreate(array $uniqueData, array $normalData): Model|Builder
    {
        return $this->model::query()->firstOrCreate($uniqueData, $normalData);
    }

    /**
     * @param array $condition
     * @param string $column
     * @param int $value
     * @return void
     */
    public function incrementWhere(array $condition, string $column, int $value): void
    {
        $this->model::query()->where($condition)->increment($column, $value);
    }

    /**
     * @param array $condition
     * @param string $column
     * @param int $value
     * @return void
     */
    public function decrementWhere(array $condition, string $column, int $value): void
    {
        $this->model::query()->where($condition)->decrement($column, $value);
    }


    /**
     * NativeQueryの読み込み
     * @param string $queryName
     * @param array $bladeParams
     * @return string
     * @throws BindingResolutionException
     */
    public function readNativeQueryFile(string $queryName, array $bladeParams = []): string
    {
        return $this->readSqlFile($queryName, native_query_path(), $bladeParams);
    }

    /**
     * @throws BindingResolutionException
     */
    private function readSqlFile(string $name, string $path, array $bladeParams = []): string
    {
        $app = app();
        $view = view();
        $orgFinder = $view->getFinder();
        $newFinder = new FileViewFinder($app['files'], [$path]);
        $view->setFinder($newFinder);
        $view->addExtension('sql', 'blade');
        $obj = $view->make($name, $bladeParams);
        $result = $obj->render();
        $view->setFinder($orgFinder);
        return $result;
    }

}