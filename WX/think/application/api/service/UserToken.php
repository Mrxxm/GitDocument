<?php

namespace app\api\service;
/**
 * Created by PhpStorm.
 * User: xuxiaomeng
 * Date: 2019/2/10
 * Time: 下午8:36
 */
use app\api\model\User as UserModel;
use app\lib\enum\ScopeEnum;
use app\lib\exception\TokenException;

class UserToken extends Token
{
    protected $code;

    protected $appId;

    protected $appSecret;

    protected $loginUrl;

    function __construct($code)
    {
        $this->code = $code;
        $this->appId = config('wx.app_id');
        $this->appSecret = config('wx.app_secret');
        $this->loginUrl = sprintf(config('wx.login_url'), $this->appId, $this->appSecret, $this->code);
    }

    public function get()
    {
        $result = curl_get($this->loginUrl);
        // true转化为数组，否则是个对象
        $wxResult = json_decode($result, true);

        if (empty($wxResult)) {

            // 不想把错误返回到客户端，使用think框架的异常
            throw new Exception('获取open_id，session_key异常，微信内部错误');

        } else {

            // 判断errorcode是否存在
            $loginFail = array_key_exists('errorcode', $wxResult);

            if ($loginFail) {
                // 失败
                $this->processLoginError($wxResult);
            } else {
                // 成功(授权令牌)
                return $this->grantToken($wxResult);
            }
        }
    }

    // 对于微信返回errorcode，异常处理方法 (错误返回到客户端，需要自定义异常)
    private function processLoginError($wxResult)
    {
        throw new \app\lib\exception\WeChatException([
            'msg' => $wxResult['errmsg'],
            'errorCode' => $wxResult['errcode']
        ]);
    }

    // 颁发令牌
    private function grantToken($wxResult)
    {
        // 拿到open_id
        $openid = $wxResult['openid'];
        // 查询数据库，判断open_id是否存在，不存在则新增一条记录，存在则不处理
        $user = UserModel::getByOpenId($openid);
        if ($user) {
            $uId = $user->id;
        } else {
            $uId = $this->newUser($openid);
        }
        // 1)准备缓存数据 2)生成令牌  3)写入缓存
        $cacheValue = $this->prepareCacheValue($wxResult, $uId);
        // 把令牌返回到客户端
        $token = $this->saveToCache($cacheValue);

        return $token;
    }

    private function newUser($openid)
    {
        $user = UserModel::create([
            'openid' => $openid
        ]);

        return $user->id;
    }

    private function prepareCacheValue($wxResult, $uId)
    {
        $cacheValue = $wxResult;
        $cacheValue['uId'] = $uId;
        // scope=16 代表APP用户权限数值
        $cacheValue['scope'] = ScopeEnum::User;
        // scope=32 代表CMS管理员权限数值
        // $cacheValue['scope'] = ScopeEnum::Super;
        return $cacheValue;
    }

    private function saveToCache($cacheValue)
    {
        $key = self::generateToken();
        // 数组转化成字符串
        $value = json_encode($cacheValue);
        $expire_in = config('setting.token_expire_in');

        // 存入缓存(默认使用文件缓存系统)
        $request = cache($key, $value, $expire_in);
        if (!$request) {
            throw new TokenException([
                'msg' => '服务器缓存异常',
                'errorCode' => 10005
            ]);
        }

        return $key;
    }
}