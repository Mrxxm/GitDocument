![](https://timgsa.baidu.com/timg?image&quality=80&size=b9999_10000&sec=1528116316391&di=d322ee2092988d0a4dd5ab1553df1383&imgtype=0&src=http%3A%2F%2Fwww.embeddedlinux.org.cn%2Fuploads%2Fallimg%2F161203%2F202440H38-0.png)

## Linux

**ssh远程登录**   
ssh 远程主机上的用户名@远程主机的IP地址或FQDN

```
# ssh root@47.93.233.9
```

ssh协议采取加密数据传输，相对比较安全，所以SSH服务器的默认配置允许root用户直接进行登录，这与传统的Telent登录方式不同。
    
用户类型：普通用户和超级用户（root）。基于安全的考虑，不建议直接使用root用户登录，建议首先以一个普通用户身份登录系统，当需要执行系统管理命令时可以使用” su -“命令（-表示同时切换用户工作环境）切换为超级用户身份，当执行完系统管理类命令时再使用eixt命令或logout命令退回到普通用户身份。此外，还可以使用sudo命令。
</br>  


## **Linux系统运行级别**  

Linux下的7个运行级别


运行级别  | 描述
------------- | -------------
运行级别 0  | 系统停机状态，系统默认运行级别不能设置为0，否则不能正常启动，机器关闭。
运行级别 1  | 单用户工作状态，root权限，用于系统维护，禁止远程登陆，就像Windows下的安全模式登录。
运行级别 2  | 多用户状态，没有NFS支持。
运行级别 3  | 完整的多用户模式，有NFS，登陆后进入控制台命令行模式。
运行级别 4  | 系统未使用，保留一般不用，在一些特殊情况下可以用它来做一些事情。例如在笔记本电脑的电池用尽时，可以切换到这个模式来做一些设置。
运行级别 5  | X11控制台，登陆后进入图形GUI模式，XWindow系统。
运行级别 6  | 系统正常关闭并重启，默认运行级别不能设为6，否则不能正常启动。运行init6机器就会重启。


**字符登录界面，默认运行级别为 3**  

**图形登录界面，默认运行级别为 5**    

**字符界面 -> 图形界面**  

`# startx &`

**查看当前系统的运行级别** 

```
# runlevel
# 输出: N 5
```

**用户可以使用如下命令切换运行级别** 

```
# init[0 1 2 3 4 5 6 S s]
# 例: init 2
```

**关机**

`# init 0`或
`# halt`  

**较安全的关机命令：**  登录被阻止，所有登录用户被通知系统将要关闭，所有的进程也会被通知系统将要关闭。

```
# shutdown 选项 参数
```

**选项**

选项  | 描述
------------- | -------------
-c  | 当执行“shutdown -h 11:50”指令时，只要按+键就可以中断关机的指令。
-f  | 重新启动时不执行fsck。
-F  | 重新启动时执行fsck。
-h  | 将系统关机。
-k  | 只是送出信息给所有用户，但不会实际关机。
-n  | 不调用init程序进行关机，而由shutdown自己进行。
-r  | shutdown之后重新启动。
-t<秒数>  | 送出警告信息和删除信息之间要延迟多少秒。

**参数**

* [时间]：设置多久时间后执行shutdown指令

* [警告信息]：要传送给所有登入用户的信息

**实例**

指定现在立即关机

`# shutdown -h now`

指定5分钟后关机，同时送出警告信息给登入用户

`# shutdown +5 "System will shutdown after 5 minutes"`

**重新启动**  

`# init 6` 或 `# reboot`  
</br>


## Shell和命令基础

Shell是系统的用户界面，提供了用户与内核进行交互操作的一种接口*(命令解释器）*。  
外层应用程序 -> 命令解释器Shell -> 系统核心 -> 硬件  

**Linux系统上的可执行文件的分类**  
Linux命令 -> 存放在/bin、/sbin目录下的命令  
内置命令 -> 出于效率的考虑，将一些常用命令的解释程序构造在Shell内部  
实用程序 -> 存放在/usr/bin、 /usr/sbin、/usr/share 、/usr/local/bin等目录下的实用程序或工具  
用户程序 -> 用户程序经过编译生成可执行文件后，也可作为Shell命令运行  
Shell脚本 -> 由Shell语言编写的批处理文件  

**Shell命令完成过程**  
  
用户输入命令 -> 提交给Shell -> 是否为内置命令 -> 是内置命令 -> 内核中的系统功能调用   
                                                                         用户输入命令 -> 提交给Shell -> 是否为内置命令 -> 是外部命令或实用程序 -> 在系统中查找该命令的文件并调入内存执行 -> 内核中的系统功能调用  
                                                                         
**内置命令**   
(如cd 、 exit等)都是内置命令，可以使用help命令查看内置命令的使用方法。

**外部命令**  
(如文件复制命令 cp)在/bin目录下的一个可执行文件。  

**RHEL/CentOS下的默认Shell是bash**  

**Shell的元字符**    
Shell中有一些特殊意义的字符，称为Shell元字符。

*代表任意字符串  
?代表任意字符  
[...]匹配任何包含在括号里的单字符  
/代表根目录或作为路径间隔符使用  
\转义字符  
\<Enter>续行符。可以使用续行符将一个命令行分写在多行上  
‘’在' ‘之间的字符都会被当做文字处理  
""在' ‘之间的字符都会被当做文字处理，并允许变量置换  

**实例：**  
列出当前目录下的首字符是a或b或c的所有文件  
`# ls [abc]*` 
 
列出当前目录下的首字符不是a或b或c的所有文件   
`# ls [!abc]*` 

列出当前目录下的首字符是字母的所有文件    
`# ls [a-zA-Z]*`

## 文件及Linux目录结构
**文件:** 字节序列。  
`*.so/*.ko/*.lib: 模板文件，库文件。`  
`*.sh/*.pl/*.rb: Shell/Perl/Ruby脚本文件。`  
`*.rpm: RPM包文件。`  
`*.tar: tar存档文件。`  
`*.Z/*.gz/*.bz2: 压缩文件。`  
`*.tar.gz/*.tgz/*.tar.bz2/*.tbz: 压缩后的tar包。`  
`*.lock: 用于表示某个程序或某种服务正在运行的锁文件。` 

## 目录和硬链接
将两个文件名（存储在其父目录的目录项中）指向硬盘上的一个存储空间，对两个文件中的任何一个的内容进行修改都会影响到另一个文件，这种链接关系称为硬链接。 
  
**由ln可以建立硬链接** 

```
ln hosts1 hosts2
```
如果删除其中的一个文件，就是删除了该文件和硬盘空间的指向关系，该硬盘空间不会释放，另外一个文件的内容也不会发生变化，但是目录详细信息中的链接数会减少。            

## 符号链接（软链接）

将一个文件指向另一个文件的文件名。  

**由ln -s命令来建立**  

```
ln -s hosts1 hosts2
```                  

如果删除hosts2 ， 对hosts并不产生任何影响。   
但是如果删除hosts， 那么hosts2成为死链接。 

## 设备文件
**用来访问硬件设备**  
设备文件存放在/dev目录下。 

## 套接字和命名管道
是Linux环境下实现进程间通信(IPC)的机制。   
套接字(socket)允许运行在不同计算机上的进程之间相互通信。     
命名管道(FIFO)文件允许运行在同一台计算机上的两个进程之间进行通信。  
    
**熟悉Linux的目录结构**  

![](https://images2015.cnblogs.com/blog/929540/201611/929540-20161123100618971-1899025714.png) 

/bin：
bin是Binary的缩写, 这个目录存放着最经常使用的命令。

/boot：
这里存放的是启动Linux时使用的一些核心文件，包括一些连接文件以及镜像文件。

/dev ：
dev是Device(设备)的缩写, 该目录下存放的是Linux的外部设备，在Linux中访问设备的方式和访问文件的方式是相同的。

/etc：
这个目录用来存放所有的系统管理所需要的配置文件和子目录。

/home：
用户的主目录，在Linux中，每个用户都有一个自己的目录，一般该目录名是以用户的账号命名的。

/lib：
这个目录里存放着系统最基本的动态连接共享库，其作用类似于Windows里的DLL文件。几乎所有的应用程序都需要用到这些共享库。

/lost+found：
这个目录一般情况下是空的，当系统非法关机后，这里就存放了一些文件。

/media：
linux系统会自动识别一些设备，例如U盘、光驱等等，当识别后，linux会把识别的设备挂载到这个目录下。

/mnt：
系统提供该目录是为了让用户临时挂载别的文件系统的，我们可以将光驱挂载在/mnt/上，然后进入该目录就可以查看光驱里的内容了。

/opt：
 这是给主机额外安装软件所摆放的目录。比如你安装一个ORACLE数据库则就可以放到这个目录下。默认是空的。

/proc：
这个目录是一个虚拟的目录，它是系统内存的映射，我们可以通过直接访问这个目录来获取系统信息。
这个目录的内容不在硬盘上而是在内存里，我们也可以直接修改里面的某些文件，比如可以通过下面的命令来屏蔽主机的ping命令，使别人无法ping你的机器：

`echo 1 > /proc/sys/net/ipv4/icmp_echo_ignore_all`  

/root：
该目录为系统管理员，也称作超级权限者的用户主目录。

/sbin：
s就是Super User的意思，这里存放的是系统管理员使用的系统管理程序。

/selinux：
 这个目录是Redhat/CentOS所特有的目录，Selinux是一个安全机制，类似于windows的防火墙，但是这套机制比较复杂，这个目录就是存放selinux相关的文件的。

/srv：
 该目录存放一些服务启动之后需要提取的数据。

/sys：
 这是linux2.6内核的一个很大的变化。该目录下安装了2.6内核中新出现的一个文件系统 sysfs 。

sysfs文件系统集成了下面3种文件系统的信息：针对进程信息的proc文件系统、针对设备的devfs文件系统以及针对伪终端的devpts文件系统。
该文件系统是内核设备树的一个直观反映。

当一个内核对象被创建的时候，对应的文件和目录也在内核对象子系统中被创建。

/tmp：
这个目录是用来存放一些临时文件的。

/usr：
 这是一个非常重要的目录，用户的很多应用程序和文件都放在这个目录下，类似于windows下的program files目录。

/usr/bin：
系统用户使用的应用程序。

/usr/sbin：
超级用户使用的比较高级的管理程序和系统守护程序。

/usr/src：内核源代码默认的放置目录。

/var：
这个目录中存放着在不断扩充着的东西，我们习惯将那些经常被修改的目录放在这个目录下。包括各种日志文件。

## Linux 常用操作命令 

* 查看包括隐含文件和目录  
`# ls -a` 

* 滚屏显示文件内容，并显示行号  
`# cat -n /etc/passwd`

* 从第十行分屏显示文件内容  
`# more +10 /etc/passwd` 

* 分屏显示文件内容  
`# less /etc/passwd` 

* 显示文件前四行的内容  
`# head -4 /etc/passwd`

* 显示文件后四行内容  
`# tail -4 /etc/passwd`

* 显示文件从第4行开始到结尾  
`# tail +4 /etc/passwd`

* 实时更新显示日志文件  
`# tail -10f /etc/passwd`

* 在文件中查找local字符串  
`# grep local hosts`

* 在多个指定文件中查找LOCAL(忽略大小写)  
`# grep -i LOCAL hosts server`

* 显示除#开始的行  
`# grep -v “^#” hosts`  

* 比较两个文件之间的差异  
`# diff hosts hosts2`

## 信息显示命令
**文件**  
stat 显示指定文件的相关信息  
file 显示指定文件的类型  
whereis 查找系统文件所在路径  

**系统**  
hostname 显示主机名称  
uname 显示操作系统信息  
dmesg 显示系统启动信息  
lsmod 显示系统加载的内核模块  
date 显示系统时间  
env 显示系统环境变量  
locale 显示当前语言变量  
curl icanhazip.com 查看公网IP 

## 文本编辑器 vi
:set nu 显示行号  
i 从光标所在位置开始插入文本  
I 将光标移动到当前行的首位，插入文本  
a 光标所在位置之后追加文本  
A 将光标移动到当前行的最后位，插入文本  
o 将光标所在行的下面新开一行，并将光标置于首位，插入文本  
O 将光标所在行的上面新开一行，并将···  
G 将光标移动到最后一行  
gg 将光标移动到第一行  
0 将光标移动到所在行首位  
$ 将光标移动到所在行最后一位  
h j k l 左下上右  

H M L 将光标移动到当前屏幕的上中下  

dd 删除一行  

yy 复制当前行  
yG 复制光标所在行 到最后一行   
p 粘贴  

/str 搜索  n 移动到下一个str的地方 N向上移动str的地方  

:q! 不保存退出Vi  

---

### netstat  
用于显示各种网络相关信息，如网络连接，路由表，接口状态 (Interface Statistics)，masquerade 连接，多播成员 (Multicast Memberships)  

列出所有端口  
`# netstat -a`

列出所有tcp端口  
`# netstat -at` 
 
列出所有udp端口   
`# netstat -au`

---

### df
用于显示磁盘分区上的可使用的磁盘空间。默认显示单位为KB。

---

### du
显示指定文件所占空间。  
`# du hosts`

---

### lsof

用于查看你进程开打的文件，打开文件的进程，进程打开的端口(TCP、UDP)。是十分方便的系统监视工具，因为lsof命令需要访问核心内存和各种文件，所以需要root用户执行

查看当前监听的TCP端口  
`# sudo lsof -nP -iTCP -sTCP:LISTEN`

---

### ps
命令用于报告当前系统的进程状态

---

### find 

列出当前目录及子目录下所有文件和文件夹  
`# find .`

在/home目录下查找以.txt结尾的文件名  
`# find /home -name "*.txt"`

同上，但忽略大小写  
`# find /home -iname "*.txt"`  

当前目录及子目录下查找所有以.txt和.pdf结尾的文件  
`# find . \( -name "*.txt" -o -name "*.pdf" \)`  
或  
`# find . -name "*.txt" -o -name "*.pdf"`  

匹配文件路径或者文件  
`# find /usr/ -path "*local*"`

基于正则表达式匹配文件路径  
`# find . -regex ".*\(\.txt\|\.pdf\)$"`

同上，但忽略大小写  
`# find . -iregex ".*\(\.txt\|\.pdf\)$"`

## SSH配置—Linux下实现免密码登录

**本地登远程服务器**

```
$ ssh root@47.93.233.9
Welcome to Ubuntu 16.04.2 LTS (GNU/Linux 4.4.0-62-generic x86_64)

 * Documentation:  https://help.ubuntu.com
 * Management:     https://landscape.canonical.com
 * Support:        https://ubuntu.com/advantage

Welcome to Alibaba Cloud Elastic Compute Service !

Last login: Tue Jun  5 19:43:47 2018 from 112.10.92.86
root@growthcoder:~# 

```

**下面开始我们的配置步骤**

1.本地生成秘钥

通过执行命令来生成我们需要的密钥  
`$ ssh-keygen -t rsa`

```
$ ssh-keygen -t rsa
Generating public/private rsa key pair.
Enter file in which to save the key (/Users/xuxiaomeng/.ssh/id_rsa): 
```

执行上面的命令时，我们直接按三次回车，之后会在用户的根目录下生成一个 .ssh 的文件夹，我们进入该文件夹下面并查看有哪些内容。

```
$ cd ~/.ssh
$ ll
total 24
-rw-------  1 xuxiaomeng  staff   1.6K Mar 12 10:13 id_rsa
-rw-r--r--  1 xuxiaomeng  staff   403B Mar 12 10:13 id_rsa.pub
-rw-r--r--  1 xuxiaomeng  staff   896B Apr 16 10:40 known_hosts
```

可能存在四个文件  
*authorized_keys: 存放远程免密登录的公钥,主要通过这个文件记录多台机器的公钥。*  
*id_rsa: 生成的私钥文件。*  
*id_rsa.pub: 生成的公钥文件。*  
*known_hosts: 已知的主机公钥清单。*  

2.远程密钥登录

三种方式

一、是通过 ssh-copy-id 命令  
通过 ssh-copy-id 命令设置。最后一个参数是我们要免密钥登录的服务器 ip 地址。

```
$ ssh-copy-id -i ~/.ssh/id_rsa.pub root@47.93.233.9
/usr/bin/ssh-copy-id: INFO: Source of key(s) to be installed: "/Users/xuxiaomeng/.ssh/id_rsa.pub"
/usr/bin/ssh-copy-id: INFO: attempting to log in with the new key(s), to filter out any that are already installed
/usr/bin/ssh-copy-id: INFO: 1 key(s) remain to be installed -- if you are prompted now it is to install the new keys
root@47.93.233.9's password: 
Permission denied, please try again.
root@47.93.233.9's password: 

Number of key(s) added:        1

Now try logging into the machine, with:   "ssh 'root@47.93.233.9'"
and check to make sure that only the key(s) you wanted were added.
```

*二、是通过 scp 命令*   
*三、是手工复制*  

以上步骤，我们就完成了免密钥登录，下面我们来进行验证。

```
$ ssh root@47.93.233.9
Welcome to Ubuntu 16.04.2 LTS (GNU/Linux 4.4.0-62-generic x86_64)

 * Documentation:  https://help.ubuntu.com
 * Management:     https://landscape.canonical.com
 * Support:        https://ubuntu.com/advantage

Welcome to Alibaba Cloud Elastic Compute Service !

Last login: Tue Jun  5 19:43:47 2018 from 112.10.92.86
root@growthcoder:~# 

```

**原理**

* ssh 客户端向 ssh 服务器端发送连接请求。  
* ssh 服务器端发送一个随机的信息。  
* ssh 客户端使用本地的私钥对服务器端发送过来的信息进行加密。  
* ssh 客户端向服务器端发送加密过后的信息。  
* ssh 服务器端使用公钥对该信息进行解密。  
* 若解密之后的信息和之前发送的信息匹配，则信任客户端，否则不信任。  