<?php
/**
 * Created by PhpStorm.
 * User: xuxiaomeng
 * Date: 2019/2/10
 * Time: 下午3:22
 */

namespace app\lib\exception;


class CategoryException extends BaseException
{
    // HTTP状态码 400，200 ...
    public $code = 404;

    // 错误具体信息
    public $msg = '指定类别不存在，请检查参数';

    // 自定义错误码
    public $errorCode = '50000';
}