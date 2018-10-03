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

```
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
 * 输出 HelloWorld 与报错信息
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

## PSR-4规范

