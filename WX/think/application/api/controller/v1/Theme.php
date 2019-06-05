<?php

namespace app\api\controller\v1;

use app\api\validate\IDCollection;
use app\api\model\Theme as ThemeModel;
use app\api\validate\IDMustBePositiveInt;
use app\lib\exception\ThemeException;

class Theme
{
    /**
     * 获取列表theme信息
     * @url /theme?ids=id1,id2,id3...
     * @http GET
     */
    public function getSimpleList($ids = '')
    {
        (new IDCollection())->goCheck();
        $ids = explode(',', $ids);
        $result = ThemeModel::getThemeByIds($ids);

        if (empty($result)) {
            throw new ThemeException();
        }
        return json($result);
    }

    /*
     * 获取主题下product列表
     * @url /theme/:id
     */
    public function getComplexOne($id)
    {
        (new IDMustBePositiveInt())->goCheck();
        $theme = ThemeModel::getThemeWithProducts($id);
        if (empty($theme)) {
            throw new ThemeException();
        }
        return $theme;
    }
}
