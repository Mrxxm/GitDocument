<?php
/**
 * Created by PhpStorm.
 * User: xuxiaomeng
 * Date: 2019/2/26
 * Time: 下午2:05
 */

namespace app\api\controller;


use think\Controller;
use app\api\service\Token as TokenService;

class BaseController extends Controller
{
    protected function checkPrimaryScope()
    {
        // 前置方法一
        TokenService::needPrimaryScope();
    }

    protected function checkExclusiveScope()
    {
        // 前置方法二
        TokenService::needExclusiveScope();
    }
}