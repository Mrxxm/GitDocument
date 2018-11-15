#### 常用存储引擎-MyISAM

* Mysql5.5之前版本默认存储引擎
* 临时表：在排序、分组等操作中，当数量超过一定的大小之后，由查询优化器建立的临时表
* MyISAM存储引擎将表存在两个系统文件中，一个是数据文件以MYD为扩展名和一个索引文件以MYI为扩展名组成
* 以frm为扩展名的文件是记录表的结构

特性：

* 并发性与锁级别 (MyISAM使用的是表级锁，对于读和写是互斥的，对表加共享锁，但有情况也在在读的情况下在表的末尾插入数据，所以对于读写混合的并发性不高)
* 表损坏修复 (检查：`check table tablename` 修复：`repair table tablename`)
* MyIsam表支持的索引类型
* MyIsam表支持数据压缩 (命令行：myisampack 进行表的压缩，独立压缩，读取单行数据时，无需进行整个表的解压，无法进行读操作)

window1 (表损坏修复)

```sql
mysql> use test;
Database changed
mysql> create table myIsam(id int, c1 varchar(10))engine=myisam;
Query OK, 0 rows affected (0.21 sec)

mysql> check table myIsam;
+-------------+-------+----------+----------+
| Table       | Op    | Msg_type | Msg_text |
+-------------+-------+----------+----------+
| test.myIsam | check | status   | OK       |
+-------------+-------+----------+----------+
1 row in set (0.02 sec)

mysql> repair table myIsam;
+-------------+--------+----------+----------+
| Table       | Op     | Msg_type | Msg_text |
+-------------+--------+----------+----------+
| test.myIsam | repair | status   | OK       |
+-------------+--------+----------+----------+
1 row in set (0.10 sec)
```

window2

```
[root@kenrou test]# pwd
/var/lib/mysql/test
[root@kenrou test]# ls -1 myIsam*
myIsam.frm
myIsam.MYD
myIsam.MYI
```

限制：

* 版本 < MYSQL5.0时默认表大小为4G
* 如果存储大表则要修改`MAX_Rows`和`AVG_ROW_LENGTH`
* 版本 > MYSQL5.0时默认支持为256TB

适用场景：

* 非事务型应用
* 只读类应用
* 空间类应用