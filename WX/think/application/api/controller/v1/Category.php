<?php
/**
 * Created by PhpStorm.
 * User: xuxiaomeng
 * Date: 2019/2/10
 * Time: 下午3:00
 */

namespace app\api\controller\v1;
use app\api\model\Category as CategoryModel;
use app\lib\exception\CategoryException;


class Category
{
    public function getAllCategories()
    {
        // $categories = CategoryModel::with('img')->select();
        // all()的第一个参数可以传入一组id，如果查询全部就传一个空数组
        $categories = CategoryModel::all([],'img');

        if ($categories->isEmpty())
        {
            throw new CategoryException();
        }
        return json($categories);
    }
}