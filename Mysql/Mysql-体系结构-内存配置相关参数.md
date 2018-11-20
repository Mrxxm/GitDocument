## 内存配置相关参数

内存配置相关参数

* 确定可以使用的内存上限
* 确定Mysql的每个连接使用的内存 

以下参数是为mysql每个连接分配内存，不宜过大

---
* `sort_buffer_size` 排序缓存区的尺寸，mysql在查询时，排序操作时，才会为每个缓冲区分配大小，mysql会为其分配这个参数指定大小的全部内存。
* `join_buffer_size` 连接缓存区的尺寸，定义每个线程连接缓存区的大小，如果一张表关联了多张表，那么会为每个关联分配一个连接缓冲。
* `read_buffer_size` 对一个mysiam表进行全表扫描时，所分配读缓存池大小，mysql只有在有查询需要时，才会为该缓存分配内存，同样会分配该参数指定内存大小的全部内存，这个参数大小一定要是4k的倍数。
* `read_rnd_buffer_size` 索引缓存区的大小，在有查询需要时，才会为该缓冲池分配内存，并且只会分配需要内存的大小，而不是参数指定的大小。
*  [以上每个参数都是为每个线程所分配的，如果有100个连接，会分配100倍的内存]

---

* 确定需要为操作系统保留多少内存
* 如何为每个缓存池分配内存
* `Innodb_buffer_pool_size` 定义Innodb缓存池的大小，Innodb不仅要缓存索引，还要缓存数据，Innodb还使用缓存池来帮组延迟写入，这样就能合并多个写入操作，一起顺序的写入到磁盘，Innodb性能严重依赖缓冲池。

Innodb缓存池的大小 (系统中都使用Innodb表) [Mysql手册中建议为服务器内存的75%以上]

```
总内存 - (每个线程所需的内存 * 连接数) - 系统保留内存
```
Innodb缓存池的内存总量，如果超过了Innodb表的总数据大小 + 索引所占用的空间大小，那就没有意义了，`Innodb_buffer_pool_size`配置需要重启才会生效，`Innodb_buffer_pool_size`配置的越大，关闭时就需要更多的时间，把数据从缓存池中刷新到磁盘。


* `key_buffer_size` 这个配置的缓存池主要是为了myisam缓存池

查询myisam表索引所占用空间大小

```
select sum(index_length) from information_schema.tables where engine = 'myisam'
```