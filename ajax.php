<?php

require_once 'include/init.php';

$action=frmGet('action');
switch ($action){
    case 'set_rezultat_apel' :
        set_rezultat_apel();
    break;
    case 'get_rezultat_apel_by_id' :
        get_rezultat_apel_by_id();
    break;
    case 'lista_firme' :
        lista_firme();
    break;
    case 'lista_apeluri_firma' :
        lista_apeluri_firma();
    break;
    case 'detaliu_firma' :
        detaliu_firma();
    break;
    case 'editare_firma' :
        editare_firma();
    break;
    case 'save_firma' :
        save_firma();
    break;
    case 'delete_firma' :
        delete_firma();
    break;
    case 'contactare_firma' :
        contactare_firma();
    break;
    case 'detaliu_user' :
        detaliu_user();
    break;
    case 'get_localitati_by_judet' :
        get_localitati_by_judet();
    break;
    case 'get_apeluri_localitati_by_judet' :
        get_apeluri_localitati_by_judet();
    break;
    case 'editare_user' :
        editare_user();
    break;
    case 'save_user' :
        save_user();
    break;
    case 'delete_user' :
        delete_user();
    break;
    case 'delete_apel' :
        delete_apel();
    break;
    case 'get_vrea_contract_content' :
        get_vrea_contract_content();
    break;
    case 'get_mail_content' :
        get_mail_content();
    break;
    case 'get_user_statistics' :
        get_user_statistics();
    break;
    case 'get_user_statistics_contract' :
        get_user_statistics_contract();
    break;
    case 'save_status_contract':
        save_status_contract();
    break;
    case 'get_status_contract_option':
        get_status_contract_option();
    break;
    case 'delete_status_contract':
        delete_status_contract();
    break;
    case 'check_contactare_firma':
        check_contactare_firma();
    break;
    case 'get_reminder_text':
        get_reminder_text();
    break;

}

function get_reminder_text(){
    $reminder_user_id= ($_SESSION['user']['user_role']==1  || $_SESSION['user']['user_role']==6)?$_SESSION['user']['id']:null;
    $reminder=get_de_revenit_today($reminder_user_id);
    echo ($reminder != 0)? "(".$reminder.")":'';
}

function check_contactare_firma(){
    
    $ret=1;

    $contactare= get_firma_contactare(frmGet('id'));
    if (count($contactare)){
        if ($_SESSION['user']['user_role']==2)
            $ret=0;
        else if ($_SESSION['user']['user_id']==$contactare['user_id'])
            $ret=0;
    } else $ret=0; 

    echo $ret;
}

function get_user_statistics_contract(){
    global $db,$config;

    $ret_txt='';

    require_once("lib/statistics.class.php");
    $statistics = new Statistics;
    $statistics->user_id=frmGet('user_id');
    $statistics->start_date=frmGet('start_date');
    $statistics->end_date=frmGet('end_date');
    $statistics->user_statistics_contract();
    
    //print_r($statistics->status_contract_statistics);

    if (count($statistics->status_contract_statistics)){
        foreach ($config['status_contract'] as $key => $value)
            if ($key>9) {
                $ret_txt.='<tr>
                                <td><a href="#" onclick="show_status_details(\''.$key.'\');"><b>'.$value.'</b><span class="pull-right clickable"><i id="glyphicon-chevron_'.$key.'" class="glyphicon glyphicon-chevron-up"></i></span></a><div id="status_details_div_'.$key.'" class="status_details_div"><br>';
                        if (isset($statistics->status_contract_statistics[$key]['firme']))
                            foreach ($statistics->status_contract_statistics[$key]['firme'] as $val)
                                $ret_txt.='<span class="status_firma_details">'.$val.'</span>';
                        $ret_txt.='<button type="button" onclick="show_status_details(\''.$key.'\');"  class="btn btn-secondary btn-sm status_details_div_close_btn">Close</button><div></td>
                                <td>'.$statistics->status_contract_statistics[$key]['nr_free'].'</td>
                                <td>'.$statistics->status_contract_statistics[$key]['val_free'].'&euro;</td>
                                <td>'.$statistics->status_contract_statistics[$key]['val_tel'].'&euro;</td>
                                <td>'.$statistics->status_contract_statistics[$key]['val_bo'].'&euro;</td>
                                <td>'.$statistics->status_contract_statistics[$key]['serv_fixe'].'&euro;</td>
                                <td>'.$statistics->status_contract_statistics[$key]['val_prod_rate'].'&euro;</td>
                                <td>'.$statistics->status_contract_statistics[$key]['val_cloud'].'&euro;</td>
                               
                            </tr>';
                $nr_free+=$statistics->status_contract_statistics[$key]['nr_free'];
                $val_free+=$statistics->status_contract_statistics[$key]['val_free'];
                $val_tel+=$statistics->status_contract_statistics[$key]['val_tel'];
                $val_bo+=$statistics->status_contract_statistics[$key]['val_bo'];
                $serv_fixe+=$statistics->status_contract_statistics[$key]['serv_fixe'];
                $val_prod_rate+=$statistics->status_contract_statistics[$key]['val_prod_rate'];
                $val_cloud+=$statistics->status_contract_statistics[$key]['val_cloud'];
            }
 
        $ret_txt.='<tr>
                        <th>Total</td>
                        <th>'.$nr_free.'</th>
                        <th>'.$val_free.'&euro;</th>
                        <th>'.$val_tel.'&euro;</th>
                        <th>'.$val_bo.'&euro;</th>
                        <th>'.$serv_fixe.'&euro;</th>
                        <th>'.$val_prod_rate.'&euro;</th>
                        <th>'.$val_cloud.'&euro;</th>
                    </tr>';         
        
    } else $ret_txt='<tr><td colspan="7">Nu sunt inregistrari</td></tr>';
    
   echo $ret_txt;

    
}

function get_user_statistics(){
    global $db;

    $ret_txt='';

    require_once("lib/statistics.class.php");
    $statistics = new Statistics;
    $statistics->sortby=frmGet('sortby');
    $statistics->sortdir=frmGet('sortdir');
    $statistics->user_id=frmGet('user_id');
    $statistics->start_date=frmGet('start_date');
    $statistics->end_date=frmGet('end_date');
    $statistics->user_statistics();
    
    //print_r($statistics);

    if ($statistics->ret['firme_sunate']>0){
        if ($statistics->user_id == '' && count($statistics->ret['users'])){
            foreach ($statistics->ret['users'] as $key=>$value){
                $user_details=get_user_details($value['user_id']);
                $ret_txt.='<tr>
                                <td>'.($key+1).'. '.$user_details['fname'].' '.$user_details['lname'].'</td>
                                <td>'.$value['firme_sunate'].'</td>
                                <td>'.$value['discutii'].'</td>
                                <td>'.$value['vrea_contract'].'</td>
                                <td>'.$value['medii_discutii_zi'].'</td>
                            </tr>';

            }
        } 
        $ret_txt.='<tr>
                        <th>Total</td>
                        <th>'.$statistics->ret['firme_sunate'].'</th>
                        <th>'.$statistics->ret['discutii'].'</th>
                        <th>'.$statistics->ret['vrea_contract'].'</th>
                        <th>'.$statistics->ret['medii_discutii_zi'].'</th>
                    </tr>';
         
        
    } else $ret_txt='<tr><td colspan="6">Nu sunt inregistrari</td></tr>';
    
    echo $ret_txt;
    
}

function get_status_contract_option(){
    global $config;
    
    $ret='<option value="0">Alege</option>';
    if (count($config['status_contract'])){
        foreach($config['status_contract'] as $key=>$value)
        if ($key>=11){
            $ret.='<option value="'.$key.'">'.$value.'</option>';
        }
    }
    echo $ret;
}

function save_status_contract(){
    global $config,$db;

    $ret=0;
    $frm=frmGet('form_status_contract');
    if ($frm != null){
        parse_str($frm, $fields);
        if ( !is_null($fields['id']) && isset($_SESSION['user'])){
    
            $sql="UPDATE status_contract SET last='0' WHERE firma_id = '".clean_content($fields['firma_id'])."'";
            $db->Query($sql);

            if ($fields['id'] == '0'){
               $sql="INSERT INTO status_contract SET ";
            } else {
                $sql="UPDATE status_contract SET ";
            }

            $sql.= " 
                firma_id = '".clean_content($fields['firma_id'])."', 
                nr_cerere = '".clean_content($fields['nr_cerere'])."', 
                nr_awb = '".clean_content($fields['nr_awb'])."', 
                tip_ctr = '".clean_content($fields['tip_ctr'])."', 
                tip_livrare = '".clean_content($fields['tip_livrare'])."', 
                livrare_produse = '".clean_content($fields['livrare_produse'])."', 
                status = '".clean_content($fields['status'])."',
                data_activare = '".clean_content($fields['data_activare'])."',
                data_livrare = '".clean_content($fields['data_livrare'])."',
                obs = '".clean_content($fields['ctr_status_obs'])."', 
                user_id = '".clean_content($_SESSION['user']['id'])."',
                last='1' 
            ";
            if ($fields['id'] != '0')
                $sql .= " WHERE id=".$fields['id'];
            $db->Query($sql);
            
            if ($fields['id'] == '0')
                $fields['id']=mysql_insert_id();

            $ret=$fields['firma_id'];
            
            
        }
                
    }
    echo $ret;

}

function get_vrea_contract_content(){
    global $config;

    $ret=array();
    if (count($config['vrea_contract'])){
        foreach($config['vrea_contract'] as $key=>$value){
            if ($key == 'detalii_bo' || $key == 'obs')
                $ret['contract_option'].='<div class="form-group">
                        <label for="'.$key.'">'.$value.':</label>
                        <textarea name="'.$key.'" id="'.$key.'"  value="" class="form-control 9_option_field"></textarea>
                    </div>';
            else $ret['contract_option'] .='
                <div class="form-group form_inline">
                    <label for="'.$key.'">'.$value.':</label>
                    <input type="text" name="'.$key.'" id="'.$key.'"  value="" class="form-control 9_option_field vs_small_input">
                </div>
            ';
        }
    }
    if ($_SESSION['user']['user_role']=='2' || $_SESSION['user']['user_role']=='4')
        $ret['status_contract']=file_get_contents('include/popup/status_contract.html');
    echo json_encode($ret);
}

function get_mail_content(){
    global $config;

    $ret='';
    if (count($config['rezultat_apel_email'])){
        foreach($config['rezultat_apel_email'] as $key=>$value){
            $ret.='<input type="checkbox" name="tip_email[]" id="tip_email'.$key.'" class="tip_email" value="'.$key.'">&nbsp;'.$value.'<br>';
        }
    }
    echo $ret;
}

function get_rezultat_apel_by_id(){
    global $db;

    $ret='';
    $id=frmGet('id');
    if (!is_null($id)){
        $res=get_apel_rezultat_by_id($id);
        if (count($res))
            $ret=json_encode($res);
    }
    echo $ret;
}

function get_localitati_by_judet(){
    global $db;

    $ret='';
    $judet=frmGet('judet');
    if ($judet != null){
        $sql = "SELECT DISTINCT localitate, COUNT(localitate) AS no FROM firma WHERE judet='".$judet."' GROUP BY localitate
ORDER BY localitate  ASC";
        $db->Query($sql);
        if ($db->iNumRows){
            $localitati=$db->ArrayResult;
            $ret='<select class="form-control" style="margin-top:5px;" id="select_localitate" name="select_localitate" onchange="firme_list();"><option value="">Localitate</option>';
            foreach ($localitati as $value)
                $ret.='<option value="'.$value['localitate'].'">'.$value['localitate'].'</option>';
                //$ret.='<option value="'.$value['localitate'].'">'.$value['localitate'].' ('.$value['no'].') </option>';
            $ret.='</select>';
        }
    }
    echo $ret;

}

function get_apeluri_localitati_by_judet(){
    global $db;

    $ret='';
    $judet=frmGet('judet');
    if ($judet != null){
        $sql = "SELECT DISTINCT f.localitate, COUNT(f.localitate) AS no,ra.id FROM rezultat_apel ra
                LEFT JOIN firma f ON f.firma_id=ra.firma_id
                WHERE f.judet='".$judet."' GROUP BY f.localitate
                ORDER BY localitate  ASC";
        $db->Query($sql);
        if ($db->iNumRows){
            $localitati=$db->ArrayResult;
            $ret='<select class="form-control" id="select_localitate" nme="select_localitate" onchange="show_apeluri_by_raspuns();"><option value="">Localitate</option>';
            foreach ($localitati as $value)
                $ret.='<option value="'.$value['localitate'].'">'.$value['localitate'].' ('.$value['no'].') </option>';
            $ret.='</select>';
        }
    }
    echo $ret;

}

function save_user(){
    global $db,$config;

    $ret=0;
    $user=frmGet('user');
    if ($user != null){
        parse_str($user, $fields);
        if ( !is_null($fields['id'])){
            if ($fields['id'] == '0'){
               $sql = "SELECT id FROM users WHERE email='".$fields['email']."'";
                $db->Query($sql);
                if ($db->iNumRows) {
                    echo 0;
                    return;
                } else $sql="INSERT INTO users SET ";
            } else $sql="UPDATE users SET ";
            foreach ($config['editare_user_fields'] as $val)
                if (isset($fields[$val]))
                    $sql.= $val. " = '".clean_content($fields[$val])."', ";
            $sql =substr($sql,0,strlen($sql)-2);
            if ($fields['id'] == '0')
                $sql.=", confirmed='1'";
                else $sql .= " WHERE id=".$fields['id'];
            $db->Query($sql);
            if ($fields['id'] == '0')
                $fields['id']=mysql_insert_id();
            $ret=$fields['id'];
        }
    }
    echo $ret;
}

function editare_user(){
    global $db;

    $ret='';
    $id=frmGet('id');
    if (!is_null($id)){
        $user=get_user_details($id);
        if (count($user))
            $ret=json_encode($user);
    }
    echo $ret;
}

function delete_user(){
    global $db;

    $ret=0;
    $id=frmGet('id');
    if (!is_null($id) && isset($_SESSION['user'])){
        $sql="UPDATE users SET active = '0' WHERE id='".$id."'";
        $db->Query($sql);
        $ret=1;
    }

    echo $ret;

}


function delete_status_contract(){
    global $db;

    $ret=0;
    $id=frmGet('id');
    if (!is_null($id) && isset($_SESSION['user'])){
        $sql = "SELECT firma_id FROM status_contract WHERE id='".$id."'";
        $db->Query($sql);
        if ($db->iNumRows)
            $firma_id=$db->ArrayResult[0]['firma_id'];

        $sql="DELETE FROM status_contract WHERE id='".$id."'";
        $db->Query($sql);

        $sql="UPDATE status_contract
                SET  last='1'
                WHERE firma_id = '".$firma_id."'
                ORDER BY date DESC LIMIT 1
            ";
        $db->Query($sql);

        $ret=$firma_id;
    }

    echo $ret;

}

function delete_apel(){
    global $db;

    $ret=0;
    $id=frmGet('id');
    if (!is_null($id) && isset($_SESSION['user'])){
        $sql = "SELECT firma_id FROM contactare_firma WHERE id='".$id."'";
        $db->Query($sql);
        if ($db->iNumRows)
            $firma_id=$db->ArrayResult[0]['firma_id'];

        $sql = "SELECT * FROM rezultat_apel WHERE contactare_id='".$id."'";
        $db->Query($sql);
        if ($db->iNumRows){
            $res=$db->ArrayResult;
            foreach ($res as $value){
                if ($value['rezultat']==7){
                    $sql="DELETE FROM rezultat_apel_interesat WHERE rezultat_apel_id='".$value['id']."'";
                    $db->Query($sql);
                }
                elseif ($value['rezultat']==8){
                    $sql="DELETE FROM rezultat_apel_mail WHERE rezultat_apel_id='".$value['id']."'";
                    $db->Query($sql);
                }
                elseif ($value['rezultat']==9){
                    $sql="DELETE FROM rezultat_apel_contract WHERE rezultat_apel_id='".$value['id']."'";
                    $db->Query($sql);
                }
            }
        }

        $sql="DELETE FROM contactare_firma WHERE id='".$id."'";
        $db->Query($sql);

        $sql="DELETE FROM rezultat_apel WHERE contactare_id='".$id."'";
        $db->Query($sql);

        $sql="UPDATE rezultat_apel
                SET  last='1'
                WHERE firma_id = '".$firma_id."'
                ORDER BY date DESC LIMIT 1
            ";
        $db->Query($sql);
        $ret=1;
    }

    echo $ret;

}

function save_firma(){
    global $db,$config;

    $ret=0;
    $firma=frmGet('firma');
    if ($firma != null){
        parse_str($firma, $fields);
        if ( !is_null($fields['firma_id'])){
            if ($fields['firma_id']==0){
                $sql = "SELECT firma_id FROM firma WHERE cui='".$fields['cui']."'";
                $db->Query($sql);
                if ($db->iNumRows) {
                    echo 'cui';
                    return;
                } $sql="INSERT INTO firma SET ";
            } else $sql="UPDATE firma SET ";

            foreach ($config['editare_firma_fields'] as $val)
                if (isset($fields[$val]))
                    $sql.= $val. " = '".clean_content($fields[$val])."', ";
            $sql =substr($sql,0,strlen($sql)-2);

            if ($fields['firma_id']!=0){
                $sql .= " WHERE firma_id=".$fields['firma_id'];

                $db->Query( "SELECT * FROM firma WHERE firma_id='".$fields['firma_id']."'");
                $old_info='';
                if (count($db->ArrayResult)){
                    $old_info=$db->ArrayResult[0];//print_r($old_info);
                    $changes=array();
                    foreach ($old_info as $key=>$value)
                        if ($key!='active' && $fields[$key] != $value)
                            $changes[$key]= array('old'=>$value,'new'=>$fields[$key]);
                    //print_r($changes);
                    if (count($changes)){                        
                        $sql1="INSERT INTO editare_firma SET 
                            firma_id='".$fields['firma_id']."',
                            user_id='".$_SESSION['user']['id']."'
                        ";
                        $db->Query($sql1);
                        $editare_firma_id=mysql_insert_id();
                        foreach ($changes as $key=>$value){
                            $sql1="INSERT INTO editare_firma_details SET 
                                field='".$key."',
                                old='".$value['old']."',
                                new='".$value['new']."',
                                firma_id='".$fields['firma_id']."',
                                editare_firma_id='".$editare_firma_id."'
                            ";
                            $db->Query($sql1);
                        }
                    }
                }
                


            }

            $db->Query($sql);

            if ($fields['firma_id']==0)
                $ret=mysql_insert_id();
            else $ret=$fields['firma_id'];
        }
    }
    echo $ret;
}

function editare_firma(){
    global $db;

    $ret='';
    $id=frmGet('id');
    if (!is_null($id)){
        $firma=get_firma_details($id);
        if (count($firma))
            $ret=json_encode($firma);
    }
    echo $ret;
}

function lista_apeluri_firma(){
    global $db;

    $ret='';
    $id=frmGet('id');
    if (!is_null($id) && isset($_SESSION['user'])){
        $contactare=get_apeluri_firma_by_user($id,$_SESSION['user']['id']);
            if (count($contactare)){
                $ret.='<input type="hidden" id="firma_id"  name="firma_id" value="'.$id.'">
                        <div class="form-group" >
                        <label for="id_apel">Apelul:</label>
                        <select id="id_apel" name="id_apel" class="form-control">';
                foreach ($contactare as $value)
                    $ret.='<option value="'.$value['id'].'">'.$value['date'].'</option>';

                $ret.='</select>
                    </div>';
            }
    }
    echo $ret;
}

function set_rezultat_apel(){
    global $db,$config;

    $ret=0;
    //$firma_id=frmGet('firma_id');

    $fields_rezultat_apel = array();
    parse_str(frmGet('form_rezultat_apel'), $fields_rezultat_apel);

    if (!is_null($fields_rezultat_apel['firma_id']) && isset($_SESSION['user'])){
        $rezultat=$fields_rezultat_apel['rezultat'];
        $observatie=$fields_rezultat_apel['observatie'];
        $contactare_id=$fields_rezultat_apel['contactare_id'];
        $firma_id=$fields_rezultat_apel['firma_id'];
        $rezultat_apel_history='';
        $current_rezultat_apel=get_apel_rezultat($contactare_id);
        //print_R($current_rezultat_apel);        
        if (count($current_rezultat_apel) && $current_rezultat_apel['user_id'] != $_SESSION['user']['id']){
            $rezultat_apel_id=$current_rezultat_apel['id'];
            if ($current_rezultat_apel['rezultat']==$rezultat){
                if ($rezultat==8)
                    $rezultat_apel_mail_id=$current_rezultat_apel['rezultat_apel_mail_id'];
                elseif ($rezultat==3)    
                    $rezultat_apel_de_revenit_id=$current_rezultat_apel['rezultat_apel_de_revenit_id'];
                elseif ($rezultat==9)
                    $rezultat_apel_contract_id=$current_rezultat_apel['rezultat_apel_contract_id'];                
            }
            
            $sql="UPDATE rezultat_apel
                    SET
                        rezultat = '".$rezultat."',
                        observatie = '".clean_content($observatie)."'
                    WHERE id='".$rezultat_apel_id."'
                ";
            $db->Query($sql);
            $rezultat_apel_history[]=$sql;
        } else {
            $sql="UPDATE rezultat_apel
                    SET  last='0'
                    WHERE firma_id = '".$firma_id."'
                ";
            $db->Query($sql);
            $rezultat_apel_history[]=$sql;

            $sql="INSERT INTO rezultat_apel
                    SET
                        firma_id = '".$firma_id."',
                        user_id='".$_SESSION['user']['id']."',
                        rezultat = '".$rezultat."',
                        observatie = '".clean_content($observatie)."',
                        contactare_id = '".$contactare_id."',
                        date=NOW()
                ";
            $db->Query($sql);
            $rezultat_apel_history[]=$sql;
            $rezultat_apel_id=mysql_insert_id();
        }     

        //$mail=frmGet('mail');
        $mail=(count($fields_rezultat_apel['tip_email']))? implode(",",$fields_rezultat_apel['tip_email']):'';
        $send_to_email=$fields_rezultat_apel['send_to_email'];
        if ($rezultat == 8 && $send_to_email != ''){
            if (isset($rezultat_apel_mail_id))
                $sql="UPDATE rezultat_apel_mail
                SET
                    mail='".$mail."',
                    send_to_email='".$send_to_email."'
                WHERE id='".$rezultat_apel_mail_id."'";

            else $sql="INSERT INTO rezultat_apel_mail
                SET
                    rezultat_apel_id = '".$rezultat_apel_id."',
                    mail='".$mail."',
                    send_to_email='".$send_to_email."'
            ";
            $db->Query($sql);
            $rezultat_apel_history[]=$sql;

            $mail_arr=explode(",",trim($mail));
            if (count($mail_arr)){
                foreach ($mail_arr as $value)
                    if (isset($config['email_attach'][$value]))
                    $attach[]=$config['email_attach'][$value];
            } else $attach='';

            $email_body=$config['default_emails'][1];
            if ($attach!='')
                $email_body=$config['default_emails'][2];

            //$send_to_email="barticpet@gmail.com";
            $ret=send_email($config['email_from_sales'],$send_to_email,$config['email_subject_from_sales'],$email_body,$attach);

        } else if ($rezultat == 3){
            if (isset($rezultat_apel_de_revenit_id))
                $sql="UPDATE rezultat_apel_de_revenit
                        SET
                            data_revenire='".clean_content($fields_rezultat_apel['data_revenire'])."'
                        WHERE id='".$rezultat_apel_de_revenit_id."'
                ";
            else $sql="INSERT INTO rezultat_apel_de_revenit
                SET
                    rezultat_apel_id = '".$rezultat_apel_id."',
                    data_revenire='".clean_content($fields_rezultat_apel['data_revenire'])."'
            ";
            $db->Query($sql);
            $rezultat_apel_history[]=$sql;
            $ret=1;
        } else if ($rezultat == 7){
            $sql="INSERT INTO rezultat_apel_interesat
                SET
                    rezultat_apel_id = '".$rezultat_apel_id."',
                    valoare_propusa='".clean_content($fields_rezultat_apel['valoare_propusa'])."',
                    categorie_produs='".clean_content($fields_rezultat_apel['categorie_produs'])."',
                    detalii_propunere='".clean_content($fields_rezultat_apel['detalii_propunere'])."',
                    data_estimata='".clean_content($fields_rezultat_apel['data_estimata'])."'
            ";
            $db->Query($sql);
            $rezultat_apel_history[]=$sql;
            $ret=1;
        } else if ($rezultat == 9){
            if (isset($rezultat_apel_contract_id))
                $sql="UPDATE rezultat_apel_contract SET ";
                else $sql="INSERT INTO rezultat_apel_contract SET ";

            foreach ($config['vrea_contract'] as $key=>$value)
                $sql .= " $key ='".$fields_rezultat_apel[$key]."',";
            if (isset($rezultat_apel_contract_id))
                $sql = rtrim($sql,',')." WHERE id = '".$rezultat_apel_contract_id."' ";
                else $sql .= " rezultat_apel_id = '".$rezultat_apel_id."' ";
            $db->Query($sql);
            $rezultat_apel_history[]=$sql;
            $ret=1;
        } else {
             $ret=1;
             if ($rezultat != 11 && $rezultat != 3 && $send_to_email != '')
                send_email($config['email_from_sales'],$send_to_email,$config['email_subject_from_sales'],$config['default_emails'][1],'');
        }
        
        if (isset($rezultat_apel_history) && $rezultat_apel_history !=''){
            $sql="INSERT INTO rezultat_apel_history
                    SET
                        user_id='".$_SESSION['user']['id']."',
                        details = '".clean_content(json_encode($rezultat_apel_history))."'
                ";
            $db->Query($sql);
        }

        if ($_SESSION['user']['user_role']=='2' || $_SESSION['user']['user_role']=='4')
            save_status_contract();
    }

    echo $ret;

}

function delete_firma(){
    global $db;

    $ret=0;
    $id=frmGet('id');
    if (!is_null($id) && isset($_SESSION['user'])){
        $sql="UPDATE firma SET active = '0' WHERE firma_id='".$id."'";
        $db->Query($sql);
        $ret=1;
    }

    echo $ret;

}

function contactare_firma(){
    global $db;

    $ret='';
    $id=frmGet('id');
    if (!is_null($id) && isset($_SESSION['user'])){
        $sql="INSERT INTO contactare_firma
                SET firma_id = '".$id."', user_id='".$_SESSION['user']['id']."'
            ";
        $db->Query($sql);
        $ret=mysql_insert_id();
    }

    echo $ret;

}


function detaliu_user(){
    global $db,$config;

    $ret='';
    $id=frmGet('id');
    if (!is_null($id)){
        $user=get_user_details($id);
        if (count($user)){
            $ret.='<div class="container"><div class="row">';

            $ret.='<div class="col-md-4">';
            $ret.='<div class="space1"><b>'.$user['fname'].' '.$user['lname'].'</b></div>
                <div class="space1">'.$user['email'].'</div>
                <div class="space1">Rol: '.$config['user_role'][$user['user_role']].'</div>
                <div class="space1">Data inregistrarii: '.$user['entry_date'].'</div>
                ';
            $ret.='</div>';

            $user_attempts=get_user_attempts($id);
            if (count($user_attempts)){
                $ret.='<div class="col-md-4"><b>Ultimile logari:</b><br>';
                $i=0;
                foreach ($user_attempts as $value){
                    if ($i<5)
                        $ret.='<div class="space1">'.$value['logindate'].' de pe '.$value['ip'].'</div>';
                    $i++;
                }
                $ret.='</div>';
            }

            $ret.='<div class="col-md-4">';
                $ret.='<button data-id="'.$id.'" class="btn btn-primary btn-sm pop" pageTitle="Editare user" onclick="show_popup(this);" pageName="include/popup/editare_user.html">Editare</button>
                    <button data-id="'.$id.'" data-name="'.$user['fname'].' '.$user['lname'].'" class="btn btn-primary btn-sm pop" pageTitle="Sterge user" onclick="show_popup(this);">Sterge </button>
                        '
                        ;
            $ret.='</div>';

            $ret.='</div></div>';
        }
    }
    echo $ret;
}

function detaliu_firma(){
    global $db,$config;

    $ret='';
    $id=frmGet('id');
    if (!is_null($id)){
        $firma=get_firma_details($id);
        if (count($firma)){
            $ret.='<div class="row">';

            $ret.='<div class="col-md-2" id="firma_contact_div_'.$id.'">
                    <div class="space1"><b>'.$firma['nume_contact'].'</b></div>';
                if ($firma['telefon'] != '')
                    $ret.='<div class="space1">Fix: <button onclick="contactare_firma('.$id.',\''.set_phone_no($firma['telefon']).'\');">'.set_phone_no($firma['telefon']).'</button></div>';
                if ($firma['mobil'] != '')
                    $ret.='<div class="space1">Mobil: <button onclick="contactare_firma('.$id.',\''.set_phone_no($firma['mobil']).'\');">'.set_phone_no($firma['mobil']).'</button></div>';
                if ($firma['mobil2'] != '')
                    $ret.='<div class="space1">Mobil 2: <button onclick="contactare_firma('.$id.',\''.set_phone_no($firma['mobil2']).'\');">'.set_phone_no($firma['mobil2']).'</button></div>';
                if ($firma['email'] != '')
                    $ret.='<div class="space1">Email: <a href="mailto:'.$firma['email'].'?Subject=Interesat de vanzare" target="_top">'.$firma['email'].'</a></div>'.'<br>';
            $ret.='</div>';

            $contactare=get_firma_contactare($id);
            if (count($contactare)){
                $ret.='<div class="col-md-4" id="lista_contactari_'.$id.'"><b>Lista contactari</b>';
                foreach ($contactare as $key=>$value){
                    $ret.='<div class="panel panel-primary">
                            <div class="panel-heading">
                                <h3 class="panel-title">'.$value['fname'].' '.$value['lname'].' - '.$value['date'];
                    if ($_SESSION['user']['id']==$value["user_id"] || $_SESSION['user']['user_role']==2)
                        $ret.='&nbsp;&nbsp;&nbsp;&nbsp;<a href="#" class="del_apel" onclick="sterge_apel(\''.$id.'\',\''.$value['id'].'\',\''.$value['date'].'\');"><img src="css/images/del.png" width="20" ></a>';
                    $ret.='     </h3>
                                <span class="pull-right clickable"><i class="glyphicon glyphicon-chevron-up"></i></span>
                            </div>
                            <div class="panel-body">';
                    $rezultat_apel=get_apel_rezultat($value['id']);
                    //$rez='0';$obs='';
                    $rezultat_id=0;
                    if (count($rezultat_apel)){
                        $ret.='<h6>'.$config['rezultat_apel'][$rezultat_apel['rezultat']].'</h6>';

                        if ($rezultat_apel['rezultat']==3){
                            if ($rezultat_apel['data_revenire'] !='')
                                $ret.='<br><b>Data revenire:</b> '.$rezultat_apel['data_revenire'];
                        }
                        if ($rezultat_apel['rezultat']==7){
                            if ($rezultat_apel['valoare_propusa'] !='')
                                $ret.='<br><b>Valoare propusa:</b> '.$rezultat_apel['valoare_propusa'];
                            if ($rezultat_apel['categorie_produs'] !='')
                                $ret.='<br><b>Categorie produs:</b> '.$rezultat_apel['categorie_produs'];
                            if ($rezultat_apel['detalii_propunere'] !='')
                                $ret.='<br><b>Detalii propunere:</b> '.$rezultat_apel['detalii_propunere'];
                            if ($rezultat_apel['data_estimata'] !='')
                                $ret.='<br><b>Data estimata:</b> '.$rezultat_apel['data_estimata'];
                            
                            //if ($key==0)
                              //  $last_apel_is_interesat=1; // verifica daca ultimul apel a rezulat cu interesul firmei
                        } elseif ($rezultat_apel['rezultat']==9){
                            foreach ($config['vrea_contract'] as $field=>$val)
                               if (isset($rezultat_apel[$field]) && $rezultat_apel[$field] !='' && $rezultat_apel[$field] !='0')
                                    $ret.='<br><b>'.$val.':</b> '.$rezultat_apel[$field]; 
                        } elseif ($rezultat_apel['rezultat']==8 && $rezultat_apel['mail']!='' ){
                            $ret.='<br><b>Email trimis cu:</b>';
                            $mails=explode (',',$rezultat_apel['mail']);
                            foreach ($mails as $val)
                                $ret.= '<br>'.$config['rezultat_apel_email'][$val];

                        }

                        if ($rezultat_apel['observatie'] != '')
                            $ret.='<br><br>'.$rezultat_apel['observatie'];
                        
                        $ret.= '<br><span style="float:right; font-size:10px">'.$rezultat_apel['date'].'</span>';
                        
                        $rezultat_id=$rezultat_apel['id'];
                    }
                    if ($_SESSION['user']['id']==$value["user_id"] || $_SESSION['user']['user_role']==2 || $_SESSION['user']['user_role']==4)
                        $ret.='<br><button class="btn btn-primary btn-sm pop" id="btn_rezultat_apel_'.$value['id'].'" onclick="set_rezultat_apel_by_id (\''.$firma['email'].'\','.$id.','.$value['id'].',\''.$rezultat_id.'\',\''.$value['date'].'\');" >Rezultat apel</button>';
                        if ($rezultat_apel['rezultat']==9){
                            $status_contract_list = get_status_contract_by_firma($id);
                            if (count($status_contract_list)){
                                $status_contract=$status_contract_list[0];
                                $ret.='<hr><h6>Status contract:</h6>';
                                if (array_key_exists($status_contract['status'],$config['status_contract']))
                                    $ret.='<b>'.$config['status_contract'][$status_contract['status']].'</b>';
                                    else $ret.='<b> Nesetat inca</b>';
                                if ($_SESSION['user']['user_role']==2)
                                    $ret.='&nbsp;&nbsp;&nbsp;&nbsp;<a href="#" class="del_apel" onclick="sterge_status_contract(\''.$status_contract['id'].'\',\''.$config['status_contract'][$status_contract['status']].'\');"><img src="css/images/del.png" width="20" ></a>';
                                $ret.='<br>';

                                    if (isset($status_contract['nr_cerere']) && $status_contract['nr_cerere'] != '')
                                        $ret.='Nr Cerere: '.$status_contract['nr_cerere'].'<br>';

                                    if (isset($status_contract['tip_ctr']) && $status_contract['tip_ctr'] != '')
                                        $ret.='Tip Ctr: '.$config['tip_ctr'][$status_contract['tip_ctr']].'<br>';

                                    if (isset($status_contract['tip_livrare']) && $status_contract['tip_livrare'] != '')
                                        $ret.='Livrare: '.$config['tip_livrare'][$status_contract['tip_livrare']].'<br>';

                                    if (isset($status_contract['data_activare']) && $status_contract['data_activare'] != '0000-00-00')
                                        $ret.='Data activare: '.$status_contract['data_activare'].'<br>';

                                    if (isset($status_contract['data_livrare']) && $status_contract['data_livrare'] != '0000-00-00')
                                        $ret.='Data livrare: '.$status_contract['data_livrare'].'<br>';

                                    if (isset($status_contract['livrare_produse']) && $status_contract['livrare_produse'] != '')
                                        $ret.='Produse: '.$config['noyes'][$status_contract['livrare_produse']].'<br>';
                                    
                                    if (isset($status_contract['nr_awb']) && $status_contract['nr_awb'] != '')
                                        $ret.='AWB: '.$status_contract['nr_awb'].'<br>';

                                    if (isset($status_contract['data_portare']) && $status_contract['data_portare'] != '0000-00-00')
                                        $ret.='Data portare: '.$status_contract['data_portare'].'<br>';
                                    
                                    if (isset($status_contract['obs']) && $status_contract['obs'] != '')
                                        $ret.='<br>Observatie:<br>'.$status_contract['obs'].'<br>';

                                $ret.='<br>'.$status_contract['fname'].' '.$status_contract['lname'].',<br>'.$status_contract['date'].'<br>-------<br>';
                            }
                        }
                    $ret.='</div>
                        </div>';
                }
                $ret.='</div>';
            }



            $ret.='<div class="col-md-2"  id="firma_contact_div_'.$id.'">';
            $ret.='<div class="space1"><b>'.$firma['nume'].
                '</b></div><div class="space1">'.$firma['judet'].
                ', '.$firma['localitate'].
                ', '.$firma['adresa'].
                '</div><div class="space1">Nr: '.$firma['numar'].
                ', CUI: '.$firma['cui'].
                '</div><div class="space1">Activitate: '.$firma['activitate'].
                '</div><div class="space1">Cifra afaceri: '.$firma['cifra_afaceri'].' RON </div>';
            $ret.='</div>';

            $ret.='<div class="col-md-2">';
                $ret.='<div class="space1"><b>Operator:'.$firma['nume_mobil'].'</b></div>'.
                        '<div class="space1">Simuri: '.$firma['nr_simuri'].'</div>'.
                        '<div class="space1">Factura: '.$firma['factura'].' EUR</div>'.
                        '<div class="space1">Data final: '.custom_date($firma['data_final']).'</div>'.
                        '<div class="space1">Obs: '.$firma['obs'].'</div>'.'<br><br>
                        <button data-id="'.$id.'" class="btn btn-primary btn-sm pop" pageTitle="Editare firma" onclick="show_popup(this);" pageName="include/popup/editare_firma.html">Editare firma</button>';
                        if (isset($_SESSION['user']['user_role']) && $_SESSION['user']['user_role']==2)
                            $ret.='&nbsp;<button data-id="'.$id.'" data-name="'.$firma['nume'].'" class="btn btn-primary btn-sm pop" pageTitle="Sterge firma" onclick="show_popup(this);">Sterge </button>'; 

                        if (count( check_editare_firma($id) ))
                                $ret.='<br><i>Firma editata</i>'; 

                        $ret.='<br>';
                        /*        
                        $status_contract_list = get_status_contract_by_firma($id);
                        if (count($status_contract_list)){
                            $ret.='<hr><h6>Status contract:</h6><br>';
                            foreach ($status_contract_list as $status_contract){
                                $ret.='<br><b>'.$config['status_contract'][$status_contract['status']].'</b>';
                                if ($_SESSION['user']['user_role']==2)
                                    $ret.='&nbsp;&nbsp;&nbsp;&nbsp;<a href="#" class="del_apel" onclick="sterge_status_contract(\''.$status_contract['id'].'\',\''.$config['status_contract'][$status_contract['status']].'\');"><img src="css/images/del.png" width="20" ></a>';
                                $ret.='<br>';

                                    if (isset($status_contract['nr_cerere']) && $status_contract['nr_cerere'] != '')
                                        $ret.='Nr Cerere: '.$status_contract['nr_cerere'].'<br>';

                                    if (isset($status_contract['tip_ctr']) && $status_contract['tip_ctr'] != '')
                                        $ret.='Tip Ctr: '.$config['tip_ctr'][$status_contract['tip_ctr']].'<br>';

                                    if (isset($status_contract['data_activare']) && $status_contract['data_activare'] != '0000-00-00')
                                        $ret.='Data activare: '.$status_contract['data_activare'].'<br>';

                                    if (isset($status_contract['data_livrare_marfa']) && $status_contract['data_livrare_marfa'] != '0000-00-00')
                                        $ret.='Data livrare marfa: '.$status_contract['data_livrare_marfa'].'<br>';

                                    if (isset($status_contract['data_portare']) && $status_contract['data_portare'] != '0000-00-00')
                                        $ret.='Data portare: '.$status_contract['data_portare'].'<br>';
                                    
                                    if (isset($status_contract['obs']) && $status_contract['obs'] != '')
                                        $ret.='<br>Observatie:<br>'.$status_contract['obs'].'<br>';

                                $ret.='<br>'.$status_contract['fname'].' '.$status_contract['lname'].',<br>'.$status_contract['date'].'<br>-------<br>';
                            }
                            $ret.='<hr>';

                        } else $ret.='<br>';
                            
                        if (isset($_SESSION['user']['user_role']) && ($_SESSION['user']['user_role']==2 || $_SESSION['user']['user_role']==4))
                                $ret.='<button data-id="'.$id.'" data-name="'.$firma['nume'].'" class="btn btn-primary btn-sm pop" pageTitle="Status contract" onclick="show_popup(this);">Status contract </button>';
                        */    
                            
                        
                                                   
            $ret.='</div>';

            $ret.='</div>';
        }
    }


    echo $ret;

}

function lista_firme(){
      include('include/datatables/lista_firme.php');


}

?>