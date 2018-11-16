#### 常用存储引擎-Innodb

* Mysql5.5及之后版本使用的默认存储引擎 (事务型存储引擎)
* Innodb使用表空间进行 数据存储
* `innodb_file_per_table` (这个参数决定使用怎样的表空间) [为ON：独立表空间：tablename.ibd/为OFF：系统表空间：ibdataX 'X'代表数字]

window1 (查看`innodb_file_per_table`参数)

```sql
mysql> show variables like 'innodb_file_per_table';
+-----------------------+-------+
| Variable_name         | Value |
+-----------------------+-------+
| innodb_file_per_table | ON    |
+-----------------------+-------+
1 row in set (0.00 sec)

```

window2 (系统中查看表文件)

```sql
[root@kenrou test]# pwd
/var/lib/mysql/test
[root@kenrou test]# ls -lh myinnodb*

# 记录表结构.frm
-rw-r----- 1 mysql mysql 8.4K Nov 15 17:28 myinnodb.frm

# innodb表实际存储的地方
-rw-r----- 1 mysql mysql  96K Nov 15 17:28 myinnodb.ibd
```

window1 (将`innodb_file_per_table`参数设置为OFF)

```sql
mysql> set global innodb_file_per_table = off;
Query OK, 0 rows affected (0.00 sec)

mysql> show variables like 'innodb_file_per_table';                 
+-----------------------+-------+
| Variable_name         | Value |
+-----------------------+-------+
| innodb_file_per_table | OFF   |
+-----------------------+-------+
1 row in set (0.01 sec)

mysql> create table myinnodb_g(id int, c1 varchar(10)) engine='innodb';
Query OK, 0 rows affected (0.05 sec)
```

window2 (系统中查看表文件)

```sql
[root@kenrou test]# ls -lh myinnodb*
-rw-r----- 1 mysql mysql 8.4K Nov 15 17:28 myinnodb.frm
-rw-r----- 1 mysql mysql 8.4K Nov 15 17:38 myinnodb_g.frm
-rw-r----- 1 mysql mysql  96K Nov 15 17:28 myinnodb.ibd
[root@kenrou test]# cd ..
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

# 数据表存在于ibdata1中
-rw-r----- 1 mysql mysql 79691776 Nov 15 17:38 ibdata1
```

* 系统表空间和独立表空间要如何选择

比较：

* 系统表空间无法简单的收缩文件大小
* 独立表空间可以通过`optimize table`命令收缩系统文件
* 系统表空间会产生IO瓶颈
* 独立表空间可以同时向多个文件刷新数据

建议：

* 对Innodb 使用独立表空间 (5.6以后独立表空间成为默认的配置)

表转移的步骤

* 把原来存在于系统表空间中的表转移到独立表空间中的方法

步骤：

* 使用mysqldump导出所有数据库数据
* 停止MYSQL服务，修改参数，并删除Innodb相关文件
* 重启MYSQL服务，重建Innodb系统表空间
* 重新导入数据