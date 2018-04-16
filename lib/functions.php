<?php

function custom_date($date){
    if (!is_null($date) && $date !='0000-00-00')
        return date("d/m/Y",strtotime($date));
}

function array_sort_by_column(&$arr, $col, $dir = SORT_DESC) {
    $sort_col = array();
    foreach ($arr as $key=> $row) {
        $sort_col[$key] = $row[$col];
    }
     //print_r($sort_col);
    array_multisort($sort_col, $dir, $arr);
}

function frmGet($id, $default = null)
{
	$ret = (isset($_REQUEST[$id]) && ("" != $_REQUEST[$id])) ? $_REQUEST[$id] : $default;
	if (is_string($ret)) $ret = trim($ret);
	if(!is_array($ret) && USE_MYSQL_ESCAPE)
		$ret = mysql_escape_string(trim($ret));
	return $ret;
}

function get_users_list($active=null){
    global $db,$config;

    $ret=array();
    $sql="SELECT * FROM users";
    if ($active==1 || $active==0)
        $sql.=" WHERE active='".$active."'";
    $sql.=" ORDER BY email DESC";
    $db->Query($sql);
    if ($db->iNumRows)
        $ret=$db->ArrayResult;


    return $ret;

}

function get_user_attempts($id){
    global $db,$config;

    $ret=array();
    if (!is_null($id)){
        $sql="SELECT * FROM user_attempts WHERE user_id='".$id."' ORDER BY id DESC";
        $db->Query($sql);
        if ($db->iNumRows)
            $ret=$db->ArrayResult;
    }

    return $ret;

}

function get_user_details($id){
    global $db,$config;

    $ret=array();
    if (!is_null($id)){
        $sql="SELECT * FROM users WHERE id=".$id;
        $db->Query($sql);
        if ($db->iNumRows)
            $ret=$db->ArrayResult[0];
    }

    return $ret;

}

function get_apel_rezultat_by_id($id){
    global $db,$config;

    $ret=array();
    if (!is_null($id)){
        $sql="SELECT 
        ra.*,
        ram.mail,ram.send_to_email,
        rai.valoare_propusa,rai.categorie_produs,rai.detalii_propunere,rai.data_estimata,
        rar.data_revenire,
        rac.*, 
        s.nr_cerere,s.tip_ctr,s.tip_livrare,s.livrare_produse,s.status,s.data_activare,s.data_livrare,s.nr_awb,s.obs AS ctr_status_obs 
        FROM rezultat_apel ra
            LEFT JOIN rezultat_apel_mail ram ON ram.rezultat_apel_id=ra.id
            LEFT JOIN rezultat_apel_interesat rai ON rai.rezultat_apel_id=ra.id
            LEFT JOIN rezultat_apel_contract rac ON rac.rezultat_apel_id=ra.id
            LEFT JOIN rezultat_apel_de_revenit rar ON rar.rezultat_apel_id=ra.id
            LEFT JOIN status_contract s ON s.firma_id =ra.firma_id AND s.last='1'
            WHERE ra.id='".$id."'" ;
        $db->Query($sql);
        if ($db->iNumRows){
            $res=$db->ArrayResult[0];
            if ($res['mail'] !=''){
                $arr_mail=explode(",",$res['mail']);
                $res['mail_arr']=$arr_mail;
            }
            $ret=$res;
        }
    }

    return $ret;

}

function get_de_revenit_today($user_id){
    global $db;
    
    $ret=0;
    $sql="SELECT ra.* FROM rezultat_apel ra
                    LEFT JOIN rezultat_apel_de_revenit rar ON rar.rezultat_apel_id=ra.id
                    WHERE ra.rezultat='3' AND last='1' AND DATE(rar.data_revenire)<=DATE(NOW()) ";
    if (!is_null($user_id))
        $sql.=" AND ra.user_id='".$user_id."'";
    $db->Query($sql);
    if ($db->iNumRows)
                $ret=$db->iNumRows;
    

    return $ret;

}

function get_apel_rezultat($id){
    global $db,$config;

    $ret=array();
    $rac_fields='';
    foreach ($config['vrea_contract'] as $field=>$val)
        $rac_fields.=',rac.'.$field;
    if (!is_null($id)){
        $sql="SELECT 
            ra.*,ram.mail,ram.id AS rezultat_apel_mail_id,
            rai.id AS rezultat_apel_interesat_id, rai.valoare_propusa,rai.categorie_produs,rai.detalii_propunere,rai.data_estimata,
            rar.id AS rezultat_apel_de_revenit_id, rar.data_revenire,
            rac.id AS rezultat_apel_contract_id".$rac_fields."  
            FROM rezultat_apel ra
            LEFT JOIN rezultat_apel_mail ram ON ram.rezultat_apel_id=ra.id
            LEFT JOIN rezultat_apel_interesat rai ON rai.rezultat_apel_id=ra.id
            LEFT JOIN rezultat_apel_contract rac ON rac.rezultat_apel_id=ra.id            
            LEFT JOIN rezultat_apel_de_revenit rar ON rar.rezultat_apel_id=ra.id
            WHERE ra.contactare_id='".$id."'
            ORDER BY ra.id DESC LIMIT 1" ;
        $db->Query($sql);
        if ($db->iNumRows)
            $ret=$db->ArrayResult[0];
    }

    return $ret;

}

function get_firma_details($id){
    global $db,$config;

    $ret=array();
    if (!is_null($id)){
        $sql="SELECT * FROM firma WHERE firma_id=".$id;
        $db->Query($sql);
        if ($db->iNumRows)
            $ret=$db->ArrayResult[0];
    }

    return $ret;

}

function check_editare_firma ($id,$before_date=''){
    global $db,$config;
    
        $ret=array();
        if (!is_null($id)){
            $sql="SELECT efd.*,ef.date FROM editare_firma ef ";
            $sql.=" LEFT JOIN editare_firma_details efd ON efd.editare_firma_id = ef.id";
            $sql.=" WHERE ef.firma_id=".$id;
            if ($before_date !='')
                $sql .= " AND DATE(date) <= '".$before_date."'";
            $db->Query($sql);
            if ($db->iNumRows)
                $ret=$db->ArrayResult;
        }
    
        return $ret;
}

function get_apeluri_firma_by_user($firma_id,$user_id){
        global $db,$config;

        $ret=array();
        if (!is_null($firma_id) && !is_null($user_id)){
                $sql="SELECT cf.* FROM contactare_firma cf
                        WHERE cf.firma_id=".$firma_id." AND cf.user_id=".$user_id." ORDER BY id DESC";
                $db->Query($sql);
                if ($db->iNumRows)
                        $ret=$db->ArrayResult;
        }

        return $ret;

}

function get_status_contract_by_firma($id){
    global $db,$config;

    $return=array();
    if (!is_null($id)){
            $sql="SELECT sc.*,u.email,u.fname,u.lname FROM status_contract sc
                    LEFT JOIN users u ON sc.user_id=u.id
                    WHERE sc.firma_id='".$id."' ORDER BY sc.id DESC";
            $db->Query($sql);
            if ($db->iNumRows){
                $ret_arr=$db->ArrayResult;
                foreach ($ret_arr as $key=>$ret){
                    if ($ret['status']==7){
                        $sql="SELECT * FROM status_contract
                        WHERE firma_id='".$id."'";
                        $db->Query($sql);
                        if ($db->iNumRows){
                            $ret_arr[$key]['data_activare']=$db->ArrayResult[0]['data_activare'];
                            $ret_arr[$key]['data_livrare_marfa']=$db->ArrayResult[0]['data_livrare_marfa'];
                            $ret_arr[$key]['data_portare']=$db->ArrayResult[0]['data_portare'];
                        }
                    }
                }
                $return=$ret_arr;
            }
    }
    //print_r($return);
    return $return;

}

function get_firma_contactare($id){
    global $db,$config;

    $ret=array();
    if (!is_null($id)){
            $sql="SELECT cf.*,u.email,u.fname,u.lname FROM contactare_firma cf
                        LEFT JOIN users u ON cf.user_id=u.id
                    WHERE cf.firma_id=".$id." ORDER BY id DESC";
            $db->Query($sql);
            if ($db->iNumRows)
                    $ret=$db->ArrayResult;
    }

    return $ret;

}

function get_firma_contact($id){
    global $db,$config;

    $ret=array();
    if (!is_null($id)){
        $sql="SELECT * FROM firma_contact WHERE firma_id=".$id;
        $db->Query($sql);
        if ($db->iNumRows)
            $ret=$db->ArrayResult;
    }

    return $ret;

}
function get_firma_operator($id){
    global $db,$config;

    $ret=array();
    if (!is_null($id)){
        $sql="SELECT * FROM firma_operator_telefonie WHERE firma_id=".$id;
        $db->Query($sql);
        if ($db->iNumRows)
            $ret=$db->ArrayResult;
    }

    return $ret;

}

function clean_content($text){
    return mysql_escape_string(trim($text));
}

function set_phone_no($text){
    return filter_var($text, FILTER_SANITIZE_NUMBER_INT);


}

function truncate_long_text($input,$long=50){
        $input = strip_tags(preg_replace('~[\r\n]+~', '',$input));
        $str = $input;
        if( strlen( $input) > $long) {
            $str = explode( "\n", wordwrap( $input, $long));
            $str = $str[0] . '...';
        }
        return $str;
}

function send_email($email_from,$email_to,$subject,$message,$attachs){
    include 'lib/mailfile.php';

    $parms = Array("subject"=>$subject,"mailto"=>$email_to,"message"=>$message,"from"=>$email_from,"files"=>$attachs);
    echo mail::sendMailParms($parms);
}

function send_email1($email_from,$email_to,$subject,$message,$attachs){
    global $mail;

    include("lib/class.simple_mail.php");

    $mail = new SimpleMail();

    $mail->setTo($email_to, 'Recipient 1')
        ->setSubject($subject)
        ->setFrom($email_from, 'Mail Bot')
        ->setReplyTo($email_from, 'Mail Bot')
        ->setHtml()
        ->setMessage($message)
        ->setWrap(100);

    if ($attachs != '' && count($attachs)){
        foreach ($attachs as $value)
            $mail->addAttachment($value);
    }
   // print_r($mail);
    $send = $mail->send();
    //$mail->debug();

    if ($send) {
        $ret=1;
    } else {
        $ret=0;
    }
    return $ret;
}

function send_email_message($email_from,$email_to,$subject,$message){
    global $db;
    //print_r($_REQUEST);
    $ret=0;
    $email_to=$email_to;
    $email_from=$email_from;
    $subject=$subject;
    $message=nl2br($message);
    if ($email_to != ''){

        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        // More headers
        //$headers .= 'From: <'.$email_from.'>' . "\r\n";
        $headers .= 'From:NextCall Team <'.$email_from.'>' . "\r\n";
        $headers .= 'BCc: barticpet@gmail.com' . "\r\n";
        //$headers .= 'BCc: barticpet@gmail.com' . "\r\n";
        //echo $email_to;
        $ret=mail($email_to,$subject,$message,$headers);
        //print_r($ret);
        //$ret=1;

    }
    return $ret;
}



?>