#!/bin/bash

REPO_PATH=~
DIR_NAME="dev95"
if [ -n "$1" ]; then
	if [ "$1" == "jwc" ]; then
		DIR_NAME="jwc2ical"
	else
		echo "Not recognized para $1"
		exit 1
	fi
fi

if [ "$UID" -ne 1001 ]; then
	echo "Switch to dev-user to run this script!"
	exit 1
fi

rm /usr/lib/cgi-bin/$DIR_NAME/* -rf
rm /var/www/$DIR_NAME/* -rf

cp $REPO_PATH/$DIR_NAME/* /var/www/$DIR_NAME/ -R
mv /var/www/$DIR_NAME/cgi/* /usr/lib/cgi-bin/$DIR_NAME/ -R
rmdir /var/www/$DIR_NAME/cgi
