<?php
/**
 * Created by PhpStorm.
 * User: xuxiaomeng
 * Date: 2019/2/11
 * Time: 下午8:06
 */

namespace app\lib\exception;


class TokenException extends BaseException
{
    // HTTP状态码 400，200 ...
    public $code = 401;

    // 错误具体信息
    public $msg = 'Token已过期或无效';

    // 自定义错误码
    public $errorCode = '10001';
}