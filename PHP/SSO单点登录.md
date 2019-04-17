#### SSO 使用场景

登录一次便可访问多个应用系统

#### SSO介绍与HTTP会话机制

http是无状态协议，每次访问需要带上身份标识。

但是每次输入账号密码不可取，且把账号密码保存在浏览器也不可取。

会话机制：session的概念，会话标识一般是一段cookie，也可以使用请求参数来实现就是url问号后面携带的参数。

```
# 第一次请求登录
GET /login?user=xxm&password=123

# 服务端进行校验账号密码生成session，生成session需要session标志
$ openssl rand -hex 8
8af7bb15138ab881

# 随机字符串保存内容
8af7bb15138ab881 => 
{
    "user" => 'xxm',
    "role" => 'admin',
    "nickname" => 'abc'
}

# 一般保存在服务端
/tmp/session.8af7bb15138ab881

# 下发session标识
200 OK HTTP/1.1
Set-Cookie: SessionID=8af7bb15138ab881

# 后续请求
GET /index
Cookie: SessionID=8af7bb15138ab881
```

#### 共享Session实现SSO的限制

* 不同系统语言可能不同

* 不同系统部署在不同地方

* 域名必须同源


#### SSO的原理

* 独立的认证中心

* 临时票据

* 全局Session

* 本地Session

#### SSO验证流程

![](https://img3.doubanio.com/view/photo/l/public/p2553834754.jpg)

![](https://img3.doubanio.com/view/photo/l/public/p2553834803.jpg)

![](https://img3.doubanio.com/view/photo/l/public/p2553834830.jpg)

服务端实现

* 验证用户的登录信息

* 创建全局会话

* 创建授权令牌

* 与sso-client通信发送令牌

* 校验sso-client令牌有效性

* 系统注册(记录哪些系统做了单点登录)

* 接收sso-client注销请求，注销所有会话

客户端实现

* 拦截子系统未登录用户请求，跳转至sso认证中心

* 接收并储存sso认证中心发送的令牌

* 与sso-server通信，校验令牌有效性

* 建立局部会话

* 拦截用户注销请求，向sso认证中心发送注销请求

* 接收sso认证中心发出的注销请求，销毁局部会话

#### SSO认证中心实现（一）

代码演示...