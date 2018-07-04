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

缓存设置

```
location ~.*\.(jpg|png|swf|gif)${
# 30天自动清除缓存
expires 30d;
}
```

```
location ~.*\.(css|js)?${
# 一个小时自动清除缓存
expires 1h;	
}
```

压缩功能配置(缩小成原网页的百分之三十)

```
#开启gzip压缩
gzip  on;
gzip_min_lenth 1k;
```

配置自动列目录

```
location / {
	root   html;
	index  index.html index.htm;
	autoindex on;
}
```

## 常用正则

```
. ： 匹配除换行符以外的任意字符  
? ： 重复0次或1次
+ ： 重复1次或更多次
* ： 重复0次或更多次
\d ：匹配数字
^ ： 匹配字符串的开始
~ ： 开头表示区分大小写的正则匹配
$ ： 匹配字符串的结束
() ：是为了提取匹配的字符串。表达式中有几个()就有几个相应的匹配字符串。
{n} ： 重复n次
{n,} ： 重复n次或更多次
[c] ： 匹配单个字符c
[a-z] ： 匹配a-z小写字母的任意一个

```

```
正则匹配
  ~ 区分大小写的匹配
  location ~ ^*.php${
    [matches]
  }
  ~* 不区分大小写的匹配
  location ~* ^*.php${
    [matches]
  }
  ^~ 普通字符匹配，如果请求匹配此规则，则其他规则忽略，只匹配该规则
  location ^~ /test{
    [matches]
  }
```
小括号()之间匹配的内容，可以在后面通过$1来引用，$2表示的是前面第二个()里的内容。正则里面容易让人困惑的是\转义特殊字符。


## location正则写法

location指令分为两种匹配模式：
 
1> 普通字符串匹配：以=开头或开头无引导字符（～）的规则   
2> 正则匹配：以～或～*开头表示正则匹配，~*表示正则不区分大小写

匹配规则

```
当nginx收到一个请求后，会截取请求的URI部份，去搜索所有location指令中定义的URI匹配模式。在server模块中可以定义多个location指令来匹配不同的url请求，多个不同location配置的URI匹配模式，总体的匹配原则是：先匹配普通字符串模式，再匹配正则模式。只识别URI部份，例如请求为：/test/abc/user.do?name=xxxx 
一个请求过来后，Nginx匹配这个请求的流程如下： 
1> 先查找是否有=开头的精确匹配，如：location = /test/abc/user.do { … } 
2> 再查找普通匹配，以 最大前缀 为原则，如有以下两个location，则会匹配后一项 
* location /test/ { … } 
* location /test/abc { … } 
3> 匹配到一个普通格式后，搜索并未结束，而是暂存当前匹配的结果，并继续搜索正则匹配模式 
4> 所有正则匹配模式location中找到第一个匹配项后，就以此项为最终匹配结果 
所以正则匹配项匹配规则，受定义的前后顺序影响，但普通匹配模式不会 
5> 如果未找到正则匹配项，则以3中缓存的结果为最终匹配结果 
6> 如果一个匹配都没搜索到，则返回404
```
![](https://img-blog.csdn.net/20170419232944871?watermark/2/text/aHR0cDovL2Jsb2cuY3Nkbi5uZXQvUm9iZXJ0b0h1YW5n/font/5a6L5L2T/fontsize/400/fill/I0JBQkFCMA==/dissolve/70/gravity/SouthEast)

```
location  = / {
  # 精确匹配 / ，主机名后面不能带任何字符串
  [ configuration A ]
}

location  / {
  # 因为所有的地址都以 / 开头，所以这条规则将匹配到所有请求
  # 但是正则和最长字符串会优先匹配
  [ configuration B ]
}

location /documents/ {
  # 匹配任何以 /documents/ 开头的地址，匹配符合以后，还要继续往下搜索
  # 只有后面的正则表达式没有匹配到时，这一条才会采用这一条
  [ configuration C ]
}

location ~ /documents/Abc {
  # 匹配任何以 /documents/Abc 开头的地址，匹配符合以后，还要继续往下搜索
  # 只有后面的正则表达式没有匹配到时，这一条才会采用这一条
  [ configuration CC ]
}

location ^~ /images/ {
  # 匹配任何以 /images/ 开头的地址，匹配符合以后，停止往下搜索正则，采用这一条。
  [ configuration D ]
}

location ~* \.(gif|jpg|jpeg)$ {
  # 匹配所有以 gif,jpg或jpeg 结尾的请求
  # 然而，所有请求 /images/ 下的图片会被 config D 处理，因为 ^~ 到达不了这一条正则
  [ configuration E ]
}

location /images/ {
  # 字符匹配到 /images/，继续往下，会发现 ^~ 存在
  [ configuration F ]
}

location /images/abc {
  # 最长字符匹配到 /images/abc，继续往下，会发现 ^~ 存在
  # F与G的放置顺序是没有关系的
  [ configuration G ]
}

location ~ /images/abc/ {
  # 只有去掉 config D 才有效：先最长匹配 config G 开头的地址，继续往下搜索，匹配到这一条正则，采用
    [ configuration H ]
}

location ~* /js/.*/\.js
```

## 配置详解

### `$uri`

这个变量指当前的请求URI，不包括任何参数(见$args)。这个变量反映任何内部重定向或index模块所做的修改。

### `last breake`

```
网友的给力解释：
last：
    重新将rewrite后的地址在server标签中执行
break：
    将rewrite后的地址在当前location标签中执行
```

### `try_files`

在0.7以后的版本中加入了一个try_files指令，配合命名location，可以部分替代原本常用的rewrite配置方式，提高解析效率。

```
语法:  try_files file ... uri 或 try_files file ... = code
默认值: 无
作用域: server location
```

作用: 是按顺序检查文件是否存在，返回第一个找到的文件或文件夹(结尾加斜线表示为文件夹)，如果所有的文件或文件夹都找不到，会进行一个内部重定向到最后一个参数。

注意:   
1.需要注意的是，只有最后一个参数可以引起一个内部重定向，之前的参数只设置内部URI的指向。最后一个参数是回退URI且必须存在，否则会出现内部500错误。     
2.命名的location也可以使用在最后一个参数中。  
3.与rewrite指令不同，如果回退URI不是命名的location那么$args不会自动保留，如果你想保留$args，则必须明确声明。
  
示例一  
try_files 将尝试你列出的文件并设置内部文件指向。

```
try_files /app/cache/ $uri @fallback; 
index index.php index.html;
```

```
它将检测$document_root/app/cache/index.php,$document_root/app/cache/index.html 和 $document_root$uri是否存在，如果不存在则内部重定向到@fallback(＠表示配置文件中预定义标记点) 。
你也可以使用一个文件或者状态码(=404)作为最后一个参数，如果是最后一个参数是文件，那么这个文件必须存在。
```
  

示例二  
nginx不解析PHP文件，以文本代码返回

```
try_files $uri /cache.php @fallback;
```

因为这个指令设置内部文件指向到 $document_root/cache.php 并返回,但没有发生内部重定向，因而没有进行location段处理而返回文本。  
(如果加上index指令可以解析PHP是因为index会触发一个内部重定向)
  
  
示例三  
跳转到变量

```
server {
 listen 8000;
 server_name 192.168.119.100;
 root html;
 index index.html index.php;
 
 location /abc {
     try_files /4.html /5.html @qwe;      		#检测文件4.html和5.html,如果存在正常显示,不存在就去查找@qwe值
}

 location @qwe  {
    rewrite ^/(.*)$   http://www.baidu.com;       #跳转到百度页面
}
```

常见错误

常见错误一

**try_files** 按顺序检查文件是否存在，返回第一个找到的文件，至少需要两个参数，但最后一个是内部重定向也就是说和rewrite效果一致，前面的值是相对$document_root的文件路径。也就是说参数的意义不同，甚至可以用一个状态码 (404)作为最后一个参数。如果不注意会有死循环造成500错误。

```
location ~.*\.(gif|jpg|jpeg|png)$ {
        root /web/wwwroot;
        try_files /static/$uri $uri;
}
```
`原意图是访问http://example.com/test.jpg时先去检查/web/wwwroot/static/test.jpg是否存在，不存在就取/web/wwwroot/test.jpg`

但由于最后一个参数是一个内部重定向，所以并不会检查/web/wwwroot/test.jpg是否存在，只要第一个路径不存在就会重新向然后再进入这个location造成死循环。结果出现500 Internal Server Error

```
location ~.*\.(gif|jpg|jpeg|png)$ {
        root /web/wwwroot;
        try_files /static/$uri $uri 404;
}
```

这样才会先检查/web/wwwroot/static/test.jpg是否存在，不存在就取/web/wwwroot/test.jpg再不存在则返回404 not found

常见错误二  
Nginx **try_files** $query_string为空的解决办法

```
server {
    listen 80;
    server_name localhost.dev;
    index index.php index.html index.htm;
    set $root_path '/var/www/phalcon/public'; 
    root $root_path;
    location / {
        try_files $uri $uri/ /index.php;
    }
    location ~ \.php$ {
        try_files $uri =404;
        fastcgi_split_path_info ^(.+\.php)(/.+)$;
        fastcgi_pass 127.0.0.1:9000;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name; include fastcgi_params;
    }
    location ~* ^/(css|img|js|flv|swf|download)/(.+)$ {
        root $root_path;
    }
    location ~ /\.ht {
        deny all;
    }
}
```

发现PHP无法获取$_GET信息

`try_files $uri $uri/ /index.php;`

改为

`try_files $uri $uri/ /index.php?$query_string;`

###  `rewrite`

rewrite功能就是，使用nginx提供的全局变量或自己设置的变量，结合正则表达式和标志位实现url重写以及重定向。

rewrite只能放在server{},location{},if{}中，并且只能对域名后边的除去传递的参数外的字符串起作用，例如 http://seanlook.com/a/we/index.php?id=1&u=str 只对/a/we/index.php重写。  

语法:

```
#正则表达式替换
rewrite regex replacement [flag];
```


### `internal`

```
语法：internal   
默认值：no 
使用字段： location 
```
internal指令指定某个location只能被“内部的”请求调用，外部的调用请求会返回"Not found"(404)"内部的"是指下列类型:
  
*  指令error_page重定向的请求。
  
* `ngx_http_ssi_module` 模块中使用include virtual指令创建的某些子请求。
  
* `ngx_http_rewrite_module` 模块中使用rewrite指令修改的请求。 

 
一个防止错误页面被用户直接访问的例子：
error_page 404 /404.html;

```  
location  /404.html {
	internal;
}
```

### `$document_root`

```
 location ~ \.php$ {
            root           html;
            fastcgi_pass   127.0.0.1:9000;
            fastcgi_index  index.php;
            fastcgi_param  SCRIPT_FILENAME  $document_root$fastcgi_script_name;
            include        fastcgi_params;
        }
```

因为$document_root 的参数是由root html那一行定义的，默认是在/usr/local/nginx/html/ 所以把 html换成站点根目录就正常了。

### `fastcgi_pass`

```
句法：	fastcgi_pass address;
默认：	-
语境：	location， if in location

```

设置FastCGI服务，其值可以是一个域名、IP地址:端口、或者是一个Unix的Socket文件。  
同时，它也只支持一个FastCGI服务集群。

```
# TCP形式传递
fastcgi_pass localhost:9000;

# Socket形式传递
fastcgi_pass unix:/tmp/fastcgi.socket;

# 传递给集群
upstream cloud {
    server cgi_1.cloud.com;
    server cgi_2.cloud.com;
}
fastcgi_pass cloud;
```

```
Nginx和PHP-FPM的进程间通信有两种方式,一种是TCP,一种是UNIX Domain Socket.
其中TCP是IP加端口,可以跨服务器.而UNIX Domain Socket不经过网络,只能用于Nginx跟PHP-FPM都在同一服务器的场景.用哪种取决于你的PHP-FPM配置:
方式1:
php-fpm.conf: listen = 127.0.0.1:9000
nginx.conf: fastcgi_pass 127.0.0.1:9000;
方式2:
php-fpm.conf: listen = /tmp/php-fpm.sock
nginx.conf: fastcgi_pass unix:/tmp/php-fpm.sock;
其中php-fpm.sock是一个文件,由php-fpm生成,类型是srw-rw----.
```

### `fastcgi_param`

`作用域：http, server, location`

设置一个传递给FastCGI服务的参数，可以是文本或者是变量。

```
例如在接入层Nginx上面传递如下5个参数
fastcgi_param  REMOTE_ADDR        $remote_addr;
fastcgi_param  REMOTE_PORT        $remote_port;
fastcgi_param  SERVER_ADDR        $server_addr;
fastcgi_param  SERVER_PORT        $server_port;
fastcgi_param  SERVER_NAME        $server_name;
那么在FastCGI上面，例如PHP-CGI上面就可以通过$_SERVER这个超全局变量获取。
$_SERVER['REMOTE_ADDR']
$_SERVER['REMOTE_PORT']
$_SERVER['SERVER_ADDR']
$_SERVER['SERVER_PORT']
$_SERVER['SERVER_NAME']
```

### `fastcgi_index`

`作用域：http, server, location`

当请求以/结尾的时候，会将请求传递给所设置的index.php文件处理。

```
fastcgi_index index.php;
fastcgi_param SCRIPT_FILENAME /home/www/scripts/php$fastcgi_script_name;
```



### `fastcgi_split_path_info`

```
句法：	fastcgi_split_path_info regex;
默认：	-
语境：	location
```

定义一个捕获`$fastcgi_path_info`变量值的正则表达式 。正则表达式应该有两个捕获：第一个成为`$fastcgi_script_name`变量的值，第二个成为`$fastcgi_path_info`变量的值

Nginx默认获取不到`PATH_INFO`的值，得通过`fastcgi_split_path_info`指定定义的正则表达式来给`$fastcgi_path_info`赋值。

其正则表达式必须要有两个捕获。

* 第一个捕获的值会重新赋值给`$fastcgi_script_name`变量。
* 第二个捕获到的值会重新赋值给`$fastcgi_path_info`变量。

```
location ~ ^(.+\.php)(.*)$ {
    fastcgi_split_path_info       ^(.+\.php)(.*)$;
    fastcgi_param SCRIPT_FILENAME /path/to/php$fastcgi_script_name;
    fastcgi_param PATH_INFO       $fastcgi_path_info;
}
```

原始请求是 `/show.php/article/0001`

通过分割，`FastCGI`得到的结果是：


* `SCRIPT_FILENAME: /path/to/php/show.php`
* `PATH_INFO: /article/0001`


Nginx在0.7.31以前是没有`fastcgi_split_path_info`这个指令的，而0.7.x这个版本一直存活了好多年，后面才高歌猛进，导致网上存在大量旧版本通过正则自己设置`PATH_INFO`的方法。

nginx 只是个 Proxy，它只负责根据用户的配置文件，通过 fastcgi_param 指令将参数忠实地传递给 FastCGI Server

由于path_info没有设定，导致url无法获取出错，导致route出错！对于php的很多框架，这个问题都是适用的！

### `fastcgi_buffer_size`

```
句法：	fastcgi_buffer_size size;
默认：	fastcgi_buffer_size 4k | 8k;
语境：	http，server，location
```

设置size用于读取从FastCGI服务器收到的响应的第一部分的缓冲区。这部分通常包含一个小的响应头。默认情况下，缓冲区大小等于一个内存页面。这是4K或8K，取决于平台。但是，它可以做得更小。

### `fastcgi_buffers`

```
句法：	fastcgi_buffers number size;
默认：	fastcgi_buffers 8 4k | 8k;
语境：	http，server，location
```

设置number和size用于读取从FastCGI的服务器的响应，供用于单个连接的缓冲器。默认情况下，缓冲区大小等于一个内存页面。这是4K或8K，取决于平台。

## PHP以FastCGI方式运行


CGI通用网关接口（Common Gateway Interface）是一个Web服务器主机提供信息服务的标准接口。通过CGI接口，Web服务器就能够获取客户端提交的信息，转交给服务器端的CGI程序进行处理，最后返回结果给客户端。

拿nginx、php这种模式来简单理解cgi更为直观：

-------------

nginx：“哎呀，收到客户端的一个http请求，该干活了......咦，有php-fpm这小子的活儿！”

nginx：“别睡了，别睡了，php-fpm你该起来干活儿了...”

php-fpm：“好滴，把客户端的http请求消息体给我一份啊......”

php-fpm：“nginx，我的活儿干完了，接收我要发给客户端的数据，麻溜的...”

nginx：“好滴，合作愉快”

-------------

Nginx接收到php-fpm处理的结果后，就可以响应客户端的http请求给予一个回应了，客户端的这一次http请求就结束了，一张由php产生的华丽丽的网页就呈现在网民的面前。在这段对话中，nginx与php-fpm并没有相互推诿扯皮，交流的很顺畅；没有推诿扯皮的原因就是nginx与php-fpm之间的数据和消息传递使用了统一的标准格式，这个标准格式就是CGI，所以倘若nginx和php-fpm中有任何一方不按CGI标准来玩，你推诿扯皮也没用。

发展到现在，对CGI的理解可以是一种标准接口（协议规范），也可以理解成处理动态网页的某种语言，比如：php、asp都可以宽泛的看做是一种cgi，这个时候cgi就被泛化了但依然包含了不推诿扯皮的交流标准的这一层含义。

FastCGI的Fast已经表明含义了，是一种快速的CGI，也是现代动态网页语言与web server之间普遍所采用的。FastCGI像是一个常驻型的CGI，它可以一直执行着，只要激活后，不会每次都要花费时间去fork一次（这是CGI最为人诟病的fork-and-execute 模式）。它还支持分布式的运算，即FastCGI程序可以在网站服务器以外的主机上执行并且接受来自其它网站服务器来的请求。

nginx与php-fpm就是采用的FastCGI模式。

cgi、fastcgi、php-fpm

```
cgi是协议  
fastcgi是cgi升级后的协议  

实现cgi协议的是cgi程序  
实现fastcgi协议的是fastcgi程序  

PHP-CGI程序就实现了fastcgi协议  
所以如果使用的是php-cgi, 启动后的进程应该叫php-cgi, 每个进程都可以解析php, 都叫php解析器  

PHP-FPM就是实现了fastcgi协议, 并且有自己的进程管理方式, 所以如果使用php-fpm, 启动后的进程应该就是php-fpm , 这里每个进程也都可以解析php, 也都可以叫php解析器
我们在进程中看到的PHP-FPM是PHP-CGI的管理调度器。  
```

详细解释：

web server（如nginx）只是内容的分发者。比如，如果请求/index.html，那么web server会去文件系统中找到这个文件，发送给浏览器，这里分发的是静态资源。如果现在请求的是/index.php，根据配置文件，nginx知道这个不是静态文件，需要去找PHP解析器来处理，那么他会把这个请求简单处理后交给PHP解析器。此时CGI便是规定了要传什么数据／以什么格式传输给php解析器的协议。当web server收到/index.php这个请求后，会启动对应的CGI程序，这里就是PHP的解析器。接下来PHP解析器会解析php.ini文件，初始化执行环境，然后处理请求，再以CGI规定的格式返回处理后的结果，退出进程。web server再把结果返回给浏览器。

那么CGI相较于Fastcgi而言其性能瓶颈在哪呢？CGI针对每个http请求都是fork一个新进程来进行处理，处理过程包括解析php.ini文件，初始化执行环境等，然后这个进程会把处理完的数据返回给web服务器，最后web服务器把内容发送给用户，刚才fork的进程也随之退出。 如果下次用户还请求动态资源，那么web服务器又再次fork一个新进程，周而复始的进行。

而Fastcgi则会先fork一个master，解析配置文件，初始化执行环境，然后再fork多个worker。当请求过来时，master会传递给一个worker，然后立即可以接受下一个请求。这样就避免了重复的劳动，效率自然是高。而且当worker不够用时，master可以根据配置预先启动几个worker等着；当然空闲worker太多时，也会停掉一些，这样就提高了性能，也节约了资源。这就是Fastcgi的对进程的管理。大多数Fastcgi实现都会维护一个进程池。注：swoole作为httpserver，实际上也是类似这样的工作方式。


此配置仅允许web目录下app.php和app_dev.php两个入口文件以PHP脚本方式运行，web目录下存在的其他PHP文件如果被访问，将被用户下载。


```
# 此段为将PHP请求转交给FastCGI服务，PHP-FPM是非常流行的选项。
location ~ ^/(app|app_dev)\.php(/|$) {
    fastcgi_pass   127.0.0.1:9000;
    fastcgi_split_path_info ^(.+\.php)(/.*)$;
    include fastcgi_params;
    fastcgi_param  SCRIPT_FILENAME    $document_root$fastcgi_script_name;
    fastcgi_param  HTTPS              off;
}
```

**PATHINFO**  

常常会见到这种格式的Url`https://blog.jjonline.cn/index.php/Article/Post/index.html `，这种Url理解有两种方式：

* index.php当做一个目录看待：访问`blog.jjonline.cn`服务器根目录下的index.php目录下的Article目录下的Post目录下的index.html静态html文本文件；  
* index.php当做一个PHP脚本看待：访问`blog.jjonline.cn`服务器根目录下的index.php脚本，由该脚本产生html页面，Url中`/Article/Post/index.html`这一部分作为index.php脚本中使用的某种类型的参数。

绝大部分情况下，这种格式的Url理解方式是第二种，而`/Article/Post/index.html`这一部分理解成PATHINFO就好了。其实PATHINFO是一个CGI 1.1的一个标准，经常用来做为传参载体，只不过咱们没必要深入。

由于Apache的默认配置文件开启了PATHINFO的支持，Apache+PHP的环境下PATHINFO格式的Url可以不出任何错误的执行正确路径的PHP脚本并在脚本中使用PATHINFO中的参数。而Nginx默认提供的有关执行php-fpm运行PHP脚本的默认配置文件中并没有启用PATHINFO，从而导致了一个长久以来的误解：nginx不支持pathinfo。

早期版本的nginx确实不能直接支持pathinfo，但有变相的解决方法，网络上的一些配置nginx支持pathinfo的文章大多就是这种变相解决方法。nginx其实早已可以很简单的通过`fastcgi_split_path_info`指令支持pathinfo模式了，严格来说是nginx的0.7.31以上版本就可以使用这个指令了。

## 使用 Nginx 的 X-Sendfile 机制提升 PHP 文件下载性能

很多时候用户需要从网站下载文件，如果文件是可以通过一个固定链接公开获取的，那么我们只需将文件存放到 webroot 下的目录里就好。但大多数情况下，我们需要做权限控制，例如下载 PDF 账单，又例如下载网盘里的档案。这时，我们通常借助于脚本代码来实现，而这无疑会增加服务器的负担。

```
<?php
    // 用户身份认证，若验证失败跳转
    authenticate(); 
    // 获取需要下载的文件，若文件不存在跳转
    $file = determine_file();
    // 读取文件内容 
    $content=file_get_contents($file);
    // 发送合适的 HTTP 头
    header("Content-type: application/octet-stream");
    header('Content-Disposition: attachment; filename="' . basename($file) . '"');
    header("Content-Length: ". filesize($file));
    echo $content; // 或者 readfile($file);
?>
```

**这样做有什么问题** 

这样做意味着我们的程序需要将文件内容从磁盘经过一个固定的 buffer 去循环读取到内存，再发送给前端 web 服务器，最后才到达用户。当需要下载的文件很大的时候，这种方式将消耗大量内存，甚至引发 php 进程超时或崩溃。Cache 也很头疼，更不用说中断重连的情况了。

一个理想的解决方式应该是，由 php 程序进行权限检查等逻辑判断，一切通过后，让前台的 web 服务器直接将文件发送给用户——像 Nginx 这样的前台更善于处理静态文件。这样一来 php 脚本就不会被 I/O 阻塞了。

**什么是 X-Sendfile** 

X-Sendfile 是一种将文件下载请求由后端应用转交给前端 web 服务器处理的机制，它可以消除后端程序既要读文件又要处理发送的压力，从而显著提高服务器效率，特别是处理大文件下载的情形下。

X-Sendfile 通过一个特定的 HTTP header 来实现：在 X-Sendfile 头中指定一个文件的地址来通告前端 web 服务器。当 web 服务器检测到后端发送的这个 header 后，它将忽略后端的其他输出，而使用自身的组件（包括 缓存头 和 断点重连 等优化）机制将文件发送给用户。

不过，在使用 X-Sendfile 之前，我们必须明白这并不是一个标准特性，在默认情况下它是被大多数 web 服务器禁用的。而不同的 web 服务器的实现也不一样，包括规定了不同的 X-Sendfile 头格式。如果配置失当，用户可能下载到 0 字节的文件。

使用 X-Sendfile 将允许下载非 web 目录中的文件（例如/root/），即使文件在 .htaccess 保护下禁止访问，也会被下载。

不同的 web 服务器实现了不同的 HTTP 头

SENDFILE 头  | 使用的 WEB 服务器
------------- | -------------
X-Sendfile  | Apache, Lighttpd v1.5, Cherokee
X-LIGHTTPD-send-file  | Lighttpd v1.4
X-Accel-Redirect | Nginx, Cherokee

使用 X-SendFile 的缺点是你失去了对文件传输机制的控制。例如如果你希望在完成文件下载后执行某些操作，比如只允许用户下载文件一次，这个 X-Sendfile 是没法做到的，因为后台的 php 脚本并不知道下载是否成功。

**使用**

Nginx 默认支持该特性，不需要加载额外的模块。只是实现有些不同，需要发送的 HTTP 头为 X-Accel-Redirect。另外，需要在配置文件中做以下设定

```
location /protected/ {
  internal;
  root   /some/path;
}
```

internal 表示这个路径只能在 Nginx 内部访问，不能用浏览器直接访问防止未授权的下载。

于是 PHP 发送 X-Accel-Redirect 给 Nginx：

```
<?php
    $filePath = '/protected/iso.img';
    header('Content-type: application/octet-stream');
    header('Content-Disposition: attachment; filename="' . basename($file) . '"');
    //让Xsendfile发送文件
    header('X-Accel-Redirect: '.$filePath);
?>
```

这样用户就会下载到 /some/path/protected/iso.img 这个路径下的文件。

如果你想发送的是 /some/path/iso.img 文件，那么 Nginx 配置应该是

```
location /protected/ {
  internal;
  alias   /some/path/; # 注意最后的斜杠
}
```
### HTTP_X-Accel-Mapping

```
＃映射到内部位置的真实路径
```

配置案例

```
server {
    listen 80;

    # [改] 网站的域名
    server_name www.example.com example.com;

    # [改] 程序的安装路径
    root /var/www/edusoho/web;

    # [改] 日志路径
    access_log /var/log/nginx/example.com.access.log;
    error_log /var/log/nginx/example.com.error.log;

    location / {
        index app.php;
        try_files $uri @rewriteapp;
    }

    location @rewriteapp {
        rewrite ^(.*)$ /app.php/$1 last;
    }

    location ~ ^/udisk {
        internal;
        # [改] 请根据程序的实际安装路径修改。该目录下存放的是私有的文件课时的视频、音频等。
        root /var/www/edusoho/app/data/;
    }

    location ~ ^/(app|app_dev)\.php(/|$) {
        # [改] 请根据实际php-fpm运行的方式修改
        fastcgi_pass   unix:/var/run/php5-fpm.sock;
        fastcgi_split_path_info ^(.+\.php)(/.*)$;
        include fastcgi_params;
        fastcgi_param  SCRIPT_FILENAME    $document_root$fastcgi_script_name;
        fastcgi_param  HTTPS              off;
        fastcgi_param HTTP_X-Sendfile-Type X-Accel-Redirect;
        # [改] 请根据程序的实际安装路径修改。该目录下存放的是私有的文件。
        fastcgi_param HTTP_X-Accel-Mapping /udisk=/var/www/edusoho/app/data/udisk;
        fastcgi_buffer_size 128k;
        fastcgi_buffers 8 128k;
    }

    # 配置设置图片格式文件
    location ~* \.(jpg|jpeg|gif|png|ico|swf)$ {
        # 过期时间为3年
        expires 3y;
        
        # 关闭日志记录
        access_log off;

        # 关闭gzip压缩，减少CPU消耗，因为图片的压缩率不高。
        gzip off;
    }

    # 配置css/js文件
    location ~* \.(css|js)$ {
        access_log off;
        expires 3y;
    }

    # 禁止用户上传目录下所有.php文件的访问，提高安全性
    location ~ ^/files/.*\.(php|php5)$ {
        deny all;
    }

    # 以下配置允许运行.php的程序，方便于其他第三方系统的集成。
    location ~ \.php$ {
        # [改] 请根据实际php-fpm运行的方式修改
        fastcgi_pass   unix:/var/run/php5-fpm.sock;
        fastcgi_split_path_info ^(.+\.php)(/.*)$;
        include fastcgi_params;
        fastcgi_param  SCRIPT_FILENAME    $document_root$fastcgi_script_name;
        fastcgi_param  HTTPS              off;
    }
}
```
匹配index.html

```
server {
        listen       80;
        server_name  blog.kenrou.cn;
        charset utf-8;
        root /usr/local/nginx/html/blog;

        location / {
            rewrite ^(.*)$ /index.html last;
        }

        location ~ ^/(index)\.html(/|$) {
            index index.html;
            try_files $uri 300;
        }


}

```
匹配index.htm

```
server {
        listen       80;
        server_name  blog.kenrou.cn;
        charset utf-8;
        root /usr/local/nginx/html/blog;

        location / {
            rewrite ^(.*)$ /blog last;
        }

        location ~ /(blog) {
            index index.htm;
            try_files /index.htm 300;
        }


}

```





## 反向代理

访问自己服务器IP代理到百度

```
server {
        listen       80;
        server_name  118.25.93.119;
        access_log /usr/local/nginx/logs/proxy-server.access.log;
        charset utf-8;

        # Gzip Compression
        gzip on;
        gzip_comp_level 6;
        gzip_vary on;
        gzip_min_length  1000;
        gzip_proxied any;
        gzip_types text/plain text/css application/json application/x-javascript text/xml application/xml application/xml+rss text/javascript;
        gzip_buffers 16 8k;

        # 反向代理 proxy-server
        location / {
                proxy_set_header X-Real-IP $remote_addr;
                proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
                proxy_set_header Host www.baidu.com;
                proxy_set_header X-NginX-Proxy true;
                # 代理的地址
                proxy_pass http://www.baidu.com;
                proxy_redirect off;
        }
}

```

## 负载均衡

```
upstream proxy-server{
   # 淘宝
   server 218.67.61.254  weight=1;
   # 淘宝
   server 182.140.245.49 weight=1;
}

server {
        listen       80;
        server_name  118.25.93.119;
        access_log /usr/local/nginx/logs/proxy-server.access.log;
        charset utf-8;

        # Gzip Compression
        gzip on;
        gzip_comp_level 6;
        gzip_vary on;
        gzip_min_length  1000;
        gzip_proxied any;
        gzip_types text/plain text/css application/json application/x-javascript text/xml application/xml application/xml+rss text/javascript;
        gzip_buffers 16 8k;

        # 反向代理 proxy-server
        location / {
                proxy_set_header X-Real-IP $remote_addr;
                proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
                proxy_set_header Host www.taobao.com;
                proxy_set_header X-NginX-Proxy true;
                # 代理的地址
                proxy_pass http://proxy-server;
                proxy_redirect off;
        }
}
```

TODO  
···
