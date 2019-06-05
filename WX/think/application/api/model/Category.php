<?php
/**
 * Created by PhpStorm.
 * User: xuxiaomeng
 * Date: 2019/2/10
 * Time: 下午3:01
 */

namespace app\api\model;


class Category extends BaseModel
{
    protected $hidden = ['delete_time', 'update_time', 'delete_time'];
    // 关联img表,关系是一对一
    public function img()
    {
        return $this->belongsTo('Image', 'topic_img_id', 'id');
    }

}