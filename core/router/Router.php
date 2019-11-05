<?php
/**
 * Created by PhpStorm.
 * User: wen
 * Date: 2019/10/30
 * Time: 16:53
 */

class Router
{
    public static $prefix = '';

    protected static $router = null;

    protected static function getRouter()
    {
        if (empty(self::$router)) {
            self::$router = new RouterDriver();
        }
        return self::$router;
    }

    public static function post($uri, $closure)
    {

    }

    public static function get($url, $closure)
    {
        var_dump(self::$prefix);
//        var_dump(get_included_files());
//        var_dump($closure);
//        self::getRouter()->get();
//        $obj = new ReflectionObject($closure);
//        var_dump($obj->getFileName());

    }

    public static function delete()
    {

    }

    public static function put()
    {

    }

    public static function head()
    {

    }

    public static function options()
    {

    }

    public static function connect()
    {

    }


    public static function trace()
    {

    }
}

function loading($dir_path, $prefix = '')
{
    foreach (scandir($dir_path) as $file) {
        if ($file != '.' && $file != '..') {
            if (is_dir($dir_path . DS . $file)) {
                loading($dir_path . DS . $file, $prefix.'/'.$file);
            } else if (is_file($dir_path . DS . $file)) {
                Router::$prefix = $prefix.'/'.substr($file, 0, strpos($file, '.'));
                include_once $dir_path . DS . $file;
            }
        }
    }
}
loading(ROUTER_PATH);
require_once CORE_PATH . DS . 'func.php';







