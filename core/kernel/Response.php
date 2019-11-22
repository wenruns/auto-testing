<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/11/22
 * Time: 17:04
 */

namespace core\kernel;


class Response
{
    protected static $header = '';

    protected static $body = '';

    protected static $route_type = '';

    protected static $closure = null;

    protected static $params = [];

    public static function setClosure($closure, $params)
    {
        self::$closure = $closure;
        self::$params = $params;
    }

    public static function send()
    {
        if (is_callable(self::$closure)) {
            return call_user_func(self::$closure, ...self::$params);
        }

        return self::makeResponse();
    }

    protected static function makeResponse()
    {
        return self::head().self::body();
    }

    protected static function head()
    {

    }

    protected static function body()
    {

    }
}