<?php
    require_once("../include/config.php");
    require_once(BASE_PATH.'lib/functions.php');

//	session_name(SESSION_NAME);
//	session_cache_expire(1440); // 24 Stunden
session_start();

require_once(BASE_PATH."lib/db.class.php");
$db = new db();
$db->SetVariables();
$db->Connect();
    require('xlsreader/php-excel-reader/excel_reader2.php');
    require('xlsreader/SpreadsheetReader.php');

    function write_in_firma($row){
        global $db;

        $ret=0;
        if (is_array($row) && count($row) && !empty($row[0])){
            $date='0000-00-00';
            if ($row[16] !='') $date='20'.substr($row[16],6,4)."-".substr($row[16],0,2)."-".substr($row[16],3,2);
                //print_r($row);$sql="INSERT INTO firma2 SET 
                //echo "<br><hr>".substr($row[16],6,4).'<br>'.$row[16].'<br>'.$sql="INSERT INTO firma2 SET
                $sql="INSERT INTO firma2 SET        
                    nume='".clean_content($row[0])."',
                    judet='".clean_content($row[1])."',
                    localitate='".clean_content($row[2])."',
                    adresa='".clean_content($row[3])."',
                    numar='".clean_content($row[4])."',
                    cui='".clean_content($row[5])."',
                    activitate='".clean_content($row[6])."',
                    cifra_afaceri='".clean_content($row[7])."',
                    nume_contact='".clean_content($row[8])."',
                    telefon='".clean_content($row[9])."',
                    mobil='".clean_content($row[10])."',
                    email='".clean_content($row[11])."',
                    web='".clean_content($row[12])."',
                    nume_mobil='".clean_content($row[13])."',
                    nr_simuri='".clean_content($row[14])."',
                    factura='".clean_content($row[15])."',
                    data_final='".$date."',
                    obs='".clean_content($row[17])."'
                ";
           //$db->Query($sql);
            //return mysql_insert_id();

        }
        return $ret;
    }

    //set_time_limit(1000);
     ini_set('max_execution_time', 1200);
    $Filepath='Vn2.xlsx';
	try
	{
            $Spreadsheet = new SpreadsheetReader($Filepath);
	    $Sheets = $Spreadsheet -> Sheets();
            $Spreadsheet -> ChangeSheet(0);
            $count=0;
            foreach ($Spreadsheet as $key => $row){
                //print_r($row);
                $firma_id = write_in_firma($row);
                $count++;
            }

            echo "Operatiune terminata cu succes, Total inregistrari adaugat:".$count;
	}
	catch (Exception $E)
	{
		echo $E -> getMessage();
	}
?>
