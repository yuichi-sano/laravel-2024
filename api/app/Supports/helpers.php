<?php

use Carbon\Carbon;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;

/**
 * generate token func
 */
if (!function_exists('generateHashToken')) {
    function generateHashToken(): string
    {
        $newAppKey = base64_decode(substr(config('app.key'), 7));
        return hash_hmac('sha256', Str::random(40), $newAppKey);
    }
}


/**
 * getPageInfo func
 *
 * @param Paginator|LengthAwarePaginator $paginator
 * @return array
 */
if (!function_exists('getPageInfo')) {
    function getPageInfo(Paginator|LengthAwarePaginator $paginator): array
    {
        return [
            'page' => $paginator->currentPage(),
            'limit' => (int)$paginator->perPage(),
            'totalPage' => (int)ceil($paginator->total() / $paginator->perPage()),
            'totalCount' => $paginator->total(),
        ];
    }
}

/**
 * convert param key from camel to snake
 *
 * @param array $param
 * @param array $fillable
 * @return array
 */
if (!function_exists('convertParamsCamelToSnake')) {
    function convertParamsCamelToSnake(array $param, array $fallible = []): array
    {
        $convertedParam = [];
        foreach ($param as $key => $value) {
            $convertedKey = strtolower(preg_replace('/(?<!^)[A-Z0-9]/', '_$0', $key));
            if (empty($fallible) || in_array($convertedKey, $fallible)) {
                if (is_array($value)) {
                    $convertedParam[$convertedKey] = convertParamsCamelToSnake($value);
                } else {
                    $convertedParam[$convertedKey] = $value;
                }
            }
        }
        return $convertedParam;
    }
}

/**
 * Format datetime template mail
 *
 * @param Carbon $dateTime
 * @return string
 */
if (!function_exists('dateFormatTemplateMail')) {
    function dateFormatTemplateMail(Carbon $dateTime): string
    {
        $weekDays = ['日', '月', '火', '水', '木', '金', '土'];

        return $dateTime->format("Y/m/d({$weekDays[$dateTime->dayOfWeek]})H:i");
    }
}

/**
 * Format datetime transmission template mail
 *
 * @param Carbon $dateTime
 * @return string
 */
if (!function_exists('dateFormatTransmissionTemplateMail')) {
    function dateFormatTransmissionTemplateMail(Carbon $dateTime): string
    {
        $weekDays = ['日', '月', '火', '水', '木', '金', '土'];

        return $dateTime->format("Y/m/d({$weekDays[$dateTime->dayOfWeek]}) H:i");
    }
}

if (!function_exists('native_query_path')) {
    /**
     * Get the path to the native_query folder.
     *
     * @param string $path
     * @return string
     */
    function native_query_path(string $path = ''): string
    {
        return app()->basePath('app/Repositories/NativeQueries') . ($path ? DIRECTORY_SEPARATOR . $path : $path);
    }
}
