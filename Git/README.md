![](https://ss0.bdstatic.com/70cFuHSh_Q1YnxGkpoWK1HF6hhy/it/u=3926926408,3148912474&fm=27&gp=0.jpg)

# Git指令

## 创建 

* 创建一个新的仓库  
`$ git init`

* 复制一个已创建的仓库  
`$ git clone https://github.com/Mrxxm/GitDocument.git`

## 本地修改

* 显示工作路径下全部已修改的文件  
`$ git status`

* 显示与上次提交版本文件的不同  
`$ git diff`

* 显示某个文件与上次提交版本文件的修改
`$ git diff Git/README.md`

* 把当前所有修改添加到下次提交中  
`$ git add .`

* 指定某个文件提交  
`$ git add Git/README.md`

* 提交本地所有修改  
`$ git commit -m "描述本次提交的文字"`

## 提交历史

* 查看提交记录  
`$ git log`

## 分支与标签

* 显示所有分支(远程和本地)  
`$ git branch -av`

* 显示本地分支  
`$ git branch`

* 切换当前分支  
`$ git checkout <branch>`

* 创建新分支(基于远程分支)  
`$ git checkout -b <new branch>` 

* 删除本地分支  
`$ git branch -d <branch>` 

* 给当前的提交打标签  
`$ git tag <tag-name>`

## 更新与发布

* 列出当前配置的远程端  
`$ git remote -v`

* 显示远程端信息  
`$ git remote show <remote>`

* 添加新的远程端  
`$ git remote add <shortname> <url>`

**下载远程端的所有改动到本地**  

* 自动合并到当前    
`$ git pull <remote> <branch>`

* 将本地版本发布到远程端  
`$ git push <remote> <branch>`

* 删除远程分支  
`$ git branch -dr <remote/branch>` 

* 发布标签  
`$ git push --tags` 

## 合并与重置

* 将分支合并到当前  
`$ git merge <branch>`

**将当前版本重置到分支** 
 
* 请勿重置已发布的提交  
`$ git rebase <branch>`

* 退出重置  
`$ git rebase --abort`

* 解决冲突后继续重置  
`$ git rebase --continue`

## 撤销

* 放弃工作目录下的所有修改  
`$ git reset --hard HEAD`  
或  
`$ git checkout .`

* 放弃某个文件的所有本地修改  
`$ git checkout HEAD <file>`


## 实例

![](https://cdn.liaoxuefeng.com/cdn/files/attachments/001384907702917346729e9afbf4127b6dfbae9207af016000/0)

create new repository

```
$ git init
$ git add README.md
$ git commit -m "first commit”
这里跟的是ssh地址，在网页上创建仓库后，会随之生成。
$ git remote add origin https://github.com/Mrxxm/1.git       
$ git push -u origin master
```

```
1.git status             查看当前仓库状态
2.git add                把文件加到暂存区
3.git commit -m ""       把文件提交到暂存区，并增加说明
  git log                查看提交记录
4.git push origin master 把文件推到远程仓库
5.git pull origin master 把文件从远程仓库拉下来
```

cancel add

```
$ git status
$ git add .
$ git reset HEAD xxx 取消暂存
```

cancel commit

```
$ git status
$ git add .
$ git commit -m "..."
$ git log
$ git reset --hard (commit id)  回滚到具体某个版本
$ 需要注意的是一旦回滚到之前某个版本，其之后的版本将消失
```

tag

```
$ git checkout develop
$ git pull
$ git tag                     查看已有tag
$ git tag 1.1.1
$ git push origin 1.1.1
```

stash

```
$ git stash 
$ git stash pop
$ git stash list
$ git stash drop xxx
```

pull origin branch

```
$ git pull origin <branch>
```

set remote

```
# 设置ssh地址
➜  open git:(develop) git remote set-url origin git@coding.codeages.net:qiqiuyun/platform-site.git

# 显示为ssh地址
➜  open git:(develop) git remote -v
origin	git@coding.codeages.net:qiqiuyun/platform-site.git (fetch)
origin	git@coding.codeages.net:qiqiuyun/platform-site.git (push)

# 显示为http地址
➜  api git:(develop) ✗ git remote -v
origin	http://coding.codeages.net/qiqiuyun/api.git (fetch)
origin	http://coding.codeages.net/qiqiuyun/api.git (push)
```

set name & email

```
➜  GitDocument git:(master) ✗ git config user.name 
Mrxxm
➜  GitDocument git:(master) ✗ git config user.email
362190221@qq.com

# 设置当前git用户邮箱
➜  GitDocument git:(master) ✗ git config --local user.name 'Mrxxm'
➜  GitDocument git:(master) ✗ git config --local user.email '362190221@qq.com'

# 设置全局git用户邮箱
➜  GitDocument git:(master) ✗ git config --global user.name 'xuxiaomeng'
➜  GitDocument git:(master) ✗ git config --global user.email 'xuxiaomeng@howzhi.com'

```

Create a new repository

```
➜  git clone git@coding.codeages.net:customization/54762-rongen-userimporter-plugin.git
➜  cd 54762-rongen-userimporter-plugin
➜  touch README.md
➜  git add README.md
➜  git commit -m "add README"
➜  git push -u origin master
```

Existing folder

```
➜  cd existing_folder
➜  git init
➜  git remote add origin git@coding.codeages.net:customization/54762-rongen-userimporter-plugin.git
➜  git add .
➜  git commit -m "Initial commit"
➜  git push -u origin master
```

Existing Git repository

```
➜  cd existing_repo
➜  git remote rename origin old-origin
➜  git remote add origin git@coding.codeages.net:customization/54762-rongen-userimporter-plugin.git
➜  git push -u origin --all
➜  git push -u origin --tags
```

### 使用脚本来改变某个repo的Git历史

```
注意： 执行这段脚本会重写 repo 所有协作者的历史。完成以下操作后，任何 fork 或 clone 的人必须获取重写后的历史并把所有本地修改 rebase 入重写后的历史中。
```

在执行这段脚本前，你需要准备的信息：

1. Mac、Linux下打开Terminal，Windows下打开命令提示符（command prompt）。
2. 给你的repo创建一个全新的clone。

	```
	➜ git clone --bare https://github.com/user/repo.git
	➜ cd repo.git
	```
3. 创建脚本文件，并添加权限。
	
	```
	# 创建文件
	➜ touch git.sh
	
	# 赋予权限
	➜ sudo chmod +x git.sh
	```

4. 复制粘贴脚本，并根据你的信息修改以下变量：旧的Email地址，正确的用户名，正确的邮件地址。
	
	```
	#!/bin/sh
	git filter-branch --env-filter '
	OLD_EMAIL="旧的Email地址"
	CORRECT_NAME="正确的用户名"
	CORRECT_EMAIL="正确的邮件地址"
	if [ "$GIT_COMMITTER_EMAIL" = "$OLD_EMAIL" ]
	then
	    export GIT_COMMITTER_NAME="$CORRECT_NAME"
	    export GIT_COMMITTER_EMAIL="$CORRECT_EMAIL"
	fi
	if [ "$GIT_AUTHOR_EMAIL" = "$OLD_EMAIL" ]
	then
	    export GIT_AUTHOR_NAME="$CORRECT_NAME"
	    export GIT_AUTHOR_EMAIL="$CORRECT_EMAIL"
	fi
	' --tag-name-filter cat -- --branches --tags
	```
	
5. 执行脚本。

	```
	➜ ./git.sh 
		Rewrite 3d23691450493c94cca5e24f731e6bfa742f2e46 (13/26) (1 seconds passed, remaining 1 predicted)    
		Ref 'refs/heads/develop' was rewritten
		Ref 'refs/heads/master' was rewritten
	``` 
	
6. 用`git log`命令看看新 Git 历史有没有错误。
7. 把正确历史 push 到 Github。

	```
	git push --force --tags origin 'refs/heads/*'
	```
	
8. 删掉刚刚临时创建的 clone。

	```
	cd ..
	rm -rf repo.git
	```
	
### github提示Permission denied (publickey)

1. 查看秘钥(默认秘钥为gitlab使用，重新生成github对应秘钥)

	```
	➜  .ssh ll
	total 48
	-rw-r--r--  1 xuxiaomeng  staff    97B Jul 27 09:26 config
	-rw-------  1 xuxiaomeng  staff   1.6K Jul 26 17:30 github
	-rw-r--r--  1 xuxiaomeng  staff   398B Jul 26 17:30 github.pub
	-rw-------  1 xuxiaomeng  staff   1.6K Mar 12 10:13 id_rsa
	-rw-r--r--  1 xuxiaomeng  staff   403B Mar 12 10:13 id_rsa.pub
	-rw-r--r--  1 xuxiaomeng  staff   2.7K Jul 23 11:02 known_hosts
	```
2. 配置config

	```
	#github
	Host github.com
     HostName github.com
     User git
     IdentityFile ~/.ssh/github
	```
3. 测试

	```
	➜  .ssh ssh -T git@github.com
	Hi Mrxxm! You've successfully authenticated, but GitHub does not provide shell access.
	```

### 恢复小卒子以后遇到的问题并解决

```
# push报错
➜  JAVACODE git:(master) ✗ git push
To https://github.com/Mrxxm/JAVACODE.git
 ! [rejected]        master -> master (fetch first)
error: failed to push some refs to 'https://github.com/Mrxxm/JAVACODE.git'
hint: Updates were rejected because the remote contains work that you do
hint: not have locally. This is usually caused by another repository pushing
hint: to the same ref. You may want to first integrate the remote changes
hint: (e.g., 'git pull ...') before pushing again.
hint: See the 'Note about fast-forwards' in 'git push --help' for details.

# 解决方式
➜  JAVACODE git:(master) ✗ git pull --allow-unrelated-histories 
remote: Counting objects: 199, done.
remote: Compressing objects: 100% (130/130), done.
remote: Total 199 (delta 64), reused 199 (delta 64), pack-reused 0
Receiving objects: 100% (199/199), 39.25 KiB | 10.00 KiB/s, done.
Resolving deltas: 100% (64/64), done.
From https://github.com/Mrxxm/JAVACODE
 + 85b3bdf...26bd6e4 master     -> origin/master  (forced update)
Merge made by the 'recursive' strategy.

# 成功push
➜  JAVACODE git:(master) ✗ git push
Counting objects: 114, done.
Delta compression using up to 4 threads.
Compressing objects: 100% (71/71), done.
Writing objects: 100% (114/114), 15.36 KiB | 3.07 MiB/s, done.
Total 114 (delta 43), reused 79 (delta 38)
remote: Resolving deltas: 100% (43/43), completed with 8 local objects.
To https://github.com/Mrxxm/JAVACODE.git
   26bd6e4..ef7796a  master -> master
```

