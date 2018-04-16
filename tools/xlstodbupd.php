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
            $date='0000-00-00';//print_r($row);
            if ($row[16] !='') $date=substr($row[16],6,4)."-".substr($row[16],3,2)."-".substr($row[16],0,2);
                echo "<br>".$sql="UPDATE firma2 SET                       
                        nume_mobil='".clean_content($row[13])."',
                        nr_simuri='".clean_content($row[14])."',
                        factura='".clean_content($row[15])."',
                        data_final='".$date."',
                        obs='".clean_content($row[17])."'
                    WHERE cui='".clean_content($row[5])."'
                ";
                //$sql="SELECT * FROM firma2 WHERE cui='".clean_content($row[5])."'";
                //$db->Query($sql);
                //if ($db->iNumRows) $ret=1;
                
           //$db->Query($sql);
           return mysql_insert_id();

        }
        return $ret;
    }

    //set_time_limit(1000);
     ini_set('max_execution_time', 1200);
    $Filepath='Ntupd1.xlsx';
	try
	{
            $Spreadsheet = new SpreadsheetReader($Filepath);
	    $Sheets = $Spreadsheet -> Sheets();
            $Spreadsheet -> ChangeSheet(0);
            $count=0;
            foreach ($Spreadsheet as $key => $row){
                //print_r($row);
                $firma_id = write_in_firma($row);
                if ($firma_id!=0)
                    $count++;
            }

            echo "Operatiune terminata cu succes, Total inregistrari adaugat:".$count;
	}
	catch (Exception $E)
	{
		echo $E -> getMessage();
	}
?>
