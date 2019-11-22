<?php
/**
 * Created by PhpStorm.
 * User: wen
 * Date: 2019/10/31
 * Time: 11:08
 */
define('START_TIME', microtime(true));


require_once __DIR__ . '/../core/autoload/Autoload.php';



$app = require_once CORE_PATH . DS . 'kernel' . DS . 'App.php';



$app->run();