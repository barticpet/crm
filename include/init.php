<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/crm/'."include/config.php");
require_once(BASE_PATH.'lib/functions.php');

//	session_name(SESSION_NAME);
//	session_cache_expire(1440); // 24 Stunden
session_start();

require_once(BASE_PATH."lib/db.class.php");
$db = new db();
$db->SetVariables();
$db->Connect();

if (!isset($_SESSION['user']))
  header('Location: lib/login/index.php');


?>