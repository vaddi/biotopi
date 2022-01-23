# BiotoPi API

The Backend for the BiotoPi Project. A simple PHP Based API class build to easily use the BiotoPi functions.


## Installation

A Simple Step by Step Guide to install the BiotoPi onto a RaspberryPi (Version 2 or 3). Run this from `/var/www`. Later we setup a simple PHP Webserver (we run here Apache2) to serve our Web Application.

First - of all, Setup your RaspberryPi (You need a running Network, change the pi User Password, setup a Hostname and System locales):

	sudo raspi-config

1. Change User Password Change password for the current user.
2. Network Options:
   N1 Hostname -> Give your System a nice Hostname to `biotopi` or something you like.  
   *Depending on how you will access to the Pi, Setup the Wifi (N2) or setup a LAN device (this have to be done manualy under the dhcpcd `/etc/dhcpcd.conf`)
3. Boot Options - Setup `B1 Desktop / CLI` to `B1 Console`, we dont need a Desktop Enviroment for a Web Service.
4. Localisation Options  
   I1 Change Locale  
   I2 Change Timezone  
   I3 Change Keyboard Layout
5. Interfacing Options - Enable the Following Options  
   P2 SSH  
   P4 SPI  
   P5 I2C  
   P7 1-Wire


Second - Install some basic Pakets (I prefer vim, feel free to use another Text Editor), git is essentiell to install and update later:

	sudo apt install vim git

Third - You have to allow the webserver user to restart/shutdown the System, to use this commands from the Website, place the following inside your sudoers file:

	sudo visudo

...  
www-data ALL=(ALL) NOPASSWD: /sbin/shutdown  
...  

Now you can shutdown or restart the RaspberrryPi from the Website. 


### Get the Sourcecode

We use `/var/www/` as install Directory, here on a fresh installed Raspian as the pi User:

	cd /var
	sudo mkdir www
	cd www
	sudo git clone https://github.com/vaddi/biotopi.git


### Prepare the System

Once we have checkout, we can change into the desired Directory, We will do all Steps from here in this Instruction:

	sudo su
	cd /var/www/biotopi


#### Helper Skript

Prepare the RaspberryPi and install all necessary Packages and Setup the I2C, SPI and other Interfaces by run the installation Script:

	./inc/assets/installation/install.sh

Keep in mind this will take some Time, the Skript does the following:

- update the system pakages
- check for essentially Packages and install them if there are not installed
- Set Folder and File Permissions
- Do some Basic File Copy Operations
- Restart Apache Webserver

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

	cp inc/assets/installation/apache2_biotopi.conf /etc/apache2/sites-available/biotopi.conf


## Ready to use

### Open the Website

Use you prefeered Webbrowser to open the Mainpage by the Hostname of your RaspberryPi:

	http://RASPBERRYPI/







Log Verzeichniss muss dem webserver user gehören, quasi wie bei dem db file.