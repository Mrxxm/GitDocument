<?php
/**
 * Created by PhpStorm.
 * User: xuxiaomeng
 * Date: 2019/2/10
 * Time: 下午1:38
 */

namespace app\lib\exception;


class ProductException extends BaseException
{
    // HTTP状态码 400，200 ...
    public $code = 404;

    // 错误具体信息
    public $msg = '指定的商品不存在，请检查参数';

    // 自定义错误码
    public $errorCode = '20000';
}