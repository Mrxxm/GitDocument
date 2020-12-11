## Mysql优化的那些事

> 大概从三个方面总结，方法、索引、数据结构...

<!--more-->

### 1.优化分析方法

#### 1.1.记录慢查询(摘录☞小肥羊的学习笔记)

* 查看慢查询相关参数(日志是否开启、日志存储位置、查询时间)

```
Database changed
mysql> show variables like '%slow_query%';
+---------------------+----------------------------------------------------+
| Variable_name       | Value                                              |
+---------------------+----------------------------------------------------+
| slow_query_log      | OFF                                                |
| slow_query_log_file | /usr/local/mysql/data/kenroudeMacBook-Pro-slow.log |
+---------------------+----------------------------------------------------+
2 rows in set (0.00 sec)

mysql> show variables like 'long_query_time';
+-----------------+-----------+
| Variable_name   | Value     |
+-----------------+-----------+
| long_query_time | 10.000000 |
+-----------------+-----------+
1 row in set (0.00 sec)
```

设置方法

* 方法一：全局变量设置(`MySQL`重启后失效)

  将`slow_query_log`全局变量设置为`ON`状态：
  
  ```
  mysql> set global slow_query_log='ON';
  ```

  设置慢查询日志存放的位置：
  
  ```  
  mysql> set global slow_query_log_file='/var/www/mysql/slow.log';
  ```

  查询超过1秒就记录：
  
  ```
  mysql> set long_query_time = 1;
  ```
  
* 实验

  1.重启服务(`for mac`)

  ```
	➜  / cd /usr/local/mysql/support-files
	➜  support-files sudo mysql.server
	Usage: mysql.server  {start|stop|restart|reload|force-reload|status}  [ MySQL server options ]
	➜  support-files sudo mysql.server restart
	Shutting down MySQL
	.. SUCCESS!
	Starting MySQL
	. SUCCESS!
  ```

  2.查看相关配置，并动态设定

  ```
	mysql> show variables like '%slow_query%';
	+---------------------+----------------------------------------------------+
	| Variable_name       | Value                                              |
	+---------------------+----------------------------------------------------+
	| slow_query_log      | OFF                                                |
	| slow_query_log_file | /usr/local/mysql/data/kenroudeMacBook-Pro-slow.log |
	+---------------------+----------------------------------------------------+
	2 rows in set (0.01 sec)
	
	mysql> show variables like 'long_query_time';
	+-----------------+-----------+
	| Variable_name   | Value     |
	+-----------------+-----------+
	| long_query_time | 10.000000 |
	+-----------------+-----------+
	1 row in set (0.00 sec)
	
	mysql> set global slow_query_log='ON';
	Query OK, 0 rows affected (0.03 sec)
	
	mysql> set global slow_query_log_file='/var/www/mysql/slow.log';
	Query OK, 0 rows affected (0.01 sec)
	
	mysql> set long_query_time = 1;
	Query OK, 0 rows affected (0.00 sec)
	
	mysql> show variables like '%slow_query%';
	+---------------------+-------------------------+
	| Variable_name       | Value                   |
	+---------------------+-------------------------+
	| slow_query_log      | ON                      |
	| slow_query_log_file | /var/www/mysql/slow.log |
	+---------------------+-------------------------+
	2 rows in set (0.00 sec)
	
	mysql> show variables like 'long_query_time';
	+-----------------+----------+
	| Variable_name   | Value    |
	+-----------------+----------+
	| long_query_time | 1.000000 |
	+-----------------+----------+
	1 row in set (0.00 sec)
  ```
  
  3.执行语句，并查看日志
  
  ```
  mysql> select sleep(2) from student where id = 5;
	+----------+
	| sleep(2) |
	+----------+
	|        0 |
	+----------+
	1 row in set (2.01 sec)
  ```
  
  ```
	  ➜  mysql tail -10f slow.log
	/usr/local/mysql/bin/mysqld, Version: 5.7.19 (MySQL Community Server (GPL)). started with:
	Tcp port: 3306  Unix socket: /tmp/mysql.sock
	Time                 Id Command    Argument
	
	# Time: 2020-12-11T02:20:10.260536Z
	# User@Host: root[root] @ localhost []  Id:     3
	# Query_time: 2.003861  Lock_time: 0.000094 Rows_sent: 1  Rows_examined: 7
	use test;
	SET timestamp=1607653210;
	select sleep(2) from student where id = 5;
  ```

设置方法

* 方法二：配置文件设置

  1.修改配置文件`my.cnf`，在`[mysqld]`下的下方加入
  
  2.加入配置
  
  ```
  [mysqld]
  slow_query_log = ON
  slow_query_log_file = /var/www/mysql/data/slow.log
  long_query_time = 1
  ```
  
  3.重启MySQL服务
  
* 实验

  1.查看慢查询配置
  
  ```
  mysql> show variables like '%slow_query%';
	+---------------------+----------------------------------------------------+
	| Variable_name       | Value                                              |
	+---------------------+----------------------------------------------------+
	| slow_query_log      | OFF                                                |
	| slow_query_log_file | /usr/local/mysql/data/kenroudeMacBook-Pro-slow.log |
	+---------------------+----------------------------------------------------+
	2 rows in set (0.00 sec)
	
	mysql> show variables like 'long_query_time';
	+-----------------+-----------+
	| Variable_name   | Value     |
	+-----------------+-----------+
	| long_query_time | 10.000000 |
	+-----------------+-----------+
	1 row in set (0.01 sec)
  ```

  2.修改配置文件，并重启服务(`mac`下没有`my.cnf`文件，需要手动创建)
  
  ```
  # 1.绝对路径 /etc 目录下
  ➜  /etc sudo touch my.cnf
  
  # 2.加入上面的配置到文件中
  ➜  /etc sudo vim my.cnf
  ```
  
  ```
  # 1.重启后查看配置
  mysql> show variables like '%slow_query%';
	ERROR 2006 (HY000): MySQL server has gone away
	No connection. Trying to reconnect...
	Connection id:    3
	Current database: test
	
	+---------------------+------------------------------+
	| Variable_name       | Value                        |
	+---------------------+------------------------------+
	| slow_query_log      | ON                           |
	| slow_query_log_file | /var/www/mysql/data/slow.log |
	+---------------------+------------------------------+
	2 rows in set (0.03 sec)
	
	mysql> show variables like 'long_query_time';
	+-----------------+----------+
	| Variable_name   | Value    |
	+-----------------+----------+
	| long_query_time | 1.000000 |
	+-----------------+----------+
	1 row in set (0.00 sec)

  ```
  
  3.执行语句，并查看日志  
  
  ```
  mysql> select sleep(2) from student where id = 6;
	+----------+
	| sleep(2) |
	+----------+
	|        0 |
	+----------+
	1 row in set (2.01 sec)
  ```
  
  ```
  ➜  data tail -10f slow.log
	/usr/local/mysql/bin/mysqld, Version: 5.7.19-log (MySQL Community Server (GPL)). started with:
	Tcp port: 0  Unix socket: (null)
	Time                 Id Command    Argument
	
	# Time: 2020-12-11T02:40:49.251760Z
	# User@Host: root[root] @ localhost []  Id:     3
	# Query_time: 2.007088  Lock_time: 0.000170 Rows_sent: 1  Rows_examined: 7
	use test;
	SET timestamp=1607654449;
	select sleep(2) from student where id = 6;
  ```
  
**MySQL慢查询是全局记录的，不能记录指定的数据，可以通过命令过滤slow.log来得到指定数据库的慢查询记录：**

```
cat slow.log | grep -A 3 database_name

注   grep -A 3 : 显示匹配行以及之后的3行
     grep -B 3 : 显示匹配行以及前面的3行
     grep -C 3 : 显示匹配行以及前后的3行
```

#### 1.2.使用`Explain`，分析单条语句

* `Explain`输出格式

Column         |Json Name           | 含义     |
--------------------|------------------|-----------------------|
id                | select_id        | SELECT 标识符          |
select_type       | None             | SELECT 类型            |
table             | table_name       | 输出行描述的表的表名      |
partitions        | partitions       | 匹配的分区              |
type              | access_type      | 连接类型                |
possible_keys     | possible_keys    | 可供选择使用的索引       |
key               | key              | 实际使用的索引          |
key_len           | key_length       | 实际使用的索引的长度     |
ref               | ref              | 与索引进行比较的列，也就是关联表使用的列   |
rows              | rows             | 将要被检查的估算的行数   |
filtered          | filtered         | 被表条件过滤的行数的百分比  |
Extra             | None             | 附件信息   |


* 实验

```
mysql> explain select * from student;
+----+-------------+---------+------------+------+---------------+------+---------+------+------+----------+-------+
| id | select_type | table   | partitions | type | possible_keys | key  | key_len | ref  | rows | filtered | Extra |
+----+-------------+---------+------------+------+---------------+------+---------+------+------+----------+-------+
|  1 | SIMPLE      | student | NULL       | ALL  | NULL          | NULL | NULL    | NULL |    6 |   100.00 | NULL  |
+----+-------------+---------+------------+------+---------------+------+---------+------+------+----------+-------+
1 row in set, 1 warning (0.00 sec)
```

[Explain详细分析参考](https://www.cnblogs.com/xuanzhi201111/p/4175635.html)

#### 1.3.其他一些方法

* 使用`show profile`：

  `set profiling = 1;`开启，服务器上执行的所有语句会检测消耗的时间，存到临时表中。
      
  ```
  mysql> show profiles;
  mysql> show profiles for query 临时表ID;
  ```
* 使用`show status`：

  `show status`会返回一些计数器，`show global status`查看服务器级别的所有计数。

  有时根据这些计数，可以猜测出哪些操作代价较高或者消耗时间多。

* `show processlist`：

  观察是否有大量线程处于不正常的状态或者特征。



### 2.基础优化点

#### 2.1.查询的数据量

* 避免使用如下SQL语句：

  1.查询不需要的记录，使用`limit`解决。  
  2.多表关联返回全部列，指定`A.id,A.name,B.age`。  
  3.总是取出全部列，`SELECT *`会让优化器无法完成索引覆盖扫描的优化。  
  4.重复查询相同的数据，可以缓存数据，下次直接读取缓存。 
  
* 过多的用户访问导致查询性能下降。
* 确定**应用程序**是否在检索大量超过需要的数据，可能是太多行或列。
* 确认**Mysql服务器**是否在分析大量不必要的数据行。
* 重写SQL语句，让优化器可以以更优的方式执行查询。
* 改变数据库和表的结构，修改数据表范式(降低范式，字段冗余)。
   
#### 2.2.长难的查询语句

* 使用尽可能少的查询是好的，但是有时将一个大的查询分解为多个小的查询时很有必要的(切分查询)。

* 切分查询：

  1.将一个大的查询分为多个小的相同的查询。  
  2.一次性删除1000万的数据要比一次删除1万，暂停一会的方案更加损耗服务器开销。

* 分解关联查询：

  1.可以将一条关联语句分解成多条SQL来执行。    
  2.执行单个查询可以减少锁的竞争。   
  3.让缓存的效率更高。   
  
#### 2.3.特定类型的查询语句

 * 优化`count()`查询：
 
  1.`count(*)`中的*会忽略所有的列，直接统计所有列数，因此不要使用count(列名)。  
  2.`MyISAM`中，没有任何`WHERE`条件语句的`count(*)`非常快。  
  3.当有`WHERE`条件，`MYISAM`的`count`统计不一定比其他表引擎快。  
  
* 优化关联查询：

  1.确定`ON`或者`USING`子句的列上有索引。  
  2.确保`GROUP BY`和`ORDER BY`中只有一个表中的列，这样`Mysql`才有可能使用索引。
  
* 优化`GROUP BY`和`DISTINCT`：

  1.这两种查询均可使用索引来优化，是最有效的优化方法。  
  2.关联查询中，使用**标识列**(主键)进行分组的效率会更高。  
  3.如果不需要`ORDER BY`，进行`GROUP BY`时使用`ORDER BY NULL`，Mysql不会再进行文件排序。 
  
* 优化子查询：
 
  1.尽可能使用关联查询来代替。   
  
* 优化`LIMIT`分页：

  1.`Limit`偏移量大的时候，查询效率较低。  
  2.可以记录上次查询的最大ID，下次查询时直接根据该ID来查询(添加条件：`WHERE ID > 上次查询最大ID`)。
  
* 优化`UNION`查询：

  1.`UNION ALL`的效率高于`UNION`, `UNION ALL` 操作符重复数据全部显示，不去重。
  
  
### 3.优化之索引

![](https://img2.doubanio.com/view/photo/l/public/p2628053963.jpg)

![](https://img3.doubanio.com/view/photo/l/public/p2628054001.jpg)
  
#### 3.1.索引的基础和类型

* 索引的基础：

  1.索引类似于书籍的目录，要想找到一本书的某个特定主题，需要先查找书的目录，定位对应的页码。
  
* 索引对性能的影响：

  1.大大减少服务器需要扫描的数据量。  
  2.帮助服务器避免排序和临时表。`b树`索引可以帮助我们进行排序以避免使用临时表(`order by`中使用索引)    
  3.将随机I/O变顺序I/O。(由于`b树`索引是顺序排放的，而数据的物理地址是随机的)    
  4.大大提高查询速度，降低写的速度(在写数据时额外还要操作索引)、占用磁盘。  
  
* 索引使用场景：

  1.对于非常小的表，大部分情况下全表扫描效率更高。  
  2.中到大型表，索引非常有效。  
  3.特大型的表，建立和使用索引的代价将随之增长，可以使用分区技术来解决。  
  
* 索引的类型(逻辑角度)：

  索引有很多种类型，都是实现在存储引擎层。
  
  1.普通索引：最基本的索引，没有任何约束限制。    
  2.唯一索引：与普通索引类似，但是具有唯一性约束(对一列的值有唯一性约束)。  
  3.主键索引：特殊的唯一索引，不允许有空值。  
  3.1.一个表只能有一个主键索引，可以有多个唯一索引。    
  3.2.主键索引一定是唯一索引，唯一索引不是主键索引。  
  3.3.主键可以与外键构成参照完整性约束，防止数据不一致。  
  4.全文索引：`MySQL`自带的全文索引只能用于`MYISAM`，并且只能对英文进行全文检索。  
  5.组合索引：将多个列组合在一起创建索引，可以覆盖多个列。  
  6.外键索引：只有InnoDB类型的表才能使用外键索引，保证数据的一致性、完整性和实现级联操作。
  
* 索引的类型(物理存储):

  1.按物理存储分类：聚簇索引(`clustered index`)、非聚簇索引(`non-clustered index`)  
  2.聚簇索引的叶子节点就是数据节点，而非聚簇索引的叶子节点仍然是索引节点，只不过有指向对应数据块的指针
  
* 创建原则:

  1.最适合索引的列是出现在`WHERE`子句中的列，或连接子句中的列而不是出现在`SELECT`关键字后的列。  
  2.索引列的基数越大，索引的效果越好。  
  3.根据情况创建**复合索引**，复合索引可以提高查询效率。  
  4.避免创建过多索引，索引会额外占用磁盘空间，降低写操作效率。  
  5.对字符串进行索引，应该制定一个前缀长度，可以节省大量的索引空间。      
   
* 注意事项:

  1.复合索引遵循**前缀原则(左前缀)**
  
  ```
  KEY(a, b, c)
  WHERE a = 1 and b = 2 and c = 3
  WHERE a = 1 and b = 2
  WHERE a = 1
  ```
  
  2.`like`查询，`%`不能在前,遵循**列前缀查询**。  
  
  ```
  WHERE name like "wang%"
  ```

  3.列类型的字符串，查询时一定要给值加引号，否则索引失效
  
  ```
  varchar(16)
  name = "100"
  // 索引没有被使用，需要给100加上引号
  where name = 100;
  ```
  
    
  3.如果`or`前的条件中的列有索引，后面的没有，索引都不会被用到
  
  ```
  // b如果没有索引 a的索引也不会被用到
  where a or b
  ```
  
#### 3.2.索引优化☞`Btree`索引和`Hash`索引

> `Mysql`的索引是在存储引擎层实现的。

* `B-tree`索引的特点 (最常见的索引类型)
  
  它使用的是`b+树`的结构来存储数据，在`b+树`中都包含一个指向下一叶子节点的指针，这样可以方便叶子节点之间的遍历。在`Innodb`存储引擎中，叶子节点指向的是主键。在`MyISAM`存储引擎中，叶子节点指向的是数据的物理地址。`B树`索引对索引是**顺序存储**的，它很适合**范围查找**。
  
* `B-tree`索引的运用

  1.全值匹配的查询 (在`order_sn`上建立了索引，查询条件为`order_sn='987643219900'`,这样就是全值匹配的查询)  
  2.匹配**列前缀**查询 (`order_sn like '9876%'`)    
  3.匹配范围值查询 (`order_sn > '987643219900' and order_sn < '987643219999'`)  
  4.匹配**左前缀**的查询 (在`order_sn`上没有建立索引，建立了`order_sn`，`order_date`联合索引，对于上面那个查询条件，还是可以使用联合索引的，如果联合索引的第一列符合条件，这个索引就可以被用到)    
  5.精确匹配**左前列**并范围匹配另外一列 (对于前面的联合查询，对`order_sn`进行精确匹配左前列，对`order_date`进行范围匹配)  
  
* `B-tree`索引的限制

  1.使用索引所命中的数据占表中大部分数据时，`mysql`查询优化器会觉得用全表扫描性能会更好  
  2.如果不是按照索引最左列开始查找，则无法使用索引 (前面联合索引例子)  
  3.`Not in` 和 `<>` 操作无法使用索引  
  4.如果查询中有某个列的范围查询，则其右边所有列都无法使用索引  
 
* `Hash`索引的特点

  1.`Hash`索引是基于`Hash`表实现的，只有查询条件精确匹配`Hash`索引中的所有列时，才能够使用到`Hash`索引 (说明hash索引是能用于**等值查询**)  
  2.对于`hash`索引中的所有列，存储引擎都会为每一行计算一个Hash码，Hash索引中存储的就是Hash码
  
* `Hash`索引的限制

  1.`Hash`索引无法用于排序  
  2.`Hash`索引不支持部分索引查找也不支持范围查找  
  3.`Hash`索引中的Hash码的计算可能存在Hash冲突
  
#### 3.3.索引优化策略

* 索引列上不能使用表达式或函数

  ```
  例：select ..... from product where to_days(out_date) - to_days(current_date) <= 30

  改：select ..... from product where out_date <= date_add(current_date, interval 30 day)
  ```
  
* 建立前缀索引

  ```
  create index index_name on table(col_name(n)); (对于Innodb列的最大宽度是767个字节)
  ```
  
* 联合索引

  1.经常会被使用到的列优先  
  2.选择性高的列优先  
  3.宽度小的列优先  
  
### 4.相关数据结构

![](https://img1.doubanio.com/view/photo/l/public/p2628054028.jpg)

> B树为了让树的高度更低，在一个树节点中包含多条数据，并包含多个指针域。

* 节点中的数字是关键字
* `Pn`是指向子节点的指针

![](https://img9.doubanio.com/view/photo/l/public/p2628054064.jpg)

1.内部节点（`internal node`）：存储了数据以及指向其子节点的指针。  
2.叶子节点（`leaf node`）：与内部节点不同的是，叶子节点只存储数据，并没有子节点。

> B+树 一个数据，既可能存在内部节点上，也可能存在叶子节点上，这一点就是B+树最大的不同。

![](https://img1.doubanio.com/view/photo/l/public/p2628054139.jpg)


1.`B+树`的非叶子节点没有存储数据指针，减少了节点占用的空间，可以减少IO的次数。  
2.同时，因为所有数据都存在叶子节点上，所以对于每条数据来说，查询的复杂度是相同的，查询效率更稳定。  
3.所有叶子节点本身根据关键字的大小进行连接，从而可以十分快速地实现范围查询。


[参考链接](https://www.codedump.info/post/20200609-btree-1/)

