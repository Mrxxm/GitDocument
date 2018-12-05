## Mysql复制性能优化

影响主从延迟的因素 (Mysql复制是异步的) [只有事务在主库上执行完，并记录到二进制日志中，从库才能读取二进制日志中已经执行完的事务，并把这些事务保存在从库中的中继日志中，然后从库的sql线程才能从中继日志中读取事件]

![](https://img3.doubanio.com/view/photo/l/public/p2541672882.jpg)

* 主库写入二进制日志的时间
* 解决：控制主库的事务大小，分割大事务
* 二进制日志传输时间
* 解决：使用MIXED日志格式或基于行的复制将设置`set binlog_row_image=minimal;`
* 默认情况下从只有一个sql线程，主上并发的修改在从上变成了串行
* 解决：使用多线程复制 (5.6以后)

在Mysql5.7中可以按照逻辑时钟的方式来分配SQL线程

如何配置多线程复制

* `stop slave` 停止复制链路
* `set global slave_parallel_type='logical_clock';` 链路并发方式为逻辑始终的方式，默认是database
* `set global slave_parallel_workers=4` 设置复制线程的数量，决定并发处理的线程数
* `start slave` 启动复制链路

window2 (从服务器配置多线程复制在GTID复制链路基础上)

```sql
mysql> show processlist;
+----+-------------+-----------+------+---------+--------+--------------------------------------------------------+------------------+
| Id | User        | Host      | db   | Command | Time   | State                                                  | Info             |
+----+-------------+-----------+------+---------+--------+--------------------------------------------------------+------------------+
| 11 | system user |           | NULL | Connect | 118232 | Waiting for master to send event                       | NULL             |
| 12 | system user |           | NULL | Connect | 117597 | Slave has read all relay log; waiting for more updates | NULL             |
| 93 | root        | localhost | NULL | Query   |      0 | starting                                               | show processlist |
+----+-------------+-----------+------+---------+--------+--------------------------------------------------------+------------------+
3 rows in set (0.00 sec)

mysql> stop slave;
Query OK, 0 rows affected (0.01 sec)

mysql> show status slave\G
ERROR 1064 (42000): You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near 'slave' at line 1
mysql> show slave status \G
*************************** 1. row ***************************
               Slave_IO_State:
                  Master_Host: 118.25.93.119
                  Master_User: repl
                  Master_Port: 3306
                Connect_Retry: 60
              Master_Log_File: mysql-bin.000006
          Read_Master_Log_Pos: 674
               Relay_Log_File: mysqld-relay-bin.000002
                Relay_Log_Pos: 887
        Relay_Master_Log_File: mysql-bin.000006
             Slave_IO_Running: No
            Slave_SQL_Running: No
              Replicate_Do_DB:
          Replicate_Ignore_DB:
           Replicate_Do_Table:
       Replicate_Ignore_Table:
      Replicate_Wild_Do_Table:
  Replicate_Wild_Ignore_Table:
                   Last_Errno: 0
                   Last_Error:
                 Skip_Counter: 0
          Exec_Master_Log_Pos: 674
              Relay_Log_Space: 1095
              Until_Condition: None
               Until_Log_File:
                Until_Log_Pos: 0
           Master_SSL_Allowed: No
           Master_SSL_CA_File:
           Master_SSL_CA_Path:
              Master_SSL_Cert:
            Master_SSL_Cipher:
               Master_SSL_Key:
        Seconds_Behind_Master: NULL
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
      Slave_SQL_Running_State:
           Master_Retry_Count: 86400
                  Master_Bind:
      Last_IO_Error_Timestamp:
     Last_SQL_Error_Timestamp:
               Master_SSL_Crl:
           Master_SSL_Crlpath:
           Retrieved_Gtid_Set: 4ff52c78-8bd1-11e8-a522-5254005ecd94:1-2
            Executed_Gtid_Set: 4ff52c78-8bd1-11e8-a522-5254005ecd94:1-2,
bd747f33-e64e-11e8-b299-5254004aa1aa:1-7
                Auto_Position: 1
         Replicate_Rewrite_DB:
                 Channel_Name:
           Master_TLS_Version:
1 row in set (0.00 sec)

mysql> show variables like 'slave_parallel_type';
+---------------------+----------+
| Variable_name       | Value    |
+---------------------+----------+
| slave_parallel_type | DATABASE |
+---------------------+----------+
1 row in set (0.00 sec)

mysql> set global slave_parallel_type='logical_clock';
Query OK, 0 rows affected (0.00 sec)

mysql> show variables like 'slave_parallel_workers';
+------------------------+-------+
| Variable_name          | Value |
+------------------------+-------+
| slave_parallel_workers | 0     |
+------------------------+-------+
1 row in set (0.00 sec)

mysql> set global slave_parallel_workers=4;
Query OK, 0 rows affected (0.00 sec)

mysql> start slave;
Query OK, 0 rows affected (0.04 sec)

mysql> show slave status \G
*************************** 1. row ***************************
               Slave_IO_State: Waiting for master to send event
                  Master_Host: 118.25.93.119
                  Master_User: repl
                  Master_Port: 3306
                Connect_Retry: 60
              Master_Log_File: mysql-bin.000006
          Read_Master_Log_Pos: 674
               Relay_Log_File: mysqld-relay-bin.000003
                Relay_Log_Pos: 454
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
          Exec_Master_Log_Pos: 674
              Relay_Log_Space: 1395
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
           Retrieved_Gtid_Set: 4ff52c78-8bd1-11e8-a522-5254005ecd94:1-2
            Executed_Gtid_Set: 4ff52c78-8bd1-11e8-a522-5254005ecd94:1-2,
bd747f33-e64e-11e8-b299-5254004aa1aa:1-7
                Auto_Position: 1
         Replicate_Rewrite_DB:
                 Channel_Name:
           Master_TLS_Version:
1 row in set (0.00 sec)

mysql> show processlist;
+-----+-------------+-----------+------+---------+------+--------------------------------------------------------+------------------+
| Id  | User        | Host      | db   | Command | Time | State                                                  | Info             |
+-----+-------------+-----------+------+---------+------+--------------------------------------------------------+------------------+
|  93 | root        | localhost | NULL | Query   |    0 | starting                                               | show processlist |
|  95 | system user |           | NULL | Connect |   30 | Waiting for master to send event                       | NULL             |
|  96 | system user |           | NULL | Connect |   29 | Slave has read all relay log; waiting for more updates | NULL             |
|  97 | system user |           | NULL | Connect |   30 | Waiting for an event from Coordinator                  | NULL             |
|  98 | system user |           | NULL | Connect |   30 | Waiting for an event from Coordinator                  | NULL             |
|  99 | system user |           | NULL | Connect |   30 | Waiting for an event from Coordinator                  | NULL             |
| 100 | system user |           | NULL | Connect |   30 | Waiting for an event from Coordinator                  | NULL             |
+-----+-------------+-----------+------+---------+------+--------------------------------------------------------+------------------+
7 rows in set (0.00 sec)
```