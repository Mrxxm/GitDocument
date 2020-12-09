## Xdebug 3.0.1 for mac

#### 1.`xdebug`官网地址

[xdebug 官网地址](https://xdebug.org/)

#### 2.点击首页栏目`install`

* 搜索`macos`

![](https://img1.doubanio.com/view/photo/l/public/p2627906918.jpg)

#### 3.打开终端

* 执行 `pecl install xdebug`

```
➜  / pecl install xdebug
···
···
Build process completed successfully
Installing '/usr/local/Cellar/php/7.4.10/pecl/20190902/xdebug.so'
install ok: channel://pecl.php.net/xdebug-3.0.1
Extension xdebug enabled in php.ini
```

#### 4.查看`php.ini`文件

* 查找`php.ini`文件

```
➜  / php --ini
Configuration File (php.ini) Path: /usr/local/etc/php/7.4
Loaded Configuration File:         /usr/local/etc/php/7.4/php.ini
Scan for additional .ini files in: /usr/local/etc/php/7.4/conf.d
Additional .ini files parsed:      /usr/local/etc/php/7.4/conf.d/ext-opcache.ini,
/usr/local/etc/php/7.4/conf.d/ext-redis.ini
```

* `vim php.ini`

```
➜  / vim /usr/local/etc/php/7.4/php.ini
···
···
# 1.安装时，默认自动添加
zend_extension="xdebug.so"
# 2.需要手动添加的配置
xdebug.client_discovery_header = ""
xdebug.client_host = localhost
xdebug.client_port = 9003
xdebug.connect_timeout_ms = 200
xdebug.discover_client_host = false
xdebug.idekey = PHPSTORM
xdebug.log =
xdebug.log_level = 7
xdebug.mode = debug
xdebug.start_upon_error = default
xdebug.start_with_request = default
xdebug.trigger_value = ""
```

#### 5.重启`php-fpm`服务

* 我这里通过直接杀死进程，自动重启进程的方式重启

```
➜  / sudo lsof -nP -iTCP -sTCP:LISTEN
Password:
COMMAND     PID       USER   FD   TYPE             DEVICE SIZE/OFF NODE NAME
···
···
php-fpm   51828       root    9u  IPv4 0xda10e00c292dc697      0t0  TCP 127.0.0.1:9000 (LISTEN)
php-fpm   51833       _www   10u  IPv4 0xda10e00c292dc697      0t0  TCP 127.0.0.1:9000 (LISTEN)
php-fpm   51834       _www   10u  IPv4 0xda10e00c292dc697      0t0  TCP 127.0.0.1:9000 (LISTEN)

➜  / sudo kill -9 51828
```

#### 6.使用`xdebug_info()`函数来查看信息

* 编写php脚本`test2.php`

```
<?php
	xdebug_info();
?>
```

* 浏览器访问`http://localhost/test2.php`

![](https://img9.doubanio.com/view/photo/l/public/p2627907545.jpg)


#### 7.准备工作

* 一个访问接口

```
http://www.laravel_bundle.com/api/demo/test
```

![](https://img3.doubanio.com/view/photo/l/public/p2627908610.jpg)

* `xdebug`相关信息查看

![](https://img9.doubanio.com/view/photo/l/public/p2627907546.jpg)

#### 8.配置`phpstorm`

* 多图展示

* `step1`

打开编辑器的偏好设置

![](https://img1.doubanio.com/view/photo/l/public/p2627908779.jpg)

* `step1.2`

![](https://img9.doubanio.com/view/photo/l/public/p2627908784.jpg)

* `step2`

配置正确的端口号

![](https://img1.doubanio.com/view/photo/l/public/p2627908788.jpg)

* `step3`

断点调试服务器配置

![](https://img1.doubanio.com/view/photo/l/public/p2627908798.jpg)

* `step3.1`

![](https://img3.doubanio.com/view/photo/l/public/p2627908831.jpg)

* `step3.2`

![](https://img1.doubanio.com/view/photo/l/public/p2627908829.jpg)

* `step4`

```
1.选择正确的访问路由
2.点击小蜘蛛图标
3.浏览器打开接口网址
4.成功进入断点
```

![](https://img3.doubanio.com/view/photo/l/public/p2627908850.jpg)





