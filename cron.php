<?php
require_once("include/config.php");

require_once("lib/db.class.php");
$db = new db();
$db->SetVariables();
$db->Connect();

$db->Query("DELETE FROM contactare_firma Where firma_id NOT IN  (SELECT DISTINCT firma_id FROM `rezultat_apel` )  
ORDER BY `contactare_firma`.`firma_id` ASC");


?>