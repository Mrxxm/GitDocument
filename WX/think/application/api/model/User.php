<?php
/**
 * Created by PhpStorm.
 * User: xuxiaomeng
 * Date: 2019/2/10
 * Time: 下午8:33
 */

namespace app\api\model;


class User extends BaseModel
{
    public function address()
    {
        // 一对一
        // 在没有外键的一方定义一对一关系用hasOne
        // 在有外键的一方定义一对一关系用belongTo,例子见BannerItem
        return $this->hasOne('UserAddress', 'user_id', 'id');
    }

    public static function getByOpenId($openid)
    {
        $user = self::where('openid', '=', $openid)
            ->find();

        return $user;
    }

}