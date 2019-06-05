<?php

namespace app\api\model;

class Product extends BaseModel
{
    // pivot 多对多关系的中间表
    protected $hidden = ['delete_time', 'update_time', 'create_time', 'main_img_id', 'pivot', 'from', 'category_id'];

    public function getMainImgUrlAttr($url, $data)
    {
        return $this->prefixImgUrl($url, $data);
    }

    public static function getMostRecent($count)
    {
        $product = self::limit($count)
            ->order('create_time desc')
            ->select();

        return $product;
    }

    public static function getProductsByCategoryId($categoryId)
    {
        $products = self::where('category_id' , '=', $categoryId)
            ->select();

        return $products;
    }

    /*
     * 商品详情图 一对多
     */
    public function imgs()
    {
        return $this->hasMany('ProductImage', 'product_id', 'id');
    }

    /*
     * 商品属性 一对多
     */
    public function properties()
    {
        return $this->hasMany('ProductProperty', 'product_id', 'id');
    }

    /*
     * 商品详情
     */
    public static function getProductDetail($id)
    {
        // 闭包查询
        // 详情图片根据order字段顺序显示
        $product = self::with([
            'imgs' => function($query) {
                $query->with(['imgUrl'])
                ->order('order', 'asc');
            }
        ])
            ->with(['properties'])
            ->find($id);

        return $product;
    }

}
