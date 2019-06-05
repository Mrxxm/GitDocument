<?php
/**
 * Created by PhpStorm.
 * User: xuxiaomeng
 * Date: 2019/2/10
 * Time: 下午10:13
 */

namespace app\lib\exception;


class WeChatException extends BaseException
{
    // HTTP状态码 400，200 ...
    public $code = 400;

    // 错误具体信息
    public $msg = '微信服务接口调用失败';

    // 自定义错误码
    public $errorCode = '999';
}