<?php
/**
 * Created by PhpStorm.
 * User: xuxiaomeng
 * Date: 2019/2/25
 * Time: 下午4:26
 */

namespace app\api\controller\v1;


use app\api\controller\BaseController;
use app\api\validate\IDMustBePositiveInt;
use app\api\validate\OrderPlace;
use app\api\service\Token as TokenService;
use app\api\service\Order as OrderService;
use app\api\model\Order as OrderModel;
use app\api\validate\PagingParameter;
use app\lib\exception\OrderException;

class Order extends BaseController
{
    protected $beforeActionList = [
        'checkExclusiveScope' => ['only' => 'placeOrder'],
        'checkPrimaryScope' => ['only' => 'getDetail, getSummaryByUser']
    ];

    // 用户在选择商品后，向API提交包含它所选择商品的相关信息
    // API在接收到信息后，需要检查订单相关商品的库存量
    // 有库存，把订单数据存入数据库中 = 下单成功了，返回客户端消息，告诉客户端可以支付了
    // 调用我们的支付接口，进行支付
    // 还需要再次进行库存量检测
    // 服务器这边就可以调用微信的支付接口，进行支付
    // 微信会返回给我们一个支付的结果 (异步)
    // 成功：也需要库存量的检查
    // 成功：进行库存量的扣除，失败：返回一个支付失败的结果
    /*
     * 只允许用户访问
     */
    public function placeOrder()
    {
        (new OrderPlace())->goCheck();
        $products = input('post.products/a');
        $uId = TokenService::getCurrentUId();

        $orderService = new OrderService();
        $status = $orderService->place($uId, $products);

        return $status;
    }

    // 订单列表
    public function getSummaryByUser($page = 1, $size = 15)
    {
        (new PagingParameter())->goCheck();
        $uId = TokenService::getCurrentUId();
        $pagingData = OrderModel::getSummaryByUser($uId, $page, $size);
        if ($pagingData->isEmpty()) {
            return [
                'data' => [],
                'current_page' => $pagingData->getCurrentPage()
            ];
        }
        $data = $pagingData->hidden(['snap_items', 'snap_address', 'prepay_id'])->toArray();
        return [
            'data' => $data,
            'current_page' => $pagingData->getCurrentPage()
        ];
    }

    // 订单详情
    public function getDetail($id)
    {
        (new IDMustBePositiveInt())->goCheck();
        $orderDetail = OrderModel::get($id);
        if (empty($orderDetail)) {
            throw new OrderException();
        }

        return $orderDetail->hidden(['prepay_id']);
    }
}