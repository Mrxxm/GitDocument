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






