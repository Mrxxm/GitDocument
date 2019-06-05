<?php
/**
 * Created by PhpStorm.
 * User: xuxiaomeng
 * Date: 2019/1/2
 * Time: 下午5:03
 */

namespace app\lib\exception;



class BannerMissException extends BaseException
{
    // HTTP状态码 400，200 ...
    public $code = 404;

    // 错误具体信息
    public $msg = 'Request Banner error';

    // 自定义错误码
    public $errorCode = '40000';

}