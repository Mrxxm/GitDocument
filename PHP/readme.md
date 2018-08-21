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