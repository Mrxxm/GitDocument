## Innodb存储引擎的特性(1)

系统表空间和独立表空间要如何选择

把系统表空间里面的数据迁移到独立表空间后，系统表空间中还有一些重要的数据信息，其中包括：

* Innodb的数据字典信息 (数据库对象结构的原数据信息：存放表，列，索引，外键等)
* Undo 回滚段和 Innodb临时表 (5.7可以从系统表空间中移除)

`.frm`数据文件和数据字典区别：

* `.frm`是Mysql数据库服务器层产生的文件，Mysql服务器层的数据字典 [`.frm`只是简单的二进制文件]
* innodb数据字典是通过b树来进行数据管理的

---

Innodb存储引擎的特性

* Innodb是一种事务型存储引擎
* 完全支持事务的ACID特性 (原子性、一致性、隔离性、持久性)
* Innodb支持行级锁
* 行级锁可以最大程度的支持并发
* 行级锁是由存储引擎层实现的

Innodb如何实现事务的ACID特性

* `Redo Log` 和 `Undo Log` (使用了两个特殊的日志类型：重做日志和回滚日志) 
* `Redo Log` (`Redo Log`主要实现事务的持久性，由两部分组成：一个是内存中的重做日志缓冲区由`innodb_log_buffer_size`设置其大小，另一个是重做日志文件，在文件系统中`ib_logfile`开头的文件) [存储已经提交的事务，顺序写入，在数据库运行时不需要对`redo log`进行读取]
* `Undo Log` (主要实现事务的原子、一致性，主要作用用于帮助未提交事务进行回滚和实现mvcc[多版本并发控制]，当我们对数据进行修改时，会产生`redo log` 和 `undo log`) [存储未提交的事务，`undo log`需要进行随机读写，5.6版本中`undo log`可以独立于系统表空间而存在，如果条件允许，我们就可以把`undo log`存储在固态存储上]


window1 (系统中查看`innodb_log_buffer_size`和`innodb_log_files_in_group`)

```sql
# 数据库查看
mysql> show variables like 'innodb_log_buffer_size';

# 字节为单位，由于我们最多会每隔一秒就会把缓存区刷新到磁盘上，所以说这个缓冲区不用配置的特别大
+------------------------+----------+
| Variable_name          | Value    |
+------------------------+----------+
| innodb_log_buffer_size | 16777216 |
+------------------------+----------+
1 row in set (0.45 sec)

mysql> show variables like 'innodb_log_files_in_group';

# 决定文件系统中`ib_logfile`文件数量
+---------------------------+-------+
| Variable_name             | Value |
+---------------------------+-------+
| innodb_log_files_in_group | 2     |
+---------------------------+-------+
1 row in set (0.00 sec)
```

window2 (系统文件查看`ib_logfile`文件)

```
# 系统文件查看
[root@kenrou mysql]# pwd
/var/lib/mysql
[root@kenrou mysql]# ll
total 188708
-rw-r----- 1 mysql mysql       56 Jul 20 11:59 auto.cnf
-rw------- 1 mysql mysql     1675 Jul 20 11:59 ca-key.pem
-rw-r--r-- 1 mysql mysql     1107 Jul 20 11:59 ca.pem
-rw-r--r-- 1 mysql mysql     1107 Jul 20 11:59 client-cert.pem
-rw------- 1 mysql mysql     1675 Jul 20 11:59 client-key.pem
drwxr-x--- 2 mysql mysql     4096 Jul 21 11:46 desired_life
drwxr-x--- 2 mysql mysql    20480 Sep 10 14:13 edusoho
-rw-r----- 1 mysql mysql     1986 Sep 15 23:46 ib_buffer_pool
-rw-r----- 1 mysql mysql 79691776 Nov 15 21:49 ibdata1

# 该文件数量是由`innodb_log_files_in_group`配置决定的
-rw-r----- 1 mysql mysql 50331648 Nov 15 21:49 ib_logfile0
-rw-r----- 1 mysql mysql 50331648 Jul 20 11:59 ib_logfile1
```

什么是锁 (锁是数据库系统区别于文件系统的重要特性)

* 锁的主要作用是管理共享资源的并发访问 (同一个邮箱同时写入两封邮件的例子：在一封邮件写入时，会阻塞另一封邮件的写入)
* 锁用于实现事务的隔离性 

锁的类型

* 共享锁 (读锁)
* 独占锁 (写锁)


|  | 写锁 | 读锁 |
| --- | --- | --- |
| 写锁 | 不兼容 | 不兼容 |
| 读锁 | 不兼容 | 兼容  |

对同一资源读写请求应该是互斥的，实际体验并不和以上的情况相同

window1 (对同一资源的读写请求互斥性验证)

```sql
mysql> show tables;
+----------------+
| Tables_in_test |
+----------------+
| myIsam         |
| myinnodb       |
| myinnodb_g     |
+----------------+
3 rows in set (0.00 sec)

mysql> desc myinnodb;
+-------+-------------+------+-----+---------+-------+
| Field | Type        | Null | Key | Default | Extra |
+-------+-------------+------+-----+---------+-------+
| id    | int(11)     | YES  |     | NULL    |       |
| c1    | varchar(10) | YES  |     | NULL    |       |
+-------+-------------+------+-----+---------+-------+
2 rows in set (0.09 sec)

mysql> insert into myinnodb values(2, 'bb'),(3, 'cc');
Query OK, 2 rows affected (0.04 sec)
Records: 2  Duplicates: 0  Warnings: 0

mysql> select * from myinnodb;
+------+------+
| id   | c1   |
+------+------+
|    2 | bb   |
|    3 | cc   |
+------+------+
2 rows in set (0.00 sec)

# 开启事务
mysql> begin;
Query OK, 0 rows affected (0.00 sec)

mysql> update myinnodb set c1 = 'bbb' where id = 2;
Query OK, 1 row affected (0.00 sec)
Rows matched: 1  Changed: 1  Warnings: 0
```

window2 

```sql 
# 这里的查询并没有被连接一的独占锁所阻塞，这和上面介绍的兼容性不符。这里Innodb利用上面介绍的Undo Log中的记录，我们这里连接查看的数据时存储在Undo Log中的版本，其实Innodb中还存在异向共享锁、异向独占锁，这是为了支持在不同粒度上加锁而设计的
mysql> select * from myinnodb
    -> ;
+------+------+
| id   | c1   |
+------+------+
|    2 | bb   |
|    3 | cc   |
+------+------+
2 rows in set (0.00 sec)
```

锁的粒度 (被加锁的最小单位 例：在行上加锁，则锁的粒度就是行) [对需要修改的内容，锁的粒度越小，则系统并发性越高，只要相互之间不产生阻塞就好]

* 表级锁 (通常在Mysql数据服务层面所实现的) [虽然Innodb实现行级锁，但在有些情况下mysql数据服务层还是会对Innodb表加上表级锁]
* 行级锁 (最大程度支持并发处理，锁的开销比表级锁要大)

window1 (在Innodb引擎表上实现表级锁)

```sql
# 查看表的存储引擎
mysql> show create table myinnodb;
+----------+-------------------------------------------------------------------------------------------------------------------------------+
| Table    | Create Table                                                                                                                  |
+----------+-------------------------------------------------------------------------------------------------------------------------------+
| myinnodb | CREATE TABLE `myinnodb` (
  `id` int(11) DEFAULT NULL,
  `c1` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 |
+----------+-------------------------------------------------------------------------------------------------------------------------------+
1 row in set (0.00 sec)

# 为Innodb添加表级独占锁
mysql> lock table myinnodb write;
Query OK, 0 rows affected (0.00 sec)
```

window2 (查询被阻塞)

```
# 查询操作被阻塞
mysql> select * from myinnodb;

```

window1 (添加解锁操作)

```sql
# 解锁
mysql> unlock tables;
Query OK, 0 rows affected (0.00 sec)
```

window2 (查询成功)

```sql
# 查询成功
mysql> select * from myinnodb;
+------+------+
| id   | c1   |
+------+------+
|    2 | bb   |
|    3 | cc   |
+------+------+
2 rows in set (1 min 17.89 sec)
```