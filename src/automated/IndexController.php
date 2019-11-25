<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/11/25
 * Time: 14:21
 */

namespace src\automated;


class IndexController
{
    public function index()
    {
        try {
            $client = new ClientController();
            $res = $client->run('https://guzzle-cn.readthedocs.io/zh_CN/latest/request-options.html', 'GET');
        } catch (\Exception $e) {
            dd($e);
        }
    }
}