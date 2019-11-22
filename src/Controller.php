<?php
/**
 * Created by PhpStorm.
 * User: wen
 * Date: 2019/10/31
 * Time: 11:18
 */
namespace src;

class Controller
{

    public function index()
    {
        echo 'ss';
        $this->show();
    }

    public function show(){
        echo 'show';
        $this->test();
    }

    public function test()
    {
        for ($i = 0; $i < 100; $i++) {
            dump('$i='.$i);
        }
    }
}