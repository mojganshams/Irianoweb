<?php

// Define the core paths
// Define them as absolute paths to make sure that require_once works as expected

// DIRECTORY_SEPARATOR is a PHP pre-defined constant
// (\ for Windows, / for Unix)
defined('DS') ? null : define('DS', "/");

defined('SITE_ROOT') ? null : 
	define('SITE_ROOT', 'C:'.DS.'xampp'.DS.'htdocs'.DS.'Irianoweb');

defined('IMG_PATH') ? null :
	define('IMG_PATH', SITE_ROOT.DS.'images'.DS.'site');

defined('LIB_PATH') ? null : define('LIB_PATH', SITE_ROOT.DS.'app'.DS.'models');


// load core objects
require_once(LIB_PATH.DS.'session.php');
require_once(LIB_PATH.DS.'database.php');
require_once(LIB_PATH.DS.'database_object.php');
require_once(LIB_PATH.DS.'pagination.php');
require_once(LIB_PATH.DS.'functions.php');
require_once(LIB_PATH.DS."phpMailer".DS."class.phpmailer.php");
require_once(LIB_PATH.DS."phpMailer".DS."class.smtp.php");
require_once(LIB_PATH.DS."phpMailer".DS."language".DS."phpmailer.lang-fa.php");

// load database-related classes
require_once(LIB_PATH.DS.'user.php');
require_once(LIB_PATH.DS.'post.php');
require_once(LIB_PATH.DS.'comment.php');

?>