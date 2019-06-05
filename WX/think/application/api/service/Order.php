<?php
/**
 * Created by PhpStorm.
 * User: xuxiaomeng
 * Date: 2019/2/26
 * Time: 下午3:12
 */

namespace app\api\service;


use app\api\model\OrderProduct;
use app\api\model\Product;
use app\api\model\UserAddress;
use app\lib\exception\OrderException;
use think\Db;
use think\Exception;

class Order
{
    /**
     * 示例$oProducts 和 $products

    protected $oProducts = [
        [
            'product_id' => 1,
            'count' => 1
        ],
        [
            'product_id' => 2,
            'count' => 1
        ],
        [
            'product_id' => 3,
            'count' => 1
        ]
    ];

    protected $products = [
        [
            'product_id' => 1,
            'count' => 100
        ],
        [
            'product_id' => 2,
            'count' => 100
        ],
        [
            'product_id' => 3,
            'count' => 100
        ]
    ];
     */

    // 订单商品列表，也就是客户端传递过来的products参数
    protected $oProducts;

    // 真实的商品信息(包括库存量)
    protected $products;

    protected $uId;

    // 下单
    public function place($uId, $oProducts)
    {
        // oProducts 和 Products 作对比
        // Products从数据库中查询出
        $this->oProducts = $oProducts;
        $this->products = $this->getProductsByOrder($oProducts);
        $this->uId = $uId;
        $status = $this->getOrderStatus();
        if (!$status['pass']) {
            $status['order_id'] = -1;
            return $status;
        }

        // 开始创建订单
        $orderSnap = $this->snapOrder($status);
        $order = $this->createOrder($orderSnap);
        $order['pass'] = true;

        return $order;
    }

    // 生成订单
    private function createOrder($snap)
    {
        Db::startTrans();
        try {
            $orderNo = self::makeOrderNo();
            $order = new \app\api\model\Order();
            $order->user_id = $this->uId;
            $order->order_no = $orderNo;
            $order->total_price = $snap['orderPrice'];
            $order->total_count = $snap['totalCount'];
            $order->snap_img = $snap['snapImg'];
            $order->snap_name = $snap['snapName'];
            $order->snap_address = $snap['snapAddress'];
            $order->snap_items = json_encode($snap['pStatus']);

            $order->save();

            // 向order_product数据表插入数据
            $orderId = $order->id;
            $createTime = $order->create_time;
            foreach ($this->oProducts as &$p) {
                $p['order_id'] = $orderId;
            }
            $orderProduct = new OrderProduct();
            $orderProduct->saveAll($this->oProducts);

            Db::commit();

            return [
                'order_no' => $orderNo,
                'order_id' => $orderId,
                'create_time' => $createTime
            ];
        } catch (Exception $e) {
            Db::rollback();
            throw $e;
        }
    }

    public static function makeOrderNo()
    {
        $yCode = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J');
        $orderSn =
            $yCode[intval(date('Y')) - 2017] . strtoupper(dechex(date('m'))) .
            date('d') . substr(time(), -5) . substr(microtime(), 2, 5) .
            sprintf('%02d', rand(0, 99));

        return $orderSn;
    }

    // 生成订单快照
    private function snapOrder($status)
    {
        $snap = [
            'orderPrice' => 0,
            'totalCount' => 0,
            'pStatus' => [],
            'snapAddress' => '',
            'snapName' => '',
            'snapImg' => ''
        ];

        $snap['orderPrice'] = $status['orderPrice'];
        $snap['totalCount'] = $status['totalCount'];
        $snap['pStatus'] = $status['pStatusArray'];
        $snap['snapAddress'] = json_encode($this->getUserAddress());
        $snap['snapImg'] = $this->products[0]['main_img_url'];
        if (count($this->products) > 1) {
            $snap['snapName'] = $this->products[0]['name'] . '等';
        }

        return $snap;
    }

    private function getUserAddress()
    {
        $userAddress = UserAddress::where('user_id', '=', $this->uId)
            ->find();
        if (!$userAddress) {
            throw new UserException(
                [
                    'msg' => '用户收货地址不存在，下单失败',
                    'errorCode' => 60001
                ]
            );
        }

        return $userAddress->toArray();
    }

    /*
     * 供外部使用，检查库存量
     * 主要使用内部方法getOrderStatus()和getProductStatus()
     * 需要的参数$oProducts和$products
     * $oProducts参数已经存在于数据表order_product中
     */
    public function checkOrderStock($orderId)
    {
        $oProducts = OrderProduct::where('order_id', '=', $orderId)
            ->select();
        $this->oProducts = $oProducts;
        $this->products = $this->getProductsByOrder($oProducts);
        $status = $this->getOrderStatus();

        return $status;
    }

    // 方法中o开头变量代表客户端商品，p开头的变量代表数据库商品
    private function getOrderStatus()
    {
        $status = [
            'pass' => true,
            'orderPrice' => 0,
            'totalCount' => 0,
            'pStatusArray' => []
        ];

        foreach ($this->oProducts as $oProduct) {
            $pStatus = $this->getProductStatus(
                $oProduct['product_id'], $oProduct['count'], $this->products);
            if (!$pStatus['haveStock']) {
                $status['pass'] = false;
            }
            $status['orderPrice'] += $pStatus['totalPrice'];
            $status['totalCount'] += $pStatus['counts'];
            array_push($status['pStatusArray'], $pStatus);
        }

        return $status;
    }

    // 根据客户端商品信息对比实际数据库商品信息，返回
    private function getProductStatus($oPId, $oCount, $products)
    {
        $pIndex = -1;

        $pStatus = [
            'id' => null,
            'haveStock' => false,
            'counts' => 0,
            'price' => 0,
            'name' => '',
            'totalPrice' => 0,
            'main_img_url' => null,
        ];

        for ($i = 0; $i < count($products); $i++) {
            if ($oPId == $products[$i]['id']) {
                $pIndex = $i;
            }
        }

        if ($pIndex == -1) {
            // 客户端传递product_id可能不存在
            throw new OrderException(
                [
                    'msg' => 'id为' . $oPId . '的商品不存在，创建订单失败'
                ]
            );
        } else {
            $product = $products[$pIndex];
            $pStatus['id'] = $product['id'];
            $pStatus['name'] = $product['name'];
            $pStatus['counts'] = $oCount;
            $pStatus['price'] = $product['price'];
            $pStatus['main_img_url'] = $product['main_img_url'];
            $pStatus['totalPrice'] = $product['price'] * $oCount;
            if ($product['stock'] - $oCount >= 0) {
                $pStatus['haveStock'] = true;
            }
        }

        return $pStatus;
    }

    // 通过订单信息查找真实的产品信息
    private function getProductsByOrder($oProducts)
    {
        // 避免循环查询数据库
        $oPIds = [];
        foreach ($oProducts as $item) {
            array_push($oPIds, $item['product_id']);
        }

        // 查询
        $products = Product::all($oPIds)
            ->visible(['id', 'price', 'stock', 'name', 'main_img_url'])
            ->toArray();

        return $products;
    }
}