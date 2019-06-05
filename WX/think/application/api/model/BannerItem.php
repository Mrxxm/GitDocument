<?php

namespace app\api\model;

class BannerItem extends BaseModel
{
    protected $hidden = ['id', 'img_id', 'banner_id', 'update_time', 'delete_time'];

    public function img()
    {
        // 一对一关系
        return $this->belongsTo('Image', 'img_id', 'id');
    }
}
