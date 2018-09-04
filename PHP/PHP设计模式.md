## PHP设计模式

#### 类的自动载入

最早之前，通过定义`__autoload()`方法，PHP在访问当前命名空间一个不存在的类时，自动调用`__autoload()`方法,将类名自动传入`__autoload()`方法。

```php
Test1::test();
Test2::test();

function __autoload($class) 
{
	require __DIR__ . '/' . $class . '.php';
}
```

后来啊，这个方法被废弃了，因为我们一个PHP工程可能依赖多个框架。如果每一个框架都有一个`__autoload()`方法，那么就会报一个函数重复定义的致命错误。

PHP5.3之后，官方提供了一个`sql_autoload_register()`这个方法来取而代之。
这个函数的特点就是允许你存在多个`autoload()`函数。

```php
sql_autoload_register(autoload1);
sql_autoload_register(autoload2);

Test1::test();
Test2::test();

function autoload1($class) 
{
	require __DIR__ . '/' . $class . '.php';
}

function autoload2($class) 
{
	require __DIR__ . '/' . $class . '.php';
}
```

## PHP面向对象高级特性

#### PSR-0规范

* PHP的命名空间必须与绝对路径一致

* 类名的首字符必须大写

* 除入口文件外，其他“.php”必须只有一个类

#### 一个PSR-0的框架

* `App/Controller/Home/Index.php`

* `Src/Loader.php`

* `Src/Object.php`

* `index.php`

`App/Controller/Home/Index.php`

```php
<?php

namespace App\Controller\Home;

class Index
{
    static function test()
    {
        echo __METHOD__;
    }
    
}

```

`Src/Object.php`

```php
<?php 

namespace Src;

class Object
{
    static function test() {

        echo __METHOD__;
    }

}
```

`Src/Loader.php`

```php
<?php

namespace Src;

class Loader
{
    static function autoload($class) 
    {
        // $class带命名空间的类名("Src\Object")
        require  BASEDIR . '/' . str_replace('\\', '/', $class) . '.php'; 
    }

}

```

`index.php`

```php
<?php

define('BASEDIR', __DIR__);

include BASEDIR . '/Src/Loader.php';
spl_autoload_register('\\Src\\Loader::autoload');

Src\Object::test();
echo "</br>";
App\Controller\Home\Index::test();


```