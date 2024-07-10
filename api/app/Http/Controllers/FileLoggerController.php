<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\FileLoggerServiceInterface;

class FileLoggerController extends Controller
{
    protected $logger;

    public function __construct(FileLoggerServiceInterface $logger)
    {
        $this->logger = $logger;
    }

    //logs/pravtice.logに文字列を出力させるメソッド
    public function writeLog()
    {
        $message = 'ログ出力テスト';
        $this->logger->info($message);
    }
}
