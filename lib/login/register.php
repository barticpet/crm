<?php
    require_once 'init.php';

    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $fname = filter_input(INPUT_POST, 'fname', FILTER_SANITIZE_STRING);
    $lname = filter_input(INPUT_POST, 'lname', FILTER_SANITIZE_STRING);
    $pass = filter_input(INPUT_POST, 'password', FILTER_DEFAULT);

    if($user->registration($email, $fname, $lname, $pass)) {
        print 'Cont creat cu succes!';
        //if($user->login( $email, $pass)){
          //  header("Location: ".BASE_URL);
        //}
        die;
    } else {
        $user->printMsg();
        die;
    }
?>
