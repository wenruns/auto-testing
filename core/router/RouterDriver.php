<?php
/**
 * Created by PhpStorm.
 * User: wem
 * Date: 2019/10/30
 * Time: 17:18
 *
 *
 * 生成一个缓存文件（命名空间缓存、映射缓存、）
 */

namespace core\router;


class RouterDriver
{
    public function post($uri, $closure)
    {

    }

    public function get()
    {

    }

    public function delete()
    {

    }

    public function put()
    {

    }

    public function head()
    {

    }

    public function options()
    {

    }

    public function connect()
    {

    }


    public function trace()
    {

    }

    protected function loading($dir_path, $router)
    {
        foreach (scandir($dir_path) as $file) {
            if ($file != '.' && $file != '..') {
                if (is_dir($dir_path.DS.$file)) {
                    includeRouters($dir_path.DS.$file);
                } else if (is_file($dir_path.DS.$file)) {
                    include_once $dir_path.DS.$file;
                }
            }
        }
    }

    public function run($router)
    {
        include_once __DIR__.DS.'Router.php';
        $this->loading(ROUTER_PATH, $router);
    }

}
$_wen_router_driver = new RouterDriver();
$_wen_router_driver->run($_wen_router_driver);