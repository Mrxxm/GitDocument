<?php
/**
 * Created by PhpStorm.
 * User: xuxiaomeng
 * Date: 2019/2/10
 * Time: 下午8:19
 */

namespace app\api\validate;


class TokenGet extends BaseValidate
{
    // require也是通过的，如果有参数名没有参数值，所以自定义为空判断
    protected $rule = [
        'code' => 'require|isNotEmpty'
    ];

    protected $message = [
        'code' => 'code值为空'
    ];
}