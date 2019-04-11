## Http2.0

#### 

Http2.0协议前生SPDY协议。

```
SPDY（读作“SPeeDY”）是Google开发的基于TCP的会话层 [1]  协议，
用以最小化网络延迟，提升网络速度，优化用户的网络使用体验。SPDY并不
是一种用于替代HTTP的协议，而是对HTTP协议的增强。新协议的功能包括数
据流的多路复用、请求优先级以及HTTP报头压缩。谷歌表示，引入SPDY协议
后，在实验室测试中页面加载速度比原先快64%。
```

提升网页加载速率的四大点：

* 二进制传输，消息的解析效率更高(传输也更加安全)

* 头部数据压缩，传输效率更高(http1.1是对数据体进行压缩)

* 多路复用，可以让请求并发执行(http1.1有些请求是串行执行的)

* 服务器主动推送数据到浏览器

`http1.1` 与 `http2.0` 加载速度demo

* `https://http2.akamai.com/demo`

#### 二进制分帧

![](https://img1.doubanio.com/view/photo/l/public/p2553296969.jpg)

帧的结构

![](https://img3.doubanio.com/view/photo/l/public/p2553297483.jpg)

* `Length` 单位`byte`比特，字节。`bit`，位。定义`Frame Payload` 的大小

* `Type` 定义第一张图中，是`HEADERS`类型还是`DATA`类型

* `Flags`

* `Stream Identifier` 存放标识的流ID，对应了哪次请求的数据

http2.0网站

* `http://nghttp2.org/`

通过抓包软件，抓取网站IP地址，数据包标识`Application Data`。

#### 头部压缩

* HPACK压缩

* 静态表

* Huffman编码

第一次请求

![](https://img1.doubanio.com/view/photo/l/public/p2553300269.jpg)


#### 多路复用

* 传统模式

![](https://img1.doubanio.com/view/photo/l/public/p2553302109.jpg)

keep-alive解决了共用三次握手和四次挥手的过程。但是每个请求数据包都是串行执行的，下一个数据包，必须等到上一个数据包接收服务器响应之后才能发送。

* `pipelining` 模式

![](https://img1.doubanio.com/view/photo/l/public/p2553302379.jpg)

该模式解决了发送的问题，但是浏览器接收服务器的响应请求时，必须严格按照发送请求的顺序，其实还是隐藏串行执行的意思在里面。

* `http 2.0`

客户端发送请求，为每个请求打上流ID。接收响应数据帧时，浏览器会对其根据ID重新组装。请求响应的数据帧是没有顺序的。这样彻底解决了串行执行请求的概念。其中`Stream Identifier` 存放标识的流ID，对应了哪次请求的数据。

还可以设置响应的优先级。

#### 服务器推送

服务器解析html文件，把其中需要加载的css、图片、js直接推给浏览器。

