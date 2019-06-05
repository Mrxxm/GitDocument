<?php
/**
 * Created by PhpStorm.
 * User: xuxiaomeng
 * Date: 2019/1/7
 * Time: 下午4:29
 */

namespace app\lib\exception;


use Throwable;

class ParameterException extends BaseException
{
    // HTTP状态码 400，200 ...
    public $code = 400;

    // 错误具体信息
    public $msg = 'Param error';

    // 自定义错误码
    public $errorCode = '10000';

}