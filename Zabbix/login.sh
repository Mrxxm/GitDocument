#! /bin/bash

filesource="loginsource.log"

file="login.log"

cd /tmp

if [ ! -f "$filesource" ]; then

  	touch "$filesource"

	last | tee /tmp/"$filesource"

elif [ ! -f "$file" ]; then

	touch "$file"
fi

last | tee /tmp/"$file"

file1=/tmp/"$filesource"
file2=/tmp/"$file"

touch /tmp/ssh.log

diff $file1 $file2 

if [[ $? = 0 ]];then

    rm /tmp/"$file"

    echo 0

   	echo 0 > /tmp/ssh.log
else
    diff $file1 $file2

    rm /tmp/"$filesource"

    rm /tmp/"$file"

    cd /tmp

    touch "$filesource"

	last | tee /tmp/"$filesource"

	echo 1

	echo 1 > /tmp/ssh.log
fi





