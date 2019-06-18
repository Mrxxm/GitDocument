#### HTTPS协议介绍

* 物理层：对光电信号进行传输-电缆

* 数据链路层：数字信号和光电信号之间的转换-网卡

* 网络层：IP、ARP协议(IP与MAC地址的转换)

* 运输层：TCP、UDP协议完成信息的传输

* 应用层：HTTP、FTP协议面向应用开发的协议

OSI模型中的应用层协议(工作在IP协议和TCP协议之上)

隶属于TCP/IP协议族(网络传输协议家族)

HTTP协议的安全版本(HTTP协议+安全传输协议)

默认端口：443

提供网站服务器的安全认证

保护交换数据的隐私和安全性

#### HTTPS和HTTP的区别

* 协议头和默认端口不同

* HTTPS协议需要使用安全证书

* 协议栈不同

```
HTTP HTTPS
TCP  SSL/TLS
IP   TCP
     IP
...  ...
```

* 资源消耗不同(HTTPS计算量大、HTTP计算量小)

* 内容传输方式不同(HTTPS加密传输、HTTP明文传输)

* 应用场景不同

HTTPS协议的优势

* 数据完整性(内容经过完整性校验)

* 数据私密性(内容经过对称加密，加解密秘钥具有唯一性)

* 身份认证(第三方无法伪造服务器、客户端身份)

* 实用性强

HTTPS协议的劣势

* 成本提高

* 性能损耗

#### HTTPS协议原理-SSL和TLS发展史

HTTP协议发展历史

HTTP/0.9

* 1991年发布
* 主要规定通信格式，不涉及数据包传输
* 客户端只有GET方式
* 服务端只返回HTML格式的字符串内容

HTTP/1.0

* 1996年发布
* 引入POST和HEAD方法
* 添加请求与响应中的头信息
* 其他新增包括：状态码、多字符集支持、多部分发送、权限、缓存、内容编码等

HTTP/1.1

* 1997年发布
* 增加Host请求头字段，支持虚拟主机
* 引入PUT、PATCH、OPTIONS、DELETE方法
* 其他新增包括：久连接、管道机制、分块传输等

HTTP/2.0

* 2015年发布
* 完全的二进制协议
* 引入多工、头信息压缩、服务器推送等新功能

安全传输协议发展历史

SSL 1.0

* 1994年由Netscape公司设计(已解散，重要发明：Javascript、Cookie...)
* 最早应用于Navigate浏览器
* 由于存在严重安全漏洞，从未正式发布

SSL 2.0

* 1995年由Netscape公司设计并发布
* 存在数个安全漏洞，容易遭到中间人攻击
* 大部分浏览器均不在支持

SSL 3.0

* 1996年由Netscape公司重新设计并发布
* 较之前版本安全性大大提高
* 2014年Google发现其设计缺陷，随后主流浏览器逐渐放弃支持SSL 3.0协议

TLS 1.0

* 1999年IETF将SSL标准化(RFC 2246),在SSL 3.0的基础上设计并发布了TLS 1.0
* 从技术上讲，TLS 1.0和SSL 3.0的差异非常小
* TLS 1.0可以降级到SSL 3.0，因此削弱了其安全性

TLS 1.1

* IETF于2006年发布TLS 1.1(RFC 4346)
* 是TLS 1.0的更新版本
* 提高了安全性，添加对CBC攻击的保护
* 支持IANA登记的参数

TLS 1.2

* IETF于2008年发布TLS 1.2(RFC 5246)
* 基于TLS 1.1规范设计
* 使用了更加高效的安全加密算法

更多

* TLS所有版本在2011年3月发布的RFC 6176中删除了对 SSL的兼容
* TLS 1.3于2018年3月称为建议标准的互联网草案


#### HTTPS传输层安全协议-SSL协议介绍

传输层安全协议

* 位于可靠的面向连接的运输层协议TCP和应用层协议HTTP之间

```
应用层协议
传输层安全协议
运输层协议
...
```

* 通过互相认证确保不可冒充性，通过数字签名确保信息完整性，通过加密确保私密性，以实现客户端和服务器之间的安全通信

* 独立于应用层协议，高层协议可以透明的分布在传输层安全协议之上

传输层安全协议

* 传输层安全协议主要有两种

* SSL（Secure Sockets Layer）安全套接层

* TLS（Transport Layer Security）传输层安全协议

SSL安全套接层

* SSL记录协议（SSL Record Protocol）建立在可靠的传输协议（如TCP）之上，为高层协议（如HTTP）提供数据封装、加解密、解压缩、签名及完整性校验等基本功能的支持

* SSL握手协议（SSL Handshake Protocol）建立在SSL记录协议之上，用于在实际数据传输开始之前，通讯双方进行身份认证、协商加密算法、交换加密秘钥等

```
应用层协议  HTTP协议、FTP协议等
SSL握手协议 身份认证、算法协商、秘钥交换
SSL记录协议 数据封装、加密、压缩、签名等
运输层协议  可靠的传输协议，如TCP协议
```

#### HTTPS传输层安全协议-TLS协议介绍

TLS传输层安全协议

* TLS记录协议（TLS Record Protocol）建立在可靠的传输协议（如TCP）之上，对数据进行加解密、解压缩、签名及完整性校验等基本功能的支持

* TLS握手协议（TSL Handshake Protocol）建立在SSL记录协议之上，使用公共秘钥和证书处理用户认证，并协商算法和加密实际数据传输的秘钥

```
应用层协议  HTTP协议、FTP协议等
TLS握手协议 身份认证、算法协商、秘钥交换
TLS记录协议 数据封装、加密、压缩、签名等
运输层协议  可靠的传输协议，如TCP协议
```

TLS和SSL比较

* TLS协议是基于SSL协议发展而来的，从技术上讲，TLS1.0和SSL3.0差异非常小

* TLS协议使用了更安全的HMAC算法，更强大的伪随机功能，更严密的报警信息

* TLS协议比SSL协议更加复杂和安全，协议的规范更精确和完善

TLS/SSL安全传输通道

* 认证用户和服务器，确保数据发送到正确的客户机和服务器

* 加密数据，以防止数据途中被窃取

* 维护数据的完整性，确保数据在传输过程中不被篡改

#### HTTPS 协议-安全传输通道介绍


TLS/SSL安全传输通道

* HTTPS在通信双方建立一个安全传输通道

* 安全传输通道保证了信息传输的安全性和完整性

攻击-窃听

* 在网络中利用某种手段非法窥探其他用户资料

HTTPS如何防范窃听风险

* 将传输内容利用对称加密的方式加密

攻击-篡改

* 

HTTPS如何防范篡改风险

* 对数据的内容利用MAC算法对数据进行签名和验证

攻击-中间人

* 通过某种手段将受控的计算机虚拟放置在两条通信计算机之间

HTTPS如何防范中间人攻击

* 建立连接的时候利用数字证书进行通信双方身份认证

#### HTTPS 协议如何保证信息的安全传输

安全传输通道的工作过程

* 利用数字证书对身份双方进行身份认证（防止通信双方被冒充，防止中间人攻击）

* 利用非对称加密方式传输会话秘钥（这个加密秘钥是用来加密双方通讯过程中传输的信息的，只有这个秘钥才能对信息的加解密）

* 利用对称加密的方式传输通信内容（这个秘钥就是上面的秘钥只有客户端和服务器才知道）

* 利用MAC算法对传输的内容进行签名和校验

#### HTTPS 协议-四次握手

![](https://img3.doubanio.com/view/photo/l/public/p2553990852.jpg)

第一次握手

* 客户端发起连接请求`Client Hello`

```
支持的安全传输协议 - SSLv2、SSLv3、TLSv1、TLSv1.1、TLSv1.2
支持的加密套件 - 身份认证+密钥协商+信息加密+完整性校验
支持的压缩算法 - 用于后续的信息压缩
随机数C - 用于后续密钥生成
扩展字段 - 协议或算法相关的辅助信息
```

第二次握手

* 服务器返回协商的结果`Server Hello`

```
选择使用的安全传输协议、加密套件和压缩算法
随机数S - 用于后续密钥的生成
```

* 服务器配置的证书链`Certificate`

* 服务器发送消息结束标识`Server Hello Done`

* 根据选择加密套件的不同，可能还会有其他的内容

客户端证书校验

* 客户端对服务器返回的证书进行校验

```
证书链是否可信 - 能够被可信任的CA根证书验证合法性
证书是否被吊销
证书是否在有效期内
证书域名与网站域名是否匹配
```

第三次握手

* 客户端生成第三个随机数`Pre-master`,并用证书公钥加密发送给服务器`Client Key Exchange`

```
客户端将通过随机数C、随机数S和Pre-master计算出本次会话的秘钥Key = func(C,S,Pre-master);
```

* 客户端向服务器确认加密方式`Change Cipher Spec`

* 客户端计算前面所有消息的摘要值并加密发送给服务器`Encrypted Handshake Message`

第四次握手

* 服务器先通过私钥解密`Client Key Exchange`的内容，获取`Pre-master`的值并计算会话秘钥`Key = func(C,S,Pre-master)`

* 服务器解密客户端发送的加密握手消息并校验

* 服务器向客户端确认加密方式`Change Cipher Spec`

* 服务器计算当前所有消息的摘要值并加密发送给客户端`Encrypted Handshake Message`

握手完成 + 信息传输

* 客户端计算所有消息的摘要值并与服务器发送的加密握手消息做校验

* 校验通过则握手完成，无需向服务器发送任何消息

#### 双向认证

* 除了客户端验证服务器身份之外，服务器也可以要求验证客户端的身份，即双向认证

* 在第二次握手时，服务器向客户端发送`Client Certificate Request`信息，请求查看客户端证书

* 在第三次握手时，客户端会同时发送`Client Certificate`和`Certificate Verify Message`给服务器

四次握手中的加密算法

* 第三次握手中使用非对称加密算法加密`Pre-master`

* 第三次和第四次握手中使用MAC算法生成`Encrypted Handshake Message`

* 握手成功后使用对称加密算法加密通道数据

#### HTTPS协议-TSL会话缓存机制

会话缓存

* 为了节省网络资源，提高HTTPS协议的工作效率，TLS协议中有两类会话缓存机制

* 会话标识`Session ID`

* 会话记录`Session Ticket`

`Session ID`缓存机制

* 在服务器端保存每次会话的ID和协商的通讯信息

* 基本所有服务器都支持

`Session Ticket`缓存机制

* 将协商的通信信息加密之后发送给客户端保存，解密的秘钥由服务器保存

* 服务器支持的范围有限

* 占用服务器资源少

比较：`Session ID`和`Session Ticket`类似于Session和Cookie，两者都支持的情况下，优先选择`Session Ticket`，在TLS协议会话缓存中的作用都是一样的

四次握手 -> 三次握手

![](https://img1.doubanio.com/view/photo/l/public/p2554151298.jpg)

会话缓存的优点

* 简化了握手过程，提高了建立握手的速度

* 减少握手的信息传输，节省了带宽和流量

* 减少了计算量，节省了客户端和服务器的资源

#### 如何获取SSL证书

* 在供应商处购买SSL证书

* 在供应商处获取免费的SSL证书

* 自行签发SSL证书

```
1.生成Key（服务器私钥）及CSR（Certificate Sign Request）文件
2.生成Key（CA私钥）及CA证书（公钥）
3.用CA证书给CSR文件签名生成服务器证书（公钥）
```

#### SSL证书的种类

* SSL证书的主要作用：身份认证和数据加密

* 向客户端证明服务器真实身份，此真实身份是通过第三个权威机构（CA）验证的

* 确保客户端和服务器之间的通信内容是高强度加密传输的，是不可能被非法篡改和窃取的

SSL证书的分类

* 域名验证型（DV）SSL证书（Domain Validation SSL）

* 组织验证型（OV）SSL证书（Organization Validation SSL）

* 扩展验证型（EV）SSL证书（Extended Validation SSL）


域名验证型

* 只验证网站域名所有权的简易型SSL证书

* 仅能加密通信内容，不能向用户证明网站的真实身份

* 适合无身份认证需求的网站使用

![](https://img1.doubanio.com/view/photo/l/public/p2554154669.jpg)


组织验证型

* 需要验证域名所有权和所属单位的真实身份的标准型SSL证书

* 不仅能够加密通信内容，还能向用户证明网站的真实身份

* 适合电子商务，企业等网站使用

![](https://img3.doubanio.com/view/photo/l/public/p2554155063.jpg)


扩展验证型

* 遵循全球统一的严格身份验证标准颁发的SSL证书，是目前业界最高安全级别的SSL证书

* 提供通信内容加密和网站身份证明，浏览器状态栏显示单位名称

* 适合金融证券、银行等网站使用

![](https://img1.doubanio.com/view/photo/l/public/p2554156607.jpg)


SSL证书的分类

* 单域型证书，仅支持单个域名`www.imooc.com`

* 多域型证书，支持多个域名`imooc.com`、`www.imooc.com`

* 通配型证书，支持带通配符的域名`*.imooc.com`

#### SSL证书的特点

购买的SSL证书

* 支持赔付

* 功能强大，支持通配符、多域名

免费的SSL证书

* 功能简单，仅支持单个域名

自发签名SSL证书

* 不可信

* 仅供开发，测试使用

#### 自行签发SSL证书步骤

* 手动生成服务器私钥、CSR文件

* 手动生成CA私钥和CA证书

* 利用CA证书给CSR文件签名并生成服务器证书

使用工具：OpenSSL

* OpenSSL是一个开源软件库，实现了SSL和TLS协议

* OpenSSL具有强大的加密库、密钥和证书封装管理功能


**第一步：生成服务器私钥**

```
# genrsa命令 作用：生成rsa格式的密钥
# -des3 方式加密的密钥
# -out 产出文件
# server.key 密钥名称
# 4096 加密强度
➜  Documents openssl genrsa -des3 -out server.key 4096
Generating RSA private key, 4096 bit long modulus
...............................................................................................................................................................................................++
.............................................................................................................................................................................................................++
e is 65537 (0x10001)
Enter pass phrase for server.key:
Verifying - Enter pass phrase for server.key:
```

**第二步：去除服务器私钥的密钥(可选)**

```
# rsa 密钥格式
# 输入server.key文件
# 输出server.key文件
➜  Documents openssl rsa -in server.key -out server.key
Enter pass phrase for server.key:
writing RSA key
```

**第三步：生成证书请求文件(CSR文件)**

```
➜  Documents openssl req -new -key server.key -out server.csr
You are about to be asked to enter information that will be incorporated
into your certificate request.
What you are about to enter is what is called a Distinguished Name or a DN.
There are quite a few fields but you can leave some blank
For some fields there will be a default value,
If you enter '.', the field will be left blank.
-----
Country Name (2 letter code) []:CN
State or Province Name (full name) []:zhejiang
Locality Name (eg, city) []:hangzhou
Organization Name (eg, company) []:doublex-man
Organizational Unit Name (eg, section) []:doublex-man
Common Name (eg, fully qualified host name) []:doublex-man.com
Email Address []:13777891945@163.com

Please enter the following 'extra' attributes
to be sent with your certificate request
A challenge password []:
```

**第四步：生成CA私钥**

```
➜  Documents openssl genrsa -des3 -out ca.key 4096
Generating RSA private key, 4096 bit long modulus
...................................................++
....................................++
e is 65537 (0x10001)
Enter pass phrase for ca.key:
Verifying - Enter pass phrase for ca.key:
```

**第五步：生成CA证书**

```
# -x509 证书格式
➜  ssl openssl req -new -x509 -key ca.key -out ca.crt -days 3652
Enter pass phrase for ca.key:
You are about to be asked to enter information that will be incorporated
into your certificate request.
What you are about to enter is what is called a Distinguished Name or a DN.
There are quite a few fields but you can leave some blank
For some fields there will be a default value,
If you enter '.', the field will be left blank.
-----
Country Name (2 letter code) []:CN
State or Province Name (full name) []:zhejiang
Locality Name (eg, city) []:hangzhou
Organization Name (eg, company) []:doublex-man
Organizational Unit Name (eg, section) []:doublex-man
Common Name (eg, fully qualified host name) []:doublex-man.com
Email Address []:13777891945@163.com
```

**第六步：利用CA证书给CSR签名**

```
➜  ssl ll
total 32
-rw-r--r--  1 xuxiaomeng  staff   2.0K Apr 21 17:15 ca.crt
-rw-r--r--  1 xuxiaomeng  staff   3.2K Apr 21 17:10 ca.key
-rw-r--r--  1 xuxiaomeng  staff   1.7K Apr 21 17:07 server.csr
-rw-r--r--  1 xuxiaomeng  staff   3.2K Apr 21 17:00 server.key
➜  ssl openssl x509 -req -days 365 -in server.csr -CA ca.crt -CAkey ca.key -CAcreateserial -out server.crt
Signature ok
subject=/C=CN/ST=zhejiang/L=hangzhou/O=doublex-man/OU=doublex-man/CN=doublex-man.com/emailAddress=13777891945@163.com
Getting CA Private Key
Enter pass phrase for ca.key:
➜  ssl ll
total 48
-rw-r--r--  1 xuxiaomeng  staff   2.0K Apr 21 17:15 ca.crt
-rw-r--r--  1 xuxiaomeng  staff   3.2K Apr 21 17:10 ca.key
-rw-r--r--  1 xuxiaomeng  staff    17B Apr 21 17:20 ca.srl
-rw-r--r--  1 xuxiaomeng  staff   2.0K Apr 21 17:20 server.crt
-rw-r--r--  1 xuxiaomeng  staff   1.7K Apr 21 17:07 server.csr
-rw-r--r--  1 xuxiaomeng  staff   3.2K Apr 21 17:00 server.key
```

* 文件分别是CA证书、CA密钥、CA序列号文件

* 服务器相关：服务器证书、证书请求文件、服务器密钥

#### Apache中部署HTTPS 服务

* 安装Apache服务器和mod_ssl模块

测试Apache服务器是否安装mod_ssl模块：`httpd -M | grep ssl`

安装`mod_ssl`模块

* 源码方式安装 `--enable-ssl --with-ssl=/usr/local/openssl/`

* 添加为共享模块 `--enable-ssl=shared --with-ssl=/usr/local/openssl`

* 包管理器安装（推荐）

```
CentOS/Redhat: yum install mod_ssl
Debian/Ubantu: apt-get install mod_ssl
```

启动`mod_ssl`模块

* 编译为静态模块安装的`mod_ssl`会自动启用

* 添加为共享模块的`mod_ssl`需要修改配置文件`LoadModule ssl_module modules/mod_ssl.so`

* 包管理器安装的`mod_ssl`在CentOS/Redhat会自动启用，而在Debian/Ubantu下需要执行`sudo a2enmod ssl`来启用

**主要配置内容**

* 启用SSL服务 

```
SSLEngine on
```

* 监听443端口

```
Listen 443
```

* 配置服务器证书、服务器秘钥

```
SSLCertificateFile /path/to/server.crt
SSLCertificateKeyFile /path/to/server.key
```

#### HTTPS服务的配置

额外配置（安全、性能）

* 配置安全传输协议 `SSLProtocol all -SSLv2 -SSLv3` 禁用ssl2、3版本

* 配置加密算法 `SSLCipherSuite HIGH:!aNULL:!MD5:!SEED:!IDEA`

服务生效

* 测试Apache配置文件 `httpd -t`

* 重启Apache服务 `httpd -k restart`

* 在浏览器打开`https://localhost`

#### Nginx中部署HTTPS 服务

准备

* 安装OpeSSL工具包 测试：`openssl version`

* 获取SSL证书

* 安装Nginx服务器和`http_ssl_module`模块 测试：`nginx -V`

安装`http_ssl_module`模块

* 源码方式安装 `--with-http_ssl_module`

* 包管理器安装Nginx自带`http_ssl_module`模块(推荐)

**主要配置内容**

* 启用SSL服务 

```
ssl on;
```

* 监听443端口

```
listen 443 ssl;
```
* 配置服务器证书、服务器秘钥

```
ssl_certificate /path/to/server.crt
ssl_certificate_key /path/to/server.key
```

![](https://img3.doubanio.com/view/photo/l/public/p2554274620.jpg)

测试HTTPS服务生效

* 测试Nginx配置文件 `nginx -t`

* 重新加载Nginx服务 `nginx -s reload`

* 浏览器打开：`https://localhost`

#### HTTPS性能优化

* CDN接入

HTTPS延迟主要取决于一次往返RTT的时间。

* 会话缓存机制

使用会话机制只需至少1 * RTT的延迟

* 硬件加速

为服务器安装专门的SSL硬件加速卡

* 远程加解密

#### HTTPS常用测试工具


## HTTPS Nginx配置实例

```
server {
        ssl on;

        listen 443;

        ssl_certificate www.kenrou.cn.pem;
        ssl_certificate_key www.kenrou.cn.key;
        ssl_session_timeout 5m;
        ssl_ciphers ECDHE-RSA-AES128-GCM-SHA256:ECDHE:ECDH:AES:HIGH:!NULL:!aNULL:!MD5:!ADH:!RC4;
        ssl_protocols TLSv1 TLSv1.1 TLSv1.2;
        ssl_prefer_server_ciphers on;

        server_name  www.kenrou.cn 47.100.197.26;
        set $root /var/www/think/think/public;
        root /var/www/think/think/public;

        error_log /var/log/nginx/think.error.log;
        access_log /var/log/nginx/think.access.log;

        #此配置用于静态文件配置
        #location /static {
         #      try_files $uri $uri/ =404;
        #}

    location / {
        #开启目录浏览功能
        autoindex on;
        #关闭详细文件大小统计，让文件大小显示MB，GB单位，默认为b
        #autoindex_exact_size on;
        #开启以服务器本地时区显示文件修改日期
        #autoindex_localtime on;
        if ( !-e $request_filename) {
            rewrite ^/(.*)$ /index.php/$1 last;
            break;
        }
    }

    #配置PHP的pathinfo
    location ~ .+\.php($|/) {
        fastcgi_pass 127.0.0.1:9000;
        fastcgi_index index.php;
        fastcgi_split_path_info ^((?U).+.php)(/?.+)$;
        fastcgi_param HTTPS on;
        fastcgi_param PATH_INFO $fastcgi_path_info;
        fastcgi_param PATH_TRANSLATED $document_root$fastcgi_path_info;
        fastcgi_param SCRIPT_FILENAME $root$fastcgi_script_name;
        include fastcgi_params;
    }
    
     location ~ .*\.(jpg|jpeg|gif|png|ico|swf)$  {
        expires 3y;
        gzip off;
    }
}
```





