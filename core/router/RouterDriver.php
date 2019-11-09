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

use \core\router\Router;

class RouterDriver
{
    protected $pools = [];

    protected $aliasPools = [];

    protected $uri = '';

    public function routeParsing($uri, $closure, $method = 'GET')
    {
        preg_match_all('/\{(.*?)\}/', $uri, $result);
        $reg = '/'.str_replace('/', '\/', $uri).'/';
        foreach ($result[0] as $key => $vo) {
            $reg = str_replace($vo, '(.*?[^\/])', $reg);
            echo $key.'=>';
            var_dump($vo);
            echo '<hr/>';
        }
        $this->pools[base64_encode($reg)] = [
            'uri' => $uri,
            'method' => $method,
            'closure' => $closure,
        ];
        $this->uri = base64_encode($reg);
        foreach ($result[1] as $key => $vo) {
            $reg = str_replace($vo, '(.*?[^\/])', $reg);
            echo $key.'=>';
            var_dump($vo);
            echo '<hr/>';
        }
        echo $reg.'<hr/>';
        $method = $_SERVER['REQUEST_METHOD'];
        $request = $_SERVER['REQUEST_URI'];
        $script_uri = $_SERVER['SCRIPT_NAME'];
        $self_uri = $_SERVER['PHP_SELF'];
        echo $uri.'<br/>';
        echo $method.'<br/>';
        echo $request.'<br/>';
        $request_uri = ltrim($request, ($script_uri ? $script_uri : $self_uri));
        preg_match_all($reg, $request, $res);
        echo $request_uri.'<br/>';
        echo $script_uri.'<br/>';
        echo $self_uri.'<hr/>';
        foreach ($res as $key => $vo) {
            echo $key.'=>';
            var_dump($vo);
            echo '<hr/>';
        }
        echo '<hr/>';
        var_dump($result);die;

    }

    public function name($name)
    {
        $this->aliasPools[$name] = $this->uri;
    }

    public function run()
    {

    }
}

include_once __DIR__ . DS . 'Router.php';