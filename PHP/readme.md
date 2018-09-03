## php

![](https://timgsa.baidu.com/timg?image&quality=80&size=b9999_10000&sec=1530768945893&di=b6e9ed67932327c795bf9c8955ff7ffc&imgtype=0&src=http%3A%2F%2Fs2.51cto.com%2Fwyfs02%2FM01%2F6C%2F13%2FwKiom1U_HIqhsyLOAADOTJeDXWM434.jpg)

```
<?php

// 重定向到test/index.php
header("Location:test/index.php");

// 重定向停留三秒
header("Refresh:3; url=test/index.php");

// 禁用缓存
header("Expires:-1");
header("Cache-Control:no_cache");
header("Pragma:no_cache");

// 定义文件下载
header("Content-type:application/octet-stream");
header("Accept-Ranges:bytes");
header("Accept-Length:$file_size");
header("Content-Disposition:attachment;filename=".$file_name);
```

## 基础点

* unset()方法：  
断开变量和内存之间的引用关系。

* isset()方法：  
判断变量是否被赋值。

* 引用传递：

	```
	$m1 = 1;
	$m2 = &$m1;
	m1变量和m2变量都指向同一块内存空间。
	```

* 可变变量：

	```
	$s1 = “abc”;
	$abc = 10;
	echo $$s1; 
	1.php的$后面跟的是变量名。
	2.最后输出为10。
	```

* `$_POST`  
用户通过表单以post方式提交的所有数据。

* `$_GET`

* `$_REQUEST`  
是get和post的合集。  
php.ini设置 `request_order = “GP”` 后者覆盖前者 post数据与get数据有相同项时，就覆盖。

* `$_SERVER`  
含义：浏览器端和服务器端的信息，时刻变化，比如可以取到用户IP，操作系统环境变量。  
`$_SERVER[‘REMOTE_IP’]`:获取访问者的IP地址。  
`$_SERVER[’SERVER_IP’]`:获取服务器IP地址。  
`$_SERVER[’SERVER_NAME’]`:获取服务器名字。  
`$_SERVER[’DOCUMENT_ROOT’]`:获取站点真是物理地址。  
`$_SERVER[’PHP_SELF’]`:获取当前网页地址。  
`$_SERVER[’SCRIPT_FILENAME’]`:获取网页地址物理路径。  
`$_SERVER[’QUERY_STRING’]`:获取当前网页地址中所有get数据。  

* `$GLOBALS`  
存储全局变量,取得全局变量的值。

---

* 常量定义：  
1.`define(“PI”, 3.14);` 取值：直接使用常量名  
2.`const PI = 3.14;` 取值：通过contant(“常量名”)函数来取得常量值，常量名是一个字符串。

* const 代码只能定义在顶层部分，且不能定在大括号中。

* 常量具有超全局作用域。常量只能存储标量类型(整数，浮点数，字符串，布尔)

* 判断常量是否定义`defined()`。

* 使用一个未定义的常量，其值就是其名。但是也会报错。

* 预定义常量：
	
	`M_PI`:圆周率。     
	`PHP_OS`:php所运行的操作系统。  
	`PHP_VERSION`:php版本。  
	`PHP_INT_MAX`:php中最大整数值。  
	
* 魔术常量：常量的形式，值是变化的  
`__FILE__`:代表当前网页文件完整物理路径。  
`__DIR__`:代表当前网页文件所在文件夹。  
`__LINE__`:代表这个常量所在的行号。  

* 数据类型：  
基本类型（标量）：整数，浮点数，字符串，布尔  
复合类型：数组，对象  
特殊类型：空类型，资源类型。  

* 八进制0开头 十六进制0x开头 二进制0b开头  
`decbin()`、`decoct()`、`dechex()` 十进制转二进制，八进制，十六进制，返回结果是字符串。  
`bindec()`、传入二进制字符串，结果为十进制数值。  

* 浮点类型：  
浮点数不进行大小比较；

* 字符串：  
‘’单引号字符串中可识别的转义符 \\ 、 \  
“”双引号字符串中可识别的转义符 \\ 、 \ 、 \n(换行符) 、 \r(回车符) 、 \t(tab键)   
还有一个\$,表示取消了其表示在双引号中变量起始含义。   

* 强制转换  
形式：（目标类型）数据。  
并不改变变量本身的数据或类型。  
有一个函数直接改变变量的类型和数据。  
setType($变量名, “目标类型”);  

---

* 运算符：  
取余运算只针对整数，如果不是自动取整。 11.3 取整 为 11， 11.8 取整 为 11。

* 字符串的自增：

	```  
	 $s1 = "a";
	 $s1++;
	 // b
	```

* 字符串比较，逐个取出字符进行比较。  
纯数值字符串比较，就转为数值。  
true > false。  

* 位运算 对进制进行分析。  

* 错误控制运算符@:

--- 

* 控制脚本执行顺序：  
die(字符串) / exit(字符串):  
输出该字符串后，立即停止php的执行！

* sleep($n):  
让程序停止运行指定的秒数。

* 文件加载：
include、require、include_once、require_once  

* 相对路径：  
./ 当前所在位置  
../ 上一级位置  
例：include “./page.php”  

* 文件载入和执行过程详解：  
1.从include语句处退出php脚本模式（进入html代码模式）。  
2.载入include语句所设定的文件中的代码，并执行之（如同在当前文件中一样）。  
3.退出html模式重新进入php脚本模式，继续之后的代码。  

* include载入文件失败，报一个错误提示。
* require载入文件失败，报错立即终止执行。

* 错误处理：  
 错误分级：  
`E_ERROR`: 致命错误  
`E_WARNING`: 警告错误  
`E_NOTICE`: 提示错误  

## SQL注入

#### 数字注入

`id = -1 OR 1 = 1;`

#### 字符串注入

用户名登录

用户名：xuxiaomeng

用户名：xuxiaomeng'#

后面一段全部当做注释处理。
`select * from user where user name = 'xuxiaomeng'#' and ...;`

-- 后面有一个空格。  
用户名：`xuxiaomeng'-- `

#### PHP支持哪些数据库

PHP通过安装相应的扩展来实现数据库操作，现代应用程序的设计离不开数据库的应用，当前主流的数据库有MsSQL，MySQL，Sybase，Db2，Oracle，PostgreSQL，Access等，这些数据库PHP都能够安装扩展来支持，一般情况下常说的LAMP架构指的是：Linux、Apache、Mysql、PHP，因此Mysql数据库在PHP中的应用非常广泛，我们会在本章中简单的了解Mysql的操作方法。

```php
if (function_exists('mysql_connect')) {
    echo 'Mysql扩展已经安装';
}
```

#### PHP数据库扩展

PHP中一个数据库可能有一个或者多个扩展，其中既有官方的，也有第三方提供的。像Mysql常用的扩展有原生的mysql库，也可以使用增强版的mysqli扩展，还可以使用PDO进行连接与操作。

不同的扩展提供基本相近的操作方法，不同的是可能具备一些新特性，以及操作性能可能会有所不同。

mysql扩展进行数据库连接的方法：

```php
$link = mysql_connect('mysql_host', 'mysql_user', 'mysql_password');

```
mysqli扩展：

```php
$link = mysqli_connect('mysql_host', 'mysql_user', 'mysql_password');
```

PDO扩展:

```php
$dsn = 'mysql:dbname=testdb;host=127.0.0.1';
$user = 'dbuser';
$password = 'dbpass';
$dbh = new PDO($dsn, $user, $password);

```

#### PHP数据库操作之连接MySQL数据库

PHP要对数据库进行操作，首先要做的是与数据库建立连接，通常我们使用`mysql_connect`函数进行数据库连接，该函数需要指定数据库的地址，用户名及密码。

```php
$host = 'localhost';
$user = 'code1';
$pass = '';
$link = mysql_connect($host, $user, $pass);

```

PHP连接数据库的方式类似于直接在命令行下通过进行连接，类似：`mysql -hlocalhost -ucode1 -p`，当连接成功以后，我们需要选择一个操作的数据库，通过`mysql_select_db`函数来选择数据库。

```php
mysql_select_db('code1');
```

通常我们会先设置一下当前连接使用的字符编码，一般的我们会使用utf8编码。

```php
mysql_query("set names 'utf8'");
```

#### PHP数据库操作之执行MySQL查询

在数据库建立连接以后就可以进行查询，采用mysql_query加sql语句的形式向数据库发送查询指令。

```php
$res = mysql_query('select * from user limit 1');
```

对于查询类的语句会返回一个资源句柄（resource），可以通过该资源获取查询结果集中的数据。

```php
$row = mysql_fetch_array($res);
var_dump($row);
```

默认的，PHP使用最近的数据库连接执行查询，但如果存在多个连接的情况，则可以通过参数指令从那个连接中进行查询。

```php
$link1 = mysql_connect('127.0.0.1', 'code1', '');
$link2 = mysql_connect('127.0.0.1', 'code1', '', true); //开启一个新的连接
$res = mysql_query('select * from user limit 1', $link1); //从第一个连接中查询数据
```

#### PHP数据库操作之插入新数据到MySQL中

当我们了解了如何使用`mysql_query`进行数据查询以后，那么类似的，插入数据其实也是通过执行一个sql语句来实现，例如：

```php
$sql = "insert into user(name, age, class) values('李四', 18, '高三一班')";
mysql_query($sql); //执行插入语句
```

通常数据都是存储在变量或者数组中，因此sql语句需要先进行字符串拼接得到。

```php
$name = '李四';
$age = 18;
$class = '高三一班';
$sql = "insert into user(name, age, class) values('$name', '$age', '$class')";
mysql_query($sql); //执行插入语句
```

在mysql中，执行插入语句以后，可以得到自增的主键id,通过PHP的mysql_insert_id函数可以获取该id。

```php
$uid = mysql_insert_id();
```

这个id的作用非常大，通常可以用来判断是否插入成功，或者作为关联ID进行其他的数据操作。

#### PHP数据库操作之取得数据查询结果

通过前面的章节，我们发现PHP操作数据库跟MySql客户端上操作极为相似，先进行连接，然后执行sql语句，再然后获取我们想要的结果集。

PHP有多个函数可以获取数据集中的一行数据，最常用的是`mysql_fetch_array`，可以通过设定参数来更改行数据的下标，默认的会包含数字索引的下标以及字段名的关联索引下标。

```php
$sql = "select * from user limit 1";
$result = mysql_query($sql);
$row = mysql_fetch_array($result);
```

可以通过设定参数`MYSQL_NUM`只获取数字索引数组，等同于`mysql_fetch_row`函数，如果设定参数为`MYSQL_ASSOC`则只获取关联索引数组，等同于`mysql_fetch_assoc`函数。

```php
$row = mysql_fetch_row($result);
$row = mysql_fetch_array($result, MYSQL_NUM); //这两个方法获取的数据是一样的

```

```php
$row = mysql_fetch_assoc($result);
$row = mysql_fetch_array($result, MYSQL_ASSOC);
```

如果要获取数据集中的所有数据，我们通过循环来遍历整个结果集。

```php
$data = array();
while ($row = mysql_fetch_array($result)) {
    $data[] = $row;
}
```

#### PHP数据库操作之更新与删除数据

对于删除与更新操作，可以通过`mysql_affected_rows`函数来获取更新过的数据行数，如果数据没有变化，则结果为0。

```php
$sql = "update user set name = '曹操' where id=2 limit 1";
if (mysql_query($sql)) {
    echo mysql_affected_rows();
}
```

#### PHP数据库操作之关闭MySQL连接

当数据库操作完成以后，可以使用`mysql_close`关闭数据库连接，默认的，当PHP执行完毕以后，会自动的关闭数据库连接。

```php
mysql_close();
```

虽然PHP会自动关闭数据库连接，一般情况下已经满足需求，但是在对性能要求比较高的情况下，可以在进行完数据库操作之后尽快关闭数据库连接，以节省资源，提高性能。

在存在多个数据库连接的情况下，可以设定连接资源参数来关闭指定的数据库连接。

```php
$link = mysql_connect($host, $user, $pass);
mysql_close($link);
```



## 字符串操作

#### 常用转义符

* `\n` 换行
* `\r` 回车
* `\t` 水平制表符
* `\f` 换页

#### 花括号

* `{}` 明确变量名的界线，将变量扩成一个整体来解析
* `{}` 通过花括号对字符串中的字符做增删改查操作

	```
	$str = 'abcd';
	$str{0}; // a 
	```
	
#### heredoc

* heredoc语法结构:

	```
	<<<标识名称
		内容...
	标识名称;
	```
	
* nowdoc语法结构:

	```
	<<<'标识名称'
		内容...
	标识名称;
	```
	
---
	
### 其他类型转换成字符串

* 数值型转换成字符串型

	```
	数值型 -> 数值本身
	true  -> 1
	false -> 空字符串
	null  -> 空字符串
	```
	
* 布尔类型转换成字符串型
	
* NULL转换成字符串型

* 数组转换成字符串型

	```
	数组 -> Array
	```

* 资源转换成字符串型

* 永久转换
	
	```
	settype($val, $type);
	settype($val, 'string');
	gettype($val);
	```
	
* 强制、临时转换

	```
	(string)
	strval()
	```

#### 字符串转其他类型

* 取合法数字，如果不是以合法数字开始，转化成0

* 字符串转换成bool类型规律：空字符串或者是字符串'0' 都装换成false(包括整形0，浮点型0.0，null和空数组也都转换成false)

### 字符串函数库

* strlen(string $string)

* strtolower(string $string) 

* strtouppper(string $string)

* ucfirst(string $string) 首字母大写

* ucwords(string $string) 每个单词首字母大写

验证码

```php
$string = 'qwertyuiopasdfghjklzxcvbnm1234567890QWERTYUIOPASDFGHJKLZXCVBNM';
$code = '';
for	($i = 0; $i < 4; $i++) {
	$code .= '<span style="color:rgb('.mt_rand(0,255).','.mt_rand(0,255).','.mt_rand(0,255).')">'.$string{mt_rand(0, strlen($string)-1)}.'</span>'; 
}
echo $code;
```

## 函数特性

#### 强类型参数

需要在页面中标识：`declare(strict_types = 1);`

定义：为参数列表中的参数指定类型。如果传入的类型不匹配，将会抛出TypeError异常。

支持类型：

* class/interface name php5.0.0
* array php5.1.0
* callable php5.4.0
* bool,float,int,string php7.0.0

#### 可变参数列表

```
func_num_args() // 返回函数参数数量
func_get_arg()  // 返回函数参数某一项
func_get_args() // 返回函数参数数组
```


```
	function get_sum(...$nums){
		
	} 
```

#### 值传递引用传递

* 引用传递 &

#### PHP自定义超全局变量

```
$_GET、$_POST、$_COOKIE、$_SERVER、$_FILES、 $_ENV、$_REQUEST、$_SESSION
```

```
// 定义全局变量
global $name;
GLOBALS['name'];
```

#### 可变函数

#### 匿名函数

* 最常用作回调函数参数的值
* 闭包函数可以作为变量的值来使用

```php
$example = function () {
	
}

$massage = "Hello";

$example = function () use($message) {
	echo $message;
}

$example();

```

```php
function test($name, Closure $clo) {
	echo "Hello, {$name}\n";
	$clo();
}

test('Lily', function(){
	echo "匿名函数作函数参数。";
});

```

#### 代码重用

```php
set_include_path('testa');
include('test1.php');

ini_set('include_path', get_include_path().PATH_SEPARATOR.'testa');
restore_include_path(); // 销毁引入的文件
```

#### `set_include_path`, `get_include_path`


* `set_include_path`

`set_include_path`是为include和require等文件包含函数用的。


例如：`projectName/home/Action/lib`，在这个目录下有如下文件：a.php, b.php..........如果我们想在其他文件中包含这些文件时，我们可以这样写

      set_include_path('projectName/home/Action/lib');

      require('a.php');

 

当指定一个目录为`include_path`时，但是当lib目录下找不到所要求包含的文件，而在当前页面目录下正好存在这个名称的文件时，则转为包含当前目录下的该文件。

当指定了多个目录为 `include_path` ，而所要求包含的文件在这几个目录都有相同名称的文件存在时，php选择使用设定 `include_path` 时排位居前的目录下的文件。不同路径之间用PHP常量`PATH_SEPARATOR`来分割。在类unix的系统中，`PATH_SEPARATOR`是 ":"；在windows系统中，`PATH_SEPARATOR`的值是";";

* `get_include_path`

获取当前`include_path` 的值，也可以输出`include_path`，查看当前的包含路径。

 	string dirname ( string path )

 
假如你的首页中用到了`_FILE_`这个变量：

（假设你的网页所在目录为：`http://localhost/web/index.php`）,那么：

 

`_FILE_`的值为`http://localhost/web/index.php`（一个绝对路径）。而此时`dirname (_FILE_)`表示的就是`http://localhost/web/`也就是没有`index.php`这个文件名。

而`dirname(dirname(_FILE_))`表示的就是上一级的目录，以此类推；



## 类与对象

#### PHP类和对象之访问控制

前面的小节，我们已经接触过访问控制了，访问控制通过关键字public，protected和private来实现。被定义为公有的类成员可以在任何地方被访问。被定义为受保护的类成员则可以被其自身以及其子类和父类访问。被定义为私有的类成员则只能被其定义所在的类访问。

类属性必须定义为公有、受保护、私有之一。为兼容PHP5以前的版本，如果采用 var 定义，则被视为公有。

```php
class Car {
    $speed = 10; //错误 属性必须定义访问控制
    public $name;   //定义共有属性
}
```

类中的方法可以被定义为公有、私有或受保护。如果没有设置这些关键字，则该方法默认为公有。

```php
class Car {
​    //默认为共有方法
    function turnLeft() {
    }
}
```

如果构造函数定义成了私有方法，则不允许直接实例化对象了，这时候一般通过静态方法进行实例化，在设计模式中会经常使用这样的方法来控制对象的创建，比如单例模式只允许有一个全局唯一的对象。

```php
class Car {
    private function __construct() {
        echo 'object create';
    }

    private static $_object = null;
    public static function getInstance() {
        if (empty(self::$_object)) {
            self::$_object = new Car(); //内部方法可以调用私有方法，因此这里可以创建对象
        }
        return self::$_object;
    }
}
//$car = new Car(); //这里不允许直接实例化对象
$car = Car::getInstance(); //通过静态方法来获得
```


#### PHP类和对象之对象继承
建立一个Truck类，扩展Car类，并覆盖speedUp方法，使速度累加50

```php
class Car {
    public $speed = 0; //汽车的起始速度是0
    
    public function speedUp() {
        $this->speed += 10;
        return $this->speed;
    }
}
//定义继承于Car的Truck类
class Truck extends Car {
    public function speedUp() {
        $this->speed = parent::speedUp() + 50;
    }
}

$car = new Truck();
$car->speedUp();
echo $car->speed;
```

#### PHP类和对象之重载

PHP中的重载指的是动态的创建属性与方法，是通过魔术方法来实现的。属性的重载通过`__set`，`__get`，`__isset`，`__unset`来分别实现对不存在属性的赋值、读取、判断属性是否设置、销毁属性。

```php
class Car {
    private $ary = array();
    
    public function __set($key, $val) {
        $this->ary[$key] = $val;
    }
    
    public function __get($key) {
        if (isset($this->ary[$key])) {
            return $this->ary[$key];
        }
        return null;
    }
    
    public function __isset($key) {
        if (isset($this->ary[$key])) {
            return true;
        }
        return false;
    }
    
    public function __unset($key) {
        unset($this->ary[$key]);
    }
}
$car = new Car();
$car->name = '汽车';  //name属性动态创建并赋值
echo $car->name;
```

方法的重载通过`__call`来实现，当调用不存在的方法的时候，将会转为参数调用__call方法，当调用不存在的静态方法时会使用`__callStatic`重载。

```php
class Car {
    public $speed = 0;
    
    public function __call($name, $args) {
        if ($name == 'speedUp') {
            $this->speed += 10;
        }
    }
}
$car = new Car();
$car->speedUp(); //调用不存在的方法会使用重载
echo $car->speed;
```

#### PHP类和对象之对象的高级特性

对象比较，当同一个类的两个实例的所有属性都相等时，可以使用比较运算符==进行判断，当需要判断两个变量是否为同一个对象的引用时，可以使用全等运算符===进行判断。

```php

class Car {
}
$a = new Car();
$b = new Car();
if ($a == $b) echo '==';   //true
if ($a === $b) echo '==='; //false

```

对象复制，在一些特殊情况下，可以通过关键字clone来复制一个对象，这时__clone方法会被调用，通过这个魔术方法来设置属性的值。

```php

class Car {
    public $name = 'car';
    
    public function __clone() {
        $obj = new Car();
        $obj->name = $this->name;
    }
}
$a = new Car();
$a->name = 'new car';
$b = clone $a;
var_dump($b);

```

对象序列化，可以通过serialize方法将对象序列化为字符串，用于存储或者传递数据，然后在需要的时候通过unserialize将字符串反序列化成对象进行使用。

```php

class Car {
    public $name = 'car';
}
$a = new Car();
$str = serialize($a); //对象序列化成字符串
echo $str.'<br>';
$b = unserialize($str); //反序列化为对象
var_dump($b);

```

## 文件系统

#### PHP文件系统之读取文件内容

PHP具有丰富的文件操作函数，最简单的读取文件的函数为file_get_contents，可以将整个文件全部读取到一个字符串中。

```php
$content = file_get_contents('./test.txt');
```

file_get_contents也可以通过参数控制读取内容的开始点以及长度。

```php
$content = file_get_contents('./test.txt', null, null, 100, 500);
```

PHP也提供类似于C语言操作文件的方法，使用fopen，fgets，fread等方法，fgets可以从文件指针中读取一行，freads可以读取指定长度的字符串。

```php
$fp = fopen('./text.txt', 'rb');
while(!feof($fp)) {
    echo fgets($fp); //读取一行
}
fclose($fp);
```

```php
$fp = fopen('./text.txt', 'rb');
$contents = '';
while(!feof($fp)) {
    $contents .= fread($fp, 4096); //一次读取4096个字符
}
fclose($fp);
```

#### PHP文件系统之判断文件是否存在

一般情况下在对文件进行操作的时候需要先判断文件是否存在，PHP中常用来判断文件存在的函数有两个is_file与file_exists.

```php
$filename = './test.txt';
if (file_exists($filename)) {
    echo file_get_contents($filename);
}
```

如果只是判断文件存在，使用file_exists就行，file_exists不仅可以判断文件是否存在，同时也可以判断目录是否存在，从函数名可以看出，is_file是确切的判断给定的路径是否是一个文件。

```php
$filename = './test.txt';
if (is_file($filename)) {
    echo file_get_contents($filename);
}
```

更加精确的可以使用is_readable与is_writeable在文件是否存在的基础上，判断文件是否可读与可写。

```php
$filename = './test.txt';
if (is_writeable($filename)) {
    file_put_contents($filename, 'test');
}
if (is_readable($filename)) {
    echo file_get_contents($filename);
}
```

#### PHP文件系统之取得文件的修改时间

文件有很多元属性，包括：文件的所有者、创建时间、修改时间、最后的访问时间等。

```php
fileowner：获得文件的所有者
filectime：获取文件的创建时间
filemtime：获取文件的修改时间
fileatime：获取文件的访问时间
```

其中最常用的是文件的修改时间，通过文件的修改时间，可以判断文件的时效性，经常用在静态文件或者缓存数据的更新。

```php
$mtime = filemtime($filename);
echo '修改时间：'.date('Y-m-d H:i:s', filemtime($filename));
```

#### PHP文件系统之取得文件的大小

通过filesize函数可以取得文件的大小，文件大小是以字节数表示的。

```php

$filename = '/data/webroot/usercode/resource/test.txt';
$size = filesize($filename);

```

如果要转换文件大小的单位，可以自己定义函数来实现。

```php

function getsize($size, $format = 'kb') {
    $p = 0;
    if ($format == 'kb') {
        $p = 1;
    } elseif ($format == 'mb') {
        $p = 2;
    } elseif ($format == 'gb') {
        $p = 3;
    }
    $size /= pow(1024, $p);
    return number_format($size, 3);
}

$filename = '/data/webroot/usercode/code/resource/test.txt';
$size = filesize($filename);

$size = getsize($size, 'kb'); //进行单位转换
echo $size.'kb';

```

值得注意的是，没法通过简单的函数来取得目录的大小，目录的大小是该目录下所有子目录以及文件大小的总和，因此需要通过递归的方法来循环计算目录的大小。

#### PHP文件系统之写入内容到文件

与读取文件对应，PHP写文件也具有两种方式，最简单的方式是采用file_put_contents。

```php
$filename = './test.txt';
$data = 'test';
file_put_contents($filename, $data);
```

上例中，$data参数可以是一个一维数组，当$data是数组的时候，会自动的将数组连接起来，相当于`$data=implode('', $data);`

同样的，PHP也支持类似C语言风格的操作方式，采用fwrite进行文件写入。

```php

$fp = fopen('./test.txt', 'w');
fwrite($fp, 'hello');
fwrite($fp, 'world');
fclose($fp);

```

#### PHP文件系统之删除文件

跟Unix系统命令类似，PHP使用unlink函数进行文件删除。

```php
unlink($filename);
```

删除文件夹使用rmdir函数，文件夹必须为空，如果不为空或者没有权限则会提示失败。

```php
rmdir($dir);
``

如果文件夹中存在文件，可以先循环删除目录中的所有文件，然后再删除该目录，循环删除可以使用glob函数遍历所有文件。

```php
foreach (glob("*") as $filename) {
   unlink($filename);
}
```

## Cookie && Session

#### Cookie

Cookie是存储在客户端浏览器中的数据，我们通过Cookie来跟踪与存储用户数据。一般情况下，Cookie通过HTTP headers从服务端返回到客户端。多数web程序都支持Cookie的操作，因为Cookie是存在于HTTP的标头之中，所以必须在其他信息输出以前进行设置，类似于header函数的使用限制。

PHP通过setcookie函数进行Cookie的设置，任何从浏览器发回的Cookie，PHP都会自动的将他存储在`$_COOKIE`的全局变量之中，因此我们可以通过`$_COOKIE['key']`的形式来读取某个Cookie值。

PHP中的Cookie具有非常广泛的使用，经常用来存储用户的登录信息，购物车等，且在使用会话Session时通常使用Cookie来存储会话id来识别用户，Cookie具备有效期，当有效期结束之后，Cookie会自动的从客户端删除。同时为了进行安全控制，Cookie还可以设置域跟路径，我们会在稍后的章节中详细的讲解他们。

#### 设置cookie

PHP设置Cookie最常用的方法就是使用setcookie函数，setcookie具有7个可选参数，我们常用到的为前5个：

name（ Cookie名）可以通过$_COOKIE['name'] 进行访问
value（Cookie的值）
expire（过期时间）Unix时间戳格式，默认为0，表示浏览器关闭即失效
path（有效路径）如果路径设置为'/'，则整个网站都有效
domain（有效域）默认整个域名都有效，如果设置了'www.imooc.com',则只在www子域中有效

```php
$value = 'test';
setcookie("TestCookie", $value);
setcookie("TestCookie", $value, time()+3600);  //有效期一小时
setcookie("TestCookie", $value, time()+3600, "/path/", "imooc.com"); //设置路径与域
```

PHP中还有一个设置Cookie的函数setrawcookie，setrawcookie跟setcookie基本一样，唯一的不同就是value值不会自动的进行urlencode，因此在需要的时候要手动的进行urlencode。

```php
setrawcookie('cookie_name', rawurlencode($value), time()+60*60*24*365); 
```

因为Cookie是通过HTTP标头进行设置的，所以也可以直接使用header方法进行设置。

```php
header("Set-Cookie:cookie_name=value");
```

#### cookie的删除与过期时间

通过前面的章节，我们了解了设置cookie的函数，但是我们却发现php中没有删除Cookie的函数，在PHP中删除cookie也是采用setcookie函数来实现。

```php
setcookie('test', '', time()-1); 
```

可以看到将cookie的过期时间设置到当前时间之前，则该cookie会自动失效，也就达到了删除cookie的目的。之所以这么设计是因为cookie是通过HTTP的标头来传递的，客户端根据服务端返回的Set-Cookie段来进行cookie的设置，如果删除cookie需要使用新的Del-Cookie来实现，则HTTP头就会变得复杂，实际上仅通过Set-Cookie就可以简单明了的实现Cookie的设置、更新与删除。

了解原理以后，我们也可以直接通过header来删除cookie。

```php
header("Set-Cookie:test=1393832059; expires=".gmdate('D, d M Y H:i:s \G\M\T', time()-1));
```

这里用到了gmdate，用来生成格林威治标准时间，以便排除时差的影响。

#### cookie的有效路径

cookie中的路径用来控制设置的cookie在哪个路径下有效，默认为'/'，在所有路径下都有，当设定了其他路径之后，则只在设定的路径以及子路径下有效，例如：

```php

setcookie('test', time(), 0, '/path');

```

上面的设置会使test在/path以及子路径/path/abc下都有效，但是在根目录下就读取不到test的cookie值。

一般情况下，大多是使用所有路径的，只有在极少数有特殊需求的时候，会设置路径，这种情况下只在指定的路径中才会传递cookie值，可以节省数据的传输，增强安全性以及提高性能。

当我们设置了有效路径的时候，不在当前路径的时候则看不到当前cookie。

```php
setcookie('test', '1',0, '/path');  
var_dump($_COOKIE['test']);  
```

#### session与cookie的异同

cookie将数据存储在客户端，建立起用户与服务器之间的联系，通常可以解决很多问题，但是cookie仍然具有一些局限：

cookie相对不是太安全，容易被盗用导致cookie欺骗
单个cookie的值最大只能存储4k
每次请求都要进行网络传输，占用带宽

session是将用户的会话数据存储在服务端，没有大小限制，通过一个`session_id`进行用户识别，PHP默认情况下`session id`是通过cookie来保存的，因此从某种程度上来说，seesion依赖于cookie。但这不是绝对的，`session id`也可以通过参数来实现，只要能将session id传递到服务端进行识别的机制都可以使用session。

#### 使用session

在PHP中使用session非常简单，先执行`session_start`方法开启session，然后通过全局变量`$_SESSION`进行session的读写。

```php
session_start();
$_SESSION['test'] = time();
var_dump($_SESSION);

```

session会自动的对要设置的值进行encode与decode，因此session可以支持任意数据类型，包括数据与对象等。

```php

session_start();
$_SESSION['ary'] = array('name' => 'jobs');
$_SESSION['obj'] = new stdClass();
var_dump($_SESSION);

```

默认情况下，session是以文件形式存储在服务器上的，因此当一个页面开启了session之后，会独占这个session文件，这样会导致当前用户的其他并发访问无法执行而等待。可以采用缓存或者数据库的形式存储来解决这个问题，这个我们会在一些高级的课程中讲到。

#### 删除与销毁`session`

删除某个session值可以使用PHP的unset函数，删除后就会从全局变量`$_SESSION`中去除，无法访问。

```
session_start();
$_SESSION['name'] = 'jobs';
unset($_SESSION['name']);
echo $_SESSION['name']; //提示name不存在
```

如果要删除所有的session，可以使用`session_destroy`函数销毁当前session，`session_destroy`会删除所有数据，但是`session_id`仍然存在。

```php
session_start();
$_SESSION['name'] = 'jobs';
$_SESSION['time'] = time();
session_destroy();

```

值得注意的是，`session_destroy`并不会立即的销毁全局变量`$_SESSION`中的值，只有当下次再访问的时候，`$_SESSION`才为空，因此如果需要立即销毁`$_SESSION`，可以使用unset函数。

```php

session_start();
$_SESSION['name'] = 'jobs';
$_SESSION['time'] = time();
unset($_SESSION);
session_destroy(); 
var_dump($_SESSION); //此时已为空

```

如果需要同时销毁cookie中的`session_id`，通常在用户退出的时候可能会用到，则还需要显式的调用setcookie方法删除`session_id`的cookie值。

#### 使用session来存储用户的登录信息

session可以用来存储多种类型的数据，因此具有很多的用途，常用来存储用户的登录信息，购物车数据，或者一些临时使用的暂存数据等。

用户在登录成功以后，通常可以将用户的信息存储在session中，一般的会单独的将一些重要的字段单独存储，然后所有的用户信息独立存储。

```php
$_SESSION['uid'] = $userinfo['uid'];
$_SESSION['userinfo'] = $userinfo;
```

一般来说，登录信息既可以存储在sessioin中，也可以存储在cookie中，他们之间的差别在于session可以方便的存取多种数据类型，而cookie只支持字符串类型，同时对于一些安全性比较高的数据，cookie需要进行格式化与加密存储，而session存储在服务端则安全性较高。






