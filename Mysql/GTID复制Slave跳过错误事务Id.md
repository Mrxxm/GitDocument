---
layout: mysql
title: GTID复制Slave跳过错误事务Id
date: 2018-12-08 15:26:51
tags:
categories:
- Mysql
---

#### GTID报错跳过分析解决(`Slave_SQL_Running: No`)

**根据`slave status`提供的信息** 

`Slave_IO_Running：`连接到主库，并读取主库的日志到本地，生成本地日志文件

`Slave_SQL_Running：`读取本地日志文件，并执行日志里的SQL命令。

<!--more-->

```
# 查看状态
mysql> show slave status\G

···
Relay_Log_File: mysqld-relay-bin.000002
Relay_Log_Pos: 367
Relay_Master_Log_File: mysql-bin.000003
Slave_IO_Running: Yes
Slave_SQL_Running: No
···
# 报错信息
Last_Error: Error 'Can't create database 'crn'; database exists' on query. Default database: 'crn'. Query: 'create database crn'
···
```

**对于`slave status`中的信息，注意如下两行,根据上面信息去查看日志** 

```
Retrieved_Gtid_Set: e7cc1373-f9e4-11e8-9e5e-5254005ecd94:1-6
Executed_Gtid_Set: 6112161c-fab2-11e8-b107-525400c4f3fb:1-11
# Retrieved_Gtid_Set是slave接收到的事务的信息，
# Executed_Gtid_Set是slave已经执行的slave的信息
```

**查看中继日志**

```
# 查看日志
[root@0CM relay_log]# mysqlbinlog -vv mysqld-relay-bin.000002
···


SET @@SESSION.GTID_NEXT= 'e7cc1373-f9e4-11e8-9e5e-5254005ecd94:1'/*!*/;
# 报错信息产生，因为从库中已经有该数据库
···
create database crn
···


SET @@SESSION.GTID_NEXT= 'e7cc1373-f9e4-11e8-9e5e-5254005ecd94:2'/*!*/;
···
create table t(id int, c1 varchar(10))
···


SET @@SESSION.GTID_NEXT= 'e7cc1373-f9e4-11e8-9e5e-5254005ecd94:3'/*!*/;
···
### INSERT INTO `crn`.`t`
### SET
###   @1=1 /* INT meta=0 nullable=1 is_null=0 */
###   @2='aa' /* VARSTRING(10) meta=10 nullable=1 is_null=0 */
### INSERT INTO `crn`.`t`
### SET
###   @1=2 /* INT meta=0 nullable=1 is_null=0 */
###   @2='bb' /* VARSTRING(10) meta=10 nullable=1 is_null=0 */
···


SET @@SESSION.GTID_NEXT= 'e7cc1373-f9e4-11e8-9e5e-5254005ecd94:4'/*!*/;
···
### UPDATE `crn`.`t`
### WHERE
###   @1=1 /* INT meta=0 nullable=1 is_null=0 */
###   @2='aa' /* VARSTRING(10) meta=10 nullable=1 is_null=0 */
### SET
###   @1=1 /* INT meta=0 nullable=1 is_null=0 */
###   @2='dd' /* VARSTRING(10) meta=10 nullable=1 is_null=0 */
···


SET @@SESSION.GTID_NEXT= 'e7cc1373-f9e4-11e8-9e5e-5254005ecd94:5'/*!*/;
···
### INSERT INTO `crn`.`t`
### SET
###   @1=3 /* INT meta=0 nullable=1 is_null=0 */
###   @2='f' /* VARSTRING(10) meta=10 nullable=1 is_null=0 */
···


SET @@SESSION.GTID_NEXT= 'e7cc1373-f9e4-11e8-9e5e-5254005ecd94:6'/*!*/;
···
### INSERT INTO `crn`.`t`
### SET
###   @1=5 /* INT meta=0 nullable=1 is_null=0 */
###   @2='gg' /* VARSTRING(10) meta=10 nullable=1 is_null=0 */
···
```

**从库数据库中数据**

```sql
mysql> select * from t;
+------+------+
| id   | c1   |
+------+------+
|    1 | dd   |
|    2 | bb   |
+------+------+
2 rows in set (0.00 sec)
```

**解决**

报错原因是因为数据表已经存在，看日志发现数据库中数据对应`e7cc1373-f9e4-11e8-9e5e-5254005ecd94:1-4`这4句已经执行过了，所以忽略这4句，我们保留5，6这两句。

```
mysql> stop slave;
Query OK, 0 rows affected (0.00 sec)

mysql> set gtid_next='e7cc1373-f9e4-11e8-9e5e-5254005ecd94:4';
Query OK, 0 rows affected (0.00 sec)

mysql> BEGIN; COMMIT;
Query OK, 0 rows affected (0.00 sec)

Query OK, 0 rows affected (0.03 sec)

mysql> SET SESSION GTID_NEXT = AUTOMATIC;
Query OK, 0 rows affected (0.00 sec)

mysql> set gtid_next='e7cc1373-f9e4-11e8-9e5e-5254005ecd94:3';
Query OK, 0 rows affected (0.00 sec)

mysql> BEGIN; COMMIT;
Query OK, 0 rows affected (0.00 sec)

Query OK, 0 rows affected (0.01 sec)

mysql> SET SESSION GTID_NEXT = AUTOMATIC;
Query OK, 0 rows affected (0.00 sec)

mysql> set gtid_next='e7cc1373-f9e4-11e8-9e5e-5254005ecd94:2';
Query OK, 0 rows affected (0.00 sec)

mysql> BEGIN; COMMIT;
Query OK, 0 rows affected (0.00 sec)

Query OK, 0 rows affected (0.01 sec)

mysql> SET SESSION GTID_NEXT = AUTOMATIC;
Query OK, 0 rows affected (0.00 sec)

mysql> set gtid_next='e7cc1373-f9e4-11e8-9e5e-5254005ecd94:1';
Query OK, 0 rows affected (0.00 sec)

mysql> BEGIN; COMMIT;
Query OK, 0 rows affected (0.00 sec)

Query OK, 0 rows affected (0.01 sec)

mysql> SET SESSION GTID_NEXT = AUTOMATIC;
Query OK, 0 rows affected (0.00 sec)
```

**重新成功开启,服务正常开启，且数据正确执行**

```
mysql> select * from t;
+------+------+
| id   | c1   |
+------+------+
|    1 | dd   |
|    2 | bb   |
|    3 | f    |
|    5 | gg   |
+------+------+
4 rows in set (0.00 sec)
```


* 报错

说明从库在应用当前事物Id的时候出错了，从库上无法应用某一个事物编号，数据要跳过一个错误，正常操作如下：

```
stop slave;

# 在session里设置gtid_next，即跳过这个GTID
set gtid_next='6d257f5b-5e6b-11e8-b668-5254003de1b6:n'; 

# 设置空事物
begin;
commit;

# 恢复事物号
set gtid_next='AUTOMATIC';
start slave;
```

* 关闭`change master`

`change master to master_host='';`
`change master to master_auto_position=0;`
