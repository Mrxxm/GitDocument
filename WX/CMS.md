### 14-1 理解CMS

### 14-2 访问CMS

[本地CMS地址](http://www.think.com/cms/pages/login.html)

### 14-3 应用令牌

实现该路由流程完成登录：

```
Route::post('api/:version/token/app', 'api/:version.Token/getAppToken');
```

### 14-4 获取订单

实现订单路由流程编写：

```
Route::get('api/:version/order/paginate', 'api/:version.Order/getSummary');
```

### 14-5 微信模板消息介绍

不是主动推送，而是被动响应的一个过程。

[模板消息链接地址](https://developers.weixin.qq.com/miniprogram/dev/framework/open-ability/template-message.html)

### 14-6/7 实现模板消息发送

发货路由编写：

```
Route::put('api/:version/order/delivery', 'api/:version.Order/delivery'); // cms
```