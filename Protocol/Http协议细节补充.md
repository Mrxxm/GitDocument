## Http协议细节补充

#### IP地址

#### 域名解析

* `/private`是系统文件夹,非用户文件夹,root级别的。里面的文件一般是系统睡眠、挂起时候的内存镜像

#### 三次握手

类比打电话

1.A->B 你能听到我说话吗？
2.B->A 你能听到我说话吗？
3.A->B 也能，那我说啦！


![](https://img3.doubanio.com/view/photo/l/public/p2535800372.jpg)

![](https://img3.doubanio.com/view/photo/l/public/p2553205420.jpg)

```
1.客户端状态为SYN_SEND -- 客户端发送同步信号SYN -- 请求包括序列号seq=x 

2.服务器状态由listen->SYN_REVD -- 服务端返回syn + ack组成的信号syn字段序列号seq=y，ack序号x+1、ack=x+1

3.客户端状态由SYN_SEND->ESTABLISHED -- 客户端返回ack的信号,ack=y+1 -- 服务端接收到后状态也变为ESTABLISHED
```

#### 三次握手抓包试验

终端使用tcpdump命令，浏览器访问百度IP抓取请求包

ping百度得到ip地址

```
➜  / ping www.baidu.com
PING www.a.shifen.com (115.239.210.27): 56 data bytes
64 bytes from 115.239.210.27: icmp_seq=0 ttl=55 time=4.394 ms
64 bytes from 115.239.210.27: icmp_seq=1 ttl=55 time=5.103 ms
64 bytes from 115.239.210.27: icmp_seq=2 ttl=55 time=4.614 ms
^C
--- www.a.shifen.com ping statistics ---
3 packets transmitted, 3 packets received, 0.0% packet loss
round-trip min/avg/max/stddev = 4.394/4.704/5.103/0.296 ms
```

使用tcpdump命令抓取4个包 -c 表示抓几个包 -v 表示显示包详细信息(本地发送两个标识为[S]的包)

```
➜  / sudo tcpdump -c 4 -v host 115.239.210.27
Password:
tcpdump: data link type PKTAP
tcpdump: listening on pktap, link-type PKTAP (Apple DLT_PKTAP), capture size 262144 bytes
14:56:02.201782 IP (tos 0x0, ttl 64, id 0, offset 0, flags [none], proto TCP (6), length 64)
# 第一个包 
# 我们发送给百度的tcp包
# [S] 代表SYN seq 代表序列号 seq = x
   192.168.2.121.55557 > 115.239.211.112.http: Flags [S], cksum 0x059c (correct), seq 3678673562, win 65535, options [mss 1460,nop,wscale 5,nop,nop,TS val 555759243 ecr 0,sackOK,eol], length 0
15:08:16.702276 IP (tos 0x0, ttl 64, id 0, offset 0, flags [none], proto TCP (6), length 64)

# 第二个包
# 百度发给我们的tcp包
# [S.] 代表 syn + ack 包含 ack = seq + 1
    115.239.211.112.http > 192.168.2.121.55557: Flags [S.], cksum 0x1fbf (correct), seq 1563673456, ack 3678673563, win 8192, options [mss 1420,nop,wscale 5,nop,nop,nop,nop,nop,nop,nop,nop,nop,nop,nop,nop,sackOK,eol], length 0
15:08:16.705677 IP (tos 0x0, ttl 64, id 0, offset 0, flags [none], proto TCP (6), length 40)

# 第三个包
# 我们发送给百度
# 表示 [.] 只有ack = 1
    192.168.2.121.55557 > 115.239.211.112.http: Flags [.], cksum 0x9578 (correct), ack 1, win 8192, length 0
```

#### 请求&响应

![](https://img3.doubanio.com/view/photo/l/public/p2553205422.jpg)

1.浏览器发送请求数据包后

2.服务器不单单只返回响应数据包

3.当服务器在准备数据时，需要较长时间。为了防止客户端认为请求数据包丢失的情况，服务器会在响应数据包之前，返回一个ack的包给客户端

抓取浏览器请求数据包

```
# 第4个请求包 
# 我们请求百度
# 标识为 [P.] seq = 1:453
192.168.2.121.55557 > 115.239.211.112.http: Flags [P.], cksum 0xd1d4 (correct), seq 1:453, ack 1, win 8192, length 452: HTTP, length: 452
	GET / HTTP/1.1
	Host: 115.239.211.112
	Connection: keep-alive
	Upgrade-Insecure-Requests: 1
	User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_13_0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/73.0.3683.86 Safari/537.36
	Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3
	Accept-Encoding: gzip, deflate
	Accept-Language: zh-CN,zh;q=0.9,en-US;q=0.8,en;q=0.7,ja;q=0.6
```

百度发送响应数据包之前的ack数据包

```
# 第五个ack包
# 百度发送给我们
# 标识 [.] 这里的ack值 等于 请求数据包中ack值 ack = 453
115.239.211.112.http > 192.168.2.121.55557: Flags [.], cksum 0xb088 (correct), ack 453, win 812, length 0
```

百度返回的响应数据包

```
# 第六个响应数据包
# 百度发送给我们
# 标识 [.] 视频中标识为 [p.] ack = 453
115.239.211.112.http > 192.168.2.121.55557: Flags [.], cksum 0xa05d (correct), seq 1:1421, ack 453, win 812, length 1420: HTTP, length: 1420
	HTTP/1.1 200 OK
	Bdpagetype: 1
	Bdqid: 0xe8b42366001a340f
	Cache-Control: private
	Connection: Keep-Alive
	Content-Encoding: gzip
	Content-Type: text/html
	Cxy_all: baidu+e3e565dc02d15c48aa10cc27082a31b3
	Date: Wed, 10 Apr 2019 07:08:16 GMT
	Expires: Wed, 10 Apr 2019 07:07:30 GMT
	P3p: CP=" OTI DSP COR IVA OUR IND COM "
	Server: BWS/1.1
	Set-Cookie: BAIDUID=14F0AA91ABD148E448C66DDFCCD593F2:FG=1; expires=Thu, 31-Dec-37 23:55:55 GMT; max-age=2147483647; path=/; domain=.baidu.com
	Set-Cookie: BIDUPSID=14F0AA91ABD148E448C66DDFCCD593F2; expires=Thu, 31-Dec-37 23:55:55 GMT; max-age=2147483647; path=/; domain=.baidu.com
	Set-Cookie: PSTM=1554880096; expires=Thu, 31-Dec-37 23:55:55 GMT; max-age=2147483647; path=/; domain=.baidu.com
	Set-Cookie: delPer=0; path=/; domain=.baidu.com
	Set-Cookie: BDSVRTM=0; path=/
	Set-Cookie: BD_HOME=0; path=/
	Set-Cookie: H_PS_PSSID=1420_21097_28767_28724_28557_28836_28584_28639_28603_28627_28606; path=/; domain=.baidu.com
	Strict-Transport-Security: max-age=0
	Vary: Accept-Encoding
	X-Ua-Compatible: IE=Edge,chrome=1
	Transfer-Encoding: chunked

	bba
```

#### 四次握手

四次握手发起者可以是客户端也可以是服务器

![](https://img1.doubanio.com/view/photo/l/public/p2553266108.jpg)

* 主机1向主机2发送一个fin的数据库包

* 主机2接收到后，向主机1发送ack数据包，表示确认收到

* 主机2完成手头任务后，向主机1再发送fin数据包，表示可以断开连接

* 主机1再向主机2发送ack数据包，表示确认收到fin数据包，可以断开

1.问题：服务器如何知道响应是否丢失了？

前提：客户端对服务器发送请求数据，服务器返回一个ack包，还有一个响应数据包。

那么服务器如何感知，自己响应数据包是丢失的？

响应的细节描述：响应会分为响应片段发送，每响应一个片段，客户端都会发送一个ack数据包，返回给服务器。

![](https://img1.doubanio.com/view/photo/l/public/p2553266499.jpg)

2.问题：这里设定主机1为客户端，主机2为服务端。如果由客户端返回响应的ack数据包，返回时间较晚，落在服务器准备四次挥手中，发送了ack数据包之后，未发送fin数据包之前。那么该如何解决。

![](https://img3.doubanio.com/view/photo/l/public/p2553266653.jpg)

服务器在发送了ack数据包之后，未发送fin数据包之前，这段时间里，对接收到的ack数据包，进行判断，判断是否要补充响应数据包。

3.问题：根据上图主机1怎么知道最后的ack是否发送成功？

主机1发送完，ack数据包之后30秒 ~ 2分钟的等待时间。主机2未接收到，主机1最后发送的ack数据包，会认为自己发送的fin数据包丢失了，则重新发送fin数据包。

主机1，在30秒 ~ 2分钟的等待中，就可以判断是否收到新的fin数据包。如果收到则表示主机2未接收到最后主机1发送的ack数据包。如果未收到则表示主机2接收到了主机1最后发送的ack数据包。

#### 抓取数据包

通过工具过滤百度IP请求

命令行访问：curl 百度IP --no-keepalive

#### 请求结构

请求行和请求头的结尾都包含回车符和换行符，服务器就是通过回车符和换行符来区分请求中的不同种数据。

* 请求行

* 请求头

* 空行 (回车符和换行符)

* 请求数据

![](https://img3.doubanio.com/view/photo/l/public/p2535799780.jpg)


#### 请求行

每部分之间用一个空格隔开。

* 请求方法(GET/POST/PUT[更新]/DELETE/HEAD[返回响应头，爬虫使用]/CONNECT[用于代理]/OPTIONS[跨域]/TRACE[调试])

* URL

* 协议版本

HTTP0.9(GET) -> HTTP1.0(GET/POST) -> HTTP1.1(八种) -> HTTP2.0

#### 请求头-内容协商

文档，搜索`request-header`

* `https://devdocs.io/http/rfc2616`

请求头分类

* 内容协商

* 缓存控制

* 其他常用头

什么是内容协商

浏览器请求服务器，比如协商响应是中文版本还是英文版本。

* Accept

* Accept-Charset

* Accept-Language

* Accept-Encoding

![](https://img1.doubanio.com/view/photo/l/public/p2553271849.jpg)

这些头部信息都是浏览器告诉服务器，自己能支持的配置。

* Accept中同种类型q值越高代表浏览器支持越好，优先返回q值更高的，有些类型后面没有q值，默认为1，那么会优先考虑没有q值的，这里优先返回`text/html`格式

* Accept-Charset `gb2312,utf-8;q=0.7,*;q=0.3` 如果前面字符集服务器都没有，那么考虑`*`匹配其他任意字符集

* Accept-Language：这里优先返回`zh-CN`代表发回中文版信息

* Accept-Encoding：服务器先压缩数据，再传输，可以加快传输速率，主流有压缩算法gizp，deflate，达到70%及以上压缩算法

1.问题：浏览器如何辨别服务器返回数据时，使用的哪种压缩算法？

#### 请求头-缓存控制

什么是缓存控制

```
浏览器 <-> 代理服务器 <-> 服务器 
```

这一过程相对来说较慢,就出现以下

![](https://img3.doubanio.com/view/photo/l/public/p2553273532.jpg)

为了保证缓存中的数据是和服务器上的一致，且是最新的，这里制定了一系列的规则。我们称为**缓存控制**。

头部信息：

* If-None-Match

![](https://img3.doubanio.com/view/photo/l/public/p2553276923.jpg)

浏览器发送请求，会去缓存中查找摘要信息发送给服务器，服务器根据请求，返回响应，提取加密响应部分为摘要信息，进行对比。服务器返回304，表示摘要信息一致，则表示信息是最新的。
如果摘要信息不一致，服务器则发送最新的响应+摘要信息。

* If-Modifyed-Since

![](https://img3.doubanio.com/view/photo/l/public/p2553277380.jpg)

过程和上面类似，将摘要信息替换成最新时间。

* If-Match

![](https://img3.doubanio.com/view/photo/l/public/p2553277684.jpg)

区别，也是发送响应数据和摘要信息，这里类比响应数据为文章，但是浏览器会对响应数据进行编辑和修改，编辑完后需要返回服务器进行保存，保存过程带上摘要信息和最新编辑过的响应数据，但是保存的时候，服务器也会取出原来的文章和摘要信息，然后对摘要信息对比。对比主要是为了防止他人对文章也做了修改，防止保存将他人修改覆盖，防止冲突。

* If-Unmodifyed-Since

![](https://img3.doubanio.com/view/photo/l/public/p2553278361.jpg)

和`If-Match`操作类似，修改时间标识的是服务器文章的修改时间。

* If-Range

断点续传

![](https://img3.doubanio.com/view/photo/l/public/p2553278545.jpg)

1.问题：浏览器怎么知道什么数据该缓存？

#### 请求头-其他常用头

* User-Agent (浏览器信息)

* Referer (记录跳转之前的地址)

* Expect (post提交)

* Host (记录主机地址)

#### 请求体

* application/json (json格式)

* text/plain (文本格式)

* application/x-www-form-urlencoded (编码之后数据格式)

* multipart/form-data (表单格式)

#### 响应结构

![](https://img3.doubanio.com/view/photo/l/public/p2553287440.jpg)

状态码

* 1xx：表示请求已接收，需要后续处理

![](https://img1.doubanio.com/view/photo/l/public/p2553288077.jpg)

浏览器就直接发送post请求，不需要发送expect请求

* 2xx：表示请求已经成功处理

`200 OK`

`204 NO Content` 客户端表单提交后，服务器返回，则客户端页面不发生跳转

`206 Partial Content` 用于包装断点续传后的请求数据。

* 3xx：通常用于重定向

`301 Moved Permanently` 客户端请求服务器，服务器资源转移到另一台服务器中，第一台服务器会返回的状态码

`302 Moved temporarily` 表示服务器资源短暂迁移

`304 Not Modified` 客户端请求数据时中的摘要信息和服务器中的摘要信息相同，不返回数据，返回304状态码

* 4xx：表示客户端发生错误

`401 Unauthorized` 请求中未携带认证信息

`403 Forbidden` 

`404 Not Found` 

* 5xx：表示服务器发生错误

`500 Internal Server Error` 内部代码错误

`502 Bad Gateway` 代理服务器无法识别请求

`504 Gateway Timeout` 代理服务器访问资源服务器未返回响应给代理服务器

#### 响应头

常用的八种

* Content-Encoding

`Content-Encoding:gzip` 

前面**请求头-内容协商**章节最后问题，由该字段标识服务器使用的压缩算法。

* Content-Language

`Content-Language:zh`

由该字段标识服务器返回资源的语言。

* Content-Type

`Content-Type:text/xml`

对应**请求头-内容协商**里面的`Accept`。

* Last-Modify

告诉浏览器响应数据最新的修改时间，对应**请求头-缓存控制**的`If-Modifyed-Since`。

* Content-Range

`Content-Range: bytes 200-299/403`

用于断点续传。

* Expires

指定浏览器缓存响应的时间设置，时间格式GMT。

* Content-Length

服务器返回给浏览器的数据大小。还有Chunk模式，分块发送响应数据，该参数只代表块的大小。

* Location

重定向。对应状态码301，302，代理服务器中设置Location重定向到资源服务器。


#### 响应体

#### 请求&响应中的那些头

终极目标：高效

1.浏览器每次请求服务器都需要经历三次握手和四次挥手。

为了减少这种开销，我们在浏览器请求头部封装`Connection: Keep-Alive`，告诉服务器这次请求要保持长连接。

2.通过压缩，gzip，delfate加快传输速率。请求头中`Accept-Encoding`,响应头中的`Content-Encoding`

3.缓存的使用，从缓存中拿数据，加快数据传输。响应头`Cache-Control: public`告诉浏览器缓存我的响应数据，`Last-Modifyed: `告诉浏览器响应数据的最新修改时间。浏览器发送请求把`Last-Modifyed`放到`If-Modifyed-Since`,发送请求。

![](https://img3.doubanio.com/view/photo/l/public/p2553293590.jpg)

4.接着第三点如果某人将服务器数据修改，修改后，修改时间已经更新，但是某人后悔，又将数据改回修改前。这时为了防止重新发送响应数据。响应头`Cache-Control: public`告诉浏览器缓存我的响应数据，`ETag`存放md5等加密过的摘要信息。浏览器发送请求把摘要信息放在`If-None-Match`中，发送请求。

![](https://img3.doubanio.com/view/photo/l/public/p2553293506.jpg)

5.断点续传。

![](https://img3.doubanio.com/view/photo/l/public/p2553293441.jpg)
