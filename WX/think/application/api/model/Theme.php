<?php

namespace app\api\model;

class Theme extends BaseModel
{
    protected $hidden = ['delete_time', 'update_time', 'topic_img_id', 'head_img_id'];

    // 一对一关系
    public function topicImg()
    {
        return $this->belongsTo('Image', 'topic_img_id', 'id');
    }

    // 一对一关系
    public function headImg()
    {
        return $this->belongsTo('Image', 'head_img_id', 'id');
    }

    // 多对多的关系
    public function products()
    {
        return $this->belongsToMany('Product', 'theme_product', 'product_id', 'theme_id');
    }

    public static function getThemeByIds($ids)
    {
        return self::with(['topicImg', 'headImg'])->select($ids);
    }

    public static function getThemeWithProducts($id)
    {
        // restful针对资源，所以要返回表中所有资源，包括topicImg
        return self::with(['products', 'topicImg', 'headImg'])->find($id);
    }
}
