## MySQL常用存储引擎-Federated

特点

* 提供了访问远程MySQL服务器上表的方法
* 本地不存储数据，数据全部放到远程服务器上
* 本地需要保存表结构和远程服务器的连接信息

如何使用 

* 默认禁止，启动需要在启动时增加federated参数
* `mysql://user_name[:password]@host_name[:port_num]/db_name/tbl_name`

window1 (查看引擎状态 FEDERATED默认关闭)

```sql
mysql> show engines;
+--------------------+---------+----------------------------------------------------------------+--------------+------+------------+
| Engine             | Support | Comment                                                        | Transactions | XA   | Savepoints |
+--------------------+---------+----------------------------------------------------------------+--------------+------+------------+
| InnoDB             | DEFAULT | Supports transactions, row-level locking, and foreign keys     | YES          | YES  | YES        |
| MRG_MYISAM         | YES     | Collection of identical MyISAM tables                          | NO           | NO   | NO         |
| MEMORY             | YES     | Hash based, stored in memory, useful for temporary tables      | NO           | NO   | NO         |
| BLACKHOLE          | YES     | /dev/null storage engine (anything you write to it disappears) | NO           | NO   | NO         |
| MyISAM             | YES     | MyISAM storage engine                                          | NO           | NO   | NO         |
| CSV                | YES     | CSV storage engine                                             | NO           | NO   | NO         |
| ARCHIVE            | YES     | Archive storage engine                                         | NO           | NO   | NO         |
| PERFORMANCE_SCHEMA | YES     | Performance Schema                                             | NO           | NO   | NO         |
| FEDERATED          | NO      | Federated MySQL storage engine                                 | NULL         | NULL | NULL       |
+--------------------+---------+----------------------------------------------------------------+--------------+------+------------+
9 rows in set (0.02 sec)
```

window2 (配置federated开启)

```
[root@kenrou /]# find / -name my.cnf
/opt/redmine-3.4.5-1/mysql/my.cnf
/etc/my.cnf
[root@kenrou /]# vim /etc/my.cnf

# 文件末尾添加federated=1
···
# Disabling symbolic-links is recommended to prevent assorted security risks
symbolic-links=0

log-error=/var/log/mysqld.log
pid-file=/var/run/mysqld/mysqld.pid

# 添加行
federated=1
#

[root@kenrou /]# service mysqld restart
Redirecting to /bin/systemctl restart mysqld.service
```

window1 (查看引擎状态 FEDERATED开启)

```sql
mysql> show engines;
+--------------------+---------+----------------------------------------------------------------+--------------+------+------------+
| Engine             | Support | Comment                                                        | Transactions | XA   | Savepoints |
+--------------------+---------+----------------------------------------------------------------+--------------+------+------------+
| InnoDB             | DEFAULT | Supports transactions, row-level locking, and foreign keys     | YES          | YES  | YES        |
| MRG_MYISAM         | YES     | Collection of identical MyISAM tables                          | NO           | NO   | NO         |
| MEMORY             | YES     | Hash based, stored in memory, useful for temporary tables      | NO           | NO   | NO         |
| BLACKHOLE          | YES     | /dev/null storage engine (anything you write to it disappears) | NO           | NO   | NO         |
| MyISAM             | YES     | MyISAM storage engine                                          | NO           | NO   | NO         |
| CSV                | YES     | CSV storage engine                                             | NO           | NO   | NO         |
| ARCHIVE            | YES     | Archive storage engine                                         | NO           | NO   | NO         |
| PERFORMANCE_SCHEMA | YES     | Performance Schema                                             | NO           | NO   | NO         |
| FEDERATED          | YES     | Federated MySQL storage engine                                 | NO           | NO   | NO         |
+--------------------+---------+----------------------------------------------------------------+--------------+------+------------+
9 rows in set (0.00 sec)
```

window1 (演示引擎本地数据库操作远程表)

```sql
# 创建本地数据库
mysql> create database local;
Query OK, 1 row affected (0.01 sec)

# 创建远程数据库
mysql> create database remote;
Query OK, 1 row affected (0.01 sec)

mysql> use remote;
Database changed

# 远程数据库使用innodb存储引擎
mysql> create table remote_fed(id int auto_increment not null, c1 varchar(10) not null default '', c2 char(10) not null default '', primary key(id)) engine = innodb;
Query OK, 0 rows affected (0.45 sec)

mysql> insert into remote_fed(c1, c2) values('aaa', 'bbb'), ('ccc', 'ddd'),('eee', 'fff');
Query OK, 3 rows affected (0.03 sec)
Records: 3  Duplicates: 0  Warnings: 0

mysql> select * from remote_fed;
+----+-----+-----+
| id | c1  | c2  |
+----+-----+-----+
|  1 | aaa | bbb |
|  2 | ccc | ddd |
|  3 | eee | fff |
+----+-----+-----+
3 rows in set (0.00 sec)

# 在远程数据建立权限
mysql> grant select,update,insert,delete on remote.remote_fed to fred_link@'127.0.0.1' identified by 'Xxm&123456';
Query OK, 0 rows affected, 1 warning (0.00 sec)

# 切换数据库
mysql> use local;
Database changed

# 创建federated引擎数据表，并指定连接方式
mysql> create table local_fed(id int auto_increment not null, c1 varchar(10) not null default '', c2 char(10) not null default '', primary key(id)) engine = federated connection = 'mysql://fred_link:Xxm&123456@127.0.0.1:3306/remote/remote_fed';
Query OK, 0 rows affected (0.02 sec)

# 查看表信息
mysql> show create table local_fed\G
*************************** 1. row ***************************
       Table: local_fed
Create Table: CREATE TABLE `local_fed` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `c1` varchar(10) NOT NULL DEFAULT '',
  `c2` char(10) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=FEDERATED DEFAULT CHARSET=latin1 CONNECTION='mysql://fred_link:Xxm&123456@127.0.0.1:3306/remote/remote_fed'
1 row in set (0.00 sec)

# 能够通过查询本地数据库表获取远程表数据
mysql> select * from local.local_fed;
+----+-----+-----+
| id | c1  | c2  |
+----+-----+-----+
|  1 | aaa | bbb |
|  2 | ccc | ddd |
|  3 | eee | fff |
+----+-----+-----+
3 rows in set (0.09 sec)

# 删除本地表数据
mysql> delete from local.local_fed where id = 2;
Query OK, 1 row affected (0.01 sec)

mysql> use remote;
Reading table information for completion of table and column names
You can turn off this feature to get a quicker startup with -A

Database changed

# 查看远程表数据被删除
mysql> select * from remote_fed;
+----+-----+-----+
| id | c1  | c2  |
+----+-----+-----+
|  1 | aaa | bbb |
|  3 | eee | fff |
+----+-----+-----+
2 rows in set (0.00 sec)
```

window2 (查看文件系统文件)

```
[root@kenrou mysql]# pwd
/var/lib/mysql
[root@kenrou mysql]# cd local/
[root@kenrou local]# ll
total 16
-rw-r----- 1 mysql mysql   65 Nov 17 16:50 db.opt
-rw-r----- 1 mysql mysql 8608 Nov 17 17:16 local_fed.frm
[root@kenrou local]# cd ../remote/
[root@kenrou remote]# ll
total 116
-rw-r----- 1 mysql mysql    65 Nov 17 17:12 db.opt
-rw-r----- 1 mysql mysql  8608 Nov 17 17:13 remote_fed.frm

# 系统文件空间表数据
-rw-r----- 1 mysql mysql 98304 Nov 17 17:20 remote_fed.ibd
```

适用场景

* 偶尔的统计分析及手工查询