<?php
/**
 * Created by PhpStorm.
 * User: wen
 * Date: 2019/11/5
 * Time: 11:38
 */

Router::group([
    'namespace' => '\automated'
], function ($router) {
    $router->any('automated/testing', 'IndexController@index');
});

Router::group([
    'namespace' => '\test'
], function ($router){
    $router->any('test/{id}/edit', 'Test@index');
    $router->any('test', function (){
        return 'this is a test!';
    });
});
