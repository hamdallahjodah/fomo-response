<?php

/*
 * This file is part of the jiannei/laravel-response.
 *
 * (c) Jiannei <longjian.huang@foxmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Jiannei\Response\Laravel\Support\Traits;

use Illuminate\Contracts\Support\Arrayable;
use Fomo\Response\Response;
use Illuminate\Pagination\AbstractCursorPaginator;
use Illuminate\Pagination\AbstractPaginator;
use Illuminate\Support\Arr;

trait JsonResponseTrait
{
    /**
     *  Respond with an accepted response and associate a location and/or content if provided.
     *
     * @param  array  $data
     * @param  string  $message
     * @param  string  $location
     * @return Response
     */
    public function accepted($data = [], string $message = '', string $location = '')
    {
        $response = $this->success($data, $message, 202);
        if ($location) {
            $response->withHeader('Location', $location);
        }

        return $response;
    }

    /**
     * Respond with a created response and associate a location if provided.
     *
     * @param  null  $data
     * @param  string  $message
     * @param  string  $location
     * @return Response
     */
    public function created($data = [], string $message = '', string $location = '')
    {
        $response = $this->success($data, $message, 201);
        if ($location) {
            $response->withHeader('Location', $location);
        }

        return $response;
    }

    /**
     * Respond with a no content response.
     *
     * @param  string  $message
     * @return Response
     */
    public function noContent(string $message = '')
    {
        return $this->success([], $message, 204);
    }

    /**
     * Alias of success method, no need to specify data parameter.
     *
     * @param  string  $message
     * @param  int  $code
     * @param  array  $headers
     * @return Response
     */
    public function ok(string $message = '', int $code = 200, array $headers = [])
    {
        return $this->success([], $message, $code, $headers);
    }

    /**
     * Alias of the successful method, no need to specify the message and data parameters.
     * You can use ResponseCodeEnum to localize the message.
     *
     * @param  int  $code
     * @param  array  $headers
     * @return Response
     */
    public function localize(int $code = 200, array $headers = [])
    {
        return $this->ok('', $code, $headers);
    }

    /**
     * Return a 400 bad request error.
     *
     * @param  string|null  $message
     * @return Response
     */
    public function errorBadRequest(string $message = '')
    {
        return $this->fail($message, 400);
    }

    /**
     * Return a 401 unauthorized error.
     *
     * @param  string  $message
     * @return Response
     */
    public function errorUnauthorized(string $message = '')
    {
        return $this->fail($message, 401);
    }

    /**
     * Return a 403 forbidden error.
     *
     * @param  string  $message
     * @return Response
     */
    public function errorForbidden(string $message = '')
    {
        return $this->fail($message, 403);
    }

    /**
     * Return a 404 not found error.
     *
     * @param  string  $message
     * @return Response
     */
    public function errorNotFound(string $message = '')
    {
        return $this->fail($message, 404);
    }

    /**
     * Return a 405 method not allowed error.
     *
     * @param  string  $message
     * @return Response
     */
    public function errorMethodNotAllowed(string $message = '')
    {
        return $this->fail($message, 405);
    }

    /**
     * Return a 500 internal server error.
     *
     * @param  string  $message
     * @return Response
     */
    public function errorInternal(string $message = '')
    {
        return $this->fail($message);
    }

    /**
     * Return an fail response.
     *
     * @param  string  $message
     * @param  int  $code
     * @param  array|null  $errors
     * @param  array  $header
     * @return Array
     */
    public function fail(string $message = '', int $code = 500, $errors = null, array $header = [])
    {
        $response = $this->formatter->response(
            $this->formatter->data(null, $message, $code, $errors),
            $code,
            $header
        );

        return $response;
    }

    /**
     * Return a success response.
     *
     * @param  AbstractPaginator|array|mixed  $data
     * @param  string  $message
     * @param  int  $code
     * @param  array  $headers
     * @return Response
     */
    public function success($data = [], string $message = '', int $code = 200, array $headers = [])
    {
        if ($data instanceof AbstractPaginator || $data instanceof AbstractCursorPaginator) {
            return $this->formatter->response($this->formatter->paginator(...func_get_args()), $code, $headers);
        }

        if ($data instanceof Arrayable) {
            $data = $data->toArray();
        }

        return $this->formatter->response($this->formatter->data(Arr::wrap($data), $message, $code), $code, $headers);
    }
}
