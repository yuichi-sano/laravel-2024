<?php

namespace App\Services;
use App\Services\FileLoggerServiceInterface;


class FileLoggerService implements FileLoggerServiceInterface
{
    private $filePath;

    public function __construct()
    {
        //ログファイルのパスを指定
        $this->filePath = storage_path('/practice.log');
    }

    //ログ出力をするメソッド
    public function info($message) {
        if($this->filePath) {
            file_put_contents($this->filePath, "[INFO]".$message.PHP_EOL.FILE_APPEND);
        } else {
            echo "ログファイルが存在しません";
        }
    }
    
}