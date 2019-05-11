## 安装
### 2-3 下载TP5

下载文档链接：

`https://www.kancloud.cn/manual/thinkphp5/118006`

需要下载两个文件：一个是TP5框架的目录文件项目，一个是核心框架项目。需要将核心框架项目剪切到目录文件项目下，并且重命名为`thinkphp`。

## `URL`&路由
### 2-9 `PATH_INFO` `URL`路径模式解析

* TP5默认的url模式：`http://serverName/index.php/module/controller/action/[param/value...]`

* url不区分大小写(TP5默认)
`config.php` 参数 `'url_convert' => true,`将true改成false，就区分大小写

* 官方称为：`PATH_INFO`

* 兼容模式：`http://serverName/index.php?s=module/controller/action/p/v`

### 3-1 多模块与模块命名空间

一个应用下面可以包含多个模块(默认index模块)，可以在`application`目录下创建新的文件夹，即定义了一个新的模块。

**新建模块**

命名规范：一般文件夹名为小写，类名以大写开头。

命名空间：TP5定义的根命名空间名为**app**，后跟上模块文件夹路径 (`config.php`文件中定义了`'app_namespace' => 'app'`，可修改)

例：`think`项目下的`baseController.php`

```
namespace app\api\controller;
```

**关于入口文件`index.php`**

该文件中定义了`APP_PATH`,规定了应用的目录：

```
define('APP_PATH', __DIR__ . '/../application/');
```

##### 新建完成模块输入路由无法访问

回顾：thinkphp的url访问：`http://serverName/index.php`（或者其它应用入口文件）/模块/控制器/操作/[参数名/参数值...]，这个需要支持pathinfo，Apache默认支持，而Nginx不支持。

* 1.php.ini中的配置参数`cgi.fix_pathinfo = 1`
* 2.修改nginx.conf文件

```
location ~ \.php(.*)$ {
        fastcgi_pass   127.0.0.1:9000;
        fastcgi_index  index.php;
        
        # 下面两句是给fastcgi权限，可以支持 ?s=/module/controller/action的url访问模式
        fastcgi_split_path_info  ^((?U).+\.php)(/?.+)$;
        fastcgi_param  SCRIPT_FILENAME  $document_root$fastcgi_script_name;
        
        # 下面两句才能真正支持index.php/index/index/index的pathinfo模式
        fastcgi_param  PATH_INFO  $fastcgi_path_info;
        fastcgi_param  PATH_TRANSLATED  $document_root$fastcgi_path_info;
        include        fastcgi_params;
}
```

修改完1，2两点，即可访问成功。

### 3-4 三种URL访问模式

TP5默认是**配置式**的路由方式。

在`Route.php`文件,删除默认代码，引入`Route`类，使用**动态注册路由**方式编写：

```
use think\Route;

Route::rule('hello', '模块名/控制器名/Action');
```

动态注册路由方式访问：

```
http://域名/hello
```

`PATH_INFO`方式访问：

```
http://域名/模块名/控制器名/Action
```

同一个`Action`同时只能由一种方式实现，即使用了**动态注册路由**方式，则`PATH_INFO`方式失效。

##### 路由总结

1.`PATH_INFO`方式

2.混合模式(既可以用`PATH_INFO`,又可以用动态注册路由方式)，但是同一个`Action`只能用一种方式实现，即定义了动态注册路由方式，则`PATH_INFO`方式自动失效,反之。

3.强制使用路由模式

TP5默认使用混合模式(`Route.php`配置文件)：

```
// 是否开启动态注册路由
'url_route_on'           => true,
    
// 是否强制使用动态注册路由
'url_route_must'         => false,
```

### 3-5 定义路由

完整路由定义格式：

```
Route::rule('路由表达式', '路由地址', '请求类型', '路由参数(数组)', '变量规则(数组)');

# 请求类型
GET POST DELETE PUT * (默认任意请求类型都支持)

# 标准格式
Route::rule('hello', 'sample/xxm/hello', 'GET', '['https'=>false]', ['']);

# 同时支持get和post类型路由
Route::rule('hello', 'sample/xxm/hello', 'GET|POST', '['https'=>false]', ['']);

# 快速注册get类型路由
Route:get('hello', 'sample/xxm/hello');
```

## 获取参数

### 3-6 获取请求参数

##### 控制器获取参数三种方式

1.第一种方法，**默认获取**(获取路由中定义的参数，`?`后面的参数和`form`表单里面定义的参数)

`GET`路由：

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

`POST`路由：

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

# form表单中数据
age 24

# 输出
NO.1 hello xxm age：24
```

2.第二种方法使用`Request`类

`param`方法获取单个参数：

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

# form表单中数据
form-date
age 24

# 输出
NO.1 hello xxm age：24
```

`param`方法获取所有参数：

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

# form表单中数据
form-date
age 24

# 输出
NO.1 hello xxm age：24
```

`get`方法获取参数(只获取到?后面的参数)：

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

# form表单中数据
form-date
age 24

# 输出
'name' => string 'xxm'
```

`route`方法获取参数(只获取到路由中的参数)

`post`方法获取参数(只获取到`form-data`中的参数)


3.第三种方法使用**助手函数**

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

##### 依赖注入的方式，替换`Request`实例获取参数

直接把`Request`实例注入到控制器的类中

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

## 验证层

### 4-3 Validate：独立验证

* 独立验证

```
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

### 4-4 Validate 验证器

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

控制器中调用：

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

### 4-6 自定义验证规则

控制器调用`check`方法，自动调用自定义的验证方法

```
<?php
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

控制器中实现：

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

### 4-7 构建接口参数校验层

创建`BaseValidate`类(继承`Validate`)

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

ID验证器 (继承`BaseValidate`) [`BaseValidate`在调用完check方法后，进入调用自定义验证方法]

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

控制器中调用展示 (一行代码搞定)：

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

## 全局异常处理

### 6-3 总结异常的分类

**用户操作导致的异常(没有通过验证器...)**

* 不记录日志
* 向客户端返回具体信息

**服务器自身异常(代码错误...)**

* 记录日志
* 不向客户端返回具体原因

### 6-4 实现自定义全局异常处理 上


* 修改`config`配置,指定到重写的子类中

```
'exception_handle'       => 'app\lib\exception\ExceptionHandle',
```

* `ExceptionHandle.php` 重写`handle`方法

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

### 6-5 实现自定义全局异常处理 下

#### 重写render方法

区分6-3中的两种异常，从结果出发分析，**需要向用户返回具体信息**，那么就会定义具体的异常类，且都继承与`BaseException`。

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

### 6-6 日志系统

##### 自动记录日志功能

根据入口文件，`public/index.php`文件中的`start.php`查找，找到`base.php`文件中找到`LOG_PATH`,根据`LOG_PATH`定义得出TP5日志自动记录在项目目录下`runtime/log`目录下

在`public/index.php`文件中,重新定义`LOG_PATH`路径

```
define('LOG_PATH', __DIR__ . '/../logs/');
```

### 6-7 在全局异常处理中加入日志记录

##### 关闭TP5默认日志行为

在`config.php`配置文件中，日志配置把`type`改为`test`

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

根据6-3中的分类，在`ExceptionHandle.php`文件中的系统异常调用该方法。

### 6-8 全局异常处理的应用 上

本章针对服务器错误处理显示，根据客户端、服务器，返回报错信息的不同。客户端/客户需要一个简单的
`json`数据，服务端/开发人员希望得到页面报错(TP5默认页面报错，需要调试)

这里对系统异常的显示方式再做了一层分类。

为了动态的开启或关闭，需要将`$switch`写到配置文件中

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

通过配置文件中的`app_debug`参数，替换`$switch`变量

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

##### 重要修改

在验证层的`goCheck`方法中，我们对异常抛出定义修改。由于我们抛出的是系统异常，当开启调试，异常定位到行，当关闭异常，异常抛出永远是999，代码如下

`BaseValidate.php`

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

`ExceptionHandle.php`

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

所以，我们需要自定义错误异常类，将传入参数错误归为自定义异常一类,这里引出第一个自定义异常类`ParameterException.php`

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

### 6-9 全局异常处理的应用 中

##### 细节修改(对自定义异常基类`BaseException`编写构造方法)

在`BaseValidate`抛出异常时，对成员变量进行了修改，最好是在`new`操作对象的时候就把值传入对象中

`BaseException`添加构造方法：

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

`BaseValidate`验证层,通过`new`的方式实现：

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

### 6-10 全局异常处理的应用 下

验证器需要验证多个参数时，需要使用`batch`方法。

对`goCheck`方法进行完善,加入`batch`方法：

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

## ORM

### 7-1 数据库操作三种方式之原生SQL

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

### 7-4 查询构造器 一


* `find`  单条记录,一维数组
* `select` 多条记录,二维数组

```
public static function getBannerByID($id)
{
    /*
     * find   单条记录,一维数组
     * select 多条记录,二维数组
     */
    $result = Db::table('banner_item')->where('banner_id', '=', $id)->select();
    var_dump($result);
    exit;
}
```

### 7-6 查询构造器 三

`where('字段名'， '表达式'， '查询条件')`

闭包写法

```
->where( function ($query) use ($id){
    $query->where('banner_id', '=', '$id');
})
```

### 7-7 开启SQL日志记录

`fetchSql()`输出sql

```
$result = Db::table('banner_item')
    ->fetchSql()
    ->where('banner_id', '=', $id)
    ->select();
```

开启sql语句自动记录到日志

`database.php`里面把debug改成`debug => true`，在`config.php`中保证`app_debug => true`的，在log日志配置选项，在level里面加入sql这个选项，`'level' => ['sql']`,其实type类型为file时，其实已经就能记录日志，但是我们为了筛选记录的日志，将type改成了test,默认日志记录被我们关闭，所以我们在需要记录日志的地方手动初始化。

在入口文件`index.php`初始化日志记录，所以在一个请求都能经过的`index.php`中初始化日志记录，并设置记录类型

```
// 初始化日志记录sql
\think\Log::init([
    'type' => 'File',
    'path' => LOG_PATH,
    'level' => ['sql']
]);
```

### 7-12 几种查询动词的总结与ORM性能问题的探讨

TP5框架查询

* `find()`   一条数据(DB)
* `select()` 一组数据(DB)
* `get()`    一条数据(模型)
* `all()`    一组数据(模型)

TP5使用DB不能使用`get`和`all`，使用模型可以使用`find`和`select`。

Laravel框架查询

* `first()` 一条数据(DB)
* `get()`   一组数据(DB)
* `find()`  一条数据(模型)
* `all()`   一组数据(模型)

### 8-2 模型关联

##### 一对多

模型中定义关联方法(第一个参数关联模型的名字 第二个参数是两个关联属性 第三个参数是当前模型的主键)

```
class Banner extends Model
{
    public function items()
    {
        return $this->hasMany('BannerItem', 'banner_id', 'id');
    }

}
```

控制器中调用查询

```
$banner = BannerModel::with('items')->find($id);
```


结果

```
{"id":1,"name":"首页置顶","description":"首页轮播图","delete_time":null,"update_time":"1970-01-01 08:00:00","items":[{"id":1,"img_id":65,"key_word":"6","type":1,"delete_time":null,"banner_id":1,"update_time":"1970-01-01 08:00:00"},{"id":2,"img_id":2,"key_word":"25","type":1,"delete_time":null,"banner_id":1,"update_time":"1970-01-01 08:00:00"},{"id":3,"img_id":3,"key_word":"11","type":1,"delete_time":null,"banner_id":1,"update_time":"1970-01-01 08:00:00"},{"id":5,"img_id":1,"key_word":"10","type":1,"delete_time":null,"banner_id":1,"update_time":"1970-01-01 08:00:00"}]}
```

### 8-3 模型关联-嵌套关联查询

##### 一对多对一

`with()`方法可以接受数组参数，形成多个关联。

`banner`表关联`banner_item`，再通过`banner_item`表关联`image`表，这个被称为嵌套关联。

快速创建模型

`php think make:model api/Image`

`banner_item.php`

```
class BannerItem extends Model
{
    public function img()
    {
        return $this->belongsTo('Image', 'img_id', 'id');
    }
}
```

* 控制器调用

```
$banner = BannerModel::with(['items', 'items.img'])->find($id);
```

### 8-4 隐藏模型字段

封装，将模型获取数据方法封装到静态方法中，不暴露在控制器中

控制器：

```
$banner = BannerModel::getBannerByID($id);
```

模型`banner`中：

```
public static function getBannerByID($id)
{
    $banner = self::with(['items', 'items.img'])->find($id);

    return $banner;
}
```


* 调用`hidden`方法隐藏

`$banner->hidden(['update_time', 'delete_time']);`

* 调用`visible`显示

`$banner->visible(['id', 'update_time']);`

### 8-5 在模型内部隐藏字段

在模型内部定义成员变量

`protected $hidden = ['id'];`

`protected $visible = ['id'];`

### 8-6 图片资源`URL`配置

扩展配置文件目录 (自定义的配置文件能够被TP5框架自动加载)

新建文件夹`extra`：`application/extra`

控制器读取配置：

`$url = config('setting.img_prefix');`

`extra`文件夹下的`setting.php`

```
<?php

return [
    'img_prefix' => 'http://www.think.com/images',
    'token_expire_in' => 7200
];
```

### 8-7 读取器的巧妙应用

读取器定义规范：

1.`get`：固定字段

2.`Url`：属性名称，首字母大写

3.`Attr`：固定字段

读取器可以接收一个参数，我们定义为`$value`,这个参数就是当前属性的取值：

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

`from`字段表示本地图片和网络图片，网络图片url地址完整。

根据`from`字段修改 读取器方法还可以接收第二个字段`$data`字段标识当前表的字段数据，可以获取`from`字段值

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

### 8-8 自定义模型基类


定义`baseModel.php`,将通用方法定义在基类中

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

在`image`模型中，编写读取器，调用基类中的处理url方法

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

### 8-9 定义API版本号

版本号改成动态的

`Route::get('api/:version/banner/:id', 'api/:version.Banner/getBanner');`

### 8-12 接口验证与重构

需要传入多个ID进行验证。

将`isPositiveInteger()`提到`BaseValidate`中，并在验证器中添加保护变量`$message`,定义通用返回的提示信息

定义`IDCollection`验证器，检查多个ID值

```
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
```

### 8-14 开启路由完整匹配模式

处理进入主题后，详情展示`product`信息，`Theme`表和`Product`表多对多的关系

```
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

需要在`config`配置文件中，开启完整路由匹配

```
route_complete_match => true
```

### 8-19 使用数据集还是数组？

```
// 数据封装成对象
$collection = collection($result);

// 临时隐藏summary字段
$result = $collection->hidden(['summary']);     
```

`database.php`配置文件

```
// 数据集返回类型
'resultset_type'  => 'collection',
```

修改之后对数据库查询返回的数据进行判空

```
$result->isEmpty();
```

### 8-21 扩展：接口粒度与接口分层

首页显示，需要调用三个接口分别是 `banner` `theme` `product`

把三个接口合并成一个接口，调用一次。



## 安装
#### 2-3 下载TP5

下载文档链接：

`https://www.kancloud.cn/manual/thinkphp5/118006`

需要下载两个文件：一个是TP5框架的目录文件项目，一个是核心框架项目。需要将核心框架项目剪切到目录文件项目下，并且重命名为`thinkphp`。

## `URL`&路由
#### 2-9 `PATH_INFO` `URL`路径模式解析

* TP5默认的url模式：`http://serverName/index.php/module/controller/action/[param/value...]`

* url不区分大小写(TP5默认)
`config.php` 参数 `'url_convert' => true,`将true改成false，就区分大小写

* 官方称为：`PATH_INFO`

* 兼容模式：`http://serverName/index.php?s=module/controller/action/p/v`

#### 3-1 多模块与模块命名空间

一个应用下面可以包含多个模块(默认index模块)，可以在`application`目录下创建新的文件夹，即定义了一个新的模块。

**新建模块**

命名规范：一般文件夹名为小写，类名以大写开头。

命名空间：TP5定义的根命名空间名为**app**，后跟上模块文件夹路径 (`config.php`文件中定义了`'app_namespace' => 'app'`，可修改)

例：`think`项目下的`baseController.php`

```
namespace app\api\controller;
```

**关于入口文件`index.php`**

该文件中定义了`APP_PATH`,规定了应用的目录：

```
define('APP_PATH', __DIR__ . '/../application/');
```

##### 新建完成模块输入路由无法访问

回顾：thinkphp的url访问：`http://serverName/index.php`（或者其它应用入口文件）/模块/控制器/操作/[参数名/参数值...]，这个需要支持pathinfo，Apache默认支持，而Nginx不支持。

* 1.php.ini中的配置参数`cgi.fix_pathinfo = 1`
* 2.修改nginx.conf文件

```
location ~ \.php(.*)$ {
        fastcgi_pass   127.0.0.1:9000;
        fastcgi_index  index.php;
        
        # 下面两句是给fastcgi权限，可以支持 ?s=/module/controller/action的url访问模式
        fastcgi_split_path_info  ^((?U).+\.php)(/?.+)$;
        fastcgi_param  SCRIPT_FILENAME  $document_root$fastcgi_script_name;
        
        # 下面两句才能真正支持index.php/index/index/index的pathinfo模式
        fastcgi_param  PATH_INFO  $fastcgi_path_info;
        fastcgi_param  PATH_TRANSLATED  $document_root$fastcgi_path_info;
        include        fastcgi_params;
}
```

修改完1，2两点，即可访问成功。

#### 3-4 三种URL访问模式

TP5默认是**配置式**的路由方式。

在`Route.php`文件,删除默认代码，引入`Route`类，使用**动态注册路由**方式编写：

```
use think\Route;

Route::rule('hello', '模块名/控制器名/Action');
```

动态注册路由方式访问：

```
http://域名/hello
```

`PATH_INFO`方式访问：

```
http://域名/模块名/控制器名/Action
```

同一个`Action`同时只能由一种方式实现，即使用了**动态注册路由**方式，则`PATH_INFO`方式失效。

##### 路由总结

1.`PATH_INFO`方式

2.混合模式(既可以用`PATH_INFO`,又可以用动态注册路由方式)，但是同一个`Action`只能用一种方式实现，即定义了动态注册路由方式，则`PATH_INFO`方式自动失效,反之。

3.强制使用路由模式

TP5默认使用混合模式(`Route.php`配置文件)：

```
// 是否开启动态注册路由
'url_route_on'           => true,
    
// 是否强制使用动态注册路由
'url_route_must'         => false,
```

#### 3-5 定义路由

完整路由定义格式：

```
Route::rule('路由表达式', '路由地址', '请求类型', '路由参数(数组)', '变量规则(数组)');

# 请求类型
GET POST DELETE PUT * (默认任意请求类型都支持)

# 标准格式
Route::rule('hello', 'sample/xxm/hello', 'GET', '['https'=>false]', ['']);

# 同时支持get和post类型路由
Route::rule('hello', 'sample/xxm/hello', 'GET|POST', '['https'=>false]', ['']);

# 快速注册get类型路由
Route:get('hello', 'sample/xxm/hello');
```

## 获取参数

#### 3-6 获取请求参数

##### 控制器获取参数三种方式

1.第一种方法，**默认获取**(获取路由中定义的参数，`?`后面的参数和`form`表单里面定义的参数)

`GET`路由：

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

`POST`路由：

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

# form表单中数据
age 24

# 输出
NO.1 hello xxm age：24
```

2.第二种方法使用`Request`类

`param`方法获取单个参数：

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

# form表单中数据
form-date
age 24

# 输出
NO.1 hello xxm age：24
```

`param`方法获取所有参数：

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

# form表单中数据
form-date
age 24

# 输出
NO.1 hello xxm age：24
```

`get`方法获取参数(只获取到?后面的参数)：

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

# form表单中数据
form-date
age 24

# 输出
'name' => string 'xxm'
```

`route`方法获取参数(只获取到路由中的参数)

`post`方法获取参数(只获取到`form-data`中的参数)


3.第三种方法使用**助手函数**

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

##### 依赖注入的方式，替换`Request`实例获取参数

直接把`Request`实例注入到控制器的类中

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

## 验证层

#### 4-3 Validate：独立验证

* 独立验证

```
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

#### 4-4 Validate 验证器

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

控制器中调用：

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

#### 4-6 自定义验证规则

控制器调用`check`方法，自动调用自定义的验证方法

```
<?php
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

控制器中实现：

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

#### 4-7 构建接口参数校验层

创建`BaseValidate`类(继承`Validate`)

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

ID验证器 (继承`BaseValidate`) [`BaseValidate`在调用完check方法后，进入调用自定义验证方法]

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

控制器中调用展示 (一行代码搞定)：

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

## 全局异常处理

#### 6-3 总结异常的分类

**用户操作导致的异常(没有通过验证器...)**

* 不记录日志
* 向客户端返回具体信息

**服务器自身异常(代码错误...)**

* 记录日志
* 不向客户端返回具体原因

#### 6-4 实现自定义全局异常处理 上


* 修改`config`配置,指定到重写的子类中

```
'exception_handle'       => 'app\lib\exception\ExceptionHandle',
```

* `ExceptionHandle.php` 重写`handle`方法

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

#### 6-5 实现自定义全局异常处理 下

#### 重写render方法

区分6-3中的两种异常，从结果出发分析，**需要向用户返回具体信息**，那么就会定义具体的异常类，且都继承与`BaseException`。

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

#### 6-6 日志系统

##### 自动记录日志功能

根据入口文件，`public/index.php`文件中的`start.php`查找，找到`base.php`文件中找到`LOG_PATH`,根据`LOG_PATH`定义得出TP5日志自动记录在项目目录下`runtime/log`目录下

在`public/index.php`文件中,重新定义`LOG_PATH`路径

```
define('LOG_PATH', __DIR__ . '/../logs/');
```

#### 6-7 在全局异常处理中加入日志记录

##### 关闭TP5默认日志行为

在`config.php`配置文件中，日志配置把`type`改为`test`

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

根据6-3中的分类，在`ExceptionHandle.php`文件中的系统异常调用该方法。

#### 6-8 全局异常处理的应用 上

本章针对服务器错误处理显示，根据客户端、服务器，返回报错信息的不同。客户端/客户需要一个简单的
`json`数据，服务端/开发人员希望得到页面报错(TP5默认页面报错，需要调试)

这里对系统异常的显示方式再做了一层分类。

为了动态的开启或关闭，需要将`$switch`写到配置文件中

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

通过配置文件中的`app_debug`参数，替换`$switch`变量

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

##### 重要修改

在验证层的`goCheck`方法中，我们对异常抛出定义修改。由于我们抛出的是系统异常，当开启调试，异常定位到行，当关闭异常，异常抛出永远是999，代码如下

`BaseValidate.php`

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

`ExceptionHandle.php`

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

所以，我们需要自定义错误异常类，将传入参数错误归为自定义异常一类,这里引出第一个自定义异常类`ParameterException.php`

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

#### 6-9 全局异常处理的应用 中

##### 细节修改(对自定义异常基类`BaseException`编写构造方法)

在`BaseValidate`抛出异常时，对成员变量进行了修改，最好是在`new`操作对象的时候就把值传入对象中

`BaseException`添加构造方法：

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

`BaseValidate`验证层,通过`new`的方式实现：

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

#### 6-10 全局异常处理的应用 下

验证器需要验证多个参数时，需要使用`batch`方法。

对`goCheck`方法进行完善,加入`batch`方法：

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

## ORM

#### 7-1 数据库操作三种方式之原生SQL

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

#### 7-4 查询构造器 一


* `find`  单条记录,一维数组
* `select` 多条记录,二维数组

```
public static function getBannerByID($id)
{
    /*
     * find   单条记录,一维数组
     * select 多条记录,二维数组
     */
    $result = Db::table('banner_item')->where('banner_id', '=', $id)->select();
    var_dump($result);
    exit;
}
```

#### 7-6 查询构造器 三

`where('字段名'， '表达式'， '查询条件')`

闭包写法

```
->where( function ($query) use ($id){
    $query->where('banner_id', '=', '$id');
})
```

#### 7-7 开启SQL日志记录

`fetchSql()`输出sql

```
$result = Db::table('banner_item')
    ->fetchSql()
    ->where('banner_id', '=', $id)
    ->select();
```

开启sql语句自动记录到日志

`database.php`里面把debug改成`debug => true`，在`config.php`中保证`app_debug => true`的，在log日志配置选项，在level里面加入sql这个选项，`'level' => ['sql']`,其实type类型为file时，其实已经就能记录日志，但是我们为了筛选记录的日志，将type改成了test,默认日志记录被我们关闭，所以我们在需要记录日志的地方手动初始化。

在入口文件`index.php`初始化日志记录，所以在一个请求都能经过的`index.php`中初始化日志记录，并设置记录类型

```
// 初始化日志记录sql
\think\Log::init([
    'type' => 'File',
    'path' => LOG_PATH,
    'level' => ['sql']
]);
```

#### 7-12 几种查询动词的总结与ORM性能问题的探讨

TP5框架查询

* `find()`   一条数据(DB)
* `select()` 一组数据(DB)
* `get()`    一条数据(模型)
* `all()`    一组数据(模型)

TP5使用DB不能使用`get`和`all`，使用模型可以使用`find`和`select`。

Laravel框架查询

* `first()` 一条数据(DB)
* `get()`   一组数据(DB)
* `find()`  一条数据(模型)
* `all()`   一组数据(模型)

#### 8-2 模型关联

##### 一对多

模型中定义关联方法(第一个参数关联模型的名字 第二个参数是两个关联属性 第三个参数是当前模型的主键)

```
class Banner extends Model
{
    public function items()
    {
        return $this->hasMany('BannerItem', 'banner_id', 'id');
    }

}
```

控制器中调用查询

```
$banner = BannerModel::with('items')->find($id);
```


结果

```
{"id":1,"name":"首页置顶","description":"首页轮播图","delete_time":null,"update_time":"1970-01-01 08:00:00","items":[{"id":1,"img_id":65,"key_word":"6","type":1,"delete_time":null,"banner_id":1,"update_time":"1970-01-01 08:00:00"},{"id":2,"img_id":2,"key_word":"25","type":1,"delete_time":null,"banner_id":1,"update_time":"1970-01-01 08:00:00"},{"id":3,"img_id":3,"key_word":"11","type":1,"delete_time":null,"banner_id":1,"update_time":"1970-01-01 08:00:00"},{"id":5,"img_id":1,"key_word":"10","type":1,"delete_time":null,"banner_id":1,"update_time":"1970-01-01 08:00:00"}]}
```

#### 8-3 模型关联-嵌套关联查询

##### 一对一

`with()`方法可以接受数组参数，形成多个关联。

`banner`表关联`banner_item`，再通过`banner_item`表关联`image`表，这个被称为嵌套关联。

快速创建模型

`php think make:model api/Image`

`banner_item.php`

```
class BannerItem extends Model
{
    public function img()
    {
        return $this->belongsTo('Image', 'img_id', 'id');
    }
}
```

* 控制器调用

```
$banner = BannerModel::with(['items', 'items.img'])->find($id);
```

#### 8-4 隐藏模型字段

封装，将模型获取数据方法封装到静态方法中，不暴露在控制器中

控制器：

```
$banner = BannerModel::getBannerByID($id);
```

模型`banner`中：

```
public static function getBannerByID($id)
{
    $banner = self::with(['items', 'items.img'])->find($id);

    return $banner;
}
```


* 调用`hidden`方法隐藏

`$banner->hidden(['update_time', 'delete_time']);`

* 调用`visible`显示

`$banner->visible(['id', 'update_time']);`

#### 8-5 在模型内部隐藏字段

在模型内部定义成员变量

`protected $hidden = ['id'];`

`protected $visible = ['id'];`

#### 8-6 图片资源`URL`配置

扩展配置文件目录 (自定义的配置文件能够被TP5框架自动加载)

新建文件夹`extra`：`application/extra`

控制器读取配置：

`$url = config('setting.img_prefix');`

`extra`文件夹下的`setting.php`

```
<?php

return [
    'img_prefix' => 'http://www.think.com/images',
    'token_expire_in' => 7200
];
```

#### 8-7 读取器的巧妙应用

读取器定义规范：

1.`get`：固定字段

2.`Url`：属性名称，首字母大写

3.`Attr`：固定字段

读取器可以接收一个参数，我们定义为`$value`,这个参数就是当前属性的取值：

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

`from`字段表示本地图片和网络图片，网络图片url地址完整。

根据`from`字段修改 读取器方法还可以接收第二个字段`$data`字段标识当前表的字段数据，可以获取`from`字段值

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

#### 8-8 自定义模型基类


定义`baseModel.php`,将通用方法定义在基类中

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

在`image`模型中，编写读取器，调用基类中的处理url方法

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

#### 8-9 定义API版本号

版本号改成动态的

`Route::get('api/:version/banner/:id', 'api/:version.Banner/getBanner');`

#### 8-12 接口验证与重构

需要传入多个ID进行验证。

将`isPositiveInteger()`提到`BaseValidate`中，并在验证器中添加保护变量`$message`,定义通用返回的提示信息

定义`IDCollection`验证器，检查多个ID值

```
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
```

#### 8-14 开启路由完整匹配模式

处理进入主题后，详情展示`product`信息，`Theme`表和`Product`表多对多的关系

```
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

需要在`config`配置文件中，开启完整路由匹配

```
route_complete_match => true
```

#### 8-19 使用数据集还是数组？

```
// 数据封装成对象
$collection = collection($result);

// 临时隐藏summary字段
$result = $collection->hidden(['summary']);     
```

`database.php`配置文件

```
// 数据集返回类型
'resultset_type'  => 'collection',
```

修改之后对数据库查询返回的数据进行判空

```
$result->isEmpty();
```

#### 8-21 扩展：接口粒度与接口分层

首页显示，需要调用三个接口分别是 `banner` `theme` `product`

把三个接口合并成一个接口，调用一次。

#### 9-10 路由变量规则与分组

将`recent`路由放在`id`路由之后，访问`recent`接口报错

```
Route::get('api/:version/product/:id', 'api/:version.Product/getOne');
Route::get('api/:version/product/recent', 'api/:version.Product/getRecent');
```

```
{
    "msg": {
        "id": "id参数必须是正整数"
    },
    "errorCode": "10000",
    "request_url": "/api/v1/product/recent"
}
```

原因是输入`recent`路由的`recent`部分匹配了`id`路由的`id`部分，所以报错。

* 解决方法，添加第4个参数，变量规则,指定`id`号传入规则

```
Route::get('api/:version/product/:id', 'api/:version.Product/getOne', [], ['id' => '\d+']);
```


路由分组实现之前：

```
//Route::get('api/:version/product/:id', 'api/:version.Product/getOne', [], ['id' => '\d+']);

//Route::get('api/:version/product/recent', 'api/:version.Product/getRecent');

```

路由分组实现之后：

```
Route::group('api/:version/product', function () {
    Route::get('/:id', 'api/:version.Product/getOne', [], ['id' => '\d+']);
    Route::get('/recent', 'api/:version.Product/getRecent');
});
```