<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/11/25
 * Time: 14:22
 */

namespace src\automated;


class ClientController
{
    protected $client = null;

    protected $errMsg = [];

    protected $requestInfo = [];

    protected $body = '';

    public function __construct()
    {
        $this->createClient();
    }

    public function run($url, $method = 'GET', $params = [])
    {
//        $params = [
//            'a'=> 12,
//            'b' => 13,
//        ];
//        $this->sendRequest($url, $method, $params);
//        dd($this->errMsg, $this->requestInfo, $this->body);
        $this->multiRequest('http://baidu.com', 4);
    }


    protected function createClient()
    {
        $this->client = curl_init();
    }

    protected function setOptions($ch, $url, $method = 'GET', $params = [], $header = [])
    {
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        if (empty($header)) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        }
        if (strtoupper($method) == 'POST') {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        } else if (!empty($params)) {
            $params = http_build_query($params);
            if (strpos($url, '?') === false) {
                $url .= '?' . $params;
            } else {
                $url .= '&' . $params;
            }
        }
        curl_setopt($ch, CURLOPT_URL, $url);
        return $ch;
    }

    protected function sendRequest($url, $method = 'GET', $params = [], $header = [])
    {
        try {
            $this->client = $this->setOptions($this->client, $url, $method, $params, $header);

            $this->body = curl_exec($this->client);
            $this->requestInfo = curl_getinfo($this->client);
            if ($errno = curl_errno($this->client)) {
                $this->errMsg = [
                    'errno' => $errno,
                    'error' => curl_error($this->client)
                ];
            }
            curl_close($this->client);
        } catch (\Exception $e) {
            dd($e);
        }
    }


    protected function multiRequest($url, $n = 1, $method = 'GET', $params = [], $header = [])
    {
        $mh = curl_multi_init();
        for ($i = 0; $i < $n; $i++) {
            $var = 'ch' . $i;
            $$var = curl_init();
            $$var = $this->setOptions($$var, $url, $method, $params, $header);
            curl_multi_add_handle($mh, $$var);
        }
        $active = null;
        $startTime = microtime(true);
        $mrc = curl_multi_exec($mh, $active);
        $endTime = microtime(true);
        dump($endTime-$startTime);
        // 关闭全部句柄
        for ($i = 0; $i < $n; $i++) {
            $var = 'ch' . $i;
            $info = curl_getinfo($$var);
            dump($info);
            curl_multi_remove_handle($mh, $$var);
        }
        curl_multi_close($mh);
    }
}