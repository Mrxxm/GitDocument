## 1-1 php性能优化

## 2-1 性能问题解析

* php语言级的性能问题

* php周边问题的性能优化

* php语言自身分析、优化(底层C性能优化)

## 3-1 压力测试工具ab简介

`Apache Benchmark`(ab)

简介：

ab是由Apache提供的压力测试软件。安装apache服务器时会自带该压测软件。

如何使用(liunx服务器上)：

`./ab -n1000 -c100 http://www.baidu.com`

* `-n` 代表请求数

* `-c` 并发数

* `url`目标压测地址

## 3-2 压力测试工具演示

主要关注两点：

* 每秒接受请求数-`Requests per second: 101.65[#/sec]`-每秒接收101个请求

* 每个请求耗时情况-`Time per request: 9.838[ms]`-一个请求耗时9毫秒

## 3-3 php自身能力

优化点：少写代码，多用php自身能力

为什么性能低：php代码需要编译解析为底层语言，这一过程每次请求都会处理一遍，开销大。

## 3-5 代码测试

还是要将代码放到线上，通过url来访问请求。


## 3-6 php代码运行流程

知识点：PHP代码运行流程

`*.php` --Scanner(扫描)--> 
`Exprs(Zend引擎识别的语法)` --Parser(解析)--> 
`Opcodes(将要使用的机器代码)` --Exec--> `Output`

* 直接用php内置函数，会在扫描和解析环节节省很多时间。从而产生代码量更少的`Opcodes`代码。执行时间也减少。

## 3-7 内置函数性能测试

比较内置函数`isset()`/`array_key_exists()`

```
<?php

    $start = current_time();
    $i = 0;
    $arr = range(1, 200000);
    while( $i < 200000 ) {
        ++$i;
        // isset($arr[$i]);
        array_key_exists($i, $arr);
    }
    $end = current_time();
    
    echo "Lost Time:" . number_format($end - $start, 3) * 1000;
    echo "\n";
```

`current_time()`:

```
function current_time() {
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
}

```

## 4-1 减少魔术方法的使用

`test.php`:

```
<?php

class test{
    private $var = "123";
    public function __get($varname) {
        return $this->var;
    }
}

$i = 0;
while ($i < 10000) {
    $i++;
    $test = new test();
    $test->var;
}
```

测试脚本使用time方法可以输出脚本执行时间：

* `time php test.php`


## 4-2 php-禁用错误抑制符

优化点：产生额外开销的错误抑制符@

* @的实际逻辑：

在代码开始前、结束后，增加`Opcode`，忽略报错


## 4-3 错误抑制符的性能测试

通过php的扩展`vld`查看php的`Opcode`代码。

* `php -dvld.active=1 -dvld.execute=0 test.php` **1**代表要使用vld扩展，**0**表示只查看`Opcode`代码并不执行


`test.php`:

```
<?php

@file_get_contents('xxx');
```

在Opcode层面多了两行代码：

```
BEGIN_SILENCE
...
END_SILENCE
...
```

## 4-4 合理使用内存和正则表达

优化点：合理使用内存

## 4-5 避免在循环内做运算

## 4-6 减少计算密集型业务

php的语言特性决定了php不适合做大数据量运算

php适合衔接`Webserver`与后端服务、UI呈现

## 4-7 务必使用带引号字符串做键值

这里的键值指的是数组的`key`

```
<?php

    define("key", "imooc");
    $array = array(
        "key" => "hello world",
        "imooc" => "hello imooc"
    );
    
    // 输出hello imooc
    echo $array[key] . "\n";
```

## 5-1 php周边问题分析