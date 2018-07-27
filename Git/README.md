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



