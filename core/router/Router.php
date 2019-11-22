<?php
/**
 * Created by PhpStorm.
 * User: wen
 * Date: 2019/10/30
 * Time: 16:53
 */
namespace core\router;

class Router
{
    public static $prefix = '';

    protected static $router = null;

    public static $group = '';

    protected static function getRouter()
    {
        if (empty(self::$router)) {
            self::$router = new RouterDriver();
        }
        return self::$router;
    }

    public static function group($options, \Closure $closure)
    {
        self::$group = $options;
        if (is_callable($closure)) {
            call_user_func($closure, new RouterInstance());
        }
    }

    public static function middleware()
    {

    }

    public static function post($uri, $closure)
    {
        return self::getRouter()->routeParsing($uri, $closure, 'POST', self::$prefix, self::$group);
    }

    public static function get($uri, $closure)
    {
        return self::getRouter()->routeParsing($uri, $closure, 'GET', self::$prefix, self::$group);
    }

    public static function delete($uri, $closure)
    {
        return self::getRouter()->routeParsing($uri, $closure, 'DELETE', self::$prefix, self::$group);
    }

    public static function put($uri, $closure)
    {
        return self::getRouter()->routeParsing($uri, $closure, 'PUT', self::$prefix, self::$group);
    }

    public static function head($uri, $closure)
    {
        return self::getRouter()->routeParsing($uri, $closure, 'HEAD', self::$prefix, self::$group);
    }

    public static function options($uri, $closure)
    {
        return self::getRouter()->routeParsing($uri, $closure, 'OPTIONS', self::$prefix, self::$group);
    }

    public static function connect($uri, $closure)
    {
        return self::getRouter()->routeParsing($uri, $closure, 'CONNECTION', self::$prefix, self::$group);
    }


    public static function trace($uri, $closure)
    {
        return self::getRouter()->routeParsing($uri, $closure, 'TRACE', self::$prefix, self::$group);
    }

    public static function any($uri, $closure)
    {
        return self::getRouter()->routeParsing($uri, $closure, 'ANY', self::$prefix, self::$group);
    }

    public static function __callStatic($name, $arguments)
    {
        // TODO: Implement __callStatic() method.
        return self::getRouter()->$name($arguments);
    }
}


