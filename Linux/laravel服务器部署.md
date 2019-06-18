## DesiredLife

通过 Composer 创建项目

```
composer create-project --prefer-dist laravel/laravel desired_life "5.5.*"
```

修改.env文件配置


创建数据库

	CREATE DATABASE `desired_life` DEFAULT CHARACTER SET utf8 DEFAULT COLLATE utf8_general_ci;
	
	
Artisan基本使用

* php artisan make:controller StudentController

* php artisan make:model Student

* php artisan make:middleware Activity

生成Auth所需文件(生成登录注册入口)

* php artisan make:auth

映射数据库

* php artisan migrate


* php artisan make:migration create_users_table

生成模型同时生成迁移文件

* php artisan make:model Article -m

生成资源软连接

* php artisan storage:link

```
在/var/www/sample目录下运行命令composer install
	
完成后运行php artisan key:generate生成密钥
	
命令php artisan migrate迁移数据库
	
命令php artisan up上线
```


## linux 安装PHP
下载:

	# wget http://cn2.php.net/distributions/php-7.1.11.tar.gz

解压并安装:

	# tar -zxvf php-7.1.11.tar.gz
	
	# yum -y install libxml2 libxml2-devel openssl openssl-devel curl-devel libjpeg-devel libpng-devel freetype-devel libmcrypt-devel

	
	ICU 相关错误
	现象：Unable to detect ICU prefix or /usr//bin/icu-config failed. Please verify ICU install prefix and make sure icu-config works
	解决办法：yum install -y icu libicu libicu-devel
	
	# cd php-7.1.11
	
	# ./configure --prefix=/usr/local/php-7.1.11  --exec-prefix=/usr/local/php-7.1.11 --bindir=/usr/local/php-7.1.11/bin --sbindir=/usr/local/php-7.1.11/sbin --includedir=/usr/local/php-7.1.11/include --libdir=/usr/local/php-7.1.11/lib/php --mandir=/usr/local/php-7.1.11/php/man --with-config-file-path=/usr/local/php-7.1.11/etc --with-mcrypt=/usr/include --with-mhash --with-openssl --with-mysqli --with-pdo-mysql=mysqlnd --with-gd --with-iconv --with-zlib --enable-zip --enable-inline-optimization --disable-debug --disable-rpath --enable-shared --enable-xml --enable-bcmath --enable-shmop --enable-sysvsem --enable-mbregex --enable-mbstring --enable-ftp --enable-gd-native-ttf --enable-pcntl --enable-sockets --with-xmlrpc --enable-soap --without-pear --with-gettext --enable-session --with-curl  --with-jpeg-dir --with-freetype-dir --enable-opcache  --enable-fpm  --with-fpm-user=www --with-fpm-group=www  --without-gdbm --disable-fileinfo --enable-intl

	上面一步可能会产生报错 某个库未安装 使得make不成功
	
	# make
 
 	# make install
 
 复制配置:
 
 	# cp php.ini-development /usr/local/php-7.1.11/etc/php.ini
 
 	# /usr/local/php-7.1.11/bin/php -v
	PHP 7.1.11 (cli) (built: Nov  6 2017 15:11:13) ( NTS )
	Copyright (c) 1997-2017 The PHP Group
	Zend Engine v3.1.0, Copyright (c) 1998-2017 Zend Technologies

	# /usr/local/php-7.1.11/bin/php -m
	...


查看php-fpm

	ps aux|grep php-fpm
	
还需要开启并配置`php-fpm`

* 复制：`/usr/local/php/etc/php-fpm.conf.default` 为 `php-fpm.conf` 并修改配置将 `pid = run/php-fpm.pid` 前面分号去掉

* 复制：`/usr/local/php/etc/php-fpm.d/www.conf.default` 为 `www.conf` 并修改配置

```
user = nobody
group =
```

* 开启：`/usr/local/php/sbin/php-fpm`


## linux 安装git

yum install git

出现提示是否下载的时候输入y并按回车。

输入git --version检查git是否安全完成，以及查看其版本号。

yum安装git被安装在/usr/libexec/git-core目录下。


## linux 安装mysql
添加 MySQL YUM 源

	# wget 'https://dev.mysql.com/get/mysql57-community-release-el7-11.noarch.rpm'
	# sudo rpm -Uvh mysql57-community-release-el7-11.noarch.rpm
	# yum repolist all | grep mysql

如果想安装最新版本的，直接使用 yum 命令即可

	# sudo yum install mysql-community-server

可以通过强制关掉yum进程：

	rm -f /var/run/yum.pid
	
启动 MySQL 服务
	
	sudo service mysqld start 
	
查看密码
	
	grep "password" /var/log/mysqld.log 
	
	utegtzngP5)R

 MySQL 5.7 中 Your password does not satisfy the current policy requirements. 问题

	set global validate_password_policy=0;  

	set global validate_password_length=4;  

	set password for 'root'@'localhost'=password('123456');
	
## 问题	
	
Linux 大部分命令失效

	PATH=/bin:/usr/bin
	
php 全局配置
	
	export PATH=$PATH:/usr/local/php-7.1.11/bin
	echo $PATH 
	
## linux 安装composer

	# 下载安装脚本
	$ php -r "readfile('https://getcomposer.org/installer');" > composer-setup.php
	
	# 执行安装脚本
	$ php composer-setup.php
	
	# 全局安装
	sudo mv composer.phar /usr/bin/composer
	
	# 中国镜像
	composer config -g repo.packagist composer https://packagist.phpcomposer.com
	
	
## 修改`.bash_profile`文件


```
function nginx_start() {

        /usr/local/nginx/sbin/nginx -c /usr/local/nginx/conf/nginx.conf
        echo -e "开启nginx服务"
}

function nginx_reload() {

        /usr/local/nginx/sbin/nginx -s reload
        echo -e "重启nginx服务"
}

function cat_tcp() {
    echo -e "查看TCP进程"
    sudo lsof -nP -iTCP -sTCP:LISTEN
}

function php_fpm_start() {
         /usr/local/php-7.1.11/sbin/php-fpm
        echo -e "开启php-fpm"
}
```


## 部署遇到的问题

* 改storage文件夹的权限

composer install 忽略版本

* composer install --ignore-platform-reqs

对于扩展Image需要安装php fileinfo

* https://segmentfault.com/a/1190000005058875

查看进程

* sudo lsof -nP -iTCP -sTCP:LISTEN

欢迎━(*｀∀´*)ノ亻!我们的第一期嘉宾-刘莹！