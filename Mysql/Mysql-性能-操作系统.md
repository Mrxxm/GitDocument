## 性能-操作系统

#### MySQL适合的操作系统

* Windows(大小写不敏感，可以通过mysql配置强制使表名和库名小写)
* FreeBSD(老版本支持不好)
* Solaris(稳定性著称，应用性不如Liunx)
* Linux

#### CentOS系统参数优化

#### 内核相关参数(/etc/sysctl.conf)

网络性能相关参数：

---

* net.core.somaxconn = 65535 [监听状态的端口，每个端口都具有监听队列，这个配置决定每个端口监听队列的最大长度]
* net.core.netdev_max_backlog = 65535 [每个网络接口接收数据包的速率比内核处理包的速率快的时候，允许被发送到队列中的数据包最大的数目]
* net.ipv4.tcp_max_syn_backlog = 65535

---
加快tcp回收的连接时间

* net.ipv4.tcp_fin_timeout = 10 [tcp等待状态的时间]
* net.ipv4.tcp_tw_reuse = 1 
* net.ipv4.tcp_tw_recycle = 1 

---
tcp连接接收和发送缓冲区大小的默认值和最大值(应该分别调大)

* net.core.wmem_default = 87830 
* net.core.wmem_max = 16777216
* net.core.rmem_default = 87380
* net.core.rmem_max = 16777216

---
用于减少失效连接占用的tcp系统资源的数量(应该分别改小)

* net.ipv4.tcp_keepalive_time = 120 [tcp发送keepalive探测消息的间隔，单位为秒]
* net.ipv4.tcp_keepalive_intvl = 30 [控制keepalive消息未获得响应时，重发消息的时间间隔，单位为秒]
* net.ipv4.tcp_keepalive_probes = 3 [tcp失效之前，允许发送多少个keepalive消息]


#### 内存相关参数(/etc/sysctl.conf)

---

* kernel.shmmax = 4294967295 [Liunx内核中最重要的参数之一，用于定义单个共享内存段的最大值]

注意： 

* 这个参数应该设置的足够大，以便能在一个共享内存段下容纳下整个Innodb缓冲池的大小
* 这个值的大小对于64位Liunx系统，可取的最大值位物理内存值-1byte,建议值为大于物理内存的一半，一般取得大于Innodb缓冲池的大小即可，可以取物理内存-1byte

---

* vm.swappiness = 0 (告诉Linux内核除非虚拟内存完全满了，否则不要使用虚拟内存分区) [这个参数当内存不足时会对性能产生比较明显的影响] 

Linux内存交换区：

* Linux系统安装时都会有一个特殊的磁盘分区，称之为系统交换分区
* 如果我们使用free-m在系统中查看可以看到类似下面内容其中swap就是交换分区
* 当操作系统没有足够的内存时就会将一些虚拟内存写到磁盘的交换区这样就会发生内存交换

结论：

* 在Mysql服务器上保留交换区还是必要的，但是要控制何时使用交换分区



#### 增加资源限制(/etc/security/limit.conf)

* 这个文件实际上是Linx PAM也就是插入式认证模块的配置文件
* 打开文件数的限制

---

打开文件数的限制

* `* soft nofile 65535`
* `* hard nofile 65535`

```
 *      表示对所有用户有效
 soft   表示当前系统生效的设置
 hard   表明系统中所能设定的最大值
 nofile 表示所限制的资源是打开文件的最大数量
 65535  就是限制数量
 
```

结论： 

* 把可打开的文件数量增加到了65535个以保证可以打开足够多的文件句柄

注意：

* 这个文件的修改需要重启系统才可生效


#### 磁盘调度策略(/sys/block/devname/queue/scheduler)

* cat /sys/block/sda/queue/scheduler [查看磁盘使用的调度策略，默认使用cfq策略]
* noop anticipatory deadline [cfq]

cfq策略(安全公平队列策略)[适用桌面系统]

* 在桌面级的系统是没有问题的
* 但是用于mysql数据库不太合适(在mysql的工作负载下，会在队列中插入一些不必要的请求，导致很差的响应时间)

noop(电梯调度策略)[适用写入较多的环境]

* Noop实现了一个FIFO队列，它像电梯的工作方法一样对I/O请求进行组织，当有一个新的请求到来时，它将请求合并到最近的请求之后，以此来保证请求同一介质。NOOP倾向饿死读而利于写，因此NOOP对于闪存设备、RAM及嵌入式系统是最好选择

deadline(截止时间调度策略)

* Deadline确保了在一个截止时间内服务请求，这个截止时间是可调整的，而默认读期限短于写期限。这样就防止了写操作因为不能读取而饿死的现象，Deadline对数据库类应用是最好选择

anticipatory(预料I/O调度策略)[适用写入比较多的环境]

* 本质上与Deadline一样，但是最后一次读操作后，要等待6ms，才能继续进行对其他I/O请求进行调度。它会在每个6ms中插入新的I/O操作，而会将一些小写入流合并成一个大写入流，用写入延迟换取最大的写入吞吐量。AS适合于写入较多的环境，比如文件服务器，AS对数据库环境表现很差

修改磁盘调度策略

```
echo <schedulername > /sys/block/devname/queue/scheduler
例 echo deadline > /sys/block/devname/queue/scheduler
```

#### 文件系统对性能的影响

Windows

* FAT
* NTFS(服务器选择的文件系统)

Linux

都具有日志功能，对数据的安全性非常重要

* EXT3 
* EXT4
* XFS(江湖传说这个文件系统性能最高)

EXT3/4注意点

系统的挂载参数(/etc/fstab)

* data参数 = writeback | ordered | journal [对应不同的日志策略]
* writeback 只有源数据写入到日志 源数据写入和数据写入并不是同步的，这是最快的一种配置,因为InnoDB有自己的事务日志，对于Innodb来说这通常是最好的选择
* ordered 只会记录源数据，但提供了一些一致性的保证，再写源数据之前，会先写数据使他们保持一致，这个慢一些但出现崩溃会更加安全
* journal 提供原子日志的一种行为，在数据写入到最终日志之前，将记录到日志中，这个选项对Innodb来说是没有必要的，最慢的一种

noatime, nodiratime

* 用户禁止记录文件的访问时间和读取目录的时间，禁用了这两个选项可以减少一些写的操作

在fstab中完整的配置

```
/dev/sda1/ext4 noatime,nodiratime,data=writeback 1 1 
```