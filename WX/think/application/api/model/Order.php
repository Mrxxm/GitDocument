<?php
/**
 * Created by PhpStorm.
 * User: xuxiaomeng
 * Date: 2019/2/26
 * Time: 下午8:32
 */

namespace app\api\model;


class Order extends BaseModel
{
    protected $hidden = ['user_id', 'delete_time', 'update_time'];

    protected $autoWriteTimestamp = true;

    public function getSnapItemsAttr($value)
    {
        if (empty($value)) {
            return null;
        }

        return json_decode($value);
    }

    public function getSnapAddressAttr($value)
    {
        if (empty($value)) {
            return null;
        }

        return json_decode($value);
    }

    public static function getSummaryByUser($uId, $page, $size)
    {
        $pagingData = self::where('user_id', '=', $uId)
            ->order('create_time desc')
            ->paginate($size, true, ['page' => $page]); // 返回的是Paginator对象

        return $pagingData;
    }
}