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

## SPL标准库简介

#### SPL提供基本操作的数据类型

* stack 先进后出

```php
$stack = new SplStack();
$stack->push("data1\n");
$stack->push("data2\n");

echo $stack->pop();
echo $stack->pop();
```

* queue 队列

```php
$queue = new SqlQueue();
$queue->enqueue("data1\n");
$queue->enqueue("data2\n");

echo $queue->dequeue();
echo $queue->dequeue();
```

* 堆

```php
$heap = new SqlMinHeap();
$heap->insert("data1\n");
$heap->insert("data2\n");

echo $heap->extract();
echo $heap->extract();
```

* 固定尺寸的数组

```php
$array = new SqlFixedArray(10);
$array[0] = 123;
$array[1] = 321;

var_dump($array);
```

#### PHP链式操作实现

每个方法`return $this;`

#### PHP魔术方法的使用

* `__get/__set` 将对象的属性做接管

* `__call/__callStatic` 对对象的方法调用

* `__toString` 将一个php对象转换成字符串

* `__invoke` 将一个php对象作为函数来使用

**实例一**

调用一个不存在的属性时

```php
$obj = new Src\Object();
echo $obj->title;

# Notice: Undefined property: Src\Object::$title in /private/var/www/psr-0/index.php on line 13
```

我们在类中定义get和set魔术方法，来接管它

```php
<?php 

namespace Src;

class Object
{
    protected $array = array();
    
    // 对象显示不存在的属性时调用sget魔术方法
    function __get($name)
    {
        return $this->array[$name];
    }

    // 对象设置不存在的属性时调用set魔术方法
    function __set($name, $value)
    {
        $this->array[$name] = $value;
    }

    // 方法不存在时回调call魔术方法
    function __call($func, $param)
    {
        return "magic funtion \n";
    }

    // 静态方法不存在时回调callStatic魔术方法
    static function __callStatic($func, $param)
    {
        return "magic static function \n";
    }

    // 对象输出成字符串时自动调用toString魔术方法
    function __toString()
    {
        return __CLASS__;
    }
    
    // 对象作为函数去使用自动回调魔术方法invoke
    function __invoke($param)
    {
        return "invoke";
    }

    static function test() {

        echo __METHOD__;
    }

}
```

打印

```php
// print class property
$obj = new Src\Object();
$obj->title = 'DoubleX-Meng';
echo $obj->title;

// print class funtion
echo "</br>";
echo $obj->print("hello", 123);

// print class static function 
echo "</br>";
echo Src\Object::show("Action", 222);

// print class to string 
echo "</br>";
echo $obj;

// print class to function 
echo "</br>";
echo $obj("xxx");
```

## 三种基本设计模式 

* 工厂 类生产对象，而不是用代码直接new

* 单例 某个类的对象仅被创建一次

* 注册 全局共享和交换对象

#### 工厂模式

```php
class Factory
{
	static function createDatabase()
	{
		return new Database();
	}
}
```

```php
// 工厂模式
$db = Src\Factory::createDatabase();
```

#### 单例模式

```php
class Database
{
	// 私有化内部实例化的对象
	private static $db;
	
	// 私有化构造方法，禁止外部实例化
	private function __construct()
	{
		echo "construct method";
	}
	
	// 公有化静态实例方法
	static function getInstance()
	{
		if (self::$db) {
			return self::$db;
		} else {
			self::$db = new self();
			return self::$db;
		}
	}
}
```

```php
// 单例模式
Src\Database::getInstance();
```

#### 工厂单例

```php
class Database
{
	// 私有化内部实例化的对象
	private static $db;
	
	// 私有化构造方法，禁止外部实例化
	private function __construct()
	{
		echo "construct method";
	}
	
	// 公有化静态实例方法
	static function getInstance()
	{
		if (self::$db) {
			return self::$db;
		} else {
			self::$db = new self();
			return self::$db;
		}
	}
}


class Factory
{
	static function createDatabase()
	{
		return Database::getInstance();		
	}
}
```

```php
// 工厂单例
Src\Factory::createDatabase();
```

#### 注册模式

解决创建对象时，调用工厂的问题；  
直接调用实例化好的对象；

```php
// 将对象注册到全局数上
class Register
{
	protected static $obj;
	
	// 为对象映射alias别名
	static function set($alias, $object)
	{
		self::$obj[$alias] =  $object;
	}
	
	static function get($name)
	{
		return self::obj[$name];
	}
	
	function _unset($alias)
	{
		unset(self::$obj[$alias]);
	}
}

class Factory
{
	static function createDatabase()
	{
		$db = Database::getInstance();
		Register::set('DB1', $db); // 注册
		return $db
	}
}
```

```php
// 工厂单例注册
Src\Factory::createDatabase(); // 创建对象，并注册到注册树上
$db = Src\Register::get('DB1');
var_dump($db);
```


