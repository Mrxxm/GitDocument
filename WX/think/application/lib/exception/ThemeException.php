<?php
/**
 * Created by PhpStorm.
 * User: xuxiaomeng
 * Date: 2019/1/15
 * Time: 上午10:45
 */

namespace app\lib\exception;


class ThemeException extends BaseException
{
    // HTTP状态码 400，200 ...
    public $code = 404;

    // 错误具体信息
    public $msg = 'Request Theme empty';

    // 自定义错误码
    public $errorCode = '30000';
}