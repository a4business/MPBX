#!/bin/bash
########
##### Multitenant PBX:  installation steps 
##### Centos 8
########

# Detect External IP:
IP_=$(ip route get 8.8.8.8 | awk -F"src " 'NR==1{split($2,a," ");print a[1]}')
read -p " Your External IP[${IP_}]" IP
IP=${IP:-$IP_}

#Domain:
[ -f ./.domain ] && . .domain
read -p " Enter resolvable Domain name(TLS/HTTPS/WSS):${DOMAIN_}" DOMAIN
DOMAIN=${DOMAIN:-$DOMAIN_}
echo "DOMAIN_=$DOMAIN" > ./.domain


# Disable Security #
setenforce 0
if [ ! -f /usr/bin/perl ];then
  echo 'Check Perl install...'
  yum install perl -y >/dev/null
fi  
perl -pi -e "s/=enforcing/=disabled/g"  /etc/selinux/config


yum groupinstall core base 'Development Tools' -y

if [ $(rpm -qa|grep remi-release|wc -l) -eq 0 ];  then
 read -p  " ###   Install epel-release and remi-release [ next ]" next
 rpm -Uvh http://dl.fedoraproject.org/pub/epel/epel-release-latest-8.noarch.rpm
 if [ $(cat /etc/redhat-release|grep "8.5.2"|wc -l) -eq 1 ];then
    rpm -Uvh https://rpms.remirepo.net/enterprise/8/remi/x86_64/remi-release-8.5-2.el8.remi.noarch.rpm
 else   
    rpm -Uvh http://rpms.famillecollet.com/enterprise/remi-release-8.rpm
 fi
fi


### Tool repo,  install DNF plugins package:
dnf -y install dnf-plugins-core
dnf config-manager --set-enabled powertools
### 

## xmlstarlet?
yum install -y gcc gcc-c++ unixODBC-devel libiodbc-devel yum-utils bison mysql-devel mysql-server tftp-server httpd make ncurses-devel libtermcap-devel sendmail sendmail-cf caching-nameserver newt-devel libxml2-devel libtiff-devel audiofile-devel gtk2-devel subversion kernel-devel git subversion kernel-devel crontabs cronie cronie-anacron wget vim certbot libtool sqlite-devel sqlite-devel  unixODBC uuid-devel libuuid-devel binutils-devel opus opus-devel libedit-devel openssl-devel libevent libevent-devel libedit-devel libxml2-devel sqlite-devel curl-devel unixODBC-devel certbot certbot-apache mod_ssl iptables iptables-services tcpdump ngrep fail2ban net-tools libsrtp libsrtp-devel

## Captagent Dependeces:
yum install -y json-c-devel expat-devel libpcap-devel flex-devel automake libtool bison libuv-devel flex


## Install Statically linked ffmpeg:
if [ ! -f /usr/bin/ffmpeg ]; then
   read -p " ### Install ffmpeg static [enter]" next
   FILE="ffmpeg-release-amd64-static.tar.xz"
   LINK="https://johnvansickle.com/ffmpeg/releases/ffmpeg-release-amd64-static.tar.xz"
   [ ! -f /usr/bin/ffmpeg ] && wget ${LINK} &&  tar -xJf ${FILE} && cp ./ffmpeg-*-amd64-static/ffmpeg  /usr/bin/ffmpeg
fi


if [ ! -f /usr/bin/sox ]; then
   read -p " ### Install SOx from Sources: [enter]" next
   GETSOX=https://ftp.icm.edu.pl/packages/sox/14.4.2/sox-14.4.2.tar.gz
   wget ${GETSOX} && tar -xvzf  sox-14.4.2.tar.gz && cd sox-14.4.2 && ./configure --prefix=/usr && make && make install
fi

if [ ! -f /usr/local/captagent/sbin/captagent ]; then
  PWD=$(pwd)
  echo "[ Install CAPAGENT  for HOMER7 ] "  ### https://github.com/sipcapture/captagent/wiki/Installation
  cd /usr/src
  git clone https://github.com/sipcapture/captagent.git captagent
  cd captagent
  ./build.sh
  ./configure
  make && make install
  cp init/el/captagent.sysconfig /etc/sysconfig/captagent
  cp init/el/captagent.service /usr/lib/systemd/system
  systemctl enable captagent
  systemctl status captagent
  cd $PWD
else
  echo "[ CAPAGENT INSTALLED ALREADY ] "
fi  


if [ ! -f /usr/bin/php ]; then
 echo -n " ### Install f@ck!ng old PHP ( 5.x ) ? " && read -p '  [enter]' next
 yum install -y php56 php56-php-curl php56-php-ldap php56-php-fileinfo php56-php-zip php56-php-fileinfo php56-php-xml php56-php-mbstring php56-php-process php56-php-http php56-php-devel php56-php-mysql php56-mod_php
 php56-pear channel-update pear.php.net && php56-pear install db-1.7.14
 update-alternatives --install /usr/bin/php php /opt/remi/php56/root/bin/php 1
 php -v
fi

[ -f /etc/opt/remi/php56/php.ini ] && perl -pi -e "s/post_max_size = 8M/post_max_size = 32M/" /etc/opt/remi/php56/php.ini
[ -f /etc/opt/remi/php56/php.ini ] && perl -pi -e "s/upload_max_filesize = 2M/upload_max_filesize = 20M/" /etc/opt/remi/php56/php.ini
[ -f /usr/lib/systemd/system/php56-php-fpm.service ] && perl -pi -e "s/PrivateTmp=true/PrivateTmp=false/" /usr/lib/systemd/system/php56-php-fpm.service && systemctl daemon-reload && systemctl restart php56-php-fpm

if [ -f /usr/sbin/mysqld ] ; then
  echo  -n " ### Install mysql-server:" && read -p '  [enter]' next
  yum install mysql-server  mysql-devel  -y 
  yum install php56-php-mysqlnd perl-DBD-MySQL  cyrus-sasl-sql postfix -y
  

  service mysqld start
  /usr/bin/mysql_secure_installation
  wget https://repo.mysql.com//mysql80-community-release-el8-1.noarch.rpm
  rpm -Uvh mysql80-community-release-el8-1.noarch.rpm
  perl -pi -e "s/gpgcheck=1/gpgcheck=0/g" /etc/yum.repos.d/mysql-community*
  yum install -y mysql-connector-odbc
fi
 

if [ ! -f /usr/sbin/asterisk ]; then
  VER=18 
 #VER=16
   read -p " ### Install Asterisk From Sources ( recommended to keep sources on the server  for future bug/patches  ) [ enter ]" next
   [ ! -f asterisk-${VER}-current.tar.gz ] && wget http://downloads.asterisk.org/pub/telephony/asterisk/asterisk-${VER}-current.tar.gz
   tar -xvzf asterisk-${VER}-current.tar.gz  && cd asterisk-${VER}.*
   ./configure --with-pjproject-bundled --with-jansson-bundled 
   menuselect/menuselect --enable res_config_mysql  menuselect.makeopts && menuselect/menuselect --enable cdr_mysql  menuselect.makeopts
   make menuselect  ### Make sure res_config_odbc , cdr_mysql,  cdr_odbc,  res_odbc are enabled.
   make && make install && make samples && make config
   [ $VER = 18 ] && perl -pi -e "s/noload = chan_sip.so/;noload = chan_sip.so/" /etc/asterisk/modules.conf
fi  
  


####### Install WEB files form this Remote:#######################################
 read -p " Install WEB GUI from repo [ enter ] " next  
 cd /var/www/html
 git clone https://git.a4business.com/george/mpbx.git ./pbx
 git clone https://git.a4business.com/george/crm.git ./crm
 curl -sS https://getcomposer.org/installer | php
 mv composer.phar /usr/local/bin/composer 
 cp /usr/local/bin/composer  /usr/bin/composer 
 cd /var/www/html/pbx && composer install
 cd /var/www/html/crm && composer install
 
## Make forlder for Text2Speech cache:
 mkdir -p /tts && chmod 777 /tts



##  Link Asterisk config files
  for CONF in func_odbc.conf macros.include conferences.include;  do ln -sf /var/www/html/pbx/core/${CONF} /etc/asterisk/${CONF}; done
  cd /etc/asterisk && for CONF in extensions.tenants inbound.include internal.include ivrmenus.include outbound.include queues.include res_parking.include ringgroups.include sip-register.tenants sip.tenants tenants.include; do touch $CONF; chown apache.apache $CONF; done
  echo "#include  res_parking.include" >>  /etc/asterisk/res_parking.conf

rm -rf /var/lib/asterisk/agi-bin && ln -sf /var/www/html/pbx/agi-bin /var/lib/asterisk/agi-bin

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


cat <<EOF > /etc/httpd/conf.d/${DOMAIN}.conf
<VirtualHost *:80>
    ServerName ${DOMAIN}
    DocumentRoot /var/www/html/crm
    ServerAlias www.${DOMAIN}
RewriteEngine on
RewriteCond %{SERVER_NAME} =${DOMAIN} [OR]
RewriteCond %{SERVER_NAME} =www.${DOMAIN}
RewriteRule ^ https://%{SERVER_NAME}%{REQUEST_URI} [END,NE,R=permanent]
</VirtualHost>
EOF


read -p " Generate TLS certificates  for Domain: ${DOMAIN}  [ enter ]" next
################ Create  ssl certificates  ( required for webrtc (wss/dtls) and for SIP/tls )
   service httpd restart

   firewall-cmd  --zone public --add-port 80/tcp --permanent
   firewall-cmd  --zone public --add-port 443/tcp --permanent
   firewall-cmd --zone public --add-source 127.0.0.1 --permanent
   firewall-cmd --zone public --add-port 5060/udp --permanent
   firewall-cmd --zone public --add-port 8081/udp --permanent
   firewall-cmd --zone public --add-port 8443/udp --permanent
   firewall-cmd --zone public --add-port 19302/udp --permanent
   firewall-cmd --zone public --add-port 19303/udp --permanent
   firewall-cmd --zone public --add-port 8081/tcp --permanent
   firewall-cmd --zone public --add-port 8443/tcp --permanent
   firewall-cmd --zone public --add-port 19302/tcp --permanent
   
   firewall-cmd --reload


   if [ "${DOMAIN:-no}" != "no" ]; then
        [ ! -d /etc/letsencrypt/live/${DOMAIN} ] && certbot -d ${DOMAIN} certonly --apache
        if [ -d /etc/letsencrypt/live/${DOMAIN} ]; then 
          [ ! -L /etc/asterisk/keys ] &&  ln -sf /etc/letsencrypt/live/${DOMAIN} /etc/asterisk/keys
          [ ! -f /etc/asterisk/keys/TLS.pem ] && cd /etc/asterisk/keys && cat privkey.pem fullchain.pem   > TLS.pem
	else
	  echo 'Warning: SSL certs not created!'
	fi
   else
        echo "WARNING: no domain name - SKIPPED SSL generation!"
   fi
   ## Commercial SSL convert into PEM:
   #    openssl rsa -in domain.com.key -out domain.com.key.pem
   #    openssl x509 -inform PEM  -in domain.com.crt -out domain.com.crt.pem
   #    cat domain.com.key.pem domain.com.crt.pem domain.com-bundle  > TLS.pem


read -p " Configure WEB server Apache2 with domain[$DOMAIN] [ enter ] " next
## Configure WEB Server :
[ ! -f /etc/httpd/conf.d/ssl.conf.old ] && mv /etc/httpd/conf.d/ssl.conf /etc/httpd/conf.d/ssl.conf.old 
if [ ! -f /etc/httpd/conf.d/${DOMAIN}.conf ]; then
cat <<EOF > /etc/httpd/conf.d/${DOMAIN}.conf
<VirtualHost *:80>
    ServerName ${DOMAIN}
    DocumentRoot /var/www/html/crm
    ServerAlias www.${DOMAIN}
RewriteEngine on
RewriteCond %{SERVER_NAME} =${DOMAIN} [OR]
RewriteCond %{SERVER_NAME} =www.${DOMAIN}
RewriteRule ^ https://%{SERVER_NAME}%{REQUEST_URI} [END,NE,R=permanent]
</VirtualHost>

<VirtualHost localhost:8081>
    ServerName ${DOMAIN}
    DocumentRoot /var/www/html/pbx
    ServerAlias www.${DOMAIN}
    <Directory /var/www/html/pbx >
     AllowOverride All
     Options Indexes MultiViews FollowSymLinks
    </Directory>
 </VirtualHost>
EOF
fi

if [ ! -f /etc/httpd/conf.d/${DOMAIN}-le-ssl.conf ]; then
cat <<EOF > /etc/httpd/conf.d/${DOMAIN}-le-ssl.conf
<IfModule mod_ssl.c>
 Listen 443 https
 Listen 8182 https
 
 <VirtualHost _default_:443>
    ServerName ${DOMAIN}
    DocumentRoot /var/www/html/crm
    ServerAlias www.${DOMAIN}
   <Directory /var/www/html/crm >
     AllowOverride All
     Options Indexes MultiViews FollowSymLinks
   </Directory>
  SSLCertificateFile /etc/letsencrypt/live/${DOMAIN}/fullchain.pem
  SSLCertificateKeyFile /etc/letsencrypt/live/${DOMAIN}/privkey.pem
  Include /etc/letsencrypt/options-ssl-apache.conf
 </VirtualHost>
 
 <VirtualHost *:8182>
    ServerName ${DOMAIN}
    DocumentRoot /var/www/html/pbx
    ServerAlias www.${DOMAIN}
    <Directory /var/www/html/pbx >
     AllowOverride All
     Options Indexes MultiViews FollowSymLinks
    </Directory>
    SSLCertificateFile /etc/letsencrypt/live/${DOMAIN}/fullchain.pem
    SSLCertificateKeyFile /etc/letsencrypt/live/${DOMAIN}/privkey.pem
    Include /etc/letsencrypt/options-ssl-apache.conf
 </VirtualHost>
</IfModule>
EOF
fi

[ ! -f /etc/httpd/options-ssl-apache.conf ] && cp /etc/letsencrypt/options-ssl-apache.conf /etc/httpd/

service httpd restart


read -p " Configure MYSQL Server [ enter ]" next
## Configure Database##
if [ $(cat /etc/my.cnf|grep utf8_unicode|wc -l) -eq 0 ] ; then
 cat <<EOF > /etc/my.cnf
[client]
default-character-set=utf8
[mysql]
default-character-set=utf8
[mysqld]
collation-server = utf8_unicode_ci
character-set-server = utf8
default_authentication_plugin = mysql_native_password
EOF
 service mysqld restart

fi


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
 

read -p " Configure CoTURN with IP[${IP_}]/Domain: $DOMAIN  [ enter ]" next
## Coturn install 
yum install coturn -y
 if [ ! -f /usr/bin/turnserver ]; then
  wget http://turnserver.open-sys.org/downloads/v4.5.1.1/turnserver-4.5.1.1.tar.gz   
  tar -xvzf turnserver-4.5.1.1.tar.gz 
  cd turnserver-4.5.1.1 
  ./configure --prefix=/usr 
  make && make install
 fi

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
# /etc/init.d/turnserver
#
# chkconfig: 2345 90 60
# description: Stun/ Turnserver  for  telephony 
# processname: turnserver
# Some things that run always
touch /var/lock/
# Carry out specific functions when asked to by the system
case "\$1" in
  start)
    echo "Starting  turnserver"
    cat /etc/turnserver.conf 2>/dev/null |grep '^listen'
    /usr/bin/turnserver -c /etc/turnserver.conf >/var/log/turnserver.log 2>&1 &
     
    ;;
  stop)
    echo "Stopping script turnserver"
    killall -9 turnserver || echo "Failed to stop turnserver "
    ;;
  *)
    echo "Usage: /etc/init.d/turnserver {start|stop}"
    exit 1
    ;;
esac
exit 0
EOF

chmod +x /etc/init.d/turnserverd
chkconfig --add turnserverd
chkconfig turnserverd on
service turnserverd start


####################### Confiure Asterisk: ###############################

read -p " configure Asterisk server[enter]" next


perl -pi -e "s/^;verbose =/verbose=/" /etc/asterisk/asterisk.conf
perl -pi -e "s/^;debug =/debug=/" /etc/asterisk/asterisk.conf
perl -pi -e "s/^;full =/full =/" /etc/asterisk/logger.conf
rasterisk -rx 'logger reload'

## Make sure cert files exists!!
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
perl -pi -e "s/^;rtcachefriends=yes/rtcachefriends=yes/" /etc/asterisk/sip.conf



  perl -pi -e "s/enabled = no/enabled = yes/" /etc/asterisk/manager.conf
  perl -pi -e "s/bindaddr = 0.0.0.0/bindaddr = 127.0.0.1/" /etc/asterisk/manager.conf
  perl -pi -e "s/; retry rules\./; retry rules\.\n#include sip-register.tenants\n"/ /etc/asterisk/sip.conf
  touch /etc/asterisk/sip.tenants && echo "#include sip.tenants" >> /etc/asterisk/sip.conf

cat <<EOF > /etc/cron.d/mpbx  
## Generate additional SIP settings every minute #
* * * * * root  /var/www/html/pbx/core/gen_sip_settings.php > /etc/asterisk/sip.include >/dev/null  2>&1 &
## Every Night clean CDRs/CEL
0 3 * * * root curl -k https://localhost:8182/jaxer.php?cleanCDRS=1 >> /var/log/pbx.log 2>&1 &
EOF

service crond restart

###  Fail2ban:
read -p '  Configure Fail2ban? [ enter ]'  next
[ -f /etc/fail2ban/jail.d/00-firewalld.conf ] && perl -pi -e "s/banaction/#banaction/g" /etc/fail2ban/jail.d/00-firewalld.conf
echo -e  "[DEFAULT]\nignoreip = 127.0.0.1/8 ::1\n" > /etc/fail2ban/jail.d/local.conf

sed -i "45i\  \t     curl -k 'https://localhost:8182/jaxer.php?blockIP=<ip>&block_reason=by-Fail2Ban-<name>-REJECT&bantime=<bantime>'" /etc/fail2ban/action.d/iptables-allports.conf
sed -i "42i\  \t     curl -k 'https://localhost:8182/jaxer.php?blockIP=<ip>&block_reason=by-Fail2Ban-<name>-REJECT&bantime=<bantime>'" /etc/fail2ban/action.d/iptables-multiport.conf

cat <<EOF > /etc/fail2ban/jail.local
[asterisk]
enabled = true
port = 0:65535
action_  = %(default/action_)s[name=%(__name__)s-tcp, protocol="tcp"]
           %(default/action_)s[name=%(__name__)s-udp, protocol="udp"]
logpath  = /var/log/asterisk/full
actionban = <iptables> -I f2b-<name> 1 -s <ip> -j <blocktype>
            curl -k "https://localhost:8182/jaxer.php?blockIP=<ip>&block_reason=by-Fail2Ban-<name>-REJECT&bantime=<bantime>" 2>/dev/null
maxretry = 3
bantime  = 3600
findtime = 300
EOF


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

# Enable WEB clients to receive calls :
perl -pi -e "s/;rtcachefriends=yes/rtcachefriends=yes/" /etc/asterisk/sip.conf
# Disable skinny public access :
perl -pi -e "s/0.0.0.0/127.0.0.1/g" /etc/asterisk/skinny.conf

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
[ $(cat /etc/asterisk/$CONF|grep TLS.pem|wc -l) -eq 0 ] && cat <<EOF > /etc/asterisk/$CONF
[general]
servername=Asterisk
enabled=no
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
   [ "$DOMAIN" != "" ] &&  perl -pi -e "s/app.a4business.com/$DOMAIN/g" /var/www/html/${PRJ}/include/config.ini
 done
 cd /var/www/html/pbx/core && php ./gen_sip_settings.php 

## Do not load some  depricated modules  (chan_sip is next)
[ $(cat /etc/asterisk/modules.conf|grep cdr_musql|wc -l) -eq 0 ] &&  echo -e  "noload = app_image\nnoload = chan_oss\nnoload = chan_skinny\nnoload = cdr_mysql" >> /etc/asterisk/modules.conf

 ### Logrotate:
cat <<EOF > /etc/logrotate.d/asterisk
/var/log/asterisk/full
/var/log/asterisk/debug
/var/log/asterisk/messages
/var/log/pbx.log
/var/log/reports.log
{ 
  rotate 6
  missingok
  maxsize 50M 
  delaycompress 
  daily
  postrotate
	/usr/sbin/asterisk -rx 'logger reload' 2>/dev/null || true
  endscript
}  
EOF
 
 
 chmod +s /usr/sbin/asterisk 
 service firewalld stop
 chkconfig firewalld off

 touch /var/log/pbx.log && chown apache.apache /var/log/pbx.log 
 chown -R apache.apache /var/lib/asterisk/sounds /var/lib/asterisk/moh  /var/lib/asterisk /tts /var/spool/asterisk/monitor
 chmod a+z  /var/spool/asterisk chmod a+x /var/spool/asterisk/monitor
 service php56-php-fpm restart
 
 for SERVICE in sendmail fail2ban mysqld httpd turnd asterisk
 do
   chkconfig --level 345 ${SERVICE} on
   service ${SERVICE} start
 done  
 
