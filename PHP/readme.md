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






