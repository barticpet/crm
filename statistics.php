<?php
$menu='statistics';
include_once 'header.php';
$what=frmGet('what','default');
    if (isset($_SESSION['user']['user_role']) && $_SESSION['user']['user_role']==2) {
        if ($what=='default'){
            include_once("statistics_default.php");
        }
        elseif ($what=='contract'){
            include_once("statistics_contract.php");
        } 
?>

<?php
}
include_once 'footer.php';
?>

