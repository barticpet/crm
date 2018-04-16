<?php
	require_once 'init.php';
        include_once '../../include/init.php';
        include_once '../../include/header.php';
	if(isset($_SESSION['user']) && $_SESSION['user']['id'] !== ''){
	  	$user->userPage();
	}else{
		header('Location: index.php');
	}
?>
