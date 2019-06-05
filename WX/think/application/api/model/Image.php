<?php

namespace app\api\model;

class Image extends BaseModel
{
    protected $hidden = ['id', 'from', 'delete_time', 'update_time'];

    // 读取器 访问url属性自动调用加载
    public function getUrlAttr($url, $data)
    {
        return $this->prefixImgUrl($url, $data);
    }
}
