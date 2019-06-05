<?php
/**
 * Created by PhpStorm.
 * User: xuxiaomeng
 * Date: 2019/2/24
 * Time: 下午8:56
 */

namespace app\api\controller\v1;


use app\api\controller\BaseController;
use app\api\model\UserAddress;
use app\api\validate\AddressNew;
use app\api\service\Token as TokenService;
use app\api\model\User as UserModel;
use app\lib\exception\SuccessMessage;
use app\lib\exception\UserException;

class Address extends BaseController
{
    protected $beforeActionList = [
        'checkPrimaryScope' => ['only' => 'createOrUpdateAddress,getUserAddress']
    ];

    /*
     * 用户管理员都可访问
     */
    public function createOrUpdateAddress()
    {
        $validate = new AddressNew();
        $validate->goCheck();

        // 1.根据Token获取Uid
        $uid = TokenService::getCurrentUId();

        // 2.根据Uid来查找用户数据，判断用户是否存在，如果不存在抛出异常
        $user = UserModel::get($uid);
        if (empty($user)) {
            throw new UserException();
        }
        // 3.获取用户从客户端提交来的地址信息
        $dataArray = $validate->getDataByRule(input('post.'));
        // 4.根据用户地址信息是否存在，从而判断是添加地址还是更新
        $userAddress = $user->address; // 模型方式获取
        if (empty($userAddress)) {
            // address()这个叫关联模型
            $user->address()->save($dataArray);
        } else {
            // address类似属性访问
            $user->address->save($dataArray);
        }

        return json(new SuccessMessage(), 201);
    }

    // 获取用户地址
    public function getUserAddress()
    {
        $uId = TokenService::getCurrentUId();
        $userAddress = UserAddress::where('user_id', $uId)
            ->find();
        if (!$userAddress) {
            throw new UserException([
                'msg' => '用户地址不存在',
                'errorCode' => 60001
            ]);
        }

        return json($userAddress);
    }
}