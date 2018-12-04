## 基于GTID的复制

基于GTID的复制是从mysql5.6版本开始支持的一种新的复制方式。

#### 基于日志复制特点

* 从服务器连接到主服务器，并告诉主服务器从哪个二进制日志的偏移量进行增量同步
* 如果指定错误就会造成遗漏或重复

#### 基于GTID的复制的特点

* 从服务器告诉主服务器，从服务器已执行事务的GTID值
* 主库会把没有从库没有执行事务的GTID值发送到从库上
* 基于GTID值的复制，可以保证同一个事务只在指定的从库执行一次


#### 什么是GTID

* 即全局事务ID，其保证为每一个在主上提交的事务在复制集群中可以生成一个唯一的ID
* `GTID = source_id:transaction_id` (`source_id`:就是执行事务的主库的`server_uuid`, 这个`server_uuid`是mysql在首次启动时自动生成的，并保存在数据库的数据目录中，有一个叫`auto.conf`文件中保存着`server_uuid`，mysql的算法保证每一个mysql实例的`server_uuid`值是不同的，而事务ID则是从1开始自增的序列，保证事务是在主库上执行的第几个事务)


#### 基于GTID复制的步骤

在主服务器上建立复制账号 (注意：不要手动在从服务器上建立相同的账号，因为基于GTID的复制会把所有没有在从服务器上执行的事务，都同步到从上去，所以再手动创建账号会在启动复制链路时出现错误)

* 在主DB服务器上建立复制账号 (`CREATE USER 'repl'@'IP段' identified by 'password';`) 
* 授权语句 (`GRANT REPLICATION SLAVE ON *.* TO 'repl'@'IP段';`)

配置主库 (与日志点复制区别所在)

* `bin_log=/usr/local/mysql/log/mysql-bin` (都是基于二进制日志进行的，这里的日志存放在mysql的log目录下，而不是用默认的数据目录存放)
* `server_id=100`
* `gtid_mode=on` 决定是否启用GTID这种模式，启用后二进制日志中会额外记录GTID事务的标识符 
* `enforce_gtid_consistency=on` 强制GTID一致性，保证事务的安全 (启用参数后以下命令无法使用：1.`create table ... select` 2.在事务中使用`Create temporary table`建立临时表 3.使用关联更新事务表和非事务表)
* `log-slave-updates=on` 在从服务器上记录主服务器传送过来的修改日志所使用的(在mysql5.7中该限制被去除)

配置从库 (如果是集群中要使用GTID复制那每台服务器上都要开启GTID模式)

* `server_id=101`
* `relay_log=/usr/local/mysql/log/relay_log`
* `gtid_mode=on`
* `enforce_gtid_consistency=on`
* `log-slave-updates=on`
* `read_only=on` [建议]
* `master_info_repository = TABLE` [建议]
* `relay_log_info_repository = TABLE` [建议]

上面两个建议的参数指定了从服务器连接主服务器的信息和中继日志的相关信息默认是存储在文件中的，可以让他们记录在相应目录中

初始化从服务器 (启用GTID，记录的就不是二进制日志的文件名和偏移量了，记录备份时最后的事务的GTID值)

* `mysqldump --master-data=2 --single-transaction` 逻辑备份 (会对表进行加锁，影响并发性。为了保证事务的一致性，对Innodb表进行备份时，需要加上`--single-transaction`参数，如果是混合使用了Innodb和MyISAM则加上`--local-auto-tables`。另一个重要的参数就是`--master-data`用于记录在备份时主库当前二进制日志文件偏移量的信息，只有记录这两个值，我们才可以在从库上使用`change master to`命令来启动主从复制的链路)
* `xtrabackup --slave-info` 对Innodb存储引擎的数据库支持热备，对不是Innodb存储引擎的数据库同样会锁表，参数`--slave-info`记录主库的二进制日志和数据偏移量


启动基于GTID的复制

* `CHANGE MASTER TO MASTER_HOST = 'master_host_ip', MASTER_USER = 'repl', MASTER_PASSWORD = 'Password', 
MASTER_AUTO_POSITION = 1;`

window1 (查看信息)

```sql
# 选择mysql数据库
mysql> use mysql;
Reading table information for completion of table and column names
You can turn off this feature to get a quicker startup with -A

Database changed

# 查看已经创建的用户
mysql> select user,host from user;
+---------------+----------------+
| user          | host           |
+---------------+----------------+
| repl          | 119.29.196.*   |
| fred_link     | 127.0.0.1      |
| mysql.session | localhost      |
| mysql.sys     | localhost      |
| root          | localhost      |
| sb            | localhost      |
+---------------+----------------+
6 rows in set (0.00 sec)

# 查看授权
mysql> show grants for repl@'119.29.196.*';
+-----------------------------------------------------------+
| Grants for repl@119.29.196.*                              |
+-----------------------------------------------------------+
| GRANT REPLICATION SLAVE ON *.* TO 'repl'@'119.29.196.*'   |
+-----------------------------------------------------------+
1 row in set (0.00 sec)
```

window1 (编译配置)

```
# 查看mysql数据目录
[root@kenrou log]# pwd
/var/lib/mysql/log

# 接下来配置参数
# vim /etc/my.cnf

# 添加以下两个参数
# log_bin
  log_bin=/var/lib/mysql/log/mysql-bin
  server-id=1
  gtid_mode=on
  enforce_gtid_consistency=on
  master_info_repository=TABLE
  relay_log_info_repository=TABLE
  
# 重启mysql服务 (通过杀死进程重新启动)
sudo /usr/sbin/mysqld --user=root

# 初始化数据
[root@kenrou ~]# mysqldump --single-transaction --master-data --triggers --routines crn  -uroot -p >> all2.sql
Enter password:
Warning: A partial dump from a server that has GTIDs will by default include the GTIDs of all transactions, even those that changed suppressed parts of the database. If you don't want to restore GTIDs, pass --set-gtid-purged=OFF. To make a complete dump, pass --all-databases --triggers --routines --events.
[root@kenrou ~]# ll
总用量 29800
-rw-r--r--  1 root   root   10973760 11月 22 15:44 1.sql
-rw-r--r--  1 root   root       2376 12月  3 11:13 all2.sql

# 传输到从服务器
[root@kenrou ~]# scp all2.sql root@119.29.196.*:/root
root@119.29.196.*'s password:
all2.sql

# 重新导出数据 (设置--set-gtid-purged=off 参数,备份时不带 SET @@GLOBAL.GTID_PURGED='9110b22b-ad94-11e8-85a4-00163e0856f7:1-74067' 这行数据)
[root@kenrou ~]# mysqldump --single-transaction --master-data --triggers --routines --set-gtid-purged=off crn  -uroot -p >> all3.sql

# 传输到从服务器
[root@kenrou ~]# scp all3.sql root@119.29.196.*:/root
root@119.29.196.*'s password:
all3.sql

# 配置完成 查看线程 (从服务器开启链路之后)
mysql> show processlist;
+----+------+----------------------+------+------------------+------+---------------------------------------------------------------+------------------+
| Id | User | Host                 | db   | Command          | Time | State                                                         | Info             |
+----+------+----------------------+------+------------------+------+---------------------------------------------------------------+------------------+
|  7 | repl | 119.29.196.*:419** | NULL | Binlog Dump GTID |  424 | Master has sent all binlog to slave; waiting for more updates | NULL             |
|  8 | root | localhost            | NULL | Query            |    0 | starting                                                      | show processlist |
+----+------+----------------------+------+------------------+------+---------------------------------------------------------------+------------------+
2 rows in set (0.00 sec)
```

window2 (关闭基于日志点主从复制)

```
mysql> stop slave;
Query OK, 0 rows affected (0.00 sec)

mysql> show slave status \G
*************************** 1. row ***************************
               Slave_IO_State:
                  Master_Host: 118.25.93.*
                  Master_User: repl
                  Master_Port: 3306
                Connect_Retry: 60
              Master_Log_File: mysql-bin.000006
          Read_Master_Log_Pos: 3267
               Relay_Log_File: mysqld-relay-bin.000002
                Relay_Log_Pos: 1136
        Relay_Master_Log_File: mysql-bin.000006
             Slave_IO_Running: No
            Slave_SQL_Running: No
```

window2 (从库配置)

```
# 查看日志存放目录
[root@HUGE_DICK_MAN log]# pwd
/var/lib/mysql/log
[root@HUGE_DICK_MAN relay_log]# pwd
/var/lib/mysql/relay_log

# 接下来配置参数
# vim /etc/my.cnf

# 添加以下两个参数
# log_bin
  log_bin=/var/lib/mysql/log/mysql-bin
  server-id=2
  relay_log=/var/lib/mysql/relay_log/mysqld-relay-bin
  gtid_mode=on
  enforce_gtid_consistency=on
  master_info_repository=TABLE
  relay_log_info_repository=TABLE
  
# 重启mysql服务 (通过杀死进程重新启动)
sudo /usr/sbin/mysqld --user=root

# 初始化数据
[root@HUGE_DICK_MAN ~]# ll
总用量 176220
-rw-r--r-- 1 root root      2376 12月  3 11:14 all2.sql

# 导入数据:报错
[root@HUGE_DICK_MAN ~]# mysql -uroot -p crn < all2.sql
Enter password:
ERROR 1840 (HY000) at line 24: @@GLOBAL.GTID_PURGED can only be set when @@GLOBAL.GTID_EXECUTED is empty.

# 从上面的报错内容来看，出现错误是在备份文件的 24 行中，那么我们看看 24 行是什么内容
# all2.sql 文件
# SET @@GLOBAL.GTID_PURGED='9110b22b-ad94-11e8-85a4-00163e0856f7:1-74067';

# 查看GTID_EXECUTED (GTID_EXECUTED不为空)
mysql> show global variables like '%GTID%';
+----------------------------------+------------------------------------------+
| Variable_name                    | Value                                    |
+----------------------------------+------------------------------------------+
| binlog_gtid_simple_recovery      | ON                                       |
| enforce_gtid_consistency         | ON                                       |
| gtid_executed                    | bd747f33-e64e-11e8-b299-5254004aa1aa:1-2 |
| gtid_executed_compression_period | 1000                                     |
| gtid_mode                        | ON                                       |
| gtid_owned                       |                                          |
| gtid_purged                      |                                          |
| session_track_gtids              | OFF                                      |
+----------------------------------+------------------------------------------+
8 rows in set (0.03 sec)

# 原因：因为要设置GLOBAL.GTID_PURGED这个值时报错了，原因是GLOBAL.GTID_EXECUTED只有这个值为空时才能设置GLOBAL.GTID_PURGED值

# 重新导入数据
[root@HUGE_DICK_MAN ~]# mysql -uroot -p crn < all3.sql
Enter password:

# 配置主从复制
mysql> change master to master_host='118.25.93.*',
    -> master_user='repl',
    -> master_password='Xxm&******',
    -> master_auto_position=1;
Query OK, 0 rows affected, 2 warnings (0.06 sec)

# 启动复制：报错
mysql> start slave;
ERROR 1872 (HY000): Slave failed to initialize relay log info structure from the repository

# 查看日志
[root@HUGE_DICK_MAN ~]# tail -200f /var/log/mysqld.log | grep 'ERROR'
2018-12-03T03:40:47.614801Z 10 [ERROR] Slave SQL for channel '': Slave failed to initialize relay log info structure from the repository, Error_code: 1872

# 解决方法
mysql> reset slave;
Query OK, 0 rows affected (0.10 sec)

# slave reset执行候做了这样几件事： 
1、删除slave_master_info，slave_relay_log_info两个表中数据； 
2、删除所有relay log文件，并重新创建新的relay log文件； 
3、不会改变gtid_executed 或者 gtid_purged的值

# 重新配置主从复制
mysql> change master to master_host='118.25.93.*',
    -> master_user='repl',
    -> master_password='Xxm&******',
    -> master_auto_position=1;
Query OK, 0 rows affected, 2 warnings (0.07 sec)

# 开启复制
mysql> start slave;
Query OK, 0 rows affected (0.01 sec)

# 查看状态
mysql> show slave status\G;
*************************** 1. row ***************************
               Slave_IO_State: Waiting for master to send event
                  Master_Host: 118.25.93.*
                  Master_User: repl
                  Master_Port: 3306
                Connect_Retry: 60
              Master_Log_File: mysql-bin.000006
          Read_Master_Log_Pos: 414
               Relay_Log_File: mysqld-relay-bin.000002
                Relay_Log_Pos: 627
        Relay_Master_Log_File: mysql-bin.000006
             Slave_IO_Running: Yes
            Slave_SQL_Running: Yes
              Replicate_Do_DB:
          Replicate_Ignore_DB:
           Replicate_Do_Table:
       Replicate_Ignore_Table:
      Replicate_Wild_Do_Table:
  Replicate_Wild_Ignore_Table:
                   Last_Errno: 0
                   Last_Error:
                 Skip_Counter: 0
          Exec_Master_Log_Pos: 414
              Relay_Log_Space: 835
              Until_Condition: None
               Until_Log_File:
                Until_Log_Pos: 0
           Master_SSL_Allowed: No
           Master_SSL_CA_File:
           Master_SSL_CA_Path:
              Master_SSL_Cert:
            Master_SSL_Cipher:
               Master_SSL_Key:
        Seconds_Behind_Master: 0
Master_SSL_Verify_Server_Cert: No
                Last_IO_Errno: 0
                Last_IO_Error:
               Last_SQL_Errno: 0
               Last_SQL_Error:
  Replicate_Ignore_Server_Ids:
             Master_Server_Id: 1
                  Master_UUID: 4ff52c78-8bd1-11e8-a522-5254005ecd94
             Master_Info_File: mysql.slave_master_info
                    SQL_Delay: 0
          SQL_Remaining_Delay: NULL
      Slave_SQL_Running_State: Slave has read all relay log; waiting for more updates
           Master_Retry_Count: 86400
                  Master_Bind:
      Last_IO_Error_Timestamp:
     Last_SQL_Error_Timestamp:
               Master_SSL_Crl:
           Master_SSL_Crlpath:
           Retrieved_Gtid_Set: 4ff52c78-8bd1-11e8-a522-5254005ecd94:1
            Executed_Gtid_Set: 4ff52c78-8bd1-11e8-a522-5254005ecd94:1,
bd747f33-e64e-11e8-b299-5254004aa1aa:1-7
                Auto_Position: 1
         Replicate_Rewrite_DB:
                 Channel_Name:
           Master_TLS_Version:
1 row in set (0.00 sec)

ERROR:
No query specified

# 查询结果发现多了一条数据 (当时情况是，从库先关闭日志点复制，然后主库插入一条数据。然后正常配置GTID复制，直到开启GTID复制后，数据库中多了一条数据。)
mysql> select * from t;
+------+---------+---------------+
| id   | c1      | c2            |
+------+---------+---------------+
|    2 | bb      | this is 2     |
|    3 | noblob  | text noblob   |
|    4 | DoubleX | HUGE_DICK_MAN |
|    5 | DoubleX | HUGE_DICK_MAN |
|    6 | a       | c             |
|    7 | b       | d             |
|    7 | b       | d             |
+------+---------+---------------+
7 rows in set (0.00 sec)
```

优点：

* 可以很方便的进行故障转移 (基于全局唯一的事务标识符)
* 从库不会丢失主库上的任何修改 (这里大概就是从库多一条数据的原因所在) 

缺点： 

* 故障处理比较复杂
* 对执行的SQL有一定的限制

选择复制模式要考虑的问题

* 所使用的Msql版本(GTID复制时Mysql5.6版本之后的功能)
* 复制架构及主从切换的方式
* 所使用的高可用管理组件
* 对应用的支持程度