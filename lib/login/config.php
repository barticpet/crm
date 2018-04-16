<?php
session_start();
require_once("../../include/config.php");

define('conString', 'mysql:host=localhost;dbname='.$config['db_db']);
define('dbUser', $config['db_user']);
define('dbPass', $config['db_password']);


define('loginsuccessfile', BASE_URL);
define('userfile', 'user.php');
define('loginfile', 'login.php');
define('activatefile', 'activate.php');
define('registerfile', 'register.php');


//template files
define('indexHead', 'inc/indexhead.htm');
define('indexTop', 'inc/indextop.htm');
define('loginForm', 'inc/loginform.php');
define('activationForm', 'inc/activationform.php');
define('indexMiddle', 'inc/indexmiddle.htm');
define('registerForm', 'inc/registerform.php');
define('indexFooter', 'inc/indexfooter.htm');
define('userPage', 'inc/userpage.php');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

