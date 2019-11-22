<?php
/**
 * Created by PhpStorm.
 * User: wen
 * Date: 2019/11/5
 * Time: 11:38
 */
Router::group([

], function ($router) {
    $router->get('/', 'Controller@index');
});

Router::group([
    'namespace' => '\test'
], function ($router){
    $router->any('test/{id}/edit', 'Test@index');
});
