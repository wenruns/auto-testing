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

use core\kernel\Response;

class RouterDriver
{
    protected $pools = [];

    protected $temporaryPools = [];

    protected $aliasPools = [];

    protected $match_uri = '';

    protected $current_uri = '';

    /**
     * 编译路由
     * @param $uri
     * @param $closure
     * @param $method
     * @param $prefix
     * @param $groupName
     * @return $this
     */
    public function routeParsing($uri, $closure, $method, $prefix, $groupName)
    {
        // todo:: 路由缓存池
        $this->setCurrentUri($uri);
        $this->cacheRoutes(base64_encode($uri), [
            'method' => $method,
            'closure' => $closure,
            'prefix' => $prefix,
            'group' => $groupName,
        ]);
        $this->matchRoute();
        return $this;
    }

    /**
     * 设置别名
     * @param $name
     */
    public function name($name)
    {
        $this->aliasPools[$name] = $this->match_uri['uri'];
    }


    /**
     * 启动路由
     */
    public function run()
    {
        $this->checkRouteMatchResult();
        $this->action();
    }

    /**
     * 检测路由控制器和方法
     */
    protected function action()
    {
        $params = [];
        foreach ($this->match_uri['result_request_uri'] as $key => $item) {
            if ($key > 0) {
                $params[] = $item[0];
            }
        }
        $uri = $this->match_uri['uri'];
        $info = $this->pools[base64_encode($uri)];
        $closure = $info['closure'];
        $group = $info['group'];
        if (is_callable($closure)) {
            Response::setClosure($closure, $params);
            return ;
        }
        $namespace = isset($group['namespace']) ? $group['namespace'] : '';
        if (strpos($closure, '@') === false) {
            $className = '\src' . $namespace . '\\' . $closure;
            preg_match('/(\/edit\/|\/edit$|\/create\/|\/create$|\/delete\/|\/delete$)/', $uri, $output);
            if (empty($output) || !isset($output[1])) {
                $output[1] = 'index';
            }
            switch (trim($output['1'], '/')) {
                case 'edit':
                    $method = 'edit';
                    break;
                case 'create':
                    $method = 'create';
                    break;
                case 'delete':
                    $method = 'delete';
                    break;
                default:
                    $method = 'index';
            }
            Response::setClosure(array(new $className(), $method), $params);
            return ;
        }
        $classes = explode('@', $closure);
        $className = '\src' . $namespace . '\\' . $classes[0];
        $method = $classes[1];
        Response::setClosure(array(new $className(), $method), $params);
    }


    /**
     * @throws \Exception
     * 判断路由是否匹配成功
     */
    protected function checkRouteMatchResult()
    {
        if (empty($this->match_uri)) {
            dd('未找到路由');
        }
    }


    /**
     * 缓存路由
     * @param $index
     * @param $value
     */
    protected function cacheRoutes($index, $value)
    {
        $this->pools[$index] = $value;
    }

    /**
     * 设置当前编译的路由uri，用于编译
     * @param $uri
     */
    protected function setCurrentUri($uri)
    {
        if (empty($uri)) {
            $uri = '/';
        }
        if (Router::$prefix != 'web') {
            $uri = trim(Router::$prefix . '/' . $uri, '/');
        }
        $this->current_uri = $uri;
    }

    /**
     * 正则匹配路由
     */
    protected function matchRoute()
    {
        if (!$this->checkMethod()) {
            return ;
        }
        $uri = $this->current_uri;
        // 匹配参数
        $request = $_SERVER['REQUEST_URI'];
        $script_uri = $_SERVER['SCRIPT_NAME'];
        $self_uri = $_SERVER['PHP_SELF'];

        preg_match_all('/\{([^\/]+)\}/', $uri, $result_uri);
        // todo:: 路由匹配规则
        $reg = '/^' . str_replace('/', '\/', $uri) . '$/';

        foreach ($result_uri[0] as $key => $vo) {
            $reg = str_replace($vo, '([^\/]+)', $reg);
        }
        // 请求uri
        $request_uri = str_replace(str_replace('index.php', '', ($script_uri ? $script_uri : $self_uri)), '', str_replace('index.php', '', $request));
        empty($request_uri) ? $request_uri = '/' : '';
        // todo:: 匹配路由
        preg_match_all($reg, $request_uri, $result_request_uri);
        if (count($result_request_uri[0]) == 1 && (empty($this->match_uri) || count($result_request_uri) < count($this->match_uri['result_request_uri']))) {
            // 保存匹配成功的路由
            $this->match_uri = [
                'uri' => $uri,
                'result_uri' => $result_uri,
                'result_request_uri' => $result_request_uri
            ];
        }
    }

    /**
     * 检测路由请求方式是否正确
     * @return bool
     */
    protected function checkMethod()
    {
        $method = $this->pools[base64_encode($this->current_uri)]['method'];
        if ($method == 'ANY') {
            return true;
        }
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        if (request('_method')) {
            $requestMethod = request('_method');
        }
        if (strtoupper($method) != strtoupper($requestMethod)) {
            return false;
        }
        return true;
    }
}