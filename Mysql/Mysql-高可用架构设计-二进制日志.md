## Mysql二进制日志

* Mysql服务层日志 (二进制日志、慢查日志、通用日志)
*  Mysql存储引擎层日志 (Innodb 重做日志、回滚日志)

* 二进制日志 (记录了所有对Mysql数据库的修改事件，包括增删改查事件和对表结构的修改事件) [在binlog中记录的事件都是已经成功执行了的，对于回滚了的语法错误了的并未成功执行的不会记录在二进制日志中的]

#### 二进制日志格式

* 基于段的二进制日志格式 `binlog_format = STATEMENT` (Mysql5.7之前默认使用的二进制日志格式)

基于段的二进制日志格式

优点：(由记录日志的方式所产生)

* 日志记录量相对较小，节约磁盘及网络I/O (记录的是每个事件执行的sql语句，不需要记录每一行的具体变化) [只对一条记录修改或者插入，row格式所产生的日志量小于段产生的日志量]

缺点：(也是由记录日志的方式所产生)

* 必须记录上下文信息，保证语句在服务器上执行结果在主服务器上相同，特定的函数如`UUID(),user()`这样非确定性函数还无法复制 (由于记录的是执行语句，为了语句能在服务器上能正确执行，必须记录上下文信息。如果在修改数据时，使用了这样的函数可能会造成Mysql复制的主从服务器数据的不一致，从而中断复制链路)

window1 (查看日志格式，设置成段格式)

```sql

# 查看binlog日志格式
mysql> show variables like 'binlog_format';
+---------------+-------+
| Variable_name | Value |
+---------------+-------+
| binlog_format | ROW   |
+---------------+-------+
1 row in set (0.18 sec)

# 设置成段格式
mysql> set session binlog_format=statement;
Query OK, 0 rows affected (0.00 sec)

# 查看binlog日志格式
mysql> show variables like 'binlog_format';
+---------------+-----------+
| Variable_name | Value     |
+---------------+-----------+
| binlog_format | STATEMENT |
+---------------+-----------+
1 row in set (0.00 sec)
```

window2 (开启binlog) [开启服务器一些服务与本次试验无关]

```
# 查找my.cnf
[root@kenrou ~]# find / -name my.cnf
/opt/redmine-3.4.5-1/mysql/my.cnf
/etc/my.cnf
[root@kenrou ~]# vim /etc/my.cnf

# 查看进程
[root@kenrou ~]# sudo lsof -nP -iTCP -sTCP:LISTEN
COMMAND PID USER   FD   TYPE DEVICE SIZE/OFF NODE NAME
sshd    859 root    3u  IPv4  15180      0t0  TCP *:22 (LISTEN)

# 开启fpm
[root@kenrou www]# sudo /usr/local/php-7.1.11/sbin/php-fpm
[root@kenrou www]# sudo lsof -nP -iTCP -sTCP:LISTEN
COMMAND   PID   USER   FD   TYPE DEVICE SIZE/OFF NODE NAME
sshd      859   root    3u  IPv4  15180      0t0  TCP *:22 (LISTEN)
php-fpm 11660   root    7u  IPv4 592266      0t0  TCP 127.0.0.1:9000 (LISTEN)
php-fpm 11661 nobody    0u  IPv4 592266      0t0  TCP 127.0.0.1:9000 (LISTEN)
php-fpm 11662 nobody    0u  IPv4 592266      0t0  TCP 127.0.0.1:9000 (LISTEN)

# 开启nginx
[root@kenrou www]# sudo /usr/local/nginx/sbin/nginx
[root@kenrou www]# sudo lsof -nP -iTCP -sTCP:LISTEN
COMMAND   PID   USER   FD   TYPE DEVICE SIZE/OFF NODE NAME
sshd      859   root    3u  IPv4  15180      0t0  TCP *:22 (LISTEN)
php-fpm 11660   root    7u  IPv4 592266      0t0  TCP 127.0.0.1:9000 (LISTEN)
php-fpm 11661 nobody    0u  IPv4 592266      0t0  TCP 127.0.0.1:9000 (LISTEN)
php-fpm 11662 nobody    0u  IPv4 592266      0t0  TCP 127.0.0.1:9000 (LISTEN)
nginx   11727   root   11u  IPv4 592908      0t0  TCP *:80 (LISTEN)
nginx   11728 nobody   11u  IPv4 592908      0t0  TCP *:80 (LISTEN)

```

window2 (Mysql服务开启报错)

```
# mysql日志目录
/var/log/mysqld.log

# mysql需要存在的目录
/var/run/mysqld/

# mysql启动
$ sudo service mysqld start

# mysql启动
[root@kenrou mysqld]# sudo /usr/sbin/mysqld
2018-11-28T01:09:00.142442Z 0 [Warning] TIMESTAMP with implicit DEFAULT value is deprecated. Please use --explicit_defaults_for_timestamp server option (see documentation for more details).
# 原因
2018-11-28T01:09:00.144160Z 0 [Note] /usr/sbin/mysqld (mysqld 5.7.23-log) starting as process 14885 ...
2018-11-28T01:09:00.145727Z 0 [ERROR] Fatal error: Please read "Security" section of the manual to find out how to run mysqld as root!

2018-11-28T01:09:00.145757Z 0 [ERROR] Aborting

2018-11-28T01:09:00.145775Z 0 [Note] Binlog end
2018-11-28T01:09:00.145924Z 0 [Note] /usr/sbin/mysqld: Shutdown complete

# mysql添加参数重新启动
[root@kenrou mysqld]# sudo /usr/sbin/mysqld --user=root
2018-11-28T01:09:59.296200Z 0 [Warning] TIMESTAMP with implicit DEFAULT value is deprecated. Please use --explicit_defaults_for_timestamp server option (see documentation for more details).
2018-11-28T01:09:59.297842Z 0 [Note] /usr/sbin/mysqld (mysqld 5.7.23-log) starting as process 15036 ...
# 原因未指定server-id
2018-11-28T01:09:59.299365Z 0 [ERROR] You have enabled the binary log, but you haven't provided the mandatory server-id. Please refer to the proper server start-up parameters documentation
2018-11-28T01:09:59.299400Z 0 [ERROR] Aborting

2018-11-28T01:09:59.299425Z 0 [Note] Binlog end
2018-11-28T01:09:59.299582Z 0 [Note] /usr/sbin/mysqld: Shutdown complete

# 编辑my.cnf文件
[root@kenrou mysqld]# vim /etc/my.cnf

# my.cnf文件
[mysqld]
#
# Remove leading # and set to the amount of RAM for the most important data
# cache in MySQL. Start at 70% of total RAM for dedicated server, else 10%.
# innodb_buffer_pool_size = 128M
#
# Remove leading # to turn on a very important data integrity option: logging
# changes to the binary log between backups.

# 开启binlog
 log_bin=mysql-bin
 server-id=1
#
# Remove leading # to set options mainly useful for reporting servers.
# The server defaults are faster for transactions and fast SELECTs.
# Adjust sizes as needed, experiment to find the optimal values.
# join_buffer_size = 128M
# sort_buffer_size = 2M
# read_rnd_buffer_size = 2M
datadir=/var/lib/mysql
socket=/var/lib/mysql/mysql.sock

# Disabling symbolic-links is recommended to prevent assorted security risks
symbolic-links=0

log-error=/var/log/mysqld.log
pid-file=/var/run/mysqld/mysqld.pid
federated=1

# mysql启动成功
[root@kenrou mysqld]# sudo /usr/sbin/mysqld --user=root
```

window1 (继续binlog操作，设置binlog格式为段，刷新binlog日志，并做一些数据库操作)

```sql
# 查看binlog日志
mysql> show binary logs;
+------------------+-----------+
| Log_name         | File_size |
+------------------+-----------+
| mysql-bin.000001 |       201 |
| mysql-bin.000002 |       201 |
| mysql-bin.000003 |       201 |
| mysql-bin.000004 |      1646 |
+------------------+-----------+
4 rows in set (0.00 sec)

# 查看binlog日志格式
mysql> show variables like 'binlog_format';
+---------------+-------+
| Variable_name | Value |
+---------------+-------+
| binlog_format | ROW   |
+---------------+-------+
1 row in set (0.00 sec)

# 设置binlog为段存储
mysql> set session binlog_format=statement;
Query OK, 0 rows affected (0.00 sec)

# 查看binlog日志格式
mysql> show variables like 'binlog_format';
+---------------+-----------+
| Variable_name | Value     |
+---------------+-----------+
| binlog_format | STATEMENT |
+---------------+-----------+
1 row in set (0.00 sec)

# 查看数据库
mysql> show databases;
+--------------------+
| Database           |
+--------------------+
| information_schema |
| crn                |
| desired_life       |
| mysql              |
| performance_schema |
| sys                |
+--------------------+
6 rows in set (0.00 sec)

# 删除数据库crn
mysql> drop database crn;
Query OK, 1 row affected (0.05 sec)

# 刷新binlog生成新的000005日志文件
mysql> flush logs;
Query OK, 0 rows affected (0.04 sec)

# 查看binlog日志
mysql> show binary logs;
+------------------+-----------+
| Log_name         | File_size |
+------------------+-----------+
| mysql-bin.000001 |       201 |
| mysql-bin.000002 |       201 |
| mysql-bin.000003 |       201 |
| mysql-bin.000004 |      1847 |
| mysql-bin.000005 |       154 |
+------------------+-----------+
5 rows in set (0.00 sec)

# 接下来一些操作
mysql> create database crn;
Query OK, 1 row affected (0.01 sec)

mysql> use crn;
Database changed
mysql> create table t(id int, c1 varchar(10));
Query OK, 0 rows affected (0.09 sec)

mysql> insert into t values(1, 'aa'),(2, 'bb');
Query OK, 2 rows affected (0.02 sec)
Records: 2  Duplicates: 0  Warnings: 0

mysql> update t set c1 = 'dd' where id = 1;
Query OK, 1 row affected (0.02 sec)
Rows matched: 1  Changed: 1  Warnings: 0

mysql> select * from t where 1;
+------+------+
| id   | c1   |
+------+------+
|    1 | dd   |
|    2 | bb   |
+------+------+
2 rows in set (0.00 sec)
```

window2 (查看binlog日志，日志中显示创建，插入，更新sql语句都被记录)

```
# mysql文件存放路径
[root@kenrou mysql]# pwd
/var/lib/mysql

# 使用mysqlbinlog命令查看binlog日志
[root@kenrou mysql]# mysqlbinlog mysql-bin.000005
/*!50530 SET @@SESSION.PSEUDO_SLAVE_MODE=1*/;
/*!50003 SET @OLD_COMPLETION_TYPE=@@COMPLETION_TYPE,COMPLETION_TYPE=0*/;
DELIMITER /*!*/;
# at 4
#181128 10:12:35 server id 1  end_log_pos 123 CRC32 0xbe620061 	Start: binlog v 4, server v 5.7.23-log created 181128 10:12:35
# Warning: this binlog is either in use or was not closed properly.
BINLOG '
k/n9Ww8BAAAAdwAAAHsAAAABAAQANS43LjIzLWxvZwAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA
AAAAAAAAAAAAAAAAAAAAAAAAEzgNAAgAEgAEBAQEEgAAXwAEGggAAAAICAgCAAAACgoKKioAEjQA
AWEAYr4=
'/*!*/;
# at 123
#181128 10:12:35 server id 1  end_log_pos 154 CRC32 0x5da0782d 	Previous-GTIDs
# [empty]
# at 154
#181128 10:12:58 server id 1  end_log_pos 219 CRC32 0xfb673565 	Anonymous_GTID	last_committed=0	sequence_number=1	rbr_only=no
SET @@SESSION.GTID_NEXT= 'ANONYMOUS'/*!*/;
# at 219
#181128 10:12:58 server id 1  end_log_pos 310 CRC32 0x918b2bf5 	Query	thread_id=7	exec_time=0	error_code=0
SET TIMESTAMP=1543371178/*!*/;
SET @@session.pseudo_thread_id=7/*!*/;
SET @@session.foreign_key_checks=1, @@session.sql_auto_is_null=0, @@session.unique_checks=1, @@session.autocommit=1/*!*/;
SET @@session.sql_mode=1436549152/*!*/;
SET @@session.auto_increment_increment=1, @@session.auto_increment_offset=1/*!*/;
/*!\C utf8 *//*!*/;
SET @@session.character_set_client=33,@@session.collation_connection=33,@@session.collation_server=8/*!*/;
SET @@session.lc_time_names=0/*!*/;
SET @@session.collation_database=DEFAULT/*!*/;

# 创建数据库
create database crn
/*!*/;
# at 310
#181128 10:13:12 server id 1  end_log_pos 375 CRC32 0x3ab7af3f 	Anonymous_GTID	last_committed=1	sequence_number=2	rbr_only=no
SET @@SESSION.GTID_NEXT= 'ANONYMOUS'/*!*/;
# at 375
#181128 10:13:12 server id 1  end_log_pos 485 CRC32 0x1fee659d 	Query	thread_id=7	exec_time=0	error_code=0
use `crn`/*!*/;
SET TIMESTAMP=1543371192/*!*/;

# 创建表
create table t(id int, c1 varchar(10))
/*!*/;
# at 485
#181128 10:13:19 server id 1  end_log_pos 550 CRC32 0xf282cdd7 	Anonymous_GTID	last_committed=2	sequence_number=3	rbr_only=no
SET @@SESSION.GTID_NEXT= 'ANONYMOUS'/*!*/;
# at 550
#181128 10:13:19 server id 1  end_log_pos 627 CRC32 0xacc83a7c 	Query	thread_id=7	exec_time=0	error_code=0
SET TIMESTAMP=1543371199/*!*/;
BEGIN
/*!*/;
# at 627
#181128 10:13:19 server id 1  end_log_pos 738 CRC32 0xf8baa4a7 	Query	thread_id=7	exec_time=0	error_code=0
SET TIMESTAMP=1543371199/*!*/;

# 插入数据
insert into t values(1, 'aa'),(2, 'bb')
/*!*/;
# at 738
#181128 10:13:19 server id 1  end_log_pos 769 CRC32 0x1b7a2f34 	Xid = 131
COMMIT/*!*/;
# at 769
#181128 10:13:26 server id 1  end_log_pos 834 CRC32 0xfa3f3c8c 	Anonymous_GTID	last_committed=3	sequence_number=4	rbr_only=no
SET @@SESSION.GTID_NEXT= 'ANONYMOUS'/*!*/;
# at 834
#181128 10:13:26 server id 1  end_log_pos 911 CRC32 0xab59bf13 	Query	thread_id=7	exec_time=0	error_code=0
SET TIMESTAMP=1543371206/*!*/;
BEGIN
/*!*/;
# at 911
#181128 10:13:26 server id 1  end_log_pos 1018 CRC32 0xd51e2090 	Query	thread_id=7	exec_time=0	error_code=0
SET TIMESTAMP=1543371206/*!*/;

# 更新数据
update t set c1 = 'dd' where id = 1
/*!*/;
# at 1018
#181128 10:13:26 server id 1  end_log_pos 1049 CRC32 0xd517990e 	Xid = 132
COMMIT/*!*/;
SET @@SESSION.GTID_NEXT= 'AUTOMATIC' /* added by mysqlbinlog */ /*!*/;
DELIMITER ;
# End of log file
/*!50003 SET COMPLETION_TYPE=@OLD_COMPLETION_TYPE*/;
/*!50530 SET @@SESSION.PSEUDO_SLAVE_MODE=0*/;
```

* 基于行的日志格式 `binlog_format=ROW` (Mysql5.7之后默认使用的二进制日志格式) [ROW格式可以避免Mysql复制中出现的主从不一致问题]
* 同一SQL语句修改了10000条数据的情况下
* 基于段的日志格式只会记录下这个SQL语句
* 基于行的日志会有10000条记录分别记录每一行的数据修改

优点： 

* 使Mysql主从复制更加安全
* 对于每一行数据的修改比基于段的复制高效
* 误操作而修改数据库中的数据，同时又没有备份可以恢复时，我们就可以通过分析二进制日志，对日志中记录的数据修改操作做反向处理的方式来达到恢复数据的目的

缺点： 

* 记录日志较大.binlog_row_image = [FULL|MINIMAL|BOBLOB] (FULL会记录所有列的数据|MINIMAL只会记录一列修改前后的数据|BOBLOB TEXT字段值被更新则记录，反之不记录)

window1 (准备工作)

```sql
mysql> show variables like 'binlog_format';
+---------------+-------+
| Variable_name | Value |
+---------------+-------+
| binlog_format | ROW   |
+---------------+-------+
1 row in set (0.00 sec)

mysql> show variables like 'binlog_row_image';
+------------------+-------+
| Variable_name    | Value |
+------------------+-------+
| binlog_row_image | FULL  |
+------------------+-------+
1 row in set (0.01 sec)

mysql> show binary logs;
+------------------+-----------+
| Log_name         | File_size |
+------------------+-----------+
| mysql-bin.000001 |       201 |
| mysql-bin.000002 |       201 |
| mysql-bin.000003 |       201 |
| mysql-bin.000004 |      1847 |
| mysql-bin.000005 |      1049 |
+------------------+-----------+
5 rows in set (0.00 sec)

mysql> flush logs;
Query OK, 0 rows affected (0.04 sec)

mysql> show binary logs;
+------------------+-----------+
| Log_name         | File_size |
+------------------+-----------+
| mysql-bin.000001 |       201 |
| mysql-bin.000002 |       201 |
| mysql-bin.000003 |       201 |
| mysql-bin.000004 |      1847 |
| mysql-bin.000005 |      1096 |
| mysql-bin.000006 |       154 |
+------------------+-----------+
6 rows in set (0.00 sec)
```

window1 (设置binlog_row_image=FULL下数据记录情况)

```sql
mysql> alter table t add c2 text;
Query OK, 0 rows affected (0.15 sec)
Records: 0  Duplicates: 0  Warnings: 0

mysql> select * from t where 1;
+------+------+------+
| id   | c1   | c2   |
+------+------+------+
|    1 | dd   | NULL |
|    2 | bb   | NULL |
+------+------+------+
2 rows in set (0.00 sec)

mysql> insert into t values(3, 'ee', 'bbb');
Query OK, 1 row affected (0.10 sec)

mysql> delete from t where id = 1;
Query OK, 1 row affected (0.02 sec)
```

window2 (查看日志，会记录更新行所在所有列的数据信息) [改为ROW格式之后需要在命令中添加 `-vv` 参数，供人工识别]

```
[root@kenrou mysql]# mysqlbinlog -vv mysql-bin.000006
/*!50530 SET @@SESSION.PSEUDO_SLAVE_MODE=1*/;
/*!50003 SET @OLD_COMPLETION_TYPE=@@COMPLETION_TYPE,COMPLETION_TYPE=0*/;
DELIMITER /*!*/;
# at 4
#181128 14:31:43 server id 1  end_log_pos 123 CRC32 0xdd564caf 	Start: binlog v 4, server v 5.7.23-log created 181128 14:31:43
# Warning: this binlog is either in use or was not closed properly.
BINLOG '
Tzb+Ww8BAAAAdwAAAHsAAAABAAQANS43LjIzLWxvZwAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA
AAAAAAAAAAAAAAAAAAAAAAAAEzgNAAgAEgAEBAQEEgAAXwAEGggAAAAICAgCAAAACgoKKioAEjQA
Aa9MVt0=
'/*!*/;
# at 123
#181128 14:31:43 server id 1  end_log_pos 154 CRC32 0xb29ada24 	Previous-GTIDs
# [empty]
# at 154
#181128 14:33:28 server id 1  end_log_pos 219 CRC32 0x4628629b 	Anonymous_GTID	last_committed=0	sequence_number=1	rbr_only=no
SET @@SESSION.GTID_NEXT= 'ANONYMOUS'/*!*/;
# at 219
#181128 14:33:28 server id 1  end_log_pos 316 CRC32 0x9508d789 	Query	thread_id=18	exec_time=0	error_code=0
use `crn`/*!*/;
SET TIMESTAMP=1543386808/*!*/;
SET @@session.pseudo_thread_id=18/*!*/;
SET @@session.foreign_key_checks=1, @@session.sql_auto_is_null=0, @@session.unique_checks=1, @@session.autocommit=1/*!*/;
SET @@session.sql_mode=1436549152/*!*/;
SET @@session.auto_increment_increment=1, @@session.auto_increment_offset=1/*!*/;
/*!\C utf8 *//*!*/;
SET @@session.character_set_client=33,@@session.collation_connection=33,@@session.collation_server=8/*!*/;
SET @@session.lc_time_names=0/*!*/;
SET @@session.collation_database=DEFAULT/*!*/;
alter table t add c2 text
/*!*/;
# at 316
#181128 14:34:18 server id 1  end_log_pos 381 CRC32 0xc28ce425 	Anonymous_GTID	last_committed=1	sequence_number=2	rbr_only=yes
/*!50718 SET TRANSACTION ISOLATION LEVEL READ COMMITTED*//*!*/;
SET @@SESSION.GTID_NEXT= 'ANONYMOUS'/*!*/;
# at 381
#181128 14:34:18 server id 1  end_log_pos 452 CRC32 0x352cc197 	Query	thread_id=18	exec_time=0	error_code=0
SET TIMESTAMP=1543386858/*!*/;
BEGIN
/*!*/;
# at 452
#181128 14:34:18 server id 1  end_log_pos 500 CRC32 0x414d89a7 	Table_map: `crn`.`t` mapped to number 153
# at 500
#181128 14:34:18 server id 1  end_log_pos 548 CRC32 0xd154a535 	Write_rows: table id 153 flags: STMT_END_F

BINLOG '
6jb+WxMBAAAAMAAAAPQBAAAAAJkAAAAAAAEAA2NybgABdAADAw/8AwoAAgeniU1B
6jb+Wx4BAAAAMAAAACQCAAAAAJkAAAAAAAEAAgAD//gDAAAAAmVlAwBiYmI1pVTR
'/*!*/;
### INSERT INTO `crn`.`t`
### SET
###   @1=3 /* INT meta=0 nullable=1 is_null=0 */
###   @2='ee' /* VARSTRING(10) meta=10 nullable=1 is_null=0 */
###   @3='bbb' /* BLOB/TEXT meta=2 nullable=1 is_null=0 */
# at 548
#181128 14:34:18 server id 1  end_log_pos 579 CRC32 0x872e2a9b 	Xid = 205
COMMIT/*!*/;
# at 579
#181128 14:34:39 server id 1  end_log_pos 644 CRC32 0x743032f0 	Anonymous_GTID	last_committed=2	sequence_number=3	rbr_only=yes
/*!50718 SET TRANSACTION ISOLATION LEVEL READ COMMITTED*//*!*/;
SET @@SESSION.GTID_NEXT= 'ANONYMOUS'/*!*/;
# at 644
#181128 14:34:39 server id 1  end_log_pos 715 CRC32 0xd3e617d0 	Query	thread_id=18	exec_time=0	error_code=0
SET TIMESTAMP=1543386879/*!*/;
BEGIN
/*!*/;
# at 715
#181128 14:34:39 server id 1  end_log_pos 763 CRC32 0x17fc2509 	Table_map: `crn`.`t` mapped to number 153
# at 763
#181128 14:34:39 server id 1  end_log_pos 806 CRC32 0x50336b49 	Delete_rows: table id 153 flags: STMT_END_F

BINLOG '
/zb+WxMBAAAAMAAAAPsCAAAAAJkAAAAAAAEAA2NybgABdAADAw/8AwoAAgcJJfwX
/zb+WyABAAAAKwAAACYDAAAAAJkAAAAAAAEAAgAD//wBAAAAAmRkSWszUA==
'/*!*/;
### DELETE FROM `crn`.`t`
### WHERE
###   @1=1 /* INT meta=0 nullable=1 is_null=0 */
###   @2='dd' /* VARSTRING(10) meta=10 nullable=1 is_null=0 */
###   @3=NULL /* BLOB/TEXT meta=2 nullable=1 is_null=1 */
# at 806
#181128 14:34:39 server id 1  end_log_pos 837 CRC32 0x7d4c641a 	Xid = 206
COMMIT/*!*/;
SET @@SESSION.GTID_NEXT= 'AUTOMATIC' /* added by mysqlbinlog */ /*!*/;
DELIMITER ;
# End of log file
/*!50003 SET COMPLETION_TYPE=@OLD_COMPLETION_TYPE*/;
/*!50530 SET @@SESSION.PSEUDO_SLAVE_MODE=0*/;
```

window1 (设置`binlog_row_image=MINIMAL`下数据记录情况)

```
mysql> set session binlog_row_image=minimal;
Query OK, 0 rows affected (0.00 sec)

mysql> show variables like 'binlog_row_image';
+------------------+---------+
| Variable_name    | Value   |
+------------------+---------+
| binlog_row_image | MINIMAL |
+------------------+---------+
1 row in set (0.00 sec)

# 做一个更新操作
mysql> update t set c2 = 'this 2' where id = 2;
Query OK, 1 row affected (0.02 sec)
Rows matched: 1  Changed: 1  Warnings: 0
```

window2 (查看日志记录情况,只记录更新所在行第三列修改前和修改后的值，修改前是空，修改后是this 2，没有记录第一列@1和第二列@2修改之后的值，这点区别)

```
### UPDATE `crn`.`t`
### WHERE
###   @1=2 /* INT meta=0 nullable=1 is_null=0 */
###   @2='bb' /* VARSTRING(10) meta=10 nullable=1 is_null=0 */
###   @3=NULL /* BLOB/TEXT meta=2 nullable=1 is_null=1 */
### SET
###   @3='this 2' /* BLOB/TEXT meta=2 nullable=1 is_null=0 */
# at 1074
#181128 14:47:04 server id 1  end_log_pos 1105 CRC32 0xdbf149fb 	Xid = 209
COMMIT/*!*/;
SET @@SESSION.GTID_NEXT= 'AUTOMATIC' /* added by mysqlbinlog */ /*!*/;
DELIMITER ;
# End of log file
/*!50003 SET COMPLETION_TYPE=@OLD_COMPLETION_TYPE*/;
/*!50530 SET @@SESSION.PSEUDO_SLAVE_MODE=0*/;
```


前面比较不够明显，做了以下比较，在相同的update操作下，前者设置为MINIMAL后者设置为FULL (区别在于更新行中某个字段，记录行所在的所有列的值还是某个字段所在的那一列的值)

window3 (区别对比)

```
BINLOG '
6Dn+WxMBAAAAMAAAAP0DAAAAAJkAAAAAAAEAA2NybgABdAADAw/8AwoAAgeR6gJP
6Dn+Wx8BAAAANQAAADIEAAAAAJkAAAAAAAEAAgAD/wT8AgAAAAJiYv4GAHRoaXMgMvmMPLU=
'/*!*/;
### UPDATE `crn`.`t`
### WHERE
###   @1=2 /* INT meta=0 nullable=1 is_null=0 */
###   @2='bb' /* VARSTRING(10) meta=10 nullable=1 is_null=0 */
###   @3=NULL /* BLOB/TEXT meta=2 nullable=1 is_null=1 */

# 区别 区别 区别！！！
### SET
###   @3='this 2' /* BLOB/TEXT meta=2 nullable=1 is_null=0 */
# at 1074
#181128 14:47:04 server id 1  end_log_pos 1105 CRC32 0xdbf149fb 	Xid = 209
COMMIT/*!*/;
# at 1105
#181128 14:55:05 server id 1  end_log_pos 1170 CRC32 0xc539e694 	Anonymous_GTID	last_committed=4	sequence_number=5	rbr_only=yes
/*!50718 SET TRANSACTION ISOLATION LEVEL READ COMMITTED*//*!*/;
SET @@SESSION.GTID_NEXT= 'ANONYMOUS'/*!*/;
# at 1170
#181128 14:55:05 server id 1  end_log_pos 1241 CRC32 0x09c7ef46 	Query	thread_id=18	exec_time=0	error_code=0
SET TIMESTAMP=1543388105/*!*/;
BEGIN
/*!*/;
# at 1241
#181128 14:55:05 server id 1  end_log_pos 1289 CRC32 0xe45a11c1 	Table_map: `crn`.`t` mapped to number 153
# at 1289
#181128 14:55:05 server id 1  end_log_pos 1360 CRC32 0xb712e8f7 	Update_rows: table id 153 flags: STMT_END_F

BINLOG '
yTv+WxMBAAAAMAAAAAkFAAAAAJkAAAAAAAEAA2NybgABdAADAw/8AwoAAgfBEVrk
yTv+Wx8BAAAARwAAAFAFAAAAAJkAAAAAAAEAAgAD///4AgAAAAJiYgYAdGhpcyAy+AIAAAACYmIJ
AHRoaXMgaXMgMvfoErc=
'/*!*/;
### UPDATE `crn`.`t`
### WHERE
###   @1=2 /* INT meta=0 nullable=1 is_null=0 */
###   @2='bb' /* VARSTRING(10) meta=10 nullable=1 is_null=0 */
###   @3='this 2' /* BLOB/TEXT meta=2 nullable=1 is_null=0 */

# 区别 区别 区别！！！
### SET
###   @1=2 /* INT meta=0 nullable=1 is_null=0 */
###   @2='bb' /* VARSTRING(10) meta=10 nullable=1 is_null=0 */
###   @3='this is 2' /* BLOB/TEXT meta=2 nullable=1 is_null=0 */
# at 1360
#181128 14:55:05 server id 1  end_log_pos 1391 CRC32 0xe32080b3 	Xid = 211
COMMIT/*!*/;
SET @@SESSION.GTID_NEXT= 'AUTOMATIC' /* added by mysqlbinlog */ /*!*/;
DELIMITER ;
# End of log file
/*!50003 SET COMPLETION_TYPE=@OLD_COMPLETION_TYPE*/;
/*!50530 SET @@SESSION.PSEUDO_SLAVE_MODE=0*/;
```


* NOBLOB的记录方式测试

window1 (不是TEXT类型的列被更新，不会被记录，其他与FULL类型相同)

```sql

# 设置为noblob类型
mysql> set session binlog_row_image=noblob;
Query OK, 0 rows affected (0.00 sec)

mysql> select * from t;
+------+------+-----------+
| id   | c1   | c2        |
+------+------+-----------+
|    2 | bb   | this is 2 |
|    3 | ee   | bbb       |
+------+------+-----------+
2 rows in set (0.00 sec)

mysql> desc t;
+-------+-------------+------+-----+---------+-------+
| Field | Type        | Null | Key | Default | Extra |
+-------+-------------+------+-----+---------+-------+
| id    | int(11)     | YES  |     | NULL    |       |
| c1    | varchar(10) | YES  |     | NULL    |       |
| c2    | text        | YES  |     | NULL    |       |
+-------+-------------+------+-----+---------+-------+
3 rows in set (0.00 sec)

# c1列为varchar类型
mysql> update t set c1 = 'noblob' where id = 3;
Query OK, 1 row affected (0.02 sec)
Rows matched: 1  Changed: 1  Warnings: 0

# c2列为TEXT类型
mysql> update t set c2 = 'text noblob' where id = 3;
Query OK, 1 row affected (0.02 sec)
Rows matched: 1  Changed: 1  Warnings: 0
```

window2 (未修改TEXT类型字段，将不会被记录)

```
8z7+Wx8BAAAAPQAAAGQGAAAAAJkAAAAAAAEAAgAD/wP4AwAAAAJlZQMAYmJi/AMAAAAGbm9ibG9i
c3NQyw==
'/*!*/;
### UPDATE `crn`.`t`
### WHERE
###   @1=3 /* INT meta=0 nullable=1 is_null=0 */
###   @2='ee' /* VARSTRING(10) meta=10 nullable=1 is_null=0 */
###   @3='bbb' /* BLOB/TEXT meta=2 nullable=1 is_null=0 */

# 区别 区别 区别！！！
### SET
###   @1=3 /* INT meta=0 nullable=1 is_null=0 */
###   @2='noblob' /* VARSTRING(10) meta=10 nullable=1 is_null=0 */
# at 1636
#181128 15:08:35 server id 1  end_log_pos 1667 CRC32 0xcf753edf 	Xid = 215
COMMIT/*!*/;
# at 1667
#181128 15:09:05 server id 1  end_log_pos 1732 CRC32 0x0ba186c7 	Anonymous_GTID	last_committed=6	sequence_number=7	rbr_only=yes
/*!50718 SET TRANSACTION ISOLATION LEVEL READ COMMITTED*//*!*/;
SET @@SESSION.GTID_NEXT= 'ANONYMOUS'/*!*/;
# at 1732
#181128 15:09:05 server id 1  end_log_pos 1803 CRC32 0x9c8e50e7 	Query	thread_id=18	exec_time=0	error_code=0
SET TIMESTAMP=1543388945/*!*/;
BEGIN
/*!*/;
# at 1803
#181128 15:09:05 server id 1  end_log_pos 1851 CRC32 0xae29444e 	Table_map: `crn`.`t` mapped to number 153
# at 1851
#181128 15:09:05 server id 1  end_log_pos 1929 CRC32 0xfdec443e 	Update_rows: table id 153 flags: STMT_END_F

BINLOG '
ET/+WxMBAAAAMAAAADsHAAAAAJkAAAAAAAEAA2NybgABdAADAw/8AwoAAgdORCmu
ET/+Wx8BAAAATgAAAIkHAAAAAJkAAAAAAAEAAgAD/wf4AwAAAAZub2Jsb2IDAGJiYvgDAAAABm5v
YmxvYgsAdGV4dCBub2Jsb2I+ROz9
'/*!*/;
### UPDATE `crn`.`t`
### WHERE
###   @1=3 /* INT meta=0 nullable=1 is_null=0 */
###   @2='noblob' /* VARSTRING(10) meta=10 nullable=1 is_null=0 */
###   @3='bbb' /* BLOB/TEXT meta=2 nullable=1 is_null=0 */

# 区别 区别 区别！！！
### SET
###   @1=3 /* INT meta=0 nullable=1 is_null=0 */
###   @2='noblob' /* VARSTRING(10) meta=10 nullable=1 is_null=0 */
###   @3='text noblob' /* BLOB/TEXT meta=2 nullable=1 is_null=0 */
# at 1929
#181128 15:09:05 server id 1  end_log_pos 1960 CRC32 0xf3e68b02 	Xid = 216
COMMIT/*!*/;
SET @@SESSION.GTID_NEXT= 'AUTOMATIC' /* added by mysqlbinlog */ /*!*/;
DELIMITER ;
# End of log file
/*!50003 SET COMPLETION_TYPE=@OLD_COMPLETION_TYPE*/;
/*!50530 SET @@SESSION.PSEUDO_SLAVE_MODE=0*/;
```

* 混合日志格式 `binlog_format = MIXED` (基于段格式日志和行格式日志折中的日志格式，严格来说不是一种独立的日志记录格式)

特点:

* 可以根据SQL语句由系统决定在基于段和基于行的日志格式中进行选择
* 数据量的大小由所执行的SQL语句决定

#### 如何选择二进制日志格式

建议使用： 

* `Binlog_format = mixed` or
* `Binlog_format = row`

使用row格式建议使用：

* `Binlog_row_image = minimal`
