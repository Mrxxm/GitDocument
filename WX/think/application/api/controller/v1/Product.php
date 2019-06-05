<?php
/**
 * Created by PhpStorm.
 * User: xuxiaomeng
 * Date: 2019/2/10
 * Time: 下午1:15
 */

namespace app\api\controller\v1;


use app\api\validate\Count;
use app\api\model\Product as ProductModel;
use app\api\validate\IDMustBePositiveInt;
use app\lib\exception\ProductException;

class Product
{
    /*
     * 获取最新商品(主页)
     */
    public function getRecent($count = 15)
    {
        (new Count())->goCheck();

        $result = ProductModel::getMostRecent($count);

        if (empty($result)) {
            throw new ProductException();
        }

        // 数据封装成对象
        // $collection = collection($result);
        // 临时隐藏summary字段 (在database.php配置中，修改返回类型为collection)
        $result = $result->hidden(['summary']);

        return json($result);
    }

    /*
     * 获取分类中的商品(分类)
     */
    public function getAllInCategory($id)
    {
        (new IDMustBePositiveInt())->goCheck();

        $products = ProductModel::getProductsByCategoryId($id);

        if ($products->isEmpty()) {
            throw new ProductException();
        }
        $products = $products->hidden(['summary']);

        return json($products);
    }

    /**
     * 获取单个商品详情
     *
     */
    public function getOne($id)
    {
        (new IDMustBePositiveInt())->goCheck();

        $product = ProductModel::getProductDetail($id);

        if (empty($product)) {
            throw new ProductException();
        }

        return json($product);
    }

    /**
     * 删除单个商品
     */
    public function deleteOne($id)
    {

    }
}