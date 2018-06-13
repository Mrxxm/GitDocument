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


重启nginx服务  
`# /usr/local/nginx/sbin/nginx -s reload`

## nginx.conf

```
#设置用户(nobody低权限用户，安全)
#user  nobody;

#工作衍生进程数(数字代表cpu的核数，通常设置为核数或核数两倍)
worker_processes  1;

#设置错误文件存放路径(错误、notice、info会记录到相应文件)
#error_log  logs/error.log;
#error_log  logs/error.log  notice;
#error_log  logs/error.log  info;

#设置pid存放路径(pid是控制系统中重要文件)
#pid        logs/nginx.pid;

#设置最大连接数
events {
    worker_connections  1024;
}


http {
    include       mime.types;
    default_type  application/octet-stream;

    #log_format  main  '$remote_addr - $remote_user [$time_local] "$request" '
    #                  '$status $body_bytes_sent "$http_referer" '
    #                  '"$http_user_agent" "$http_x_forwarded_for"';

    #access_log  logs/access.log  main;

    sendfile        on;
    #tcp_nopush     on;

    #keepalive_timeout  0;
    keepalive_timeout  65;

	#开启gzip压缩
    #gzip  on;

    server {
        listen       80;
        server_name  localhost;

		#设置字符
        #charset koi8-r;

        #access_log  logs/host.access.log  main;

        location / {
            root   html;
            index  index.html index.htm;
        }

        #error_page  404              /404.html;

        # redirect server error pages to the static page /50x.html
        #
        error_page   500 502 503 504  /50x.html;
        location = /50x.html {
            root   html;
        }

        # proxy the PHP scripts to Apache listening on 127.0.0.1:80
        #
        #location ~ \.php$ {
        #    proxy_pass   http://127.0.0.1;
        #}

        # pass the PHP scripts to FastCGI server listening on 127.0.0.1:9000
        #
        #location ~ \.php$ {
        #    root           html;
        #    fastcgi_pass   127.0.0.1:9000;
        #    fastcgi_index  index.php;
        #    fastcgi_param  SCRIPT_FILENAME  /scripts$fastcgi_script_name;
        #    include        fastcgi_params;
        #}

        # deny access to .htaccess files, if Apache's document root
        # concurs with nginx's one
        #
        #location ~ /\.ht {
        #    deny  all;
        #}
    }


    # another virtual host using mix of IP-, name-, and port-based configuration
    #
    #server {
    #    listen       8000;
    #    listen       somename:8080;
    #    server_name  somename  alias  another.alias;

    #    location / {
    #        root   html;
    #        index  index.html index.htm;
    #    }
    #}


    # HTTPS server
    #
    #server {
    #    listen       443 ssl;
    #    server_name  localhost;

    #    ssl_certificate      cert.pem;
    #    ssl_certificate_key  cert.key;

    #    ssl_session_cache    shared:SSL:1m;
    #    ssl_session_timeout  5m;

    #    ssl_ciphers  HIGH:!aNULL:!MD5;
    #    ssl_prefer_server_ciphers  on;

    #    location / {
    #        root   html;
    #        index  index.html index.htm;
    #    }
    #
    #}
}

```

必需项归类

```
worker_processes  1;

events {
    worker_connections  1024;
}

http {
	server {
	
	}
	
	server {
	
	}
}
```

## 配置虚拟主机

```
[root@VM_15_196_centos nginx]# pwd
/usr/local/nginx
[root@VM_15_196_centos nginx]# cd conf/
[root@VM_15_196_centos conf]# vim nginx.conf
```

在nginx.conf文件`http{}`配置加入    
`include sites-enabled/*;`

vi状态显示行号  
`:set nu`

```
 96     # HTTPS server
 97     #
 98     #server {
 99     #    listen       443 ssl;
100     #    server_name  localhost;
101 
102     #    ssl_certificate      cert.pem;
103     #    ssl_certificate_key  cert.key;
104 
105     #    ssl_session_cache    shared:SSL:1m;
106     #    ssl_session_timeout  5m;
107 
108     #    ssl_ciphers  HIGH:!aNULL:!MD5;
109     #    ssl_prefer_server_ciphers  on;
110 
111     #    location / {
112     #        root   html;
113     #        index  index.html index.htm;
114     #    }
115     #
116     #}
117     include sites-enabled/*;
118 }

```

新建sites-enabled文件夹并新建server、blog配置

```
[root@VM_15_196_centos conf]# mkdir sites-enabled
[root@VM_15_196_centos conf]# cd sites-enabled/
[root@VM_15_196_centos conf]# touch server
[root@VM_15_196_centos conf]# touch blog
[root@VM_15_196_centos sites-enabled]# ll
total 8
-rw-r--r-- 1 root root 200 Jun 13 12:47 blog
-rw-r--r-- 1 root root 201 Jun 13 12:46 server
```

blog

```
server {
	listen       80;
        server_name  blog.kenrou.cn;
        charset utf-8;

        location / {
            index  index.html index.htm index.php;
            root html/blog;
        }
}
```
server

```
server {
	listen       80;
        server_name  www.kenrou.cn;
        charset utf-8;

        location / {
            index  index.html index.htm index.php;
            root html/server;
        }
}
```
location对应html目录下相应的文件

```
[root@VM_15_196_centos html]# pwd
/usr/local/nginx/html
[root@VM_15_196_centos html]# ll
total 16
-rw-r--r-- 1 root root  537 Jun 13 10:47 50x.html
drwxr-xr-x 2 root root 4096 Jun 13 13:06 blog
-rw-r--r-- 1 root root  612 Jun 13 10:47 index.html
drwxr-xr-x 2 root root 4096 Jun 13 12:51 server
[root@VM_15_196_centos html]# cd blog
[root@VM_15_196_centos blog]# ll
total 4
-rw-r--r-- 1 root root 46 Jun 13 13:06 index.html
[root@VM_15_196_centos blog]# cd ../server/
[root@VM_15_196_centos server]# ll
total 4
-rw-r--r-- 1 root root 927 Jun 13 12:51 index.html
[root@VM_15_196_centos server]# 
```

重启nginx服务  
`# /usr/local/nginx/sbin/nginx -s reload`

结果显示(分别显示server目录下的index.html和blog目录下的index.html)

```
[root@VM_15_196_centos conf]# curl www.kenrou.cn
<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
	<h2>节点复制和删除</h2>
···
```

```
[root@VM_15_196_centos conf]# curl blog.kenrou.cn
<a href="https://github.com/Mrxxm">github</a>

```

## 日志文件配置
nginx.conf文件片段

```
 17 http {
 18     include       mime.types;
 19     default_type  application/octet-stream;
 20 
 21     #log_format  main  '$remote_addr - $remote_user [$time_local] "$request" '
 22     #                  '$status $body_bytes_sent "$http_referer" '
 23     #                  '"$http_user_agent" "$http_x_forwarded_for"';
 24 
 25     #access_log  logs/access.log  main;
 26 
 27     sendfile        on;
 28     #tcp_nopush     on;
 29  
```
设置日志文件的格式 **log_format** 

```
1. $remote_addr              客户端IP地址
2. $remote_user              客户端用户名
3. $request                  请求的url(客户访问的网址)
4. $status                   请求状态
5. $body_bytes_sent          发送给客户端的字节数(给客户返回数据)
6. $http_referer             原网页(客户跳转到落地页的上一个网页)
7. $http_user_agent          客户端浏览器对应信息(浏览器类型及其他信息)
8. $http_x_forwarded_for     客户端IP地址
```

日志信息打印

```
115.199.181.190 - - [13/Jun/2018:16:59:49 +0800] "GET / HTTP/1.1" 200 612 "-" "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_13_0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/67.0.3396.79 Safari/537.36"
```

日志文件存储路径配置 **access_log**

```
#日志文件完整地址 /usr/local/nginx/logs/access.log
```

关闭日志文件记录

```
access_log off;
```

## 日志文件切割

进入日志目录

```
[root@VM_15_196_centos nginx]# cd logs/
[root@VM_15_196_centos logs]# ll
total 16
-rw-r--r-- 1 root root 1954 Jun 13 17:03 access.log
-rw-r--r-- 1 root root 5849 Jun 13 16:56 error.log
-rw-r--r-- 1 root root    6 Jun 13 11:02 nginx.pid
```

第一步(将当前日志文件移动到相应文件夹已时间命名)

```
[root@VM_15_196_centos logs]# mkdir mainLog
[root@VM_15_196_centos logs]# ll
total 20
-rw-r--r-- 1 root root 2112 Jun 13 17:06 access.log
-rw-r--r-- 1 root root 5849 Jun 13 16:56 error.log
drwxr-xr-x 2 root root 4096 Jun 13 17:07 mainLog
-rw-r--r-- 1 root root    6 Jun 13 11:02 nginx.pid
[root@VM_15_196_centos logs]# mv access.log mainLog/201806131708.log
```

第二步(开启日志记录)

`# kill -USR1 7825`

```
[root@VM_15_196_centos logs]# ps -ef|grep nginx
root      7825     1  0 17:10 ?        00:00:00 nginx: master process /usr/local/nginx/sbin/nginx -c /usr/local/nginx/conf/nginx.conf
nobody    7826  7825  0 17:10 ?        00:00:00 nginx: worker process
root      7830  7468  0 17:10 pts/1    00:00:00 grep --color=auto nginx
[root@VM_15_196_centos logs]# ll
total 16
-rw-r--r-- 1 root root 5849 Jun 13 16:56 error.log
drwxr-xr-x 2 root root 4096 Jun 13 17:11 mainLog
-rw-r--r-- 1 root root    5 Jun 13 17:10 nginx.pid
[root@VM_15_196_centos logs]# kill -USR1 7825
[root@VM_15_196_centos logs]# ll
total 16
-rw-r--r-- 1 nobody root    0 Jun 13 17:11 access.log
-rw-r--r-- 1 nobody root 5849 Jun 13 16:56 error.log
drwxr-xr-x 2 root   root 4096 Jun 13 17:11 mainLog
-rw-r--r-- 1 root   root    5 Jun 13 17:10 nginx.pid
```

**定时执行脚本实现上面操作**

```
[root@VM_15_196_centos logs]# touch cutlog.sh
[root@VM_15_196_centos logs]# vim cutlog.sh 
```
cutlog.sh

```
D=$(date +%Y%m%d%H%M)
mv /usr/local/nginx/logs/access.log /usr/local/nginx/logs/mainLog/${D}.log
kill -USR1 $(cat /usr/local/nginx/logs/nginx.pid)
```

crontab

```
*/1 * * * * /bin/bash /usr/local/nginx/logs/cutlog.sh
```

结果

```
[root@VM_15_196_centos mainLog]# ll
total 4
-rw-r--r-- 1 nobody root    0 Jun 13 17:53 201806131754.log
-rw-r--r-- 1 nobody root    0 Jun 13 17:54 201806131755.log
-rw-r--r-- 1 nobody root    0 Jun 13 17:55 201806131756.log
-rw-r--r-- 1 nobody root 1120 Jun 13 17:56 201806131757.log
```


## 缓存和其他配置


TODO  
···
