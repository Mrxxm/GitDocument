<?php
/**
 * Created by PhpStorm.
 * User: xuxiaomeng
 * Date: 2019/1/2
 * Time: 下午4:08
 */

namespace app\api\model;

class Banner extends BaseModel
{
    protected $hidden = ['update_time', 'delete_time'];

    public function items()
    {
        // 一对多关系 第一个参数关联模型的名字 第二个参数是两个关联属性 第三个参数是当前模型的主键
        return $this->hasMany('BannerItem', 'banner_id', 'id');
    }

    public static function getBannerByID($id)
    {
        // $result = Db::query('select * from banner_item where banner_id = ?', [$id]);

        /*
         * find 单条记录,一维数组
         * select 多条记录,二维数组
         */
        //  $result = Db::table('banner_item')
        //  ->where('banner_id', '=', $id)
        //  ->select();

        //  return $result;
        $banner = self::with(['items', 'items.img'])->find($id);

        return $banner;
    }
}