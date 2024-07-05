<?php

namespace app\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

abstract class Controller extends BaseController
{
    use AuthorizesRequests;
    use DispatchesJobs;
    use ValidatesRequests;

    /**
     * Create a new JSON response instance.
     *
     * @param  mixed  $data
     * @param  int  $status
     * @param  array  $headers
     * @return JsonResponse
     */
    public function response($data = [], $status = Response::HTTP_OK, array $headers = []): JsonResponse
    {
        $headers['Content-Type'] = 'application/json';
        switch ($status) {
            case Response::HTTP_OK:
            case Response::HTTP_CREATED:
            case Response::HTTP_NO_CONTENT:
                $result = $data;
                break;
            default:
                $result['errors'] = $data;
        }

        return response()->json($result, $status, $headers, JSON_UNESCAPED_SLASHES | JSON_PRESERVE_ZERO_FRACTION);
    }

    /**
     * Create a new stream response instance.
     *
     * @param  \Closure  $callback
     * @param  int  $status
     * @param  array  $headers
     * @return StreamedResponse
     */
    public function responseStream($callback, $status = Response::HTTP_OK, array $headers = []): StreamedResponse
    {
        return response()->stream($callback, $status, $headers);
    }
}

