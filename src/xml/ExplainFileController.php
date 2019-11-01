<?php
/**
 * Created by PhpStorm.
 * User: wen
 * Date: 2019/10/29
 * Time: 18:47
 */
namespace src\xml;



class ExplainFileController extends BaseController
{

    protected $key = '123456';

    protected $acceptFilePath = __DIR__;

    protected $acceptFileName = 'accept.txt';

    protected $xmlFilePath = '';

    protected $xmlFileName = '';


    public function __construct()
    {
        parent::__construct();
        $this->key = config('config.key', '123456');
        $this->xmlFilePath = config('config.xml_save_path', storage_path());
        $this->xmlFileName = config('config.xml_save_name', 'xmlFile.xml');
    }

    public function accept()
    {
        try {
            // 获取xml内容
            $content = $this->request('head').$this->file('xmlFile')->getContent().$this->request('footer');
            // 保存到目标xml文件
            $this->saveXml($content);
            apiResponse([
                'status' => true,
                'code' => 200,
                'errMsg' => 'ok'
            ]);
        } catch (\Exception $e) {
            apiResponse([
                'status' => false,
                'code' => 400,
                'errMsg' => $e->getMessage()
            ]);
        }
    }

    protected function saveXml($content)
    {
        // 解密
        $xmlContent = $this->decrypt($content, $this->key, $this->request('iv'));
        if (!is_dir($this->xmlFilePath)) {
            mkdir($this->xmlFilePath, 777, true);
            chmod($this->xmlFilePath, 0777);
        }
        // 保存目标文件
        file_put_contents($this->xmlFilePath.DS.$this->xmlFileName, $xmlContent);

        $logPath = $this->xmlFilePath.DS.date('Ymd').DS.date('His');
        if (!is_dir($logPath)) {
            mkdir($logPath, 0777, true);
            chmod($logPath, 0777);
        }
        // 保存日志文件
        file_put_contents($logPath.DS.$this->file('xmlFile')->getName(), $content);
        file_put_contents($logPath.DS.$this->xmlFileName, $xmlContent);
    }

}