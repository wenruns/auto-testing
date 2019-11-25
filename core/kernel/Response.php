<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/11/22
 * Time: 17:04
 */

namespace core\kernel;


class Response
{
    protected static $header = '';

    protected static $body = '';

    protected static $route_type = '';

    protected static $closure = null;

    protected static $params = [];

    public static function setClosure($closure, $params)
    {
        self::$closure = $closure;
        self::$params = $params;
    }

    public static function send()
    {
        if (is_callable(self::$closure)) {
            self::$body = call_user_func(self::$closure, ...self::$params);
        }
        echo self::doc();
    }

    protected static function doc()
    {
        $title = 'test';
        $body = self::$body;
        return  <<<EOT
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>$title</title>
</head>
<body>
$body
</body>
</html>
EOT;
    }
}