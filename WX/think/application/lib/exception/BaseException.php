<?php
/**
 * Created by PhpStorm.
 * User: xuxiaomeng
 * Date: 2019/1/2
 * Time: 下午4:56
 */

namespace app\lib\exception;

use think\Exception;

class BaseException extends Exception
{
    // HTTP状态码 400，200 ...
    public $code = 400;

    // 错误具体信息
    public $msg = 'parameter error';

    // 自定义错误码
    public $errorCode = '10000';

    public function __construct($params = [])
    {
        if (!is_array($params)) {
            return ;
//            throw new Exception('参数必须是数组');
        }
        if (array_key_exists('code', $params)) {
            $this->code = $params['code'];
        }
        if (array_key_exists('msg', $params)) {
            $this->msg = $params['msg'];
        }
        if (array_key_exists('errorCode', $params)) {
            $this->errorCode = $params['errorCode'];
        }

    }
}