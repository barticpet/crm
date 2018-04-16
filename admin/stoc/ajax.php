<?php

require_once '../../include/init.php';

$action=frmGet('action');
switch ($action){
    case 'parse_stoc_file' :
        parse_stoc_file();
    break;
}

function parse_stoc_file(){
    global $db;

    $count=0;

    $file=frmGet('file');

    if (($handle = fopen($file, "r")) !== FALSE) {
        $db->Query("UPDATE stoc SET last ='0'");
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            $ret=write_in_stoc($data);
            if ($ret != 0)
                $count++;  
        }
        fclose($handle);
    }

   /* $content = file($file);
    $array = array();
    
    if (count($content)>1){
        $db->Query("UPDATE stoc SET last ='0'");
        for($i = 1; $i < count($content); $i++) {
            $line = explode(',', $content[$i]);
            for($j = 0; $j < count($line); $j++) {
                $array[$i][$j + 1] = $line[$j];
            } 
            $ret=write_in_stoc($array[$i]);
            if ($ret != 0)
                $count++;  
        } 
    } */  
    echo $count;    
    
}

function write_in_stoc($row){
    global $db;

    $ret=0;
    if (is_array($row) && count($row) && !empty($row[0]) && $row[0]!= 'Denumire'){
        $sql="INSERT INTO stoc SET        
                denumire='".clean_content(trim ($row[0],'"'))."',
                pret_fmc ='".clean_content(substr(str_replace(",","",$row[1]),1))."',
                stoc_tkr ='".clean_content(intval($row[2]))."',
                stoc_tkrm ='".clean_content(intval($row[3]))."',
                realimentare ='".clean_content($row[4])."'               
            ";
        $db->Query($sql);
        $ret=mysql_insert_id();

    }
    return $ret;
}



?>