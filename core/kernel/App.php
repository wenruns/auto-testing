<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/11/22
 * Time: 13:35
 */

namespace core\wen\kernel;



use core\kernel\Response;
use core\router\Router;

class App
{

    public function run()
    {
        $this->loadingRoutes(ROUTER_PATH);
        Router::run();
        $this->response();
    }

    protected function response()
    {
        Response::send();
    }

    protected function loadingRoutes($dir_path, $prefix = '')
    {
        foreach (scandir($dir_path) as $file) {
            if ($file != '.' && $file != '..') {
                if (is_dir($dir_path . DS . $file)) {
                    $this->loadingRoutes($dir_path . DS . $file, $prefix . '/' . $file);
                } else if (is_file($dir_path . DS . $file)) {
                    Router::$prefix = trim($prefix . '/' . substr($file, 0, strpos($file, '.')), '/');
                    $code = file_get_contents($dir_path . DS . $file);
                    eval('use core\router\Router;'.rtrim(ltrim($code,'<?php'), '?>'));
                }
            }
        }
    }
}
return new App();