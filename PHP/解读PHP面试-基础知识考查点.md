## 基础知识考查点

### PHP引用变量考察点(上)

#### 概念

在PHP中引用意味着用不同名字访问同一个变量内容

#### 定义方式

使用 & 符号

#### COW(Copy On Write)概念

COW：写时复制，即只有当对其中一个或多个变量进行写操作的时候，才会复制一份内存，对其内容进行修改。
COW：是优化内存的一种手段，是的值相同的变量可以共用同一块内存，从而减少了内存的分配，提高了内存的使用率。

* `memory_get_usage()` 查看内存使用情况

    ```
    var_dump(memory_get_usage());
    ```
    
* `zval` `Zend`引擎中`zval`变量容器

    ```
    $a = range(0, 1);
    xdebug_debug_zval('a');
    ```

    打印结果：

    * `refcount=1`代表有**一个变量**指向内存空间

    * `is_ref`代表是不是**引用**

    ```
    a: (refcount=1, is_ref=0)=array (0 => (refcount=0, is_ref=0)=0, 1 => (refcount=0, is_ref=0)=1)
    ```
    
#### 赋值

当变量a和变量b，使用同一块内存空间，存在`COW`机制。
当需要修改变量a或变量b时，将会重新开辟一块内存空间。

#### 引用

用不同的名字访问相同的变量内容，不存在`COW`机制。

### PHP引用变量考察点(下)

#### `unset()` 

只会取消引用，不会销毁内存空间

#### 对象赋值

对象本身就是引用传递。赋值对象时，不存在`COW`机制。

```
class Person{
    public $name = 'zhangsan';
}
$p1 = new Person();
xdebug_debug_zval('p1');

$p2 = $p1;
xdebug_debug_zval('p1');

$p2->name = 'lisi';
xdebug_debug_zval('p1');
```

打印结果：

* `(refcount=1, is_ref=0)`
* `(refcount=2, is_ref=0)`
* `(refcount=2, is_ref=0)`

```
p1: (refcount=1, is_ref=0)=class Person { public $name = (refcount=2, is_ref=0)='zhangsan' }

p1: (refcount=2, is_ref=0)=class Person { public $name = (refcount=2, is_ref=0)='zhangsan' }

p1: (refcount=2, is_ref=0)=class Person { public $name = (refcount=0, is_ref=0)='lisi' }
```

#### 真题解析

```
<?php

$data = ['a', 'b', 'c'];

foreach($data as $key => $val)
{
    $val = &$data[$key];
}
```

请问输出`$data`的值是什么？

`['b', 'c', 'c']`

* 第一次循环

```
$key   = 0;
$value = a;
$value = &$data[0];  

# 结果
['a', 'b', 'c'];
```

* 第二次循环

```
$key = 1;
$value = &$data[0] = b;
$value = &$data[1];

# 结果
['b', 'b', 'c']
```


* 第三次循环

```
$key = 2;
$value = &$data[1] = c;
$value = &$data['2'];

# 结果
['b', 'c', 'c']
```

### 常量及数据类型考察点

#### 真题回顾

PHP中字符串可以用哪三种定义方法以及各自的区别是什么？

#### 定义方式

* 单引号

* 双引号

* `heredoc`和`newdoc`

#### 单引号、双引号区别

单引号不能解析变量。

单引号不能解析转义字符，只能解析单引号和反斜杠本身。

变量和变量，变量和字符串，字符串和字符换之间可以用`.`来连接。

双引号可以解析变量，变量可以使用特殊字符和`{}`包含。

双引号可以解析所有的转义字符。

也可以使用`.`来连接。

**单引号的效率高于双引号**。


#### Heredoc 和 Newdoc区别

Heredoc类似于双引号。

Newdoc类似于单引号。

Heredoc:

```
$str = <<< EoT
···
EoT
```
Newdoc:

```
$str = <<< 'EoT'
···
EoT
```

#### 数据类型

三大数据类型(标量、复合、特殊)。

* 标量：字符串、整型、浮点型和布尔

* 复合：数组和对象

* 特殊：null和resource(资源)

#### 浮点类型

浮点类型不能用到比较运算中。

#### 布尔类型

`False`的七种情况。

* 0
* 0.0
* ''
* '0'
* false
* array()
* NULL

#### 数组类型

超全局数组。

`$GLOBALS、$_GET、$_POST、$_REQUEST、$_SESSION、$_COOKIE、$_SERVER、$_FILES、$_ENV`

`$GLOBALS`包含后面所有的内容。

`$_REQUEST`包含`$_GET`、`$_POST`和`$_COOKIE`。

* 重点记忆`$_SERVER`

`$_SERVER['SERVER_ADDR']`：服务器端IP地址。

`$_SERVER['SERVER_NAME']`：服务器名称。

`$_SERVER['REQUEST_TIME']`：请求时间。

`$_SERVER['QUERY_STRING']`：请求参数。

`$_SERVER['HTTP_REFERER']`：上级请求页面。

`$_SERVER['HTTP_USER_AGENT']`：用户相关信息，包括浏览器信息和操作系统信息等。

`$_SERVER['REMOTE_ADDR']`：客户端IP地址。

`$_SERVER['REQUEST_URI']`：请求的URI，域名后的请求地址。

`$_SERVER['PATH_INFO']`：请求的路径。

* 获取url中的scheme，host和path

Scheme: `$_SERVER['REQUEST_SCHEME'];`

Host: `$_SERVER['HTTP_HOST'];`

Path: `$_SERVER['PHP_SELF'];`

参数: `$_SERVER['QUERY_STRING'];`


#### NULL

三种情况。

直接赋值为`NULL`、未定义的变量、`unset`销毁的变量。

#### 常量

`const`、`define`。

`const`更快，是语言结构，`define`是函数。

`define`不能定义类的常量，`const`可以定义。

常量一经定义，不能修改，不能删除。

* 预定义常量

`__FILE__`：所在文件的路径和文件名称。

`__LINE__`：所在行的行号。

`__DIR__`：所在目录。

`__FUNCTION__`：所在的函数体。

`__CLASS__`：所在类名

`__TRAIT__`：Trait的名称。

`__METHOD__`：类名➕方法名。

`__NAMESPACE__`：命名空间。



### 运算符知识点考察点

#### 回顾真题

* `foo()`和`@foo()`之间的区别？

`@`:错误控制符。
    
延伸考点：运算符优先级、比较运算符、递增／递减运算符和逻辑运算符

#### 错误控制符

PHP支持一个错误运算符：`@`。当将其中放置在一个PHP表达式之前，该表达式可能产生的任何错误信息都被忽落掉。

#### 运算符优先级

**递增、递减** > `!` > **算术运算符** > **大小比较** > （不）相等比较 > 引用 > 位运算符（`^`） > 位运算符（`|`）> **逻辑与** > **逻辑或** > **三目** > **赋值** > `and` > `xor` > `or`  

`xor`（异或）：相同取0，相异取1。

`^`（按位异或）。

`|`（按位或）。

括号的使用可以增加代码的可读性，推荐使用。

#### 比较运算符

* `==` 和 `===` 的区别?

比较的值的不同和比较值和类型的不同。

等值判断（FALSE的七种情况）：

* 0
* 0.0
* ''
* '0'
* false
* array()
* NULL

#### 递增、递减运算符

递增、递减运算符不影响**布尔值**（布尔值的`++`、`--`不会产生影响）。

递减**NULL**值没有效果。

递增**NULL**值为1。

递增和递减在前就**先运算**后返回，反之就先返回，**后运算**。

#### 逻辑运算符

**短路**作用。

`||`和`&&`与`or`和`and`的**优先级**不同。

```
前者为true时，后者将不再运算
$a = true || $b == 3;

前者为false时，后者将不再运算
$b = false && $a == 1;
```

```
逻辑或||优先级大于=赋值运算符$a = true;
$a = false || true;  

$b = false;
$b = false or true;
```



#### 例题

```
<?php

$a = 0;
$b = 0;

if ($a = 3 > 0 || $b = 3 > 0)
{
    $a++;
    $b++;
    echo $a."\n";
    echo $b."\n";
}
```

运算符优先级比较：`>` > `||` > `=`。

首先执行`（3 > 0）`。

其次执行`（（3 > 0）|| $b = 3 > 0）`。

最后执行if判断的结果是`$a = true`;短路作用`（$b = 3 > 0）`并没有执行。

结果是：

```
$a = true++; 
$b = 0++;

输出：1
输出：1
```

解释：`true` `echo`输出为`1`；当`$c=$b++`,那么`$c`得值为`0`，但是`$b`的值为`1`。


### 流程控制考察点


请列出3种PHP循环数组操作的语法，并注明各种循环的区别？

PHP的遍历数组的三种方式及各自区别。

延伸：分支结构。


#### PHP的遍历数组的三种方式及各自区别


* 使用`for`循环。

* 使用`foreach`循环。

* 使用`while`、`list()`、`each()`组合循环。

`for`循环只能遍历**索引数组**，`foreach`可以遍历**索引数组和关联数组**，联合使用`list()`,`each()`和`while`循环同样可以遍历**索引和关联数组**。

`list()`,`each()`和`while`组合不会`reset()`。

`foreach`遍历会对数组进行`reset()`操作。

#### 延伸考点：PHP分支考点

使用`elseif`语句有个基本原则，总是把优先范围小的条件(**可能性较大的情况**)放在前面处理。

* `switch...case...`

switch后面的控制表达式的数据类型只能是**整形、浮点类型或者字符串**。

在`switch`循环中`continue` 等价于 `break`。

当`switch`循环外还包含`for`循环，希望`continue`作用于`for`循环上，且跳过当次循环，将`continue`改成`continue2`。

`switch...case`会生成跳转表，直接跳转到对应`case`。

**效率**：如果条件比一个简单的比较要复杂得多或者在一个很多次的循环中，那么`switch`语句可能会快一点。

```
switch($var){
    case ...:
    ...
    break;
    
    case ...:
    ...
    break;
    
    default:
    ...
}
```


#### 解题方法

理解循环内部机制，更易于记忆`foreach`的`reset`特性，分支结构中理解了`switch...case`的执行步骤也就不难理解为什么效率这么高。


#### 真题

如何优化多个`if...elseif`语句的情况？

* 表达式可能性越大，越往前放。

* 判断内容比较复杂，判断值是整形浮点和字符串可以用`switch`来替代`if`循环。


### 自定义函数及内部函数考察点（上）

#### 真题回顾

```
<?php

$count = 5;
function get_count()
{
    static $count;
    return $count++;
}
echo $count;
++$count;

echo get_count();
echo get_count();

?>
```

结果：

```
echo $count;      --> 5
++$count;         --> 6

echo get_count(); --> NULL
echo get_count(); --> 1 
```

考官考点：

变量的**作用域**和**静态**变量。

延伸：函数的参数及参数的引用传递。

延伸：函数的返回值及引用返回。

延伸：外部文件的导入。

延伸：系统内置函数。

#### 变量的作用域

```
// 全局变量
$outer = 'str';

function myFunc()
{
    // 函数体内，使用全局变量(赋值、输出和返回)
    global $outer;
    $GLOBALS['outer'];

    // 局部变量
    echo $outer;
}
```

#### 静态变量

`static`关键字

* 仅初始化一次
* 初始化时需要赋值
* 每次执行函数该值会保留
* `static`修饰的变量时局部的，仅在函数体内部有效
* 可以记录函数的调用次数，从而可以在某些条件下终止递归


### 自定义函数及内部函数考察点（下）

#### 延伸考点：函数的返回值

省略`return`,返回值为`NULL`，不可有多个返回值。

#### 延伸考点：引用返回

···

#### 延伸考点：外部文件的引用

`include`/`require`语句包含并运行指定文件。

如果给出路径名按照路径来找，否则从`include_path`中查找。

如果`include_path`中也没有，则从调用脚本文件所在的目录和当前工作目录下寻找。

`include`和`require`的区别：`include`结构会发出一条警告；`require`会发出一个**致命错误**。

`require(include)/require_one(include_once)`的区别：是PHP会检查文件**是否已经被包含过**，如果是则**不会再次包含**。

#### 延伸考点：系统内置函数

时间日期函数：

* `date();`

* `strtotime();`

* `mktime();`

* `time();`

* `microtime();`

* `date_default_timezone_set();`


IP处理函数：

* `ip2long();`

* `long2ip();`

打印处理：

* `print();`  输出语言结构（单个值）

* `echo`     输出语言结构（多个值，用逗号隔开）

---

* `printf();`  根据格式进行输出

* `sprintf();` 不输出，会返回

---

* `print_r();` 数组，对象进行格式化打印

* `var_dump();` 数组，对象进行格式化打印，显示每一个值的类型

---

* `var_export();` 将内容进行格式化输出，添加`true`参数变成返回


序列化和反序列化函数:

* `serialize();`

* `unserialize();`


字符串处理函数：

* `implode();`

* `explode();`

* `join();`

* `strrev();`

* `trim();`

* `ltrim();`

* `rtrim();`

* `strstr();`

* `number_format();`

数组处理函数：

* `array_keys();`

* `array_values();`

* `array_diff();` 取数组差集

* `array_intersect();` 取数组交集

* `array_merge();`

* `array_shift();` 模拟队列

* `array_unshift();`

* `array_pop();`

* `array_push();`

* `sort();` 排序

### 正则表达式考察知识点

至少写出一种验证139开头的11位手机号码的正则表达式？

考官考点：

手机号码的正则表达式编写。

延伸：正则表达式组成及编写方法。

#### 延伸考点：正则表达式

正则表达式的作用：分割、查找、匹配、替换字符串。

分隔符：正斜线(`/`)、`hash`符号(`#`)、取反符号(`~`)

通用原子：

* `\d`：十进制的0到9

* `\D`：十进制除了0到9

* `\w`：数字、字母和下划线

* `\W`：除了数字、字母和下划线

* `\s`：空白符

* `\S`：除了空白符

元字符：

* `.`：除了换行符之外的任意字符

* `*`：匹配前面的内容出现0，1或者多次

* `?`：匹配前面的内容出现0，1

* `^`：匹配字符串开头

* `$`：匹配字符串结尾

* `+`：出现一次或者多次

* `{n}`：恰巧出现n次

* `{n,}`：大于等于n次

* `{n,m}`：大于等于n，小于等于m次

* `[]`：代表一个集合

* `()`：当做一个整体

* `[^]`：取反，除了集合中的元素

* `|`：或者的意思

* `[-]`：代表一个范围

模式修正符：

* `i`：不区分大小写

* `m`：将字符串进行分割，每一行分别进行匹配，前提字符串中有换行符

* `e`：匹配结果进行php语法处理(php7中去除)

* `s`：将字符串视为单行,换行符作为普通字符

* `U`：只匹配最近的一个字符串;不重复匹配

* `x`：将模式中的空白忽略

* `A`：强制从目标字符串开头匹配

* `D`：如果使用$限制结尾字符,则不允许结尾有换行

* `u`：utf-8中文匹配，可以用到

#### 后向引用

```
$str = '<b>abc</b>';

$pattern = '/<b>(.*)<\/b>/';

preg_replace($pattern, '\\1', $str);
```

#### 贪婪模式

```
$str = '<b>abc</b><b>bcd</b>';

$pattern = '/<b>.*?<\/b>/';

preg_replace($pattern, '\\1', $str);
```

正则表达式PCRE函数：

* `preg_match()`：执行匹配正则表达式

* `preg_match_all()`：执行一个全局正则表达式匹配

* `preg_replace()`：执行一个正则表达式的搜索和替换

* `preg_split()`：通过一个正则表达式分隔字符串

中文匹配：

UTF-8汉字编码范围是`0x4e00-0x9fa5`，在ANSI(gb2312)环境下，`0xb0-0xf7`,`0xa1-0xfe`。

UTF-8要使用u模式修正符使模式字符串被当成UTF-8，在ANSI(gb2312)环境下，要使用chr将Ascii码转换为字符。



#### 例题

匹配中文？

```
<?php

$str = '中文';

// \x代表16进制 +匹配一次或者多次 /u代表utf-8
$pattern = '/[\x{4e00}-\x{9fa5}]+/u';

preg_match($pattern, $str, $match);

var_dump($match);
```

请写出以139开头的11位手机号码的正则？

``` 
// 13988888888

$str = '13988888888';

// \d{8} 表示0-9之间的的数值出现八次
$pattern = '/^139\d{8}$/';
```

练习常用的正则表达式（URL、Email、IP地址、手机号码）。


请写一个正则，取出页面中所有img标签中的src值？

```
<?php

$str = '<img alt="" id="" src="pic.jpg" />';

// 贪婪模式 匹配了整个img标签
$pattern = '/<img.*?src=".*?".*?\/?>/i'; 

// 后向引用，将src中内容添加括号
$pattern = '/<img.*?src="(.*?)".*?\/?>/i';

preg_match($pattern, $str, $match);
```

删除字符串中非数字字符?

```
$str = '$123.456A';

$str = preg_replace('/[^.0-9]/', '', $str);

echo($str); // 123.456

i表示不区分大小写

$str = '$123.456A';

$str = preg_replace('/[^a-z.0-9]/i', '', $str);

echo($str); // 123.456A
```

```
<?php

$str = '$123.456A';

$pattern = '/\d{3}[.]\d{3}/';

$str = preg_match($pattern, $str, $match);

var_dump($match); // 123.456
```

### 文件及目录处理考点

不断在文件 `hello.txt` 头部写入一行 `“Hello World”`字符串，要求代码完整？

考官考点：

文件读取、写入操作。

延伸：目录操作函数、其他文件操作。

#### 文件读取和写入操作

`fopen()`函数：

用来打开一个文件，打开时需要指定打开模式。

打开模式：

* `r/r+`：只读打开，将文件指针指向文件开头/读写方式打开

* `w/w+`：只写方式打开，将文件指针指向文件开头，并清空文件/读写模式(`w`模式如果文件不存在，会创建一个文件)

* `a/a+`：追加的写入方式，将指针指向文件末尾，如果文件不存在也会创建/读写方式

* `x/x+`：写入方式打开，将指针指向文件开头，文件已经存在会报一个warning错误，并且fo返回一个false，文件不存在才会创建/读写方式打开

* `b`：打开二进制文件

* `t`：windows独有

写入函数：

* `fwrite()`

* `fputs()`

读取函数：

* `fread()`

* `fgets()`

* `fgetc()`

关闭文件函数：

* `fclose()`

不需要`fopen()`打开的函数：

* `file_get_contents()`

* `file_put_contents()`

其他读取函数：

* `file()`：将整个文件读取到数组中

* `readfile()`：读取内容输出到缓冲区

访问远程文件：

在`php.ini`中，开启`allow_url_fopen`,HTTP协议连接只能使用只读，FTP协议可以使用只读或者只写。

名称相关：

* `basename()`

* `dirname()`

* `pathinfo()`

目录读取：

* `opendir()`

* `readdir()`

* `closedir()`

* `rewinddir()`

目录删除：

* `rmdir()` ： 前提是目录下为空

目录创建：

* `mkdir()`

#### 延伸考点：其他函数

文件大小：

* `filesize()`

磁盘大小：

* `disk_free_space()`

* `disk_total_space()`

文件拷贝：

* `copy()`

删除文件：

* `unlink()`

文件类型：

* `filetype()`

重命名文件或者目录：

* `rename()`：重命名还可以移动位置

文件截取：

* `ftruncate()`

文件属性:

* `file_exists()`：文件是否存在

* `is_readable()`

* `is_writeable()`

* `is_executable()`

* `filectime()`：修改时间

* `fileatime()`：访问时间

* `filemtime()`：整个修改时间

文件锁：

* `flock()`

文件指针：

* `ftell()`

* `fseek()`

* `rewind()`











