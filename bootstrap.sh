#!/usr/bin/env bash

debconf-set-selections <<< 'mysql-server mysql-server/root_password password rootpass'
debconf-set-selections <<< 'mysql-server mysql-server/root_password_again password rootpass'

sudo apt-get update
sudo apt-get -y install git mysql-server-5.7 mysql-common apache2 php7.0 php7.0-mysql libapache2-mod-php php-mcrypt php7.0-gd

if ! [ -L /var/livenote ]; then
  rm -rf /var/livenote
  git clone https://github.com/ThePhiladelphiaOrchestra/iNotes.git /var/livenote
  ln -fs /var/livenote/web /var/www/html
  ln -fs /var/livenote/CreateContent /var/www/html
  service apache2 restart
fi

if [ ! -f /var/log/databasesetup ]; then
	echo "CREATE USER 'inotes'@'localhost' IDENTIFIED BY 'inotes'" | mysql --defaults-extra-file=/etc/mysql/debian.cnf
    echo "CREATE DATABASE content" | mysql --defaults-extra-file=/etc/mysql/debian.cnf
    echo "GRANT ALL ON content.* TO 'inotes'@'localhost'" | mysql --defaults-extra-file=/etc/mysql/debian.cnf
    echo "flush privileges" | mysql --defaults-extra-file=/etc/mysql/debian.cnf

    mysql --defaults-extra-file=/etc/mysql/debian.cnf content < /var/livenote/database/CurrentConcert.sql
    mysql --defaults-extra-file=/etc/mysql/debian.cnf content < /var/livenote/database/Strauss,\ Don\ Juan_\ Don\ Juan.sql
    mysql --defaults-extra-file=/etc/mysql/debian.cnf content < /var/livenote/database/currentMeasure.sql

    touch /var/log/databasesetup
fi