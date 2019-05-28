## Zabbix监控本机相关配置

官网下载：

[zabbix下载地址](https://www.zabbix.com/download)

[zabbix4.0官方文档](https://www.zabbix.com/documentation/4.0/manual/config/items/itemtypes/zabbix_agent)

钉钉脚本`dingding.py`

日志处理脚本`login.sh`:`*/1 * * * * /Users/xuxiaomeng/Desktop/login.sh`

主体结构分为服务端、客户端和监控端。

* `zabbix_server`：通过收集`agent`发送的数据，写入数据库（`MySQL`，`ORACLE`等），再通过`php+nginx`在`web`前端展示

* `zabbix/frontends/php/`：客户端代码，需要放到服务器目录中运行

* `zabbix_agent`：主机通过安装`agent`方式采集数据。

1.创建`zabbix`数据库，并导入表结构

```
# mysql -uroot -p
mysql> create database if not exists zabbix default character set utf8 collate utf8_general_ci;
mysql> use zabbix;
mysql> source /tmp/zabbix-3.4.2/database/mysql/schema.sql;
mysql> source /tmp/zabbix-3.4.2/database/mysql/images.sql;
mysql> source /tmp/zabbix-3.4.2/database/mysql/data.sql;
```

2.创建日志目录

```
cd /usr/local/zabbix
# mkdir logs
# chown zabbix:zabbix logs
```

3.修改`zabbix_server.conf`

```
# vim ./etc/zabbix_server.conf

LogFile=/usr/local/zabbix/logs/zabbix_server.log
PidFile=/tmp/zabbix_server.pid
DBHost=localhost
DBName=zabbix
DBUser=zabbix
DBPassword=zabbix
DBSocket=/tmp/mysql.sock
Include=/usr/local/zabbix/etc/zabbix_server.conf.d/*.conf
```

启动`zabbix_server`和`zabiix_agent`服务

```
# /usr/local/zabbix/sbin/zabbix_server
# /usr/local/zabbix/sbin/zabbix_agent
```

#### 配置网卡流量监控

* 流量流出监控项配置

![](https://img1.doubanio.com/view/photo/l/public/p2557952529.jpg)

* 流量流入监控项配置

![](https://img3.doubanio.com/view/photo/l/public/p2557952533.jpg)

* 流量监控速率配置

![](https://img3.doubanio.com/view/photo/l/public/p2557952676.jpg)

* 流量流出触发器配置

![](https://img3.doubanio.com/view/photo/l/public/p2557952772.jpg)


* 流量流入触发器配置

![](https://img1.doubanio.com/view/photo/l/public/p2557952777.jpg)


#### 内存监控

* 内存使用率监控项配置

![](https://img3.doubanio.com/view/photo/l/public/p2557952914.jpg)

* 内存使用率触发器配置

![](https://img3.doubanio.com/view/photo/l/public/p2557952910.jpg)


#### cpu

* cpu使用率监控项配置

![](https://img1.doubanio.com/view/photo/l/public/p2557953069.jpg)

* cpu使用率触发器配置

![](https://img1.doubanio.com/view/photo/l/public/p2557953058.jpg)

#### 日志监控

* 日志监控项配置

![](https://img1.doubanio.com/view/photo/l/public/p2557953168.jpg)

* 日志监控触发器配置

![](https://img3.doubanio.com/view/photo/l/public/p2557953170.jpg)

#### 端口模板创建 

* 端口模板配置一

![](https://img3.doubanio.com/view/photo/l/public/p2557953300.jpg)

* 端口模板配置二

![](https://img3.doubanio.com/view/photo/l/public/p2557953302.jpg)


* 端口模板监控项配置

![](https://img3.doubanio.com/view/photo/l/public/p2557953370.jpg)

* 端口模板触发器配置

![](https://img3.doubanio.com/view/photo/l/public/p2557953363.jpg)


#### 端口`22`监控

* 端口`22`监控项配置

![](https://img3.doubanio.com/view/photo/l/public/p2557953533.jpg)

* 端口`22`触发器配置

![](https://img1.doubanio.com/view/photo/l/public/p2557953538.jpg)

#### 动作相关配置

* 邮件报警媒介配置

![](https://img3.doubanio.com/view/photo/l/public/p2557953614.jpg)

* 钉钉脚本报警媒介配置

![](https://img3.doubanio.com/view/photo/l/public/p2557953615.jpg)

* 用户添加报警媒介

![](https://img3.doubanio.com/view/photo/l/public/p2557953713.jpg)

* 动作配置一

![](https://img3.doubanio.com/view/photo/l/public/p2557953772.jpg)

* 动作配置二

![](https://img3.doubanio.com/view/photo/l/public/p2557953774.jpg)
