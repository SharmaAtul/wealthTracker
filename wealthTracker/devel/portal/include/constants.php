<?php

session_start();

//phpinfo();

// PATHS
define('DOMAIN','ifswa.com.au');
define('BASE_WEB','http://www.ifswa.com.au');
define('BASE_WEB_SECURE','https://www.ifswa.com.au');

//define('DEVEL_REDIRECT',15); // seconds
define('LOGIN_ATTEMPT_MAX',3);  // maximum number of incorrect login attempts

if( substr_count($_SERVER['HTTP_HOST'], DOMAIN) == 1  && preg_match('/devel/', $_SERVER['SCRIPT_FILENAME']) == false)
{
	//LIVE
	define('DEV_SERVER', false);
	define('HOSTNAME', 'http://www.ifswa.com.au/');
	define('ROOT', '/home/ifswacom/www/');
	define('SECURE_ROOT', '/home/ifswacom/www/');
	define('SECURE_DOMAIN', 'https://www.ifswa.com.au/');
	define('SECURE_ROOT_WEB', SECURE_DOMAIN);
	define('HTTPS_ON', true);
	
	define('DB_HOST', 'localhost');
	define('DB_NAME', 'ifswacom_prod');
	define('DB_USERNAME', 'ifswacom_34ewr43');
	define('DB_PASSWORD', 'y8rh438yrsada');
	
	define('NEXT_SYSTEM_NAME', 'IFSWA');
	define('NEXT_SYSTEM_EMAIL', 'info@ifswa.com.au');
	define('ADMIN_EMAIL', 'info@vaughanmasters.com.au');
	define('REGISTER_EMAIL', 'info@ifswa.com.au');
	define('SYSTEM_EMAIL', 'info@ifswa.com.au');
	define('QUERY_EMAIL', 'info@ifswa.com.au');
	define('APPOINTMENT_EMAIL', 'info@ifswa.com.au');
	define('BULK_EMAIL', 'info@ifswa.com.au');	
}
else 
{
	// DEVELOPMENT
	define('HOSTNAME', 'http://www.ifswa.com.au/devel/');
	define('ROOT', '/home/ifswa/www/devel/');
	define('SECURE_ROOT', '/home/ifswa/www/devel/');
	define('SECURE_DOMAIN', 'https://www.ifswa.com.au/devel/');
	define('SECURE_ROOT_WEB', SECURE_DOMAIN);
	define('HTTPS_ON', true);
	
	define('DB_HOST', 'localhost');
	define('DB_NAME', 'ifswacom_devel');
	define('DB_USERNAME', 'ifswacom_34ewr43');
	define('DB_PASSWORD', 'y8rh438yrsada');

	define('NEXT_SYSTEM_NAME', 'DEVEL IFSWA');
	define('NEXT_SYSTEM_EMAIL', 'info@vaughanmasters.com.au');
	define('ADMIN_EMAIL', 'info@vaughanmasters.com.au');
	define('REGISTER_EMAIL', 'info@vaughanmasters.com.au');
	define('SYSTEM_EMAIL', 'info@vaughanmasters.com.au');
	define('QUERY_EMAIL', 'info@vaughanmasters.com.au');
	define('APPOINTMENT_EMAIL', 'info@vaughanmasters.com.au');
	define('BULK_EMAIL', 'info@vaughanmasters.com.au');
	
	error_reporting(E_ERROR | E_WARNING);
	ini_set('display_errors','On');
	
}

// SET SERVER TIME TO WA!!!!
//putenv("TZ=-08-08"); 

define('ROOT_WEB', HOSTNAME);
//define('ADMIN_ROOT', SECURE_ROOT . 'portal/admin/');
//define('ADMIN_ROOT_WEB', SECURE_ROOT_WEB . 'portal/admin/');
define('ACTION_SCRIPT',ROOT_WEB.'portal/actions/action.php');

// defaults
define('PASSWORD_LENGTH', 6); // minimum length of password
define('HISTORICAL',60*60*24*5); // how old a record can be before it is classed as historical (read only) - 5 days

// contacts
define('OFFICE_PHONE','(08) 9201 3300');
define('OFFICE_FAX','(08) 9201 2077');

// includes
include("database.php");
include("functions.php");
include("date.php");

// connect to the database
db_connect();

// if the user is logged in, they must be on the secure server.
//  OR if they are in the admin.
if(HTTPS_ON == true && $secure_required == true && $_SERVER['HTTPS'] != "on")
{
	//Header("Location: " . substr(BASE_WEB_SECURE, 0, -1) . $_SERVER['PHP_SELF']);
	Header("Location: " . BASE_WEB_SECURE . $_SERVER['PHP_SELF']);
}


?>