<?php
/**
 * Created by PhpStorm.
 * User: xuxiaomeng
 * Date: 2019/1/15
 * Time: 上午8:58
 */

namespace app\api\validate;


class IDCollection extends BaseValidate
{
    protected $rule = [
        'ids' => 'require|checkIDs'
    ];

    protected $message = [
        'ids' => 'ids参数必须是以逗号分隔的多个正整数'
    ];

    // $value = $ids
    protected function checkIDs($value)
    {
        $values = explode(',', $value);
        if (empty($values)) {
            return false;
        }

        foreach ($values as $key => $id)
        {
           return $this->isPositiveInteger($id);
        }
        return true;
    }
}