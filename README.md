# BiotoPi API

The Backend for the BiotoPi Project. A simple PHP Based API class build to easily use the BiotoPi functions.


## Installation

A Simple Step by Step Guide to install the BiotoPi onto a RaspberryPi (Version 2 or 3). Run this from `/var/www`. Later we setup a simple PHP Webserver (Apache) to server our Application.


### Get the Sourcecode

We use `/var/www/` for example:

	cd /var/www/
	git clone https://github.com/vaddi/biotopi.git


### Prepare the System

Once we have checkout, we can change into the desired Directory, We will do all Stuff from here in the later Instructions:

	cd biotopi


#### Helper Skript

Prepare the RaspberryPi and install all necessary Packages and Setup the I2C, SPI and other Interfaces by run the installation Script:

	./inc/assets/installation/install.sh


After Installation you should also went back into the filesystem

	cd /var/www/biotopi


#### Setup config

Create the configuration file by using `config.php.example` as Template. 

	cp inc/config.php.example inc/config.php

Use you favorit Editor;

	vim inc/config.php


A few Important Setup Parts:

- Setup a unique Secret
- Setup your Timezome (default is Europe/Berlin)
- If you plan to use a MySL, you have to Setup the MySQL Database Connection Section.  

#### Database

There are currently 2 Databases which can be used. Feel free to add a new implemention in the `inc/class/Database.php` class.

*  SQLite	- A Simple Filebased Database  (Default)
*  MySQL	- A MySQL Database connection


##### Create the Tables

Currently there are no installation processes to create the Database tables and fill them by default data. All the neccessary SQL Files will be found under the `assets/sql` folder.

SQLite

	sqlite3 inc/db/database.db < inc/assets/sql/db_create_sqlite.sql


#### Setup the Webserver

The Application need the PHP Language, which musst be served from a Webserver. Feel free to add nginx or other Webserver. I prefeer Apache. 

There can be found some apache config examples under `inc/assets/installation/` Directory.


## Ready to use

### Open the Website

Use you prefeered Webbrowser to open the Mainpage by the Hostname of your RaspberryPi:

	http://RASPBERRYPI/


