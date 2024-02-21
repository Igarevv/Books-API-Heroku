<?php

namespace App\Core\Http\Response;

abstract class Response
{
    const OK            = 200;

    const CREATED       = 201;

    const NO_CONTENT    = 204;

    const MOVED         = 301;

    const NOT_MODIFIED  = 304;

    const BAD_REQUEST   = 400;

    const UNAUTHORIZED  = 401;

    const FORBIDDEN     = 403;

    const NOT_FOUND     = 404;

    const UNPROCESSABLE = 422;

    const SERVER_ERROR  = 500;

    protected const HTTP_STATUS_MESSAGE = [
      '200' => '200 | OK',
      '201' => '201 | Created',
      '204' => '204 | No Content',
      '301' => '301 | Moved',
      '304' => '304 | Not Modified',
      '400' => '400 | Bad Request',
      '401' => '401 | Unauthorized',
      '403' => '403 | Forbidden',
      '404' => '404 | Not Found',
      '422' => '422 | Unprocessable Entity',
      '500' => '500 | Server Error',
    ];
    public abstract function send(): void;
}