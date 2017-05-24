#!/usr/bin/env bash

debconf-set-selections <<< 'mysql-server mysql-server/root_password password rootpass'
debconf-set-selections <<< 'mysql-server mysql-server/root_password_again password rootpass'

sudo apt-get update
sudo apt-get -y install git mysql-server-5.7 mysql-common apache2 php7.0 php7.0-mysql libapache2-mod-php php-mcrypt php7.0-gd build-essential libmysqlclient-dev

# size of swapfile in megabytes
swapsize=2000

# does the swap file already exist?
grep -q "swapfile" /etc/fstab

# if not then create it
if [ $? -ne 0 ]; then
  echo 'swapfile not found. Adding swapfile.'
  fallocate -l ${swapsize}M /swapfile
  chmod 600 /swapfile
  mkswap /swapfile
  swapon /swapfile
  echo '/swapfile none swap defaults 0 0' >> /etc/fstab
else
  echo 'swapfile found. No changes made.'
fi

if ! [ -L /opt/livenote ]; then
  rm -rf /opt/livenote
  git clone https://github.com/ThePhiladelphiaOrchestra/iNotes-Server.git /opt/livenote
  cp /opt/livenote-configs/apache/livenote.conf /etc/apache2/sites-available/

  a2ensite livenote
  a2dissite 000-default
  service apache2 restart
fi

if [ ! -f /var/log/databasesetup ]; then
	echo "CREATE USER 'inotes'@'localhost' IDENTIFIED BY 'inotes'" | mysql --defaults-extra-file=/etc/mysql/debian.cnf
  echo "CREATE DATABASE content" | mysql --defaults-extra-file=/etc/mysql/debian.cnf
  echo "GRANT ALL ON content.* TO 'inotes'@'localhost'" | mysql --defaults-extra-file=/etc/mysql/debian.cnf
  echo "CREATE DATABASE content_dev" | mysql --defaults-extra-file=/etc/mysql/debian.cnf
  echo "GRANT ALL ON content_dev.* TO 'inotes'@'localhost'" | mysql --defaults-extra-file=/etc/mysql/debian.cnf
  echo "flush privileges" | mysql --defaults-extra-file=/etc/mysql/debian.cnf

  mysql --defaults-extra-file=/etc/mysql/debian.cnf content < /opt/livenote/database/CurrentConcert.sql
  mysql --defaults-extra-file=/etc/mysql/debian.cnf content < /opt/livenote/database/Strauss,\ Don\ Juan_\ Don\ Juan.sql
  mysql --defaults-extra-file=/etc/mysql/debian.cnf content < /opt/livenote/database/currentMeasure.sql

  touch /var/log/databasesetup
fi

if [ ! -f /var/log/multicastsetup ]; then
  cd /opt/livenote/multicast-server/transmission
  cc -D _BSD_SOURCE -o multicast-server MulticastServerPush.c `mysql_config --cflags --libs`

  cp /opt/livenote-configs/systemd/multicast-server.service /etc/systemd/system/multicast-server.service
  systemctl enable multicast-server
  service multicast-server start
  
  touch /var/log/multicastsetup
fi