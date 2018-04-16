<?php
	require_once 'init.php';
	$user->indexHead();
	$user->indexTop();
	$user->loginForm();
	//$user->activationForm();
	$user->indexMiddle();
	$user->registerForm();
	$user->indexFooter();
?>
