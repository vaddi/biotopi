# BiotoPi, RaspberryPi Enviroment Controller #

A RaspberryPi Controller and Datalogger for biotope and habitat (Reptile houses, Fish tanks and similar). 


### About ###

*  just a private project
*  Version 0.1


### Dependencies ###

*  [wiringPi][]
*  [MySQL][]
*  [PHP][]5
*  Apache Webserver-[Apache][]

## Installation ##

Get last version from github.com by following command:

    git clone git://github.com/vaddi/biotopi.git

Edit `config.php` to change username and password and Database Connection.


### How do I set it up? ###

* Manuel Installation
** Just Edit "base_url" in application/bootstrap.php to fits your Path
** in .htaccess edit the "RewriteBase" to fits your Path 
** chown to the Webserveruser application/cache and application/logs 
** Edit the application/config/constants.php to setup the used Mailadress, Path and especially the Salt
* Dependencies
** PHP5.x 
** MySQL
** Installation via apt-get -y install mysql-server apache2 php5 php5-mysql php-httpl php5-dev libcurl4-gnutls-dev libmagic-dev php-pear php-http curl libcurl3 libcurl3-dev php5-curl php5-mcrypt php5-gd 
** To install the PECL Stuff juyt type "pecl install pecl_http-1.7.6" and confirm with Enter
** Enable the Apache rewrite Module by type "a2enmod rewrite" and hit again the Enter Button 
** At last don't forget to restart apache after installing all Packages by "apachectl -k restart" and confirm with Enter 
** Installation can be checked by the install.php File (if it exist under the name they will be automaticly loaded)
* Database configuration
** Setup Database, Database user and Password in application/config/database.php


### Credits ###

1. [wiringPi][] wiringPi

[PHP]: (http://php.net/)
[MySQL]: (http://www.mysql.com/)
[Apache]: (http://httpd.apache.org/)
[wiringPi]: (http://wiringpi.org/)
[Bootstrap]: (http://getbootstrap.com/)


