## Innodb存储引擎的特性(2)

阻塞和死锁

* 什么是阻塞 (阻塞是因为不同锁之间兼容性的关系，在有些时刻，一个事务中的锁要等到另一个事务中的锁的释放，它所占用的资源，这就形成了阻塞)
* 阻塞是为了确保事务的可以并发且正常的运行 (当系统中存在大量的阻塞，则表明存在大量的问题) [大量阻塞：在一个频繁更新的表上出现慢查询，还有可能出现在表备份时，对一个频繁访问的资源加上了排它锁]

* 什么是死锁 (是指两个或两个以上的事务在执行过程中相互占用了对方等待的资源，而产生一种异常)
* 阻塞只是占用了被阻塞事务的资源，而死锁是产生事务的多个事务之间相互占有对方等待的资源 (死锁可由系统自动处理)


Innodb状态检查

* `show engine innodb status`

```sql
mysql> show engine innodb status\G;
*************************** 1. row ***************************
  Type: InnoDB
  Name:
Status:
=====================================
2018-11-16 15:30:49 0x7fa2c0195700 INNODB MONITOR OUTPUT
=====================================

# 最近23秒的一个统计信息
Per second averages calculated from the last 23 seconds

# 后台进程
-----------------
BACKGROUND THREAD
-----------------

# Innodb主进程循环次数
srv_master_thread loops: 237 srv_active, 0 srv_shutdown, 5326018 srv_idle

# log刷新的次数
srv_master_thread log flush and writes: 5326170

# 信号信息
----------
SEMAPHORES
----------
OS WAIT ARRAY INFO: reservation count 526
OS WAIT ARRAY INFO: signal count 521
RW-shared spins 0, rounds 1000, OS waits 499
RW-excl spins 0, rounds 90, OS waits 3
RW-sx spins 0, rounds 0, OS waits 0
Spin rounds per wait: 1000.00 RW-shared, 90.00 RW-excl, 0.00 RW-sx

# 事务信息
------------
TRANSACTIONS
------------
Trx id counter 3922
Purge done for trx's n:o < 3922 undo n:o < 0 state: running but idle
History list length 38
LIST OF TRANSACTIONS FOR EACH SESSION:

# 事务
---TRANSACTION 421812421969744, not started
0 lock struct(s), heap size 1136, 0 row lock(s)

# 事务
---TRANSACTION 421812421970656, not started
0 lock struct(s), heap size 1136, 0 row lock(s)

# 文件IO信息 (读写进程 默认4个读进程 4个写进程)
--------
FILE I/O
--------
I/O thread 0 state: waiting for completed aio requests (insert buffer thread)
I/O thread 1 state: waiting for completed aio requests (log thread)
I/O thread 2 state: waiting for completed aio requests (read thread)
I/O thread 3 state: waiting for completed aio requests (read thread)
I/O thread 4 state: waiting for completed aio requests (read thread)
I/O thread 5 state: waiting for completed aio requests (read thread)
I/O thread 6 state: waiting for completed aio requests (write thread)
I/O thread 7 state: waiting for completed aio requests (write thread)
I/O thread 8 state: waiting for completed aio requests (write thread)
I/O thread 9 state: waiting for completed aio requests (write thread)
Pending normal aio reads: [0, 0, 0, 0] , aio writes: [0, 0, 0, 0] ,
 ibuf aio reads:, log i/o's:, sync i/o's:
Pending flushes (fsync) log: 0; buffer pool: 0
782 OS file reads, 2609 OS file writes, 1993 OS fsyncs
0.00 reads/s, 0 avg bytes/read, 0.00 writes/s, 0.00 fsyncs/s

# 缓存信息
-------------------------------------
INSERT BUFFER AND ADAPTIVE HASH INDEX
-------------------------------------
Ibuf: size 1, free list len 0, seg size 2, 0 merges
merged operations:
 insert 0, delete mark 0, delete 0
discarded operations:
 insert 0, delete mark 0, delete 0
Hash table size 34673, node heap has 2 buffer(s)
Hash table size 34673, node heap has 1 buffer(s)
Hash table size 34673, node heap has 1 buffer(s)
Hash table size 34673, node heap has 1 buffer(s)
Hash table size 34673, node heap has 2 buffer(s)
Hash table size 34673, node heap has 1 buffer(s)
Hash table size 34673, node heap has 0 buffer(s)
Hash table size 34673, node heap has 2 buffer(s)
0.00 hash searches/s, 0.00 non-hash searches/s

# 日志信息
---
LOG
---
Log sequence number 5208598
Log flushed up to   5208598
Pages flushed up to 5208598
Last checkpoint at  5208589
0 pending log flushes, 0 pending chkp writes
1251 log i/o's done, 0.00 log i/o's/second

# 缓存池 内存信息
----------------------
BUFFER POOL AND MEMORY
----------------------
Total large memory allocated 137428992
Dictionary memory allocated 264287
Buffer pool size   8191
Free buffers       7453
Database pages     728
Old database pages 286
Modified db pages  0
Pending reads      0
Pending writes: LRU 0, flush list 0, single page 0
Pages made young 0, not young 0
0.00 youngs/s, 0.00 non-youngs/s
Pages read 682, created 46, written 1110
0.00 reads/s, 0.00 creates/s, 0.00 writes/s
No buffer pool page gets since the last printout
Pages read ahead 0.00/s, evicted without access 0.00/s, Random read ahead 0.00/s
LRU len: 728, unzip_LRU len: 0
I/O sum[0]:cur[0], unzip sum[0]:cur[0]

# 行的操作信息
--------------
ROW OPERATIONS
--------------
0 queries inside InnoDB, 0 queries in queue
0 read views open inside InnoDB
Process ID=2029, Main thread ID=140337145333504, state: sleeping

# 插入233行 更新2行 删除4行
Number of rows inserted 233, updated 2, deleted 4, read 36711
0.00 inserts/s, 0.00 updates/s, 0.00 deletes/s, 0.00 reads/s
----------------------------
END OF INNODB MONITOR OUTPUT
============================

1 row in set (0.00 sec)

ERROR:
No query specified
```

适用场景

* Innodb适合于大多数OLTP应用 (支持全文索引，空间函数)