## 浅谈PHP echo中的字符串连接效率

#### 前言

在之前遇到的一个PHP代码优化问题中，出现了这样一个说法，在 `echo` 中，用 `,` 代替 `.` 会节省 `echo` 输出的时间。

这里索性对PHP `echo` 中的字符串连接效率问题做一下研究和总结。

#### echo连接方式

目的是将变量嵌入到字符串中，所以出现了字符串连接的问题，可以使用的方式有以下几种：

**双引号解析变量**

```php
function test_1 () {
    $user = 'Dandelion_Miss';
    echo "Hello $user welcome on my website";
}  
```

**双引号解析变量，通过 `{}` 来定位变量**

```php
function test_2 () {
    $user = 'Dandelion_Miss';
    echo "Hello {$user} welcome on my website.";
}

```

**双引号，通过 `.` 连接字符串和变量**

```php
function test_3 () {
    $user = 'Dandelion_Miss';
    echo "Hello ".$user." welcome on my website.";
}
```

**单引号，通过 `.` 连接字符串和变量**

```php
function test_4 () {
    $user = 'Dandelion_Miss';
    echo 'Hello '.$user.' welcome on my website.';
}
```

**双引号，通过 `,` 连接字符串和变量**

```php
function test_5 () {
    $user = 'Dandelion_Miss';
    echo "Hello ", $user, " welcome on my website.";
}
```

**单引号，通过 `,` 连接字符串和变量**

```php
function test_6 () {
    $user = 'Dandelion_Miss';
    echo 'Hello ', $user, ' welcome on my website.';
}
```

从图中可以得出这样几个结论：

* 不管什么时候，方法2（双引号解析变量，通过 `{}` 来定位变量）都要比方法1（双引号解析变量）快；

* 循环次数小的时候，方法4（单引号，通过 `.` 连接字符串和变量）相对而言是最慢的；

* 随着循环次数的增加，方法1（双引号解析变量）的效率越来越低；

* 使用单引号 `‘` 的效率要比双引号 `“` 高；

* 使用逗号 `,` 的效率要比句号 `.` 高，也要比单引号 `‘` 高；

* 循环次数多的时候，方法2（双引号解析变量，通过 `{}` 来定位变量）是最快的，其次是方法6（单引号，通过 `,` 连接字符串和变量）；

#### 总结

* 直接通过双引号解析字符串，效率最低，不建议使用；

* 可以通过以下两种方式优化 `echo` 语句：
* 双引号括起整个字符串，通过 `{}` 定位变量位置
* 单引号括起字符串，通过 , 连接变量

#### 原因分析

* `,` 和 `.` 的区别

使用 `,`是按照多个参数进行处理的，而使用 `.` 是先进行拼接（concat）然后当做一个整体输出。进行拼接的速度要比直接输出慢。