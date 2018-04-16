<?php
    require_once 'init.php';

    $user->logout();

    header('location: index.php');
?>
