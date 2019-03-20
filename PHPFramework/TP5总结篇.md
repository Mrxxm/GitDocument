## 1.导语

1. AOP 10~20%
2. TP5，小程序，数据库 80%

## 产品所使用的技术

#### ThinkPHP 5

* 编写业务逻辑
* 访问数据库
* 向客户端提供数据

#### MYSQL

* 数据存储
* 数据表设计
* 与业务紧密结合

#### 微信

* 支付
* 善于借鉴与模仿，学习微信接口设计

#### 小程序

* 直接与用户交互
* 体验很重要

## 流程与体系

#### 服务端

* ThinkPHP 5 + MYSQL构建REST API

#### 客户端(用户使用)

* 向服务端请求数据，完成自身行为逻辑

#### CMS(管理员，运营人员使用)

* 向服务端请求数据，实现发货与发送微信消息

#### 总结下CMS的功能

两大类

* 基础数据的增删改查
* 特殊操作，比如我们要实现发送微信消息

## 项目特点

#### 课程特点

* 构建一个通用的，适合互联网公司的，有良好结构的产品

* 三端分离(客户端、服务端和数据管理分离)

* 基于`REST API`

* 基于`Token`令牌管理权限

* 一套架构适配`iOS`，`Android`，小程序与单页面

* 真正理解`MVC`

* `AOP`面向切面编程思想在真实项目中的应用

* 使用`ORM`的方式与数据库交互 (Object Relation Mapping)

* MYSQL数据表设计和数据冗余的合理利用

* 面向对象的思维构建前端代码 (ES6 Class&Module)


## TP5技术点简介

#### TP5

* Web框架三大核心内容(控制器，路由和模型)

* 验证器，读取器，缓存和全局异常处理

* ORM:模型与关联模型
 
## 微信技术点简介

* 微信小程序

* 微信登录

* 微信支付(预订单、库存量检测与回调通知处理)

* 微信模板消息

## MySQL技术点简介

* 数据表的设计

* 数据冗余的合理利用

* 事务与锁在订单(库存量)检测中的应用

## 其他知识点

#### 依赖或者包管理

* composer (PHP)

* npm (node.js)

* pip (Python)


## 2.环境与开发工具

#### Web框架

* ThinkPHP 5.07

* PHP 5.6

* MYSQL

* nginx

#### 客户端

* 小程序

#### 开发工具

* PHPStorm

* 微信Web开发者工具

* PostMan

* Navicat

## 下载ThinkPHP 5

* composer 安装

* Git 安装

* 直接下载

## TP5自带的Web Server

在项目的public目录下:`php -S localhost:8080 router.php`

## `PATH_INFO` URL路径模式解析

#### TP5默认URL路径格式

* `http://serverName/index.php/module/controller/action/[param/value...]` 

* `module`代表模块,模块定义在`application`文件夹下

* URL不区分大小写(TP5默认)
`config.php` 参数 `'url_convert' => true,`将true改成false，就区分大小写

* URL路径格式官方称为：`PATH_INFO`

* 兼容模式：`http://serverName/index.php?s=module/controller/action/p/v`

TP5默认URL路径格式的缺点：

* 太长
* URL路径暴露出了服务器文件结构
* 不够灵活
* 不能很好的支持URL的语义话(最大缺陷)

#### 关于入口文件`index.php`

该文件中定义了`APP_PATH`,规定了应用的目录

* `define('APP_PATH', __DIR__ . '/../application/');`


## 3.模块、路由

## 编写一个简单的模块（多模块与模块命名空间）

一个应用下面可以包含多个模块(默认index模块)

#### 新建模块

一般文件夹名小写，类名大写开头

命名空间，TP5定义的根命名空间名为**app**，后跟上模块文件夹路径 ( `config.php`文件中定义了`'app_namespace' => 'app'`，可修改)



#### 新建完成模块输入路由无法访问

回顾：thinkphp的url访问：`http://serverName/index.php/模块/控制器/操作/[参数名/参数值...]`，这个需要支持pathinfo，Apache默认支持，而Nginx不支持。

* 1.php.ini中的配置参数`cgi.fix_pathinfo = 1`
* 2.修改nginx.conf文件

```
location ~ \.php(.*)$ {
        fastcgi_pass   127.0.0.1:9000;
        fastcgi_index  index.php;
        #下面两句是给fastcgi权限，可以支持 ?s=/module/controller/action的url访问模式
        fastcgi_split_path_info  ^((?U).+\.php)(/?.+)$;
        fastcgi_param  SCRIPT_FILENAME  $document_root$fastcgi_script_name;
        #下面两句才能真正支持 index.php/index/index/index的pathinfo模式
        fastcgi_param  PATH_INFO  $fastcgi_path_info;
        fastcgi_param  PATH_TRANSLATED  $document_root$fastcgi_path_info;
        include        fastcgi_params;
}
```

* 3.去掉/index.php/

```
location / {
    index  index.html index.htm index.php;
    #autoindex  on;

  if (!-e $request_filename) {
    rewrite  ^(.*)$  /index.php?s=/$1  last;
    break;
  }
}
```

修改完1，2两点，即可访问成功，第三点应该是配置的兼容模式，未修改。

## 配置虚拟域名简化URL路径

## 三种URL访问模式

修改Route.php文件,动态注册路由(原有的`PATH_INFO`方法失效)

```
use think\Route;

Route::rule('hello', 'sample/xxm/hello');
```

#### 路由总结

* `PATH_INFO`方式

* 混合模式(既可以用PATH_INFO,又可以用路由方式)

* 强制使用路由模式

```
## 默认使用混合模式

// 是否开启路由
'url_route_on'           => true,
    
// 是否强制使用路由
'url_route_must'         => false,
```

## 定义路由

完整路由定义格式

```
Route::rule('路由表达式', '路由地址', '请求类型', '路由参数(数组)', '变量规则(数组)');

# 请求类型
// GET POST DELETE PUT * (默认任意请求类型都支持)

# 标准格式
Route::rule('hello', 'sample/xxm/hello', 'GET', '['https'=>false]', ['']);

# 同时支持get和post类型路由
Route::rule('hello', 'sample/xxm/hello', 'GET|POST', '['https'=>false]', ['']);

# 快速注册get类型路由
Route:get('hello', 'sample/xxm/hello');
```

## 获取请求参数


* get的两种方式传参

```
# 第一种get传参方式,存在于URL路径中
Route:get('hello/:id', 'sample/xxm/hello');

# 第二种方式在路由后跟?传参
```

#### 控制器获取参数三种方式

##### 1.获取路由参数的第一种方法，默认获取(获取路由中定义的参数和?后面的参数和form-data里面定义的参数)

GET路由

```

# 路由
Route::rule('hello/:id', 'sample/xxm/hello');

# 控制器
public function hello($id, $name)
{
    return "NO." . $id . " hello " . $name;
}

# 输入
http://www.think.com/hello/1?name=xxm

# 输出
NO.1 hello xxm
```

POST路由

```
# 路由
Route::post('hello/:id', 'sample/xxm/hello');

# 控制器
 public function hello($id, $name, $age)
{
    return "NO." . $id . " hello " . $name . " age：" . $age;
}

# 输入
http://www.think.com/hello/1?name=xxm

# form-date
age 24

# 输出
NO.1 hello xxm age：24
```

##### 2.获取路由参数的第二种方法使用Request

param方法获取post路由单个参数

```
# 路由
Route::post('hello/:id', 'sample/xxm/hello');

# 控制器
use think\Request;

public function hello()
{
    // param方法不区分请求类型
    $id = Request::instance()->param('id');
    $name = Request::instance()->param('name');
    $age = Request::instance()->param('age');
    return "NO." . $id . " hello " . $name . " age：" . $age;
}

# 输入
http://www.think.com/hello/1?name=xxm

# form-date
age 24

# 输出
NO.1 hello xxm age：24
```

param方法获取post路由所有参数

```
# 路由
Route::post('hello/:id', 'sample/xxm/hello');

# 控制器
public function hello()
{
    // param方法不区分请求类型
    $data = Request::instance()->param();
        
    return "NO." . $data['id'] . " hello " . $data['name'] . " age：" . $data['age'];
}
    
# 输入
http://www.think.com/hello/1?name=xxm

# form-date
age 24

# 输出
NO.1 hello xxm age：24
```

get方法获取参数(只获取到?后面的参数)

```
# 路由
Route::post('hello/:id', 'sample/xxm/hello');

# 控制器
public function hello()
{
    // param方法不区分请求类型
    $data = Request::instance()->get();
        
    var_dump($data);
}
    
# 输入
http://www.think.com/hello/1?name=xxm

# form-date
age 24

# 输出
'name' => string 'xxm'
```

route方法获取参数(只获取到路由中的参数)

post方法获取参数(只获取到form-data中的参数)


##### 3.获取路由参数的第三种方法使用助手函数

```
# 控制器

$data = input('param.'); // 所有
$id = $data = input('param.id'); 
$name = input('param.name');
$age = input('param.age');
$id = input('route.id');
$name = input('get.name');
$age = input('post.age');
```

#### 依赖注入的方式，替换Request实例获取参数

```
# 控制器
 public function hello(Request $request, $id)
{
    $name = $request->get('name');
    $age = $request->post('age');
    return "NO." . $id . " hello " . $name . " age：" . $age;
}

# 输出
NO.1 hello xxm age：24
```

## 4.验证层

## Validate：独立验证

* 框架中的独立验证

```
// 独立验证
$data = [
    'name' => 'vender12345',
    'email' => 'venderqq.com'
];

$validate = new Validate([
    'name' => 'require|max:10',
    'email' => 'email'
]);

$result = $validate->batch()->check($data);
var_dump($validate->getError());

/**
 * @输出
 * array (size=2)
 * 'name' => string 'name长度不能超过 10' (length=25)
 * 'email' => string 'email格式不符' (length=17)
 */
```

## Validate 验证器

##### 针对具体的验证字段，封装成类

* 验证器`TestValidate`

```
use think\Validate;

class TestValidate extends Validate
{
    protected $rule = [
        'name' => 'require|max:10',
        'email' => 'email'
    ];
}
```

控制器中调用

```
// 验证器
$data = [
    'name' => 'vender12345',
    'email' => 'venderqq.com'
];

$testValidate = new TestValidate();
$result = $testValidate->batch()->check($data);
var_dump($testValidate->getError());
/**
 * @输出
 * array (size=2)
 * 'name' => string 'name长度不能超过 10' (length=25)
 * 'email' => string 'email格式不符' (length=17)
 */
```

## 自定义验证规则

控制器调用check方法，自动调用自定义的验证方法

```
<?php
/**
 * Created by PhpStorm.
 * User: xuxiaomeng
 * Date: 2019/1/2
 * Time: 下午2:10
 */

namespace app\api\validate;


use think\Validate;

class IDMustBePositiveInt extends Validate
{
    protected $rule = [
        'id' => 'require|isPositiveInteger'
    ];

    protected function isPositiveInteger($value, $rule = '', $data = '', $field = '')
    {
        if (is_numeric($value) && is_int($value + 0) && ($value + 0) > 0) {
            return true;
        } else {
            return $field."必须是正整数";
        }
    }
}
```

控制器

```
class Banner
{
    /**
     * 获取指定id的banner信息
     * @url /banner/:id
     * @http GET
     * @id bannerd的id号
     */
    public function getBanner($id)
    {
        $validate = new IDMustBePositiveInt();
        $result = $validate->check(['id' => $id]);
        var_dump($result);
        var_dump($validate->getError());
    }
}
```

## 构建接口参数校验层

* `BaseValidate` (继承`Validate`)

```
<?php

namespace app\api\validate;

use think\Exception;
use think\Request;
use think\Validate;

class BaseValidate extends Validate
{
    public function goCheck()
    {
        /**
         * 获取http传入的所有参数，并对这些参数进行校验
         */
        $params = Request::instance()->param();

        $result = $this->check($params);

        if (!$result) {
           $error = $this->error;
           throw new Exception($error);
        } else {
            return true;
        }
    }
}
```

* ID验证器 (继承`BaseValidate`) [`BaseValidate`在调用完check方法后，进入调用自定义验证方法]

```
<?php

namespace app\api\validate;


use app\api\validate\BaseValidate;

class IDMustBePositiveInt extends BaseValidate
{
    protected $rule = [
        'id' => 'require|isPositiveInteger'
    ];

    protected function isPositiveInteger($value, $rule = '', $data = '', $field = '')
    {
        if (is_numeric($value) && is_int($value + 0) && ($value + 0) > 0) {
            return true;
        } else {
            return $field."必须是正整数";
        }
    }
}
```

* 控制器中调用展示 (一行代码搞定)

```
<?php

namespace app\api\controller\v1;

use app\api\validate\IDMustBePositiveInt;

class Banner
{
    /**
     * 获取指定id的banner信息
     * @url /banner/:id
     * @http GET
     * @id bannerd的id号
     */
    public function getBanner($id)
    {
        $result = (new IDMustBePositiveInt())->goCheck();

    }
}
```

## 5.理解RESTFul

## 介绍下REST之前的重要协议：SOAP

#### 什么是REST

* `Representational State Transfer` 表述性状态转移

* SOAP vs REST

* `Simple Object Access Protocol` 使用XML描述数据


## RESTFul API 的特点解析

#### @ RESTFul API

基于资源，增删改查都只是对于资源状态的改变

使用HTTP动词来操作资源

```
GET：/movie/:mid
```

## RESTFul API的最佳实践

#### @HTTP动词 (幂等性、资源安全性)

POST：创建

PUT：更新

GET：查询

DELETE：删除

#### 状态码

404、400、200、201、202、401、403、500

错误码：自定义的错误ID号

统一描述错误：错误码、错误信息、当前URL

使用Token令牌来授权和验证身份

版本控制

测试与生产环境分开：api.xxx.com dev.api.xxx.com

URL语义要明确，最好可以“望文知意”

最好有一份比较标准的文档

## 6.异常&日志

## 异常的分类

#### 异常分类

**由于用户行为导致的异常(没有通过验证器，没查询到结果)**

通常不需要记录日志、需要向用户返回具体信息

**服务器自身异常(代码错误，调用外部接口错误)**

通常记录日志，不向客户端返回具体原因

## 实现自定义全局异常处理 上


* 修改config配置,指定到重写的子类中

```
'exception_handle'       => 'app\lib\exception\ExceptionHandle',
```

* `ExceptionHandle.php` 重写handle方法

```
<?php

namespace app\lib\exception;

use Exception;
use think\exception\Handle;

class ExceptionHandle extends Handle
{
    // 错误返回到页面显示的方法
    public function render(Exception $e)
    {
        return json("0000000000000");
    }
}
```

异常抛出到全局，都会经过render方法处理。

## 实现自定义全局异常处理 下

#### 重写render方法

区分两种异常，从结果出发分析，“需要向用户返回具体信息”，那么就会定义具体的异常类，且都继承与BaseException。

```
// 错误返回到页面显示的方法
public function render(Exception $e)
{
    if ($e instanceof BaseException) {
        // 自定义异常
    } else {

    }
}
```

完善之后

```
<?php

namespace app\lib\exception;

use Exception;
use think\exception\Handle;
use think\Request;

class ExceptionHandle extends Handle
{
    private $code;

    private $msg;

    private $errorCode;

    // 需要返回客户端当前请求的URL

    // 错误返回到页面显示的方法
    public function render(Exception $e)
    {
        if ($e instanceof BaseException) {
            // 自定义异常
            $this->code = $e->code;
            $this->msg = $e->msg;
            $this->errorCode = $e->errorCode;

        } else {
            // 系统异常
            $this->code = 500;
            $this->msg = 'Server internal error';
            $this->errorCode = 999;
        }

        $result = [
            'msg' => $this->msg,
            'errorCode' => $this->errorCode,
            'request_url' => Request::instance()->url(),
        ];

        return json($result, $this->code);
    }
}
```

## ThinkPHP5中的日志系统

#### TP自动记录日志功能

根据入口文件，`public/index.php`文件中的start.php查找，找到base.php文件中找到`LOG_PATH`,根据`LOG_PATH`定义得出TP5日志自动记录在项目目录下`runtime/log`目录下

在`public/index.php`文件中,重新定义`LOG_PATH`路径

```
define('LOG_PATH', __DIR__ . '/../logs/');
```


## 在全局异常处理中加入日志记录

#### 关闭TP5默认日志行为

在config.php配置文件中，日志配置把type改为test

在`ExceptionHandle.php`文件中,添加方法

```
private function recordErrorLog(Exception $e)
{
    // 日志初始化
    Log::init([
        'type' => 'File',
        'path'  => LOG_PATH,
        'level' => ['error'],
    ]);
    Log::record($e->getMessage(), 'error'); // 第二个参数，定义日志级别
}
```

在`ExceptionHandle.php`文件中的系统异常调用该方法


## 全局异常处理的应用 上 (重要章节)

本章针对服务器错误处理显示，根据客户端、服务器，返回报错信息的不同。客户端/客户需要一个简单的json数据，服务端/开发人员希望得到页面报错(TP5默认页面报错，需要调试)


为了动态的开启或关闭，需要将$switch写到配置文件中

```
public function render(Exception $e)
{
    if ($e instanceof BaseException) {
        // 自定义异常
        $this->code = $e->code;
        $this->msg = $e->msg;
        $this->errorCode = $e->errorCode;

    } else {

        $switch = true;
        if ($switch) {
            return parent::render($e);
        } else {
            // 系统异常
            $this->code = 500;
            $this->msg = 'Server internal error';
            $this->errorCode = 999;
            $this->recordErrorLog($e);
        }
    }
```

通过配置文件中的app_debug参数，替换$switch变量

```
if ($e instanceof BaseException) {
    // 自定义异常
    $this->code = $e->code;
    $this->msg = $e->msg;
    $this->errorCode = $e->errorCode;

} else {

    if (config('app_debug')) {
        return parent::render($e);
    } else {
        // 系统异常
        $this->code = 500;
        $this->msg = 'Server internal error';
        $this->errorCode = 999;
        $this->recordErrorLog($e);
    }
}
```



#### 重要修改

在验证层的goCheck方法中，我们对异常抛出定义修改。由于我们抛出的是系统异常，当开启调试，异常定位到行，当关闭异常，异常抛出永远是999，代码如下

BaseValidate

```
class BaseValidate extends Validate
{
    public function goCheck()
    {
        /**
         * 获取http传入的所有参数，并对这些参数进行校验
         */
        $params = Request::instance()->param();

        $result = $this->check($params);

        if (!$result) {
           $error = $this->error;
           throw new Exception($error);
        } else {
            return true;
        }
    }
}
```

ExceptionHandle

```
// 错误返回到页面显示的方法
public function render(Exception $e)
{
    if ($e instanceof BaseException) {
        // 自定义异常
        $this->code = $e->code;
        $this->msg = $e->msg;
        $this->errorCode = $e->errorCode;

    } else {
        // config TP5提供的助手函数 Config::get('app_debug');
        if (config('app_debug')) {
            return parent::render($e);
        } else {
            // 系统异常
            $this->code = 500;
            $this->msg = 'Server internal error';
            $this->errorCode = 999;
            $this->recordErrorLog($e);
        }
    }

    $result = [
        'msg' => $this->msg,
        'errorCode' => $this->errorCode,
        'request_url' => Request::instance()->url(),
    ];

    return json($result, $this->code);
}
```

So 我们需要自定义一个参数错误的异常，将传入参数错误归为自定义异常一类

```
public function goCheck()
{
    /**
     * 获取http传入的所有参数，并对这些参数进行校验
     */
    $params = Request::instance()->param();

    $result = $this->check($params);

    if (!$result) {
       $error = $this->error;
//           throw new Exception($error);
        $e = new ParameterException();
        $e->msg = $error;
        throw $e;
    } else {
        return true;
    }
}
```

## 全局异常处理的应用 中

#### 细节修改

在BaseValidate抛出异常时，对成员变量进行了修改，最好是在new操作对象的时候就把值传入对象中

BaseException 添加构造方法

```
class BaseException extends Exception
{
    // HTTP状态码 400，200 ...
    public $code = 400;

    // 错误具体信息
    public $msg = 'parameter error';

    // 自定义错误码
    public $errorCode = '10000';

    public function __construct($params = [])
    {
        if (!is_array($params)) {
            return ;
//            throw new Exception('参数必须是数组');
        }
        if (array_key_exists('code', $params)) {
            $this->code = $params['code'];
        }
        if (array_key_exists('msg', $params)) {
            $this->msg = $params['msg'];
        }
        if (array_key_exists('errorCode', $params)) {
            $this->errorCode = $params['errorCode'];
        }

    }
}
```

BaseValidate 验证层 new的方式修改

```
class BaseValidate extends Validate
{
    public function goCheck()
    {
        /**
         * 获取http传入的所有参数，并对这些参数进行校验
         */
        $params = Request::instance()->param();

        $result = $this->check($params);

        if (!$result) {
           $error = $this->error;
//           throw new Exception($error);
            throw new ParameterException(['msg' => $error]);
        } else {
            return true;
        }
    }
}
```

## 全局异常处理的应用 下

对goCheck方法进行完善,加入batch方法

```
class BaseValidate extends Validate
{
    public function goCheck()
    {
        /**
         * 获取http传入的所有参数，并对这些参数进行校验
         */
        $params = Request::instance()->param();

        $result = $this->batch()->check($params); // 加入batch方法，对所有错误参数都校验

        if (!$result) {
           $error = $this->error;
//           throw new Exception($error);
            throw new ParameterException(['msg' => $error]);
        } else {
            return true;
        }
    }
}
```


## 7.数据交互

## 数据库操作三种方式之原生SQL

```
class Banner
{
    public static function getBannerByID($id)
    {
        $result = Db::query('select * from banner_item where banner_id = ?', [$id]);
        return $result;
    }
}
```

## 从一个错误了解Exception的继承关系


在ExceptionHandle类中，将think/Exception改成基类的Exception,为`\Exception`

```
class ExceptionHandle extends Handle
{
    private $code;

    private $msg;

    private $errorCode;

    // 需要返回客户端当前请求的URL

    // 错误返回到页面显示的方法
    public function render(\Exception $e)
    {
        if ($e instanceof BaseException) {
            // 自定义异常
            $this->code = $e->code;
            $this->msg = $e->msg;
            $this->errorCode = $e->errorCode;

        } else {
            // config TP5提供的助手函数 Config::get('app_debug');
            if (config('app_debug')) {
                return parent::render($e);
            } else {
                // 系统异常
                $this->code = 500;
                $this->msg = 'Server internal error';
                $this->errorCode = 999;
                $this->recordErrorLog($e);
            }
        }

        $result = [
            'msg' => $this->msg,
            'errorCode' => $this->errorCode,
            'request_url' => Request::instance()->url(),
        ];

        return json($result, $this->code);
    }

    private function recordErrorLog(\Exception $e)
    {
        // 日志初始化
        Log::init([
            'type' => 'File',
            'path'  => LOG_PATH,
            'level' => ['error'],
        ]);
        Log::record($e->getMessage(), 'error'); // 第二个参数，定义日志级别
    }
}
```

## 查询构造器 一


* find 单条记录,一维数组
* select 多条记录,二维数组

```
public static function getBannerByID($id)
{
//   $result = Db::query('select * from banner_item where banner_id = ?', [$id]);

    /*
     * find 单条记录,一维数组
     * select 多条记录,二维数组
     */
    $result = Db::table('banner_item')->where('banner_id', '=', $id)->select();
    var_dump($result);
    exit;
}
```

## 查询构造器 三

`where('字段名'， '表达式'， '查询条件')`

闭包写法

```
->where( function ($query) use ($id){
    $query->where('banner_id', '=', '$id');
})
```

## 开启SQL日志记录

`fetchSql()`输出sql

```
$result = Db::table('banner_item')
    ->fetchSql()
    ->where('banner_id', '=', $id)
    ->select();
```

开启sql语句自动记录到日志

数据库配置里面把debug改成`debug => true`，在config.php中保证app_debug是为true的，在log日志配置选项，在level里面加入sql这个选项，`'level' => ['sql']`,其实type类型为file时，其实已经就能记录日志，但是我们为了筛选记录的日志，将type改成了test。

以上操作并没有效果，在入口文件index.php初始化日志记录，生效，自定义日志记录之后，ExceptionHandle中的日志记录并不是每一次都能执行，所以在一个请求都能经过的index.php中初始化日志记录，并设置记录类型

```
// 初始化日志记录sql
\think\Log::init([
    'type' => 'File',
    'path' => LOG_PATH,
    'level' => ['sql']
]);
```

## 初识模型

将banner类继承Model类，实现模型方法调用

`class Banner extends Model`

控制器中调用，返回结果是个模型对象

`$banner = BannerModel::get($id);`



## 模型定义总结

业务简单情况下，一个模型对应一张数据表。

关联模型，一个模型对应多张数据表。

默认情况下，模型的类名和表名是一一对应的。也可以在类中添加$table变量来指定数据表

```
protected $table = ‘’;
```

快速创建模型

```
php think make:model api/BannerItem
```

## 几种查询动词的总结与ORM性能问题的探讨

查询

* get()   一条数据(模型)
* find()  一条数据(DB)
* select() 一组数据(DB)
* all()    一组数据(模型)

使用DB不能使用get和all，使用模型可以使用find和select。

## 8.模型

## 模型关联与查询关联

模型中定义关联方法(第一个参数关联模型的名字 第二个参数是两个关联属性 第三个参数是当前模型的主键)

模型关联:一对多

```
class Banner extends Model
{
    public function items()
    {
        return $this->hasMany('BannerItem', 'banner_id', 'id');
    }

}
```

控制器中调用查询:关联查询

```
$banner = BannerModel::with('items')->find($id);
```


结果

```
{"id":1,"name":"首页置顶","description":"首页轮播图","delete_time":null,"update_time":"1970-01-01 08:00:00","items":[{"id":1,"img_id":65,"key_word":"6","type":1,"delete_time":null,"banner_id":1,"update_time":"1970-01-01 08:00:00"},{"id":2,"img_id":2,"key_word":"25","type":1,"delete_time":null,"banner_id":1,"update_time":"1970-01-01 08:00:00"},{"id":3,"img_id":3,"key_word":"11","type":1,"delete_time":null,"banner_id":1,"update_time":"1970-01-01 08:00:00"},{"id":5,"img_id":1,"key_word":"10","type":1,"delete_time":null,"banner_id":1,"update_time":"1970-01-01 08:00:00"}]}
```

## 嵌套关联查询

`with()`方法可以接受数组参数，形成多个关联。

`banner`表关联`banner_item`，再通过`banner_item`表关联`image`表，这个被称为嵌套关联。

快速创建模型

`php think make:model api/Image`

* banner_item,`belongsTo`一对一

```
class BannerItem extends Model
{
    public function img()
    {
        return $this->belongsTo('Image', 'img_id', 'id');
    }
}
```

* 控制器调用:嵌套关联查询

```
$banner = BannerModel::with(['items', 'items.img'])->find($id);
```

## 隐藏模型字段

封装，将模型获取数据方法封装到静态方法中，不暴露在控制器中

控制器

```
$banner = BannerModel::getBannerByID($id);
```

* 控制器中隐藏

`$banner->hidden(['update_time', 'delete_time']);`

* 控制器中显示

`$banner->visible(['id', 'update_time']);`


模型banner中

```
public static function getBannerByID($id)
{
    $banner = self::with(['items', 'items.img'])->find($id);

    return $banner;
}
```

## 在模型内部隐藏字段

在模型内部定义成员变量

`protected $hidden = ['id'];`

`protected $visible = ['id'];`


## 图片资源URL配置

扩展配置文件目录 (自定义的配置文件能够被TP5框架自动加载)

`application/extra`

控制器读取配置

`$url = config('setting.img_prefix');`

## 读取器的巧妙应用

读取器 自动调用加载 image表中的url字段

```
class Image extends Model
{
    protected $hidden = ['id', 'from', 'delete_time', 'update_time'];

    // 读取器 自动调用加载
    public function getUrlAttr($value)
    {
        return config('setting.img_prefix') . $value;
    }
}
```


根据from字段修改 读取器方法 `$data`字段可以获取from字段值

```
// 读取器 自动调用加载
    public function getUrlAttr($value, $data)
{
    $finalUrl = $value;
    if ($data['from'] == 1) {
        $finalUrl = config('setting.img_prefix') . $value;
    }

    return $finalUrl;
}
```

读取器说明 (当执行访问url属性，就自动访问读取器)

```
$image = new Image();
$image->url;
```

## 自定义模型基类


定义baseModel，现在将url拼接放在基类中

```
class BaseModel extends Model
{
    protected function prefixImgUrl($value, $data)
    {
        $finalUrl = $value;
        if ($data['from'] == 1) {
            $finalUrl = config('setting.img_prefix') . $value;
        }

        return $finalUrl;
    }
}

```

在image模型中，保留url读取器，调用基类中的拼接url方法

```
class Image extends BaseModel
{
    protected $hidden = ['id', 'from', 'delete_time', 'update_time'];

    // 读取器 访问url属性自动调用加载
    public function getUrlAttr($url, $data)
    {
        return $this->prefixImgUrl($url, $data);
    }
}

```

## 定义API版本号

版本号改成动态的

`Route::get('api/:version/banner/:id', 'api/:version.Banner/getBanner');`


## 专题接口模型分析

多对多一般会设计第三张表，来中转两张对应的表

命令创建控制器

`php think make:controller api/v1/Theme`


## Theme接口验证与重构

精选主题实现方式：

1.客户端传入参数，服务端返回

2.调用服务端接口，服务端返回后台定义好的精选主题

将`isPositiveInteger()`提到`BaseValidate`中，并在验证器中添加保护变量`$message`,定义通用返回的提示信息(`isPositiveInteger`方法是自定义的验证方法)

## 开启路由完整匹配模式

处理进入主题后，详情展示product信息，Theme表和Product表多对多的关系

* theme.php模型中

```
// 多对多的关系
public function products()
{
    return $this->belongsToMany('Product', 'theme_product', 'product_id', 'theme_id');
}
```


当存在下面两个路由，只匹配第一个

```
Route::get('api/:version/theme', 'api/:version.Theme/getSimpleList');

Route::get('api/:version/theme/:id', 'api/:version.Theme/getComplexOne');
```

需要在config配置文件中，开启完整路由匹配

```
route_complete_match => true
```

## 数据库字段冗余的合理利用

嵌套比较多的情况下，可以考虑数据冗余。
其中product表中`img_id`和`main_img_url`

## REST的合理利用


根据不同属性字段定义的读取器

```
public function getMainImgUrlAttr($url, $data)
{
    return $this->prefixImgUrl($url, $data);
}
```

## 最近新品接口编写

TP5框架通过模型写入数据，create_time,update_time会自动更新数据。

## 使用数据集还是数组？

```
// 数据封装成对象
$collection = collection($result);

// 临时隐藏summary字段
$result = $collection->hidden(['summary']);     
```

database.php

```
// 数据集返回类型
'resultset_type'  => 'collection',
```

修改之后对数据库查询返回的数据进行判空

```
$result->isEmpty();
```

## 分类列表接口编写

分类存储在category表,topic_img_id对应的是界面上最大的那张图片

控制器 Category.php

```
public function getAllCategories()
{
    // $categories = CategoryModel::with('img')->select();
    // all()的第一个参数可以传入一组id，如果查询全部就传一个空数组
    $categories = CategoryModel::all([],'img');

    if ($categories->isEmpty())
    {
        throw new CategoryException();
    }
    return json($categories);
}
```

## 扩展：接口粒度与接口分层

首页显示，需要调用三个接口分别是 banner theme product

把三个接口合并成一个接口，调用一次