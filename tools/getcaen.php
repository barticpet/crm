<?php
    require_once '../include/init.php';


    function get_caen(){
        global $db,$config;

        $caen='';
        $sql="SELECT DISTINCT(activitate) FROM firma  ORDER BY activitate ASC";
            $db->Query($sql);
            if ($db->iNumRows){
                $ret=$db->ArrayResult;

                $caen_arr=array();
                foreach ($ret as $value)
                    $caen_arr[substr($value['activitate'],0,4)] = trim(substr($value['activitate'],4));

                asort($caen_arr); //print_r($caen_arr);
                foreach ($caen_arr as $key=>$value)
                    $caen.='<option value="'.$key.'">'.truncate_long_text($value,50).'</option>';


            }

        return $caen;
    }

    echo get_caen();

?>
