<?php
/**
 * Created by PhpStorm.
 * User: xuxiaomeng
 * Date: 2019/1/2
 * Time: 上午9:44
 */

namespace app\api\controller\v1;

use app\api\validate\IDMustBePositiveInt;
use app\api\model\Banner as BannerModel;
use app\lib\exception\BannerMissException;

class Banner
{
    /**
     * 获取指定id的banner信息
     * @url /banner/:id
     * @http GET
     * @id bannerd的id号
     */
    public function getBanner($id)
    {
        (new IDMustBePositiveInt())->goCheck();

        /**
         * @异常
         * 1.数据库查询报错
         * 2.banner为空
         */
        $banner = BannerModel::getBannerByID($id);

        if (empty($banner)) {
            throw new BannerMissException();
        }
        return json($banner);
    }
}