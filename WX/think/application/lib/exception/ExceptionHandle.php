<?php
/**
 * Created by PhpStorm.
 * User: xuxiaomeng
 * Date: 2019/1/2
 * Time: 下午4:52
 */

namespace app\lib\exception;

use think\exception\Handle;
use think\Log;
use think\Request;

class ExceptionHandle extends Handle
{
    private $code;

    private $msg;

    private $errorCode;

    // 需要返回客户端当前请求的URL

    // 错误返回到页面显示的方法
    public function render(\Exception $e)
    {
        if ($e instanceof BaseException) {
            // 自定义异常
            $this->code = $e->code;
            $this->msg = $e->msg;
            $this->errorCode = $e->errorCode;

        } else {
            // config TP5提供的助手函数 Config::get('app_debug');
            if (config('app_debug')) {
                return parent::render($e);
            } else {
                // 系统异常
                $this->code = 500;
                $this->msg = 'Server internal error';
                $this->errorCode = 999;
                $this->recordErrorLog($e);
            }
        }

        $result = [
            'msg' => $this->msg,
            'errorCode' => $this->errorCode,
            'request_url' => Request::instance()->url(),
        ];

        return json($result, $this->code);
    }

    private function recordErrorLog(\Exception $e)
    {
        // 日志初始化
        Log::init([
            'type' => 'File',
            'path'  => LOG_PATH,
            'level' => ['error'],
        ]);
        Log::record($e->getMessage(), 'error'); // 第二个参数，定义日志级别
    }
}