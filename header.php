<?php include_once ("include/init.php"); //print_r($_SESSION); ?>
<!DOCTYPE html>
<html lang="en">

  <head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="favicon.ico">

    <title>NEXTCALL CRM</title>

    <!-- Bootstrap core CSS -->
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
     
    <!-- Custom fonts for this template -->
    <link href="vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Lato:400,700,400italic,700italic" rel="stylesheet" type="text/css">


<!-- Custom styles for this template -->
    <link href="css/freelancer.css" rel="stylesheet">


    <link rel="stylesheet" type="text/css" href="<?php echo LIB_PATH; ?>datatables/editor/css/jquery.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo LIB_PATH; ?>datatables/editor/css/buttons.dataTables.min.css">
	<link rel="stylesheet" type="text/css" href="<?php echo LIB_PATH; ?>datatables/editor/css/select.dataTables.min.css">
	<link rel="stylesheet" type="text/css" href="<?php echo LIB_PATH; ?>datatables/editor/css/editor.dataTables.min.css">
  <link rel="stylesheet" type="text/css" href="<?php echo LIB_PATH; ?>datatables/editor/resources/syntax/shCore.css">
  
<script type="text/javascript" language="javascript" src="js/jquery-1.12.4.js"></script>
        
<script type="text/javascript" language="javascript" src="js/main.js"></script>


    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="css/bootstrap-pit.css" >



    <!-- datapicker -->
      <link rel="stylesheet" href="css/jquery-ui.min.css">
     <!-- <link rel="stylesheet" href="css/jquery-ui-timepicker-addon.css">-->

      <link href="lib/datetimepicker/css/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen">
      
    
      <link rel="stylesheet" type="text/css" href="css/main.css">
      <link rel="apple-touch-icon" sizes="57x57" href="favicon/apple-icon-57x57.png">
      <link rel="apple-touch-icon" sizes="60x60" href="favicon/apple-icon-60x60.png">
      <link rel="apple-touch-icon" sizes="72x72" href="favicon/apple-icon-72x72.png">
      <link rel="apple-touch-icon" sizes="76x76" href="favicon/apple-icon-76x76.png">
      <link rel="apple-touch-icon" sizes="114x114" href="favicon/apple-icon-114x114.png">
      <link rel="apple-touch-icon" sizes="120x120" href="favicon/apple-icon-120x120.png">
      <link rel="apple-touch-icon" sizes="144x144" href="favicon/apple-icon-144x144.png">
      <link rel="apple-touch-icon" sizes="152x152" href="favicon/apple-icon-152x152.png">
      <link rel="apple-touch-icon" sizes="180x180" href="favicon/apple-icon-180x180.png">
      <link rel="icon" type="image/png" sizes="192x192"  href="favicon/android-icon-192x192.png">
      <link rel="icon" type="image/png" sizes="32x32" href="favicon/favicon-32x32.png">
      <link rel="icon" type="image/png" sizes="96x96" href="favicon/favicon-96x96.png">
      <link rel="icon" type="image/png" sizes="16x16" href="favicon/favicon-16x16.png">
      <link rel="manifest" href="manifest.json">
      <meta name="msapplication-TileColor" content="#ffffff">
      <meta name="msapplication-TileImage" content="favicon/ms-icon-144x144.png">
      <meta name="theme-color" content="#ffffff">

  </head>

  <body id="page-top">

    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light fixed-top" id="mainNav">
      <div class="container">
        <a class="navbar-brand" href="#page-top"><img src="logo.png" class="logo"><span class="hidden-xs">NextCall CRM</span></a>
        <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
          Menu
          <i class="fa fa-bars"></i>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
          <ul class="navbar-nav ml-auto">
            <li class="nav-item"><a class="nav-link <?php if (!isset($menu)) echo 'active';  ?>" href="<?php echo BASE_URL; ?>">Home</a></li>
            <li class="nav-item"><a class="nav-link <?php if ($menu=='firme') echo 'active';  ?>" href="<?php echo BASE_URL; ?>firme.php">Firme</a></li>
            
            <?php
            if (isset($_SESSION['user']['user_role']) && ($_SESSION['user']['user_role']==6 || $_SESSION['user']['user_role']==2 || $_SESSION['user']['user_role']==1 )) {
              ?>
            <li class="nav-item"><a class="nav-link <?php if ($menu=='apeluri') echo 'active';  ?>" href="<?php echo BASE_URL; ?>apeluri.php" >Apeluri</a></li>
            <li class="nav-item"><a class="nav-link <?php if ($menu=='apeluri-revenit') echo 'active';  ?>" href="<?php echo BASE_URL; ?>apeluri-revenit.php" >Reminder <span id="reminder_text"></span></a></li>
            <? } ?>

            <?php
            if (isset($_SESSION['user']['user_role']) && ($_SESSION['user']['user_role']==2 || $_SESSION['user']['user_role']==5)) { ?>
            <li class="nav-item"><a class="nav-link <?php if ($menu=='hunter_activity') echo 'active';  ?>" href="<?php echo BASE_URL; ?>hunter_activity.php">Editari</a></li>
            <? } ?>
            
            <?php
            if (isset($_SESSION['user']['user_role']) && $_SESSION['user']['user_role']==2) { ?>
              <li class="nav-item"><a class="nav-link <?php if ($menu=='users') echo 'active';  ?>" href="<?php echo BASE_URL; ?>users.php">Useri</a></li>
              <li class="nav-item"><a class="nav-link <?php if ($menu=='statistics') echo 'active';  ?>" href="<?php echo BASE_URL; ?>statistics.php">Statistici</a></li>
            <? } ?>

            <?php if (isset($_SESSION['user']['user_role']) && ($_SESSION['user']['user_role']==6 || $_SESSION['user']['user_role']==2 || $_SESSION['user']['user_role']==1 )) { ?>
              <li class="nav-item"><a class="nav-link <?php if ($menu=='stoc') echo 'active';  ?>" href="<?php echo BASE_URL; ?>stoc.php">Stoc</a></li>
            <?php } ?>

            <?php
            if (isset($_SESSION['user'])) { ?>
            <li class="nav-item"><a class="nav-link" href="<?php echo BASE_URL; ?>lib/login/logout.php">Logout</a></li>
            <? } ?>

          </ul>
        </div>
      </div>
    </nav>