# BiotoPi API

The Backend for the BiotoPi Project. A simple PHP Based API class build to easily use the BiotoPi functions.


## Installation

A Simple Step by Step Guide to install the BiotoPi onto a RaspberryPi (Version 2 or 3). Run this from `/var/www`. Later we setup a simple PHP Webserver (Apache) to server our Application.


### Get the Sourcecode

	git clone https://github.com/vaddi/biotopi.git


### Prepare the System

Change into the desired Directory:

	cd /var/www/biotopi

Prepare the RaspberryPi and install all necessary Packages and Setup the I2C, SPI and other Interfaces by run the installation Script:

	./install.sh

Create the configuration file by using `config.php.example` as Template. 

	cp config.php.example config.php

Now setup the Database Connection in `config.php` File. 

	vim confog.php

There are currently 2 Databases which can be used. Feel free to add a new implemention in the `inc/class/Database.php` class.

*  SQLite	- A Simple Filebased Database 
*  MySQL	- A MySQL Database connection


#### Create the Tables

Currently there are no installation processes to create the Database tables and fill them by default data. Only the neccessary SQL Files will be found under the `assets/sql` folder.



### Setup the Webserver

The Application need the PHP Language, which musst be served from a Webserver. Feel free to add nginx or other Webserver. I prefeer Apache.


## Ready to use

### Open the Website

Use you prefeered Webbrowser to open the Mainpage.

	http://RASPBERRYPI/


