<?php
/**
 * Created by PhpStorm.
 * User: xuxiaomeng
 * Date: 2019/2/27
 * Time: 下午4:57
 */

namespace app\lib\enum;


class OrderStatusEnum
{
    const UNPAID = 1;

    const PAID = 2;

    // 已发货
    const DELIVERED = 3;

    // 已支付，但库存不足
    const PAID_BUT_OUT_OF = 4;
}