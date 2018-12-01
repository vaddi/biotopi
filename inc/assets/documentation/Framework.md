# The BiotoPi Framework

To make it easier adding new functions or Classes this API can be used as an very raw PHP Framework. It will helps to easily create new Classes (interfaces and or extdension depending classes). They are used as a Base for action calls (class methods) and as parameter resolver and sanitizer.


## Settings

Constants can be changed/created directly in the `config.php` file.

#### Constants

List of currnetly implemented constants. Just add one to use them into a Controller.


###### Security SHA Token

The used String to generate a SHA Token.


###### Database connection

Setup the Database and its depending Parameters by choose a Database type: 

MySQL

*  Database Host
*  Database Username
*  Database Userpassword
*  Database Port

SQLite

*  Database Type (FILE/MEM)
*  Database File


###### Server Timezone

The used default Timezone. 


###### Running Enviroment (dev/prod)

Set the used Server enviroment. Currently there are **prod**uction and **dev**elopment enviroments implemented. Feel free to add a testing Suite or something else.


## Token based secrurity calls

In the `prod` enviroment the API calls will be validated against a changing SHA token. A JWT implemention is planned, but now we have to use this solution. You need to add the Token in the http param `tk` like:

	http://APP/?tk=123456&controller=devices&action=read&id=1

Or in a javascript POST Object

	const postObj = {
		'tk': 				'123456',
		'controller':		'devices',
		'action':			'read',
		'id':				'1',
	}


## PHP Classes

PHP Classes will automaticly instanciated by the API. Just place a Controller by the Same Classname into the `inc/class/controller` folder. Normaly they're named first Character in uppercase like `Daemon` or `Devices`. Take a look in the existing Controller to see how they're work.

If there is the need for extensions or interfaces, just place them into the `inc/class/extensions` or `inc/class/interfaces` folder and use them normaly in your Controller by `extends` and `implements` Class clauses. They will be autoloaded from the API  [spl\_autoload\_register](http://php.net/manual/en/language.oop5.autoload.php).


## Requests

After creating a Controller they can be used by a simple HTTP request:

	http://APP/index.php?controller=devices&action=read
or short

	http://APP/?controller=devices&action=read

#### Parameters

The usable Parameters will be defined by the parameters of the Controllers `__constructor` method. Other parameters are defined into the `API.php` class:

*  debug			0 or 1
*  controller		The name in lowercase of the controller class
*  action			The name of the classmethod



## Response

The Response is a JSON object which contain a state (true if call succeded, false if there was an Error) and the returned data (e.g. arrays from SQL Calls).

A Request and Response Example:

###### Request

```
http://APP/?controller=devices&action=read
```

###### Response

```PHP
Array (
	[state] => 1
	[data] => Array (
		[0] => Array (
			[id] => 3
			[name] => hum1
			...
		)
	)
)
```



## ToDo

*  Imlementing [JWT](https://github.com/firebase/php-jwt) for Token Auth API calls.

