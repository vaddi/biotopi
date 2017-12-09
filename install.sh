#!/bin/bash

#########################
### Application Setup ###
#########################
# run as root from the  #
# App main directory    #
#########################
# 2016 by mvattersen    #
# mvattersen@gmail.com  #
#########################

# TODO:
# /etc/modprobe.d/raspi-blacklist.conf blacklisted modules
# adduser pi i2c

WIRINGDIR="/root" # wiringPi install directory

#APPNAME=${PWD##*/} # Application Name read from folder
APPNAME="BiotoPi"		# Application Name

# Pkg Array, all of this Pkgs will be installed in this order
PKGS=(	# mysql-server
  sqlite3
	apache2
	php
	php-mysql
	php-http
	php-dev
  php-sqlite3
	libcurl4-openssl-dev
	libmagic-dev
	php-pear
	curl
	libcurl3
	php-curl
	php-mcrypt
	php-gd
	daemon
	htop
	iftop
	vim
  locate
	libi2c-dev
	git-core
	bc)

modules=(i2c-dev
	i2c-bcm2708
	w1-gpio
	w1-therm)

# Colorized status output
OK="\033[0;32mOK\033[0m"
FAIL="\033[0;31mFAIL\033[0m"

function check_input {
if [ $1 -eq 0 ]; then
	echo -e "$OK"
else
	echo -e "$FAIL"
fi
}


# Check for System (read first word from /etc/issue)
SYSTEM=`cut -d' ' -f1 "/etc/issue"`
SYSTEM=`echo -n "${SYSTEM//[[:space:]]/}"`

case $SYSTEM in
	"CRUX")
		INSTALLER="prt-get"
		WSUSER="www:www"
		;;
	"Ubuntu")
		INSTALLER="apt-get"
		WSUSER="www-data:www-data"
		;;
	"Debian")
		INSTALLER="apt-get"
		WSUSER="www-data:www-data"
		;;
	*)
		INSTALLER="apt-get"
		WSUSER="www-data:www-data"
		;;
esac

# Cleanup
if [ "$1" == "uninstall" ]; then
	printf  "\033cRemoving \033[0;34m$APPNAME\033[0;37m Application on a \033[1;34m$SYSTEM\033[0m System\n\n"

	echo "uninstall all PKGs: "
	for PKG in "${PKGS[@]}"
	do
		# check if pkg is allready installed
		PKG_OK=$(dpkg-query -W --showformat='${Status}\n' $PKG|grep "install ok installed")

		case $PKG_OK in
			"install ok installed")
				echo -e -n "Remove $PKG: "
				apt-get -qq -y remove "$PKG" &> /dev/null
				check_input $?
				;;
			*)
#				echo -en "$PKG \033[1;31mnot found\033[0m"
#				echo -n " try to install: "
#				apt-get -qq -y install "$PKG" &> /dev/null
#				check_input $?
				;;
		esac
	done

	echo "uninstall wiringPi"
	OLDDIR=`pwd`
	cd $WIRINGDIR
	if [ -d "$WIRINGDIR/wiringPi" ]; then
		cd wiringPi
		./build uninstall &> /dev/null
		check_input $?
	fi
	cd $ODLDIR

	echo "unload kernel module"
	for modul in "${modules[@]}"
	do
		modname=${modul//[-]/_}
		cmd=`lsmod | grep "$modname" | wc -l`
		if [ ! $cmd -eq 0 ]; then
			echo -n "Disable module $modul: "; modprobe -r $modname; check_input $?;
		fi
	done

	echo "uninstall pecl http: "
	pecl uninstall pecl_http-1.7.6 &> /dev/null
	check_input $?
	echo

	exit 0
fi


# clear screen
printf  "\033cInstalling \033[0;34m$APPNAME\033[0m Application on a \033[1;34m$SYSTEM\033[0m System\n\n"

# Set permissions
echo -n "Set Main permissions: "
find . -type d -exec chmod 0755 {} \;
check_input $?

#echo -n "Chown Cache-Folder to Webserver-User: "
#chown -R "$WSUSER" application/cache
#check_input $?

#echo -n "Chown Log-Folder to Webserver-User: "
#chown -R "$WSUSER" application/logs
#check_input $?


# remove recursive gitignore & ~ files
#echo -n "Remove recursive all gitignore & ~ Files: "
#find . -name \*.gitignore -exec rm -v '{}' \;
#check_input $?


# remove my joe backups
#echo -n "Remove all joe Backupfiles: "
#find . -name \*~ -exec rm -v '{}' \;
#check_input $?


# remove some files (git, Readme, etc.)
#for REMOVE in .git build.xml composer.json CONTRIBUTING.md README.md .gitmodules .gitmodules-dev .travis.yml
#do
#	if [ -e "$REMOVE" ]
#	then
#		echo "$REMOVE removed"
#		rm -r "$REMOVE"
#	fi
#done

# copy the .htacces file
file="example.htaccess"
if [ -e "$file" ]
then
  echo -n "Rename $file to .htaccess:"
  mv "$file" ".htaccess"
  check_input $?
fi

# rename the install.php file
file="install.php"
if [ -e "$file" ]
then
  echo -n "Rename $file to old_$file: "
  mv "$file" "old_$file"
  check_input $?
fi

# prepare to install PKGs
echo -n "Update Sources: "
apt-get -qq -y update &> /dev/null
if [ $? -eq 0 ]; then
  echo -e "$OK"
else
	echo -e "$FAIL couldn't run \"apt-get update\""
fi

echo -n "Upgrade Packages: "
apt-get -qq -y upgrade &> /dev/null
if [ $? -eq 0 ]; then
  echo -e "$OK"
else
	  echo -e "$FAIL couldn't run \"apt-get upgrade\""
fi


# install Pkgs
echo
echo "Check for PKGs: "
for PKG in "${PKGS[@]}"
do
	# check if pkg is allready installed
	PKG_OK=$(dpkg-query -W --showformat='${Status}\n' $PKG|grep "install ok installed")

	case $PKG_OK in
		"install ok installed")
			echo -e "$PKG: $OK"
			;;
		*)
			echo -en "$PKG \033[1;31mnot found\033[0m"
			echo -n " try to install: "
			apt-get -qq -y install "$PKG" &> /dev/null
			check_input $?
			;;
	esac
done

# install pecl
#echo
#echo -n "Check for pecl_http-1.7.6: "
#PECLINST=$(yes '' | pecl install -s -a pecl_http-1.7.6 | tail -n1)
#if [ "$PECLINST" == "install failed" ]; then
#  echo -e "$OK"
#else
##	echo -n "Try to install via pecl install: "
##	yes '' | pecl install -s -a pecl_http-1.7.6 &> /dev/null##	if [ $? -eq 0 ]; then
##    echo -e "$OK"
##	else
#		echo -e "$FAIL"
#		echo -e "Run manualy: p\033[1;32mecl install pecl_http-1.7.6\033[0m"
#    sleep 5s
##	fi
#fi

# install pear Mail
#echo
#echo -n "Check for pear Mail: "
#UPDATEPEAR=$(pear channel-update pear.php.net)
#MAILINST=$(pear install Mail)
#SMPTINST=$(pear install Net_SMTP)

# install wiringPi
echo
echo -n "Check for wiringPi: "
if [ ! -d "$WIRINGDIR/wiringPi" ]; then
  echo -e "$FAIL"
  echo "Not installed, try to install: "
  OLDDIR=`pwd`
  cd $WIRINGDIR
	git clone git://git.drogon.net/wiringPi
  cd wiringPi
  git pull origin &> /dev/null
  ./build &> /dev/null
  check_input $?
  echo -n "Verify installation: "
  command -v gpio &> /dev/null
  check_input $?
  cd $ODLDIR
else
  echo -e "$OK"
fi

# Enable kernel modules
for modul in "${modules[@]}"
do
	modname=${modul//[-]/_}
	if [ "$modul" = "w1-gpio" ]; then
		cmd=`lsmod | grep "$modname" | wc -l`
		if [ $cmd -eq 0 ]; then
			echo -n "Enable kernel module $modul (pullup=1): "; modprobe $modname pullup=1; check_input $?;
		fi
	else
		cmd=`lsmod | grep "$modname" | wc -l`
		if [ $cmd -eq 0 ]; then
			echo -n "Enable kernel module $modul: "; modprobe $modname; check_input $?;
		fi
	fi
done

# write kernel modules to cofigs
if [ ! `grep dtoverlay /boot/config.txt | wc -l` -gt 0 ]; then
	echo -n "Add dtoverlay to /boot/config.txt: "
	echo -e "\ndtoverlay=w1-gpio,gpiopin=4,pullup=on" >> /boot/config.txt
	check_input $?
fi
if [ ! `grep "i2c-" /etc/modules | wc -l` -gt 0 ]; then
	echo -n "Add i2c modules to /etc/modules: "
	echo -e "\n# i2c bus \ni2c-dev \ni2c-bcm2708" >> /etc/modules
	check_input $?
fi
if [ ! `grep "w1-" /etc/modules | wc -l` -gt 0 ]; then
	echo -n "Add ds18b20 modules to /etc/modules: "
	echo -e "\n# ds18b20 temperature on OneWire \nw1-gpio pullup=1 \nw1-therm" >> /etc/modules
	check_input $?
fi

echo
echo -n "Enable php enmod mcrypt: "
phpenmod mcrypt
check_input $?

echo
echo -n "Check BiotoPi Apache2 Site: "
file="/etc/apache2/sites-available/biotopi.conf"
if [ ! -e "$file" ]; then
  echo -e "$FAIL"
  echo -n "Create BiotoPi Site config: "
  cat << EOF > $file
<VirtualHost *:80>
  ServerAdmin webmaster@localhost

  DocumentRoot /var/www
  <Directory />
    Options FollowSymLinks
    AllowOverride None
  </Directory>
  <Directory /var/www/>
    Options Indexes FollowSymLinks MultiViews
    AllowOverride None
    Order allow,deny
    allow from all
  </Directory>

  ScriptAlias /cgi-bin/ /usr/lib/cgi-bin/
  <Directory "/usr/lib/cgi-bin">
    AllowOverride None
    Options +ExecCGI -MultiViews +SymLinksIfOwnerMatch
    Order allow,deny
    Allow from all
  </Directory>

  ErrorLog ${APACHE_LOG_DIR}/error.log

  # Possible values include: debug, info, notice, warn, error, crit, alert, emerg.
  LogLevel warn

  CustomLog ${APACHE_LOG_DIR}/access.log combined
</VirtualHost>
EOF
  check_input $?
  echo -n "Enable BiotoPi Site: "
  if [ ! -e "$file" ]; then
    a2ensite -q biotopi
    check_input $?
  fi
  if [ -e "/etc/apache2/sites-enabled/000-default.conf" ]; then
    echo -n "Disable apache 000-default site: "
    a2dissite -q 000-default
    check_input $?
  fi
fi

echo
echo -n "Update locate Database"
file="/usr/bin/updatedb"
if [ -e "$file" ]; then
  updatedb
  check_input $?
fi
# crondir=$( dirname $( grep -rsl updatedb /etc/cron* ) | cut -d. -f2- )
# crontime=$(grep $crondir /etc/crontab | cut -b1-5)
# echo "Locate Database updates from cron are running $\033[0;32mcrondir\033[0m at \033[0;32$crontime\033[0m"

echo
echo -n "Reload Apache Module: "
# load apache rewrite module
a2enmod -q rewrite
check_input $?

echo -n "Restart Apache Webserver: "
# restart apache webserver
apachectl -k restart
check_input $?

echo
echo -e "All Done. Run \033[0;32m./install.sh uninstall\033[0m if you wish to remove all changes."
echo

exit 0

# end of file
