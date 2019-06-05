<?php
/**
 * Created by PhpStorm.
 * User: xuxiaomeng
 * Date: 2019/2/15
 * Time: 下午4:49
 */

namespace app\api\model;


class ProductImage extends BaseModel
{
    protected $hidden = ['img_id', 'delete_time', 'product_id'];

    /*
     * productImage表关联Image表 一对一的关系
     */
    public function imgUrl()
    {
        return $this->belongsTo('Image', 'img_id', 'id');
    }
}