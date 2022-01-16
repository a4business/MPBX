#!/bin/bash
########
##### Multitenant PBX:  installation steps 
##### Centos 8
########

#speed:
n=3

# Disable Security #
setenforce 0

yum groupinstall core base 'Development Tools' -y

if [ `rpm -qa|grep remi-release|wc -l` -eq 0 ];  then
 echo " ###   Install epel-release and remi-release" && sleep $n
 rpm -Uvh http://dl.fedoraproject.org/pub/epel/epel-release-latest-8.noarch.rpm
 rpm -Uvh http://rpms.famillecollet.com/enterprise/remi-release-8.rpm 
fi

### PowerTool repo,  install DNF plugins package:
dnf -y install dnf-plugins-core
dnf config-manager --set-enabled powertools
### 


yum install -y gcc gcc-c++ unixODBC-devel libiodbc-devel yum-utils bison mysql-devel mysql-server tftp-server httpd make ncurses-devel libtermcap-devel sendmail sendmail-cf caching-nameserver sox newt-devel libxml2-devel libtiff-devel audiofile-devel gtk2-devel subversion kernel-devel git subversion kernel-devel crontabs cronie cronie-anacron wget vim libtool sqlite-devel unixODBC libuuid-devel binutils-devel xmlstarlet opus opus-devel libedit-devel openssl-devel libevent libevent-devel libedit-devel libxml2-devel sqlite-devel curl-devel unixODBC-devel certbot mod_ssl iptables tcpdump ngrep

perl -pi -e "s/enforcing/disabled/g"  /etc/selinux/config


## Install Statically linked ffmpeg:
if [ ! -f /usr/bin/ffmpeg ]; then
   echo -n " ### Install ffmpeg static" && read -p '  [enter]' next
   FILE="ffmpeg-release-amd64-static.tar.xz"
   LINK="https://johnvansickle.com/ffmpeg/releases/ffmpeg-release-amd64-static.tar.xz"
   [ ! -f /usr/bin/ffmpeg ] && wget ${LINK} &&  tar -xJf ${FILE} && cp ./ffmpeg-*-amd64-static/ffmpeg  /usr/bin/ffmpeg
fi


if [ ! -f /usr/bin/sox ]; then
   echo -n " ### Install SOx from Sources:" && read -p '  [enter]' next
   wget https://kent.dl.sourceforge.net/project/sox/sox/14.4.2/sox-14.4.2.tar.bz2 && tar -xvjf  sox-14.4.2.tar.bz2 && cd sox-14.4.2 && ./configure && make && make install
fi


if [ ! -f /usr/bin/php ]; then
 echo -n " ### Install PHP ( 5.x ) " && read -p '  [enter]' next
 yum install -y php56 php56-php-curl php56-php-ldap php56-php-fileinfo php56-php-zip php56-php-fileinfo php56-php-xml php56-php-mbstring php56-php-process
 php56-pear channel-update pear.php.net && php56-pear install db-1.7.14
fi


if [ -f /usr/sbin/mysqld ] ; then
  echo  -n " ### Install mysql-server:" && read -p '  [enter]' next
  yum install mysql-server  mysql-devel  -y 
  yum install php56-php-mysqlnd perl-DBD-MySQL  cyrus-sasl-sql postfix -y
  service mysqld start
  /usr/bin/mysql_secure_installation
  wget https://repo.mysql.com//mysql80-community-release-el8-1.noarch.rpm
  rpm -Uvh mysql80-community-release-el8-1.noarch.rpm && yum install -y mysql-connector-odbc
fi
 


if [ ! -f /usr/sbin/asterisk ]; then
  VER=18 
 #VER=16
  echo ' ### Install Asterisk From Sources ( recommended to keep sources on the server  for future bug/patches  )' && sleep $n
   [ ! -f asterisk-${VER}-current.tar.gz ] && wget http://downloads.asterisk.org/pub/telephony/asterisk/asterisk-${VER}-current.tar.gz
   tar -xvzf asterisk-${VER}-current.tar.gz  && cd asterisk-${VER}.*
   ./configure --with-pjproject-bundled --with-jansson-bundled 
   menuselect/menuselect --enable res_config_mysql  menuselect.makeopts && menuselect/menuselect --enable cdr_mysql  menuselect.makeopts
   make menuselect  ### Make sure res_config_odbc , cdr_mysql,  cdr_odbc,  res_odbc are enabled.
   make && make install && make samples && make config
   [ $VER = 18 ] && perl -pi -e "s/noload = chan_sip.so/;noload = chan_sip.so/" /etc/asterisk/modules.conf
fi  
  


####### Install WEB files form this Remote:#######################################
 
 cd /var/www/html
 git clone https://git.a4business.com/george/mpbx.git ./pbx
 git clone https://git.a4business.com/george/crm.git ./crm
 curl -sS https://getcomposer.org/installer | php
 mv composer.phar /usr/local/bin/composer 
 cd /var/www/html/ && composer install
 
## Make forlder for Text2Speech cache:
 mkdir -p /tts && chmod 777 /tts



##  Link Asterisk config files
  for CONF in func_odbc.conf macros.include conferences.include;  do ln -sf /var/www/html/core/${CONF} /etc/asterisk/${CONF}; done
  cd /etc/asterisk && for CONF in extensions.tenants inbound.include internal.include ivrmenus.include outbound.include queues.include res_parking.include ringgroups.include sip-register.tenants sip.tenants tenants.include; do touch $CONF; chown apache.apache $CONF; done
  echo "#include  res_parking.include" >>  /etc/asterisk/res_parking.conf


### Create Databas ###
 ## To get tmeporary pass:          grep 'temporary password' /var/log/mysqld.log
 ## or reset root:
 ##         sudo mysqld_safe --skip-grant-tables &
 ##         alter user 'root'@'localhost' IDENTIFIED BY 'p@ssw0rd';
 ##         /etc/init.d/mysqld restart
      read -p " Provide Mysql root password to setup DB(type help for reset info):" P
      [ "${P}" = "help" ] && echo -e "To get myslq root after mysqld install:   grep 'temporary password' /var/log/mysqld.log\n\t To reset mysql root: \n\t\tsudo mysqld_safe --skip-grant-tables\n\t\techo alter user 'root'@'localhost' IDENTIFIED BY 'p@ssw0rd'; | mysql\n\t\t/etc/init.d/mysqld restart\n" && exit
      export MYSQL_PWD=${P}

      echo 'create database mpbx ;'| mysql -p${P} 
      echo 'create user mpbx_web@`localhost` identified by "P@ssw0rd123" ;'| mysql -p${P} 
      echo 'grant all privileges on mpbx.* to mpbx_web@`localhost` '| mysql -p${P} 

  ## Create database structure 
      cat /var/www/html/pbx/core/proc/mpbx.sql | mysql -u root -p${P} mpbx 2>/dev/null 
      cat /var/www/html/pbx/core/proc/mpbx-initial-data.sql | mysql -u root -p${P} mpbx 2>/dev/null
      cd /var/www/html/pbx/core/proc && ./install ${P} 2>/dev/null


### COnfigure ODBC connection
## First: make sure the Mysql Driver name used is mentioned in :  /etc/odbcinst.ini  and Librarry is in place 
## For example:   perl -pi -e "s/libmyodbc5.so/libmyodbc8a.so/g" /etc/odbcinst.ini

[ $(cat /etc/odbc.ini|grep PBX|wc -l) -eq 0 ] && cat <<EOF >> /etc/odbc.ini
[mpbx]
Description = PBX MySQL ODBC
Driver = MySQL ODBC 8.0 Unicode Driver
Socket = /var/lib/mysql/mysql.sock
User = mpbx_web
Password = P@ssw0rd123
Database = mpbx
Option = 3
EOF

##  NOTE: after setting up odbc.ini,  Make sure you can connect by running:   isql mpbx  


 
   ################ Create  ssl certificates  ( required for webrtc (wss/dtls) and for SIP/tls ) 
   ### Use your Domain Name there, domain must be resolvable to the current centos server  
   read -p ' CERTBOT: Obtaining TLS/SSL certs, type the domain name(type no to skip): ' DOMAIN
   if [ "${DOMAIN:-no}" != "no" ]; then
	[ ! -d /etc/letsencrypt/live/${DOMAIN} ] && certbot -d ${DOMAIN} certonly --standalone
	if [ -d /etc/letsencrypt/live/${DOMAIN} ]; then
        	ln -sf /etc/letsencrypt/live/${DOMAIN} /etc/asterisk/keys
		cd /etc/asterisk/keys
		cat privkey.pem fullchain.pem   > TLS.pem
	fi
   fi
   ##NOTE: after ssl certificate renew (every 90 days), execute the command to update asterisk file:  cd /etc/asterisk/keys && cat privkey.pem fullchain.pem   > TLS.pem
   ## Commercial SSL convert into PEM:
   #	openssl rsa -in domain.com.key -out domain.com.key.pem
   #	openssl x509 -inform PEM  -in domain.com.crt -out domain.com.crt.pem
   #	cat domain.com.key.pem domain.com.crt.pem domain.com-bundle  > TLS.pem


## Coturn install 
 if [ ! -f /usr/bin/turnserver ]; then
  wget http://turnserver.open-sys.org/downloads/v4.5.1.1/turnserver-4.5.1.1.tar.gz 
  tar -xvzf turnserver-4.5.1.1.tar.gz 
  cd turnserver-4.5.1.1 
  ./configure --prefix=/usr 
  make && make install
 fi

  IP_=$(ip route get 8.8.8.8 | awk -F"src " 'NR==1{split($2,a," ");print a[1]}')
  
  read -p " Configure CoTURN with IP[${IP_}]" IP
  IP=${IP:-$IP_}
  
cat <<EOF > /etc/turnserver.conf
listening-ip=$IP
relay-ip=$IP
listening-port=19302
tls-listening-port=19303
cert=/etc/asterisk/keys/TLS.pem
pkey=/etc/asterisk/keys/privkey.pem
fingerprint
realm=${DOMAIN}
server-name=${DOMAIN}
lt-cred-mech
user=turn:secured
user=stun:secured
log-file=/var/log/coturn.log
no-stdout-log
EOF

  
#### Install Initd file:   to /etc/init.d/turnserverd

cat <<EOF > /etc/init.d/turnserverd
#! /bin/sh
# /etc/init.d/$PROG
#
# chkconfig: 2345 90 60
# description: Stun/ Turnserver  for  telephony 
# processname: turnserver
PROG=turnserver
# Some things that run always
touch /var/lock/
# Carry out specific functions when asked to by the system
case "$1" in
  start)
    echo "Starting $PROG "
    cat /etc/turnserver.conf 2>/dev/null |grep '^listen'
    /usr/bin/turnserver -c /etc/turnserver.conf >/var/log/turnserver.log 2>&1 &
     
    ;;
  stop)
    echo "Stopping script $PROG"
    killall -9 turnserver || echo "Failed to stop ${PROG} "
    ;;
  *)
    echo "Usage: /etc/init.d/$PROG {start|stop}"
    exit 1
    ;;
esac
exit 0
EOF

chmod +x /etc/init.d/turnserverd
chkconfig --add turnserverd
chkconfig turnserverd on


####################### Confiure Asterisk: ###############################

read -p " configure Asterisk server[enter]" next
cat <<EOF > /etc/asterisk/sip_tls.conf
tlsenable=yes
tlsbindaddr=0.0.0.0:5061
tlscertfile=/etc/asterisk/keys/TLS.pem
tlsprivatekey=/etc/asterisk/keys/privkey.pem
tlscafile=/etc/asterisk/keys/fullchain.pem
tlsclientmethod=ALL
tlscipher=ALL
tlsclientmethod=tlsv1
tlsdontverifyserver=yes
EOF
perl -pi -e "s/^;tlscertfile/#include sip_tls.conf\n;;;tlscertfile/" /etc/asterisk/sip.conf



  perl -pi -e "s/enabled = no/enabled = yes/" /etc/asterisk/manager.conf
  perl -pi -e "s/bindaddr = 0.0.0.0/bindaddr = 127.0.0.1/" /etc/asterisk/manager.conf
  perl -pi -e "s/; retry rules\./; retry rules\.\n#include sip-register.tenants\n"/ /etc/asterisk/sip.conf
  touch /etc/asterisk/sip.tenants && echo "#include sip.tenants" >> /etc/asterisk/sip.conf
  
  echo "* * * * * root  /var/www/html/pbx/core/gen_sip_settings.php > /etc/asterisk/sip.include >/dev/null  2>&1 &" > /etc/cron.d/mpbx
  service crond restart

CONF=cdr_adaptive_odbc.conf
[ $(cat /etc/asterisk/$CONF|grep pbxdb|wc -l) -eq 0 ] && cat <<EOF >> /etc/asterisk/$CONF
[global]
connection=pbxdb
username = mpbx_web
password = P@ssw0rd123
loguniqueid=yes
dispositionstring=yes
table=t_cdrs            ;"cdr" is default table name
usegmtime=no             ; set to "yes" to log in GMT
alias start => calldate
EOF

CONF=res_odbc.conf
[ $(cat /etc/asterisk/$CONF|grep pbxdb|wc -l) -eq 0 ] && cat <<EOF >> /etc/asterisk/$CONF
[pbxdb]
enabled => yes
dsn => mpbx
username => mpbx_web
password => P@ssw0rd123
pre-connect => yes
EOF

CONF=res_config_mysql.conf
[ $(cat /etc/asterisk/$CONF|grep pbxdb|wc -l) -eq 0 ] && cat <<EOF >> /etc/asterisk/$CONF
[pbxdb]
dbhost=localhost
dbname=mpbx
dbuser=mpbx_web
dbpass=P@ssw0rd123
dbport=3306
dbsock=/var/lib/mysql/mysql.sock
requirements=warn
EOF

CONF=extensions.conf
[ $(cat /etc/asterisk/$CONF|grep tenants|wc -l) -eq 0 ] && cat <<EOF > /etc/asterisk/$CONF
[general]
static=yes
writeprotect=yes
priorityjumping=no
autofallthrough=no
clearglobalvars=yes

[globals]
RECORDING_FORMAT=WAV
#include extensions.tenants

[from-pstn]
include => did-inbound
exten => i,1,NooP( INVALID INBOUND EXTEN:${EXTEN}  FROM \${CHANNEL(peerip)} )
same => n,Playback(invalid)
same => n,Hangup()
EOF


CONF=extconfig.conf
[ $(cat /etc/asterisk/$CONF|grep pbxdb|wc -l) -eq 0 ] && cat <<EOF >> /etc/asterisk/$CONF
musiconhold   => mysql,pbxdb,t_moh
sippeers      => mysql,pbxdb,t_sip_users
voicemail     => mysql,pbxdb,t_vmusers
queues        => mysql,pbxdb,t_queues
queue_members => mysql,pbxdb,t_queue_members
extensions    => mysql,pbxdb,t_extensions
followme      => mysql,pbxdb,t_user_options
followme_numbers => mysql,pbxdb,t_user_followme
EOF

CONF=manager.conf
[ $(cat /etc/asterisk/$CONF|grep pbx-manager-dev|wc -l) -eq 0 ] && cat <<EOF >> /etc/asterisk/$CONF
cat <<EOF >>  /etc/asterisk/manager.conf
[pbx-manager-dev]
writetimeout=200
displayconnects=no
secret=92jdf3hfdf
deny=0.0.0.0/0.0.0.0
permit=127.0.0.1/255.255.255.0
permit=192.168.0.0/255.255.0.0 ; RFC 1918 addresses
permit=10.0.0.0/255.0.0.0      ; Also RFC1918
permit=172.16.0.0/12           ; Another RFC1918 with CIDR notation
permit=169.254.0.0/255.255.0.0 ; Zero conf local network
read = system,call,log,verbose,agent,user,dtmf,reporting,cdr,dialplan
write = system,call,agent,user,command,reporting,originate
EOF


CONF=http.conf
cat <<EOF >> /etc/asterisk/http.conf
[ $(cat /etc/asterisk/$CONF|grep TLS.pem|wc -l) -eq 0 ] && cat <<EOF >> /etc/asterisk/$CONF
[general]
servername=Asterisk
enabled=yes
bindaddr=127.0.0.1
bindport=8081
tlsenable=yes         
tlsbindaddr=0.0.0.0:8443   
tlscertfile=/etc/asterisk/keys/TLS.pem 
tlsprivatekey=/etc/asterisk/keys/privkey.pem   
EOF




## Create INI files from samples
 read -p " Generate dynamic  configuration [ enter ]" next
 for PRJ in pbx crm; do
   [ ! -f /var/www/html/${PRJ}/include/config.ini ] && cp /var/www/html/${PRJ}/include/config.ini.sample /var/www/html/${PRJ}/include/config.ini 2>/dev/null
 done
 cd /var/www/html/pbx/core && php ./gen_sip_settings.php 


 

 service firewalld stop
 chkconfig firewalld off
 chkconfig --level 345 mysqld on && service mysqld start 
 chkconfig --level 345 httpd on && service httpd start
 chkconfig --level 345 turnd on && service turnserverd start
 chkconfig --level 345 asterisk on && service asterisk start
