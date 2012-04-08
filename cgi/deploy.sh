#!/bin/bash

REPO_PATH=~
DIR_NAME="jwc2ical"
if [ -n "$1"]; then
	if [ "$1" -eq "test"]; then
		$DIR_NAME = "dev95";
	else
		echo "Not recognized para $1\n";
		exit 1;
	fi
fi

rm /usr/lib/cgi-bin/$DIR_NAME/* -rf
rm /var/www/$DIR_NAME/* -rf

cp $REPO_PATH/$DIR_NAME/* /var/www/$DIR_NAME/ -R
mv /var/www/$DIR_NAME/cgi/* /usr/lib/cgi-bin/$DIR_NAME/ -R
rmdir /var/www/$DIR_NAME/cgi
