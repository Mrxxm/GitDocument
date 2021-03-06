## 案例

#### 数据库架构： 一台主服务器(master),15台从服务器(Slave)

存在的问题：

* 没有主从复制组件(一旦master宕机，需要手动将一台slave提升为master，并将其他slave对新master进行同步，比较耗时)

* 多台slave在业务量大的情况下，对master网卡容量也是一种挑战

#### 监控信息:

* QPS：每秒钟处理的查询量(峰值：每秒35万次)

* TPS: 每秒处理的消息数(峰值：每秒5~10万次)

* 并发量：同一时间处理的请求的数量

* 同时连接数：同一时间的连接数(峰值：上千)[连接量一般大于并发量，其中只有一小部分被处理，大部分处于sleep状态]

* idle：空闲的百分比值越高空闲率越高

* 磁盘IO：读写[不要在主库上进行数据备份，大型活动前取消耗性能的计划]

#### 影响数据库的因素

* sql查询数据

* 服务器硬件

* 网卡流量

* 磁盘IO

#### 超高的QPS和TPS

风险：

* 效率低下的SQL

Mysql还不支持多cpu并发运算(每一个sql只能用到一个cpu)

```
10 ms 处理 1个sql
1 s  处理 100个sql
QPS <= 100
```

```
100 ms 处理 1个sql
QPS <= 10
```

#### 大量的并发和超高的CPU使用率

风险：

* 大量的并发：数据库连接被占满

* 超高的CPU使用率：因CPU资源耗尽而出现宕机

所能允许建立的连接数[max_connections默认100]

#### 磁盘IO

风险：

* 磁盘IO性能突然下降(热数据远远大于服务器可用内存的情况下)[使用更快的磁盘设备]

其他大量消耗磁盘性能的计划任务(调整计划任务，做好磁盘维护)

#### 网卡流量

风险：

* 网卡IO被占满(1000Mb/8≈100MB)[1.bit就是位，也叫比特位，是计算机表示数据最小的单位 2.byte就是字节 3.1byte=1B 4.1byte=8bit 1000兆是小b相当于1000个位]

如何避免无法连接数据库的情况：

* 减少从服务器的数量(从服务器要去主服务器复制日志，从服务器越多网络流量越大)

* 进行分级缓存(避免前端大量缓存失效，而对服务器产生冲击)

* 避免使用`select *`进行查询

* 分离业务网络和服务器网络(可以避免主从同步网络备份引起的网络性能)


#### 大表带来的问题

大表

* 记录行数巨大，单表的记录行数超过千万

* 表数据文件巨大，表数据文件超过10G

大表对查询的影响

* 慢查询：很难在一定的时间内过滤出所需要的数据(显示订单：来源少->区分度低->大量磁盘IO->降低磁盘效率 产生大量慢查询)

**大表对DDL操作的影响**

1.建立索引需要很长时间

风险：

* MYSQL版本 < 5.5 建立索引会锁表

* MYSQL版本 >= 5.5 虽然不会锁表，但是会引起主从延迟

2.修改表结构需要长时间锁表

风险：

* 会造成长时间的主从延迟(主从复制机制[单线程]：对于DDL操作先在主库上完成再通过日志传递给从库，再在从库上执行相同的操作)

* 影响正常的数据操作(操作被阻塞)

**如何处理数据库中的大表**

1.分库分表把一张大表分成多个小表

难点：

* 分表主键的选择

* 分表后跨分区数据的查询和统计

2.大表的历史数据归档

减少对前后端业务的影响

难点： 

* 归档时间点的选择

* 如何进行归档操作

#### 大事务带来的问题

什么是事务

* 事务是数据库系统区别于其他一切文件系统的重要特性之一
* 事务是一组具有原子性(要么全部完成，要么全部失败)的SQL语句，或是一个独立的工作单元
* 原子性、一致性、隔离性、持久性

事务的原子性

定义：

* 一个事务必须被视为一个不可分割的最小工作单元，对于一个事务来说，不可能只执行其中的一部分操作

事务的一致性

定义：

* 一致性是指事务将数据库从一种一致性状态到另一种一致性状态，在事务开始之前和事务结束后数据库中数据的完整性没有被破坏

事务的隔离性(隔离性越高，并发性越低)

定义：

* 隔离性要求一个事务对数据库中数据的修改，在未提交完成前对于其他事务是不可见的

SQL标准中定义的四种隔离级别

* 未提交读 [READ UNCOMMITED] (对数据进行修改，事务还没有被提交，对其他事务都是可见的，事务可以读取未提交的数据-脏读)
* 已提交读 [READ COMMITED] (大多数据库中的默认级别，满足事务的隔离性的定义)
* 可重复读 [REPEATABLE READ] (InnoDB中的默认级别，在同一个事务中，多次读取同样的记录的结果是一致的)
* 可串行化 [SERIALIZABLE] (最高隔离级别，在读取的每行数据上都加锁，可能导致大量的锁超时锁征用问题，实际中很少使用)

事务的隔离性-已提交读-可重复读区别

```sql
## 查看隔离性(可重复读)

mysql> show variables like '%iso%';
+---------------+-----------------+
| Variable_name | Value           |
+---------------+-----------------+
| tx_isolation  | REPEATABLE-READ |
+---------------+-----------------+
1 row in set (0.02 sec)
```

* window1 (测试第三种隔离级别)

```sql
mysql> select * from class;
+------+
| num  |
+------+
| 1    |
| 5    |
| 7    |
+------+
3 rows in set (0.00 sec)

## 查看隔离性(可重复读)

mysql> show variables like '%iso%';
+---------------+-----------------+
| Variable_name | Value           |
+---------------+-----------------+
| tx_isolation  | REPEATABLE-READ |
+---------------+-----------------+
1 row in set (0.01 sec)

## 开启事务

mysql> begin;
Query OK, 0 rows affected (0.00 sec)

mysql> select * from class where num < 5;
+------+
| num  |
+------+
| 1    |
+------+
1 row in set (0.00 sec)

## 在window2执行完插入操作后查询

mysql> select * from class where num < 5;
+------+
| num  |
+------+
| 1    |
+------+
1 row in set (0.00 sec)
```

* window2 (测试第三种隔离级别)

```sql
mysql> select * from class;
+------+
| num  |
+------+
| 1    |
| 5    |
| 7    |
+------+
3 rows in set (0.00 sec)

## 查看隔离性(可重复读)

mysql> show variables like '%iso%';
+---------------+-----------------+
| Variable_name | Value           |
+---------------+-----------------+
| tx_isolation  | REPEATABLE-READ |
+---------------+-----------------+
1 row in set (0.01 sec)

## 开启事务

mysql> begin;
Query OK, 0 rows affected (0.00 sec)

mysql> insert into class (num) values('3');
Query OK, 1 row affected (0.00 sec)

## 提交事务

mysql> commit;
Query OK, 0 rows affected (0.00 sec)
```

修改隔离级别后操作

* window1 (测试第二种隔离级别)

```sql
## 设置隔离级别(已提交读)

mysql> set session tx_isolation='read-committed';
Query OK, 0 rows affected (0.00 sec)

mysql> show variables like '%iso%';
+---------------+----------------+
| Variable_name | Value          |
+---------------+----------------+
| tx_isolation  | READ-COMMITTED |
+---------------+----------------+
1 row in set (0.01 sec)

mysql> begin;
Query OK, 0 rows affected (0.01 sec)

mysql> select * from class where num < 5;
+------+
| num  |
+------+
| 1    |
| 3    |
+------+
2 rows in set (0.00 sec)

## 在window2执行完插入操作后查询

mysql> select * from class where num < 5;
+------+
| num  |
+------+
| 1    |
| 3    |
| 4    |
+------+
3 rows in set (0.00 sec)
```

* window2 (测试第二种隔离级别)

```sql
mysql> begin;
Query OK, 0 rows affected (0.00 sec)

mysql> insert into class (num) values('4');
Query OK, 1 row affected (0.00 sec)

mysql> commit;
Query OK, 0 rows affected (0.00 sec)
```

事务的持久性

定义：

* 一旦事务被提交，则其所做的修改就会永久保存到数据库中。即使，此时系统崩溃，已经提交的修改数据也不会丢失

什么是大事务

定义： 

* 运行时间比较长，操作的数据比较多的事务

风险：

* 锁定太多的数据，造成大量的阻塞和锁超时(对于InnoDB这种事务型存储引擎，虽然使用的是行级锁，但在一事务中为了保证事务的一致性通常会把事务中所有处理记录都加锁，这样就会把所有记录都给锁住，这是用户需要操作就会阻塞，在并发比较大的情况下，会使数据库服务器连接所占满，同时严重影响数据库的性能和稳定性)

* 回滚所需要的时间会比较长(所有锁住的数据，还是会被锁住)

* 执行时间长，容易造成主从延迟

如何处理大事务

* 避免一次处理太多的数据

* 移出不必要在事务中的SELECT语句

#### 总结

直观的展示了数据库在繁忙时的系统状态简单了解了对性能有影响的一些因素


