<?php
/**
 * Created by PhpStorm.
 * User: xuxiaomeng
 * Date: 2019/2/27
 * Time: 下午3:17
 */

namespace app\api\controller\v1;


use app\api\controller\BaseController;
use app\api\validate\IDMustBePositiveInt;
use app\api\service\Pay as PayService;
use app\api\service\WxNotify as WxNotifyService;

class Pay extends BaseController
{
    protected $beforeActionList = [
        'checkExclusiveScope' => ['only' => 'getPreOrder']
    ];
    /*
     * 预订单信息
     * 只允许用户访问
     */
    public function getPreOrder($id = '')
    {
        (new IDMustBePositiveInt())->goCheck();
        $payService = new PayService($id);

        return $payService->pay();
    }

    /*
     * 支付回调函数
     * 每隔一段时间调用
     * 频率：15/15/30/180/1800/1800/1800/1800/3600, 单位:秒
     */
    public function receiveNotify()
    {
        // 1.检测库存量，超卖
        // 2.更新订单status状态
        // 3.减库存
        // 如果成功处理则向微信返回成功处理，否则，返回没有成功处理。
        // 微信返回消息特点：post，xml格式，回调路由不携带参数
        $notify = new WxNotifyService();
        $wxConfig = new \WxPayConfig();
        $notify->Handle($wxConfig);
    }
}