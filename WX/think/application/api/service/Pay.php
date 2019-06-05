<?php
/**
 * Created by PhpStorm.
 * User: xuxiaomeng
 * Date: 2019/2/27
 * Time: 下午3:46
 */

namespace app\api\service;


use app\lib\enum\OrderStatusEnum;
use app\lib\exception\OrderException;
use app\lib\exception\TokenException;
use think\Config;
use think\Exception;
use app\api\model\Order as OrderModel;
use app\api\service\Order as OrderService;
use app\api\service\Token as TokenService;
use think\Loader;
use think\Log;

Loader::import('WxPay.WxPay', EXTEND_PATH, '.Api.php');

class Pay
{
    private $orderId;
    private $orderNo;

    function __construct($orderId)
    {
        if (empty($orderId)) {
            throw new Exception('订单号不允许为空');
        }
        $this->orderId = $orderId;
    }

    public function pay()
    {
        // 订单前置检测
        $this->checkOrderValid();

        // 进行库存量检测
        $orderService = new OrderService();
        $orderStatus = $orderService->checkOrderStock($this->orderId);

        if (!$orderStatus['pass']) {
            return $orderStatus;
        }

        // 向微信服务器请求预订单接口
        return $this->makeWxPreOrder($orderStatus['orderPrice']);
    }

    /*
     * 向微信服务器请求预订单接口
     * 这里开始调用微信的SDK，方便请求
     * 前置参数$openId,身份标识
     */
    private function makeWxPreOrder($totalPrice)
    {
        $openId = TokenService::getCurrentTokenVar('openid');
        if (empty($openId)) {
            throw new TokenException();
        }

        // 实例化、赋值微信支付对象
        $wxOrderData = new \WxPayUnifiedOrder();
        $wxOrderData->SetOut_trade_no($this->orderNo);
        $wxOrderData->SetTrade_type('JSAPI');
        $wxOrderData->SetTotal_fee($totalPrice * 100);
        $wxOrderData->SetBody('向往的生活');
        $wxOrderData->SetOpenid($openId);
        $wxOrderData->SetNotify_url(config('secure.pay_back_url')); // 回调地址,

        // 调用预订单请求接口方法
        return $this->getPaySignature($wxOrderData);
    }

    // 调用预订单请求接口方法
    private function getPaySignature($wxOrderData)
    {
        $wxPayConfig = new \WxPayConfig();
        // 发送预订单请求
        $wxOrder = \WxPayApi::unifiedOrder($wxPayConfig, $wxOrderData);
        if ($wxOrder['return_code'] != 'SUCCESS' ||
        $wxOrder['result_code'] != 'SUCCESS') {
            Log::record($wxOrder, 'error');
            Log::record('获取预支付订单失败', 'error');
        }

        // prepay_id 作用：向用户推送模板消息
        $this->recordPreOrder($wxOrder);

        // 封装返回客户端的参数和签名
        return $this->sign($wxOrder);
    }

    // 封装返回客户端的参数和签名
    private function sign($wxOrder)
    {
        $jsApiPayData = new \WxPayJsApiPay();
        $jsApiPayData->SetAppid(config('wx.app_id'));
        $jsApiPayData->SetTimeStamp((string)time());

       $rand = md5(time() . mt_rand(0, 1000));
        $jsApiPayData->SetNonceStr($rand);

        $jsApiPayData->SetPackage('prepay_id=' . $wxOrder['prepay_id']);
        $jsApiPayData->SetSignType('md5');

        // 以上部分是参数，以下生成签名
        $sign = $jsApiPayData->MakeSign();

        // 获取数组，返回到小程序
        $rawValues = $jsApiPayData->GetValues();
        $rawValues['paySign'] = $sign;
        unset($rawValues['appId']);

        return $rawValues;
    }

    // 记录返回回来的支付参数中的prepay_id
    private function recordPreOrder($wxOrder)
    {
        OrderModel::where('id', '=', $this->orderId)
            ->update(['prepay_id' => $wxOrder['prepay_id']]);
    }

    /*
     * 判断订单号是否存在
     * 判断订单用户和当前用户是否匹配
     * 判断订单是否被支付过
     */
    private function checkOrderValid()
    {
        // 判断订单号是否存在
        $order = OrderModel::where('id', '=', $this->orderId)
            ->find();
        if (empty($order)) {
            throw new OrderException();
        }

        // 判断订单用户和当前用户是否匹配
        $result = Token::isValidOpera($order->user_id);
        if (!$result) {
            throw new TokenException([
                'msg' => '订单与用户不匹配',
                'errorCode' => 10003
            ]);
        }

        // 判断订单是否被支付过
        if ($order->status != OrderStatusEnum::UNPAID) {
            throw new OrderException(
                [
                    'msg' => '不是未支付订单',
                    'errorCode' => 80003,
                    'code' => 400
                ]
            );
        }

        $this->orderNo = $order->order_no;

        return true;
    }

}