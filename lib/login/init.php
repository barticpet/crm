<?php
require_once 'config.php';
require_once 'class/user.php';

$user = new User();
$user->dbConnect(conString, dbUser, dbPass);


?>