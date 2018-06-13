# Nginx

![](https://timgsa.baidu.com/timg?image&quality=80&size=b9999_10000&sec=1528871232621&di=c51088645cd09b8dc6ac2666d1dee490&imgtype=0&src=http%3A%2F%2Fr.sinaimg.cn%2Flarge%2Ftc%2Fmmbiz_qpic_cn%2Fbb7e03d21edb0e6fd365a011602e1922.jpg)

## Nginx

* Nginx 服务器软件

登录linux服务器

```
➜  open git:(develop) ✗ ssh root@kenrou.cn
Last login: Wed Jun 13 10:17:43 2018 from 124.160.104.78
[root@VM_15_196_centos ~]# cd /usr/local/etc/
[root@VM_15_196_centos etc]# ll
total 0
```
下载nginx安装包

`# wget -c https://nginx.org/download/nginx-1.12.1.tar.gz`

```
[root@VM_15_196_centos etc]# wget -c https://nginx.org/download/nginx-1.12.1.tar.gz
--2018-06-13 10:27:26--  https://nginx.org/download/nginx-1.12.1.tar.gz
Resolving nginx.org (nginx.org)... 206.251.255.63, 95.211.80.227, 2001:1af8:4060:a004:21::e3, ...
Connecting to nginx.org (nginx.org)|206.251.255.63|:443... connected.
HTTP request sent, awaiting response... 200 OK
Length: 981093 (958K) [application/octet-stream]
Saving to: ‘nginx-1.12.1.tar.gz’

100%[====================================================================================================================>] 981,093      625KB/s   in 1.5s   

2018-06-13 10:27:30 (625 KB/s) - ‘nginx-1.12.1.tar.gz’ saved [981093/981093]

[root@VM_15_196_centos etc]# ll
total 964
-rw-r--r-- 1 root root 981093 Jul 11  2017 nginx-1.12.1.tar.gz
```

解压安装包   
`# tar -zxvf nginx-1.12.1.tar.gz `
 
```
[root@VM_15_196_centos etc]# tar -zxvf nginx-1.12.1.tar.gz 
nginx-1.12.1/
nginx-1.12.1/auto/
nginx-1.12.1/conf/
nginx-1.12.1/contrib/
nginx-1.12.1/src/
nginx-1.12.1/configure
nginx-1.12.1/LICENSE
nginx-1.12.1/README
nginx-1.12.1/html/
nginx-1.12.1/man/
nginx-1.12.1/CHANGES.ru
nginx-1.12.1/CHANGES
nginx-1.12.1/man/nginx.8
nginx-1.12.1/html/50x.html
nginx-1.12.1/html/index.html
···
```
 
编译nginx  
`# ./configure`
 
```
[root@VM_15_196_centos etc]# cd nginx-1.12.1/
[root@VM_15_196_centos nginx-1.12.1]# ll
total 732
drwxr-xr-x 6 1001 1001   4096 Jun 13 10:30 auto
-rw-r--r-- 1 1001 1001 277349 Jul 11  2017 CHANGES
-rw-r--r-- 1 1001 1001 422542 Jul 11  2017 CHANGES.ru
drwxr-xr-x 2 1001 1001   4096 Jun 13 10:30 conf
-rwxr-xr-x 1 1001 1001   2481 Jul 11  2017 configure
drwxr-xr-x 4 1001 1001   4096 Jun 13 10:30 contrib
drwxr-xr-x 2 1001 1001   4096 Jun 13 10:30 html
-rw-r--r-- 1 1001 1001   1397 Jul 11  2017 LICENSE
drwxr-xr-x 2 1001 1001   4096 Jun 13 10:30 man
-rw-r--r-- 1 1001 1001     49 Jul 11  2017 README
drwxr-xr-x 9 1001 1001   4096 Jun 13 10:30 src
[root@VM_15_196_centos nginx-1.12.1]# ./configure
```
 
错误一
 
 ```
 [root@VM_15_196_centos nginx-1.12.1]# ./configure
checking for OS
 + Linux 3.10.0-514.21.1.el7.x86_64 x86_64
checking for C compiler ... not found

./configure: error: C compiler cc is not found
 ```
 
 自动配置自动安装gc++   
 `# yum -y install gcc gcc-c++ autoconf automake`
 
```
[root@VM_15_196_centos /]# yum -y install gcc gcc-c++ autoconf automake
Loaded plugins: fastestmirror, langpacks
epel                                                                                                                                   | 3.2 kB  00:00:00     
extras                                                                                                                                 | 3.4 kB  00:00:00     
os                                                                                                                                     | 3.6 kB  00:00:00     
updates                                                                                                                                | 3.4 kB  00:00:00     
(1/7): epel/7/x86_64/group_gz                                                                                                          |  88 kB  00:00:00     
(2/7): extras/7/x86_64/primary_db                                                                                                      | 147 kB  00:00:00     
···
···
···
Complete!
```
 
 
错误二
 
```
[root@VM_15_196_centos nginx-1.12.1]# ./configure
checking for OS
 + Linux 3.10.0-514.21.1.el7.x86_64 x86_64
checking for C compiler ... found
 ···
 
 ./configure: error: the HTTP rewrite module requires the PCRE library.

```
执行命令安装pcre、openssl  
 
`# yum -y install pcre pcre-devel.i686 `
 
`# yum -y install pcre-devel openssl openssl-devel`
 
完成编译
 
```
[root@VM_15_196_centos nginx-1.12.1]# ./configure
checking for OS
 + Linux 3.10.0-514.21.1.el7.x86_64 x86_64
checking for C compiler ... found
 + using GNU C compiler

 ···
 Configuration summary
  + using system PCRE library
  + OpenSSL library is not used
  + using system zlib library

  nginx path prefix: "/usr/local/nginx"
  nginx binary file: "/usr/local/nginx/sbin/nginx"
  nginx modules path: "/usr/local/nginx/modules"
  nginx configuration prefix: "/usr/local/nginx/conf"
  nginx configuration file: "/usr/local/nginx/conf/nginx.conf"
  nginx pid file: "/usr/local/nginx/logs/nginx.pid"
  nginx error log file: "/usr/local/nginx/logs/error.log"
  nginx http access log file: "/usr/local/nginx/logs/access.log"
  nginx http client request body temporary files: "client_body_temp"
  nginx http proxy temporary files: "proxy_temp"
  nginx http fastcgi temporary files: "fastcgi_temp"
  nginx http uwsgi temporary files: "uwsgi_temp"
  nginx http scgi temporary files: "scgi_temp"

```

执行make指令  
`# make`
  
```
[root@VM_15_196_centos nginx-1.12.1]# make
make -f objs/Makefile
make[1]: Entering directory `/usr/local/etc/nginx-1.12.1'
cc -c -pipe  -O -W -Wall -Wpointer-arith -Wno-unused-parameter -Werror -g  -I src/core -I src/event -I src/event/modules -I src/os/unix -I objs \
	-o objs/src/core/nginx.o \
	src/core/nginx.c
···
```
  
执行make install指令  
`# make install`
  

```
[root@VM_15_196_centos nginx-1.12.1]# make install
make -f objs/Makefile install
make[1]: Entering directory `/usr/local/etc/nginx-1.12.1'
test -d '/usr/local/nginx' || mkdir -p '/usr/local/nginx'
test -d '/usr/local/nginx/sbin' \
	|| mkdir -p '/usr/local/nginx/sbin'
···
```
  
在/usr/local目录下生成nginx文件夹  
  
```
conf 配置  
html 网页程序  
logs 日志文件  
sbin nginx启动文件 
  
```
  
```
[root@VM_15_196_centos local]# cd nginx/
[root@VM_15_196_centos nginx]# ll
total 16
drwxr-xr-x 2 root root 4096 Jun 13 10:47 conf
drwxr-xr-x 2 root root 4096 Jun 13 10:47 html
drwxr-xr-x 2 root root 4096 Jun 13 10:47 logs
drwxr-xr-x 2 root root 4096 Jun 13 10:47 sbin
```
 
  
查看nginx主进程号  
`# ps -ef | grep nginx`

查询服务器公网ip
  
```
root@VM_15_196_centos sbin]# curl icanhazip.com
118.25.93.119
```
  
  
启动nginx服务  
`# /usr/local/nginx/sbin/nginx -c /usr/local/nginx/conf/nginx.conf`

TODO  
···