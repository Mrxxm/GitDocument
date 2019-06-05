<?php
/**
 * Created by PhpStorm.
 * User: xuxiaomeng
 * Date: 2019/2/28
 * Time: 下午3:24
 */

namespace app\api\service;

use app\api\model\Product;
use app\lib\enum\OrderStatusEnum;
use think\Db;
use think\Exception;
use think\Loader;
use app\api\model\Order as OrderModel;
use app\api\service\Order as OrderService;

Loader::import('WxPay.WxPay', EXTEND_PATH, '.Api.php');

class WxNotify extends \WxPayNotify
{
    /*
     * <xml>
        <appid><![CDATA[wx2421b1c4370ec43b]]></appid>
        <attach><![CDATA[支付测试]]></attach>
        <bank_type><![CDATA[CFT]]></bank_type>
        <fee_type><![CDATA[CNY]]></fee_type>
        <is_subscribe><![CDATA[Y]]></is_subscribe>
        <mch_id><![CDATA[10000100]]></mch_id>
        <nonce_str><![CDATA[5d2b6c2a8db53831f7eda20af46e531c]]></nonce_str>
        <openid><![CDATA[oUpF8uMEb4qRXf22hE3X68TekukE]]></openid>
        <out_trade_no><![CDATA[1409811653]]></out_trade_no>
        <result_code><![CDATA[SUCCESS]]></result_code>
        <return_code><![CDATA[SUCCESS]]></return_code>
        <sign><![CDATA[B552ED6B279343CB493C5DD0D78AB241]]></sign>
        <sub_mch_id><![CDATA[10000100]]></sub_mch_id>
        <time_end><![CDATA[20140903131540]]></time_end>
        <total_fee>1</total_fee>
        <coupon_fee><![CDATA[10]]></coupon_fee>
        <coupon_count><![CDATA[1]]></coupon_count>
        <coupon_type><![CDATA[CASH]]></coupon_type>
        <coupon_id><![CDATA[10000]]></coupon_id>
        <coupon_fee><![CDATA[100]]></coupon_fee>
        <trade_type><![CDATA[JSAPI]]></trade_type>
        <transaction_id><![CDATA[1004400740201409030005092168]]></transaction_id>
       </xml>
     */

    public function NotifyProcess($objData, $config, &$msg)
    {
        // result_code才是判断是否是否成功的标准
        if ($objData['result_code'] == 'SUCCESS') {
            $orderNo = $objData['out_trade_no'];

            // 添加事务保证库存不被多次减少
            Db::startTrans();
            try{
                $order = OrderModel::where('order_no', '=', $orderNo)
                    ->find();
                // 订单为未支付状态，才处理
                if ($order->status == 1) {
                    // 库存检测
                    $orderService = new OrderService();
                    $stockStatus = $orderService->checkOrderStock($order->id);
                    if ($stockStatus['pass']) {
                        // 更新订单状态
                        $this->updateOrderStatus($order->id, true);
                        // 减库存
                        $this->reduceStock($stockStatus);
                    } else {
                        $this->updateOrderStatus($order->id, false);
                    }

                    Db::commit();
                    // NotifyProcess()父类方法中，返回给微信true表示成功处理，false表示处理失败
                    // 成功处理，通知微信不需要继续发送回调通知
                    return true;
                }
            } catch (Exception $e) {
                Db::rollback();
                Log::error($e);
                return false;
            }
        } else {

            // 这里返回的true或false控制的是微信是否发送异步回调通知
            return true;
        }
    }

    /*
     * $stockStatus 结构
     * $stockStatus = [
            'pass' => true,
            'orderPrice' => 0,
            'totalCount' => 0,
            'pStatusArray' => []
        ];

        array_push($status['pStatusArray'], $pStatus);

        $pStatus = [
            'id' => null,
            'haveStock' => false,
            'count' => 0,
            'name' => '',
            'totalPrice' => 0
        ];
     */

    private function reduceStock($stockStatus)
    {
        foreach ($stockStatus['pStatusArray'] as $pStatus) {
            Product::where('id', '=', $pStatus['id'])
                ->setDec('stock', $pStatus['count']);
        }
    }

    private function updateOrderStatus($orderId, $success)
    {
        $status = $success ?
            OrderStatusEnum::PAID :
            OrderStatusEnum::PAID_BUT_OUT_OF;
        OrderModel::where('id', '=', $orderId)
            ->update(['status' => $status]);
    }
}