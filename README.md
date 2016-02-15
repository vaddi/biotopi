# BiotoPi #

A RaspberryPi Enviroment Controller and Datalogger for biotope and habitat (Reptile houses, Fish tanks and similar). 


### About ###

*  just a private project
*  Version 0.1

This Project is my personel testarea. I play arround by building small C-Programms witch will be executed from PHPs shell_exec() Command. The PHP Skripts will called from AJAX to reduce Pageload and get a possibility to have a polling event. Inside the PHP core, the dispatcher class will generate an instance of each Modulecall and assign all parameters to the Modulclass.

At the moment there are a lots of chaos inside this code, but hey: this is an private Project, feel free to join.

### Dependencies ###

*  [wiringPi][]
*  [MySQL][]
*  [PHP][]
*  Apache Webserver-[Apache][]

## Installation ##

Get last version from github.com by following command:

    git clone git://github.com/vaddi/biotopi.git

Edit `config.php` to change username and password and Database Connection.


### How do I set it up? ###

*  ToDo


### Credits ###

1. [wiringPi][] wiringPi
2. [Bootstrap][] Bootstrap


[PHP]: http://php.net/
[MySQL]: http://www.mysql.com/
[Apache]: http://httpd.apache.org/
[wiringPi]: http://wiringpi.org/
[Bootstrap]: http://getbootstrap.com/


