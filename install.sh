#!/bin/bash

#########################
### Application Setup ###
#########################
# run as root from the  #
# App main directory    #
#########################
# 2016 by mvattersen    #
# vaddi@mvattersen.de   #
#########################

# TODO:
# /etc/modprobe.d/raspi-blacklist.conf blacklisted modules
# adduser pi i2c

WIRINGDIR="/root" # wiringPi install directory 

#APPNAME=${PWD##*/} # Application Name read from folder
APPNAME="BiotoPi"		# Manual set Application name

# Pkg Array, all of this Pkgs will be installed in this order
PKGS=(	mysql-server 
				apache2 
				php5 
				php5-mysql 
				php-http 
				php5-dev 
				libcurl4-openssl-dev
				libmagic-dev 
				php-pear 
				curl 
				libcurl3 
				php5-curl 
				php5-mcrypt 
				php5-gd
				htop
				iftop
				vim
				libi2c-dev
				git-core
				bc)

modules=(	i2c-dev
					i2c-bcm2708
					w1-gpio
					w1-therm)

# Colorized status output
OK="\033[1;32mOK\033[0;37m"
FAIL="\033[0;31mFAIL\033[0;37m"

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
	printf  "\033cRemoving \033[0;34m$APPNAME\033[0;37m Application on a \033[1;34m$SYSTEM\033[0;37m System\n\n"
	
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
#				echo -en "$PKG \033[1;31mnot found\033[0;37m"
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
printf  "\033cInstalling \033[0;34m$APPNAME\033[0;37m Application on a \033[1;34m$SYSTEM\033[0;37m System\n\n"

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


# remove some files (git, kohana, default Readme, etc.)
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
			echo -en "$PKG \033[1;31mnot found\033[0;37m"
			echo -n " try to install: "
			apt-get -qq -y install "$PKG" &> /dev/null
			check_input $?
			;;
	esac
done

# install pecl 
echo 
echo -n "Check for pecl_http-1.7.6: "
PECLINST=$(yes '' | pecl install -s -a pecl_http-1.7.6 | tail -n1) 
if [ "$PECLINST" == "install failed" ]; then
  echo -e "$OK"
else
#	echo -n "Try to install via pecl install: "
#	yes '' | pecl install -s -a pecl_http-1.7.6 &> /dev/null
#	if [ $? -eq 0 ]; then
#    echo -e "$OK"
#	else
		echo -e "$FAIL"
		echo "Run manualy: pecl install pecl_http-1.7.6"
#	fi
fi

# install wiringPi
echo 
echo -n "Check for wiringPi: "
OLDDIR=`pwd`
cd $WIRINGDIR
if [ ! -d "$WIRINGDIR/wiringPi" ]; then
	git clone git://git.drogon.net/wiringPi
fi
cd wiringPi
git pull origin &> /dev/null
./build &> /dev/null
# verify installation
command -v gpio &> /dev/null
check_input $?
cd $ODLDIR

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
echo -n "Enable php5enmod mcrypt: "
php5enmod mcrypt
check_input $?

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
echo "All Done. Run ./install.sh uninstall if you wish to remove all changes."
echo

exit 0

# end of file
