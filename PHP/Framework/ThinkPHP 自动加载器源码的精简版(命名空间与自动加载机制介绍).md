## ThinkPHP 自动加载器源码的精简版(命名空间与自动加载机制介绍)

![](https://img3.doubanio.com/view/photo/l/public/p2535457061.jpg)

## 前言

`include` 和 `require` 是PHP中引入文件的两个基本方法。在小规模开发中直接使用 `include` 和 `require` 没哟什么不妥，但在大型项目中会造成大量的 `include` 和 `require` 堆积。这样的代码既不优雅，执行效率也很低，而且维护起来也相当困难。

为了解决这个问题，部分框架会给出一个引入文件的配置清单，在对象初始化的时候把需要的文件引入。但这只是让代码变得更简洁了一些，引入的效果仍然是差强人意。PHP5 之后，随着 PHP 面向对象支持的完善，`__autoload` 函数才真正使得自动加载成为可能。

* `include` 和 `require` 功能是一样的，它们的不同在于 `include` 出错时只会产生警告，而 require 会抛出错误终止脚本。

*  `include_once` 和 `include` 唯一的区别在于 `include_once` 会检查文件是否已经引入，如果是则不会重复引入。

## 自动加载

实现自动加载最简单的方式就是使用 `__autoload` 魔术方法。 **当需要使用的类没有被引入时，这个函数会在PHP报错前被触发，未定义的类名会被当作参数传入** 。至于函数具体的逻辑，这需要用户自己去实现。

首先创建一个 `autoload.php` 来做一个简单的测试：

```php
<?php

// 类未定义时，系统自动调用
function __autoload($class) 
{
	/* 具体处理逻辑 */
	echo "this is " . $class; // 简单的输出未定义的类名

} 	

new HelloWorld();

/**
 * 输出 HelloWorld 与报错信息
 * this is HelloWorld
 * Fatal error: Uncaught Error: Class 'HelloWorld' not found in /private/var/www/test/autoload.php:11
 */
```

通过这个简单的例子可以发现，在类的实例化过程中，系统所做的工作大致是这样的：

```php
/* 模拟系统实例化过程 */
function instance($class)
{
    // 如果类存在则返回其实例
    if (class_exists($class, false)) {
        return new $class();
    }
    // 查看 autoload 函数是否被用户定义
    if (function_exists('__autoload')) {
        __autoload($class); // 最后一次引入的机会
    }
    // 再次检查类是否存在
    if (class_exists($class, false)) {
        return new $class();
    } else { // 系统：我实在没辙了
        throw new Exception('Class Not Found');
    }
}
```

明白了 __autoload 函数的工作原理之后，那就让我们来用它去实现自动加载。

首先创建一个类文件（建议文件名与类名一致），代码如下：

* autoload.php
* HelloWorld.php

HelloWorld.php

```php
<?php

class HelloWorld 
{
    // 对象实例化时输出当前类名
    function __construct()
    {
        echo "<br/> " . __CLASS__;
    }
}
```
autoload.php

```php
<?php

// 类未定义时，系统自动调用
function __autoload($class) 
{
	echo "this is " . $class; // 简单的输出未定义的类名

	// 根据类名确定文件名
    $file = $class . '.php';

    echo "<br/>" . $file;

    if (file_exists($file)) {
        include $file; // 引入PHP文件
    }

} 	

new HelloWorld();

/**
 * this is HelloWorld
 * HelloWorld.php
 * HelloWorld
 */
```

## 命名空间

其实命名空间并不是什么新生事物，很多语言（例如C++）早都支持这个特性了。只不过 PHP 起步比较晚，直到 PHP 5.3 之后才支持。

**在当前命名空间没有声明的情况下，限定类名和完全限定类名是等价的。** 因为如果不指定空间，则默认为全局（\）。


```
new 百度\李彦宏(); // 限定类名
new \百度\李彦宏(); // 完全限定类名

```

这个例子展示了在命名空间下，使用限定类名和完全限定类名的区别。（完全限定类名 = 当前命名空间 + 限定类名）

```
namespace 谷歌;

new 百度\李彦宏(); // 谷歌\百度\李彦宏（实际结果）
new \百度\李彦宏(); // 百度\李彦宏（实际结果）
```

## spl_autoload

接下来让我们要在含有命名空间的情况下去实现自动加载。这里我们使用 `spl_autoload_register()` 函数来实现，这需要你的 PHP 版本号大于 5.12。

**`spl_autoload_register` 函数的功能就是把传入的函数（参数可以为回调函数或函数名称形式）注册到 `SPL __autoload` 函数队列中，并移除系统默认的 `__autoload()` 函数。**

一旦调用 `spl_autoload_register()` 函数，当调用未定义类时，系统就会按顺序调用注册到 `spl_autoload_register()` 函数的所有函数，而不是自动调用 `__autoload()` 函数。

现在，我们来创建一个 Linux 类，它使用 os 作为它的命名空间（建议文件名与类名保持一致）：

* Linux.php
* autoload.php

Linux.php

```php
<?php

namespace os;

class Linux {

	function __construct()
    {
        echo '<h1>' . __CLASS__ . '</h1>';
    }
    
}
```

aotuload.php

```php
<?php

spl_autoload_register(function ($class) { // class = os\Linux

    /* 限定类名路径映射 */
    $class_map = array(
        // 限定类名 => 文件路径
        'os\\Linux' => './Linux.php',
    );

    /* 根据类名确定文件名 */
    $file = $class_map[$class];

    /* 引入相关文件 */
    if (file_exists($file)) {
        include $file;
    }
});

new \os\Linux();

/**
 * os\Linux
 */
```

这里我们使用了一个数组去保存类名与文件路径的关系，这样当类名传入时，自动加载器就知道该引入哪个文件去加载这个类了。

但是一旦文件多起来的话，映射数组会变得很长，这样的话维护起来会相当麻烦。**如果命名能遵守统一的约定，就可以让自动加载器自动解析判断类文件所在的路径。** 接下来要介绍的PSR-4 就是一种被广泛采用的约定方式。

## PSR-4规范

PSR-4 是关于由文件路径自动载入对应类的相关规范，规范规定了一个完全限定类名需要具有以下结构：

`\<顶级命名空间>(\<子命名空间>)*\<类名>`

**PSR-4 规范中必须要有一个顶级命名空间，它的意义在于表示某一个特殊的目录（文件基目录）。子命名空间代表的是类文件相对于文件基目录的这一段路径（相对路径），类名则与文件名保持一致（注意大小写的区别）。**

举个例子：在全限定类名 `\app\view\news\Index` 中，如果 app 代表 `C:\Baidu`，那么这个类的路径则是 `C:\Baidu\view\news\Index.php`

我们就以解析 `\app\view\news\Index` 为例，编写一个简单的 Demo：

```php
$class = 'app\view\news\Index';

/* 顶级命名空间路径映射 */
$vendor_map = array(
    'app' => 'C:\Baidu',
);

/* 解析类名为文件路径 */
$vendor = substr($class, 0, strpos($class, '\\')); // 取出顶级命名空间[app]
$vendor_dir = $vendor_map[$vendor]; // 文件基目录[C:\Baidu]
$rel_path = dirname(substr($class, strlen($vendor))); // 相对路径[/view/news]
$file_name = basename($class) . '.php'; // 文件名[Index.php]

/* 输出文件所在路径 */
echo $vendor_dir . $rel_path . DIRECTORY_SEPARATOR . $file_name;
```

通过这个 Demo 可以看出限定类名转换为路径的过程。那么现在就让我们用规范的面向对象方式去实现自动加载器吧。

首先我们创建一个文件 Index.php，它处于 `\app\mvc\view\home` 目录中：

```php
namespace app\mvc\view\home;

class Index
{
    function __construct()
    {
        echo '<h1> Welcome To Home </h1>';
    }
}
```

接着我们在创建一个加载类（不需要命名空间），它处于 \ 目录中：

```php
class Loader
{
    /* 路径映射 */
    public static $vendorMap = array(
        'app' => __DIR__ . DIRECTORY_SEPARATOR . 'app',
    );

    /**
     * 自动加载器
     */
    public static function autoload($class)
    {
        $file = self::findFile($class);
        if (file_exists($file)) {
            self::includeFile($file);
        }
    }

    /**
     * 解析文件路径
     */
    private static function findFile($class)
    {
        $vendor = substr($class, 0, strpos($class, '\\')); // 顶级命名空间
        $vendorDir = self::$vendorMap[$vendor]; // 文件基目录
        $filePath = substr($class, strlen($vendor)) . '.php'; // 文件相对路径
        return strtr($vendorDir . $filePath, '\\', DIRECTORY_SEPARATOR); // 文件标准路径
    }

    /**
     * 引入文件
     */
    private static function includeFile($file)
    {
        if (is_file($file)) {
            include $file;
        }
    }
}
```

最后，将 Loader 类中的 autoload 注册到 `spl_autoload_register` 函数中：

```php
include 'Loader.php'; // 引入加载器
spl_autoload_register('Loader::autoload'); // 注册自动加载

new \app\mvc\view\home\Index(); // 实例化未引用的类

/**
 * 输出: <h1> Welcome To Home </h1>
 */
```

示例中的代码其实就是 ThinkPHP 自动加载器源码的精简版，它是 ThinkPHP 5 能实现惰性加载的关键。




