## MMM架构实例演示一

#### MMM部署步骤

* 配置主主复制及主从同步集群

* 安装主从节点所需要的支持包

* 安装及配置MMM工具集

* 运行MMM监控进程

* 测试

---

![](https://img3.doubanio.com/view/photo/l/public/p2541738603.jpg)

前面配置和主从复制配置一样(这里配置的是基于日志点的复制)

在从库上查看日志点信息

`show master status \G` 

然后再主上指定复制链路日志点和偏移量通过 

`change master`命令

两台服务器就互为主从的关系了(原来这就是主主复制)。

---

## MMM架构实例演示二

首先在三台服务器上配置yum源

* `wget http://mirrors.opencas.cn/epel/epel-release-latest-6.noarch.rpm`

* `wget http://rpms.famillecollet.com/enterprise/remi-release-6.rpm`

安装

* `rpm -ivh epel-release-latest-6.noarch.rpm`
* `rpm -ivh remi-release-6.rpm`

修改配置

* `vim /etc/yum.repos.d/remi.repo`
* `# [remi]`
* `enabled = 1` 将enabled参数修改为1
* `vim /etc/yum.repos.d/epel.repo`
* `# [epel]`
* 将`baseurl`注释去掉
* 将`mirrorlist`加上注释

以上完成了yum的配置。

---

安装监控服务 (需要在每一个DB服务器上安装)

* `yum search mmm` 查看支持的mmm包
* `yum install mysql-mmm-agent.noarch -y` 安装代理需要在每一个DB服务器上安装
* `yum -y install mysql-mmm*` 在第三台从库服务器上安装监控服务

---

配置添加账号

首先在主服务器DB上建立几个服务账号(会同步到其他服务器上)

* 监控账号 (检查数据库服务器的健康状况，是replcation client权限)
* `grant replication clinet on *.* to 'mmm_monitor'@'IP' identfied by '密码';`


* 代理账号 (改变read_only的模式，改变从服务器的主，它的主要作用就是故障转移和主从切换，需要的权限比较大super,replication client,process)
* `grant super,replication client,process on *.* to 'mmm_agent'@'IP' identified by '密码';`

* 复制账号 (在建立集群的时候已经创建)

---

修改配置文件

会建立`/etc/mysql-mmm/`这样一个目录(这个目录中有两个示例的配置文件`mmm_agent.conf` 和 `mmm_common.conf`) [`mmm_common.conf` 在每台服务器上保持一致,代理服务器上编辑`mmm_agent.conf`这个文件]

* `vim mmm_common.conf` 修改其中的复制账号密码代理账号密码
* `<host default>`
* `replication_user repl`
* `replication_password 123456`
* `agent_user mmm_agent`
* `agent_password 123456`
* `<host db1>`
* `ip ` db1的ip地址(主)
* `mode master`
* `peer db2`
* `<host db2>`
* `ip ` db2的ip地址(主)
* `mode master`
* `peer db1`
* `<host db3>`
* `ip ` db3的ip地址(从)
* `mode slave`
* `<role writer>`
* `hosts db1,db2`
* `ips 192.168.3.90` 写的虚拟IP
* `mode exclusive`
* `<role reader>`
* `hosts db1,db2,db3`
* `ips 192.168.3.91,192.168.3.92,192.168.3.93` 读的虚拟IP
* `mode balanced`

* 通过`scp`命令将配置文件拷贝到其他两台服务器上
* `scp mmm_common.conf root@ip:/etc/mysql-mmm/`

* 编辑`mmm_agent.conf` 每台服务器上指定自己的db名称
* `vim mmm_agent.conf`
* `db1` 第一台服务器
* `db2` 第二台服务器
* `db3` 第三台服务器

以上完成数据库节点的配置。

---

接下里配置监控节点 (这里我们监控节点在第三台服务器上)

监控节点的`/etc/mysql-mmm/`文件夹下示例配置文件会比其他两台服务器多

* 这里我们编辑监控配置
* `vim mmm_mon.conf`
* `<monitor>`
* `ping_ips 192.168.3.100,192.168.3.101,192.168.3.102` 额外检查需要的新ip的地址
* `<host default>` 监控用户账号密码
* `monitor_user mmm_monitor`
* `monitor_password 123456`

以上完成mmm配置过程。

---

接下来启动mmm集群

启动三个节点上的mmm代理 (启动监控之前必须先启动代理)

* `/etc/init.d/` 使用`yum`安装完成，文件夹下面就有相应的启动文件
* `mysql-mmm-agent` 启动代理
* `mysql-mmm-monitor` 启动监控
* `/etc/init.d/mysql-mmm-agent start` 启动命令(三台服务器上都执行)

在监控节点上，启动监控服务

* `/etc/init.d/mysql-mmm-monitor start` 启动命令(监控服务器上执行，这里是第三台数据库服务器)

在监控服务器上，通过`mmm_control show`命令来查看集群状态

* `mmm_control show`
* `...master/ONLINE. reader(192.168.3.93), writer(192.168.3.90)` 还显示包括读的IP地址和写的IP地址
* `...master/ONLINE. reader(192.168.3.91)`
* `...slave/ONLINE. reader(192.168.3.92)`

通过`ip addr`命令，来查看这些读写的IP是否正确的被配置到服务器上 (成功显示，表示虚拟IP被正确配置)

---

接下来测试故障转移和主从切换的测试

将集群中的数据库节点一关闭，查看会有什么样的结果

* `/etc/init.d/mysqld stop` 关闭mysql服务

* 通过`mmm_control show`命令来再次查看集群状态

* `mmm_control show`
* `...master/OFFLINE.` 还显示包括读的IP地址和写的IP地址
* `...master/ONLINE. reader(192.168.3.91), writer(192.168.3.90)`
* `...slave/ONLINE. reader(192.168.3.92), reader(192.168.3.92)`

以上表示主库的写操作正确转移到DB2上。

---

确定DB3的主从复制关系，是否是和DB2做的 (当时配置DB3的从是作为DB1做的)

通过`show slave status \G`查看

* `mysql> show slave status \G`
* `************ 1. row *********`
* `MASTER_HOST: 192.168.3.101` 已经成功转移为DB2的主从复制






