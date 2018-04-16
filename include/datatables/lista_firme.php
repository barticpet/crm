<?php

require_once '../../include/init.php';

include( BASE_PATH.'lib/datatables/editor/php/DataTables.php' );

// Alias Editor classes so they are easy to use
use
        DataTables\Editor,
        DataTables\Editor\Field,
        DataTables\Editor\Format,
        DataTables\Editor\Mjoin,
        DataTables\Editor\Options,
        DataTables\Editor\Upload,
        DataTables\Editor\Validate;


/*
 * Example PHP implementation used for the joinLinkTable.html example
 */
$editor=Editor::inst( $db, 'firma','firma_id' )
                ->field(
                        Field::inst( 'firma.nume' ),
                        Field::inst( 'firma.localitate' ),
                        Field::inst( 'firma.nume_contact' ),
                        Field::inst( 'firma.mobil' ),
                        Field::inst( 'firma.telefon' )
                )
                //->leftJoin( 'contactare_firma',     'contactare_firma.firma_id',          '=', 'firma.firma_id' )
                //->where( function ( $q ) {
                    //  $q->where( "contactare_firma.firma_id", "( SELECT firma_id FROM contactare_firma ORDER BY id DESC LIMIT 1 )", "IN", false );
                        //})
                ;
$editor->where('active',1);
if (isset($_SESSION['user']['user_role']) && $_SESSION['user']['user_role'] !=2 && $_SESSION['user']['user_role'] !=5){
        $editor->where( function ( $q ) {
                $q->where( function ( $r ) {
                        $r->where( 'telefon', '', '!=' );
                        $r->or_where( 'mobil', '','!=' );
                } );
        } );
}


if (isset($_REQUEST['raspuns']) && $_REQUEST['raspuns'] !='') {
        $editor->where( function ( $q ) {
                $q->where( 'firma_id', "(SELECT firma_id FROM rezultat_apel WHERE rezultat='".$_REQUEST['raspuns']."' AND last='1' )", 'IN', false );
        } );
}

if (isset($_SESSION['user']['user_role']) && $_SESSION['user']['user_role'] !=2 && $_SESSION['user']['user_role']!=4 && $_SESSION['user']['user_role']!=5) {
        if (isset($_REQUEST['raspuns']) && $_REQUEST['raspuns'] !='') {
                $editor->where( function ( $q ) {
                        //$q->where( 'firma_id', "(SELECT firma_id FROM rezultat_apel WHERE rezultat='7' AND user_id != '".$_SESSION['user']['id']."' )", "NOT IN", false );
                        $q->where( 'firma_id', "(SELECT firma_id FROM rezultat_apel WHERE user_id != '".$_SESSION['user']['id']."' )", "NOT IN", false );
                } );
        } else {
                $editor->where( function ( $q ) {
                        $q->where( 'firma_id', "(SELECT firma_id FROM rezultat_apel WHERE last='1' AND date > DATE_SUB( CURDATE() ,INTERVAL 1 MONTH ))", "NOT IN", false );
                } );
        }

}

if (isset($_REQUEST['apel_start_date'])){
        $editor->where( function ( $q ) {
                $q->where( 'firma_id', "(SELECT firma_id FROM rezultat_apel WHERE last='1' AND date >= '".$_REQUEST['apel_start_date']."' )", "IN", false );
        } );
}

if (isset($_REQUEST['apel_end_date'])){
        $editor->where( function ( $q ) {
                $q->where( 'firma_id', "(SELECT firma_id FROM rezultat_apel WHERE last='1' AND date <= '".$_REQUEST['apel_end_date']."' )", "IN", false );
        } );
}

if (isset($_REQUEST['status_contract']) && $_REQUEST['status_contract'] !=0 && $_REQUEST['status_contract'] !='x'){ 
        $editor->where( function ( $q ) { 
                $q->where( 'firma_id', "(SELECT firma_id FROM status_contract WHERE status='".$_REQUEST['status_contract']."' AND last='1')", ' IN', false );
        } );
} elseif (isset($_REQUEST['status_contract']) && $_REQUEST['status_contract'] == '0'){ 
        //echo $_REQUEST['status_contract'].' - '.$_SESSION['user']['user_role'];
        $editor->where( function ( $q ) {
                $q->where( 'firma_id', "(SELECT firma_id FROM status_contract WHERE last='1')", 'NOT IN', false );
        } );
}elseif ($_SESSION['user']['user_role']==4) { //echo $_REQUEST['status_contract'];
        $editor->where( function ( $q ) {
          $q->where( 'firma_id', "(SELECT firma_id FROM status_contract WHERE last='1')", 'IN', false );
        } );
}

 
if (isset($_REQUEST['contract_status_start_date'])){
        $editor->where( function ( $q ) {
                $q->where( 'firma_id', "(SELECT firma_id FROM status_contract WHERE date <> '0000-00-00'  AND date>='".$_REQUEST['contract_status_start_date']." 00:00:00' AND last='1')", 'IN', false );
        } );
    }  

if (isset($_REQUEST['contract_status_end_date'])){
            $editor->where( function ( $q ) {
                    $q->where( 'firma_id', "(SELECT firma_id FROM status_contract WHERE date <> '0000-00-00'  AND date<='".$_REQUEST['contract_status_end_date']." 23:59:59' AND last='1')", 'IN', false );
            } );
        }    
   


if (isset($_REQUEST['activitate']) && $_REQUEST['activitate'] !=''){
        $editor->where( function ( $q ) {
                $q->where( 'firma_id', '(SELECT firma_id FROM firma WHERE activitate LIKE "%'.$_REQUEST['activitate'].'%")', 'IN', false );
        } );
}

if (isset($_REQUEST['cifra_afaceri']) && $_REQUEST['cifra_afaceri'] !=''){         
        $editor->where( function ( $q ) {
                $q->where( 'firma_id', '(SELECT firma_id FROM firma WHERE cifra_afaceri>= "'.intval($_REQUEST['cifra_afaceri']).'")', 'IN', false );
        } );
}

if (isset($_REQUEST['valoare_factura']) && $_REQUEST['valoare_factura'] !=''){         
        $editor->where( function ( $q ) {
                $q->where( 'firma_id', '(SELECT firma_id FROM firma WHERE factura>= "'.intval($_REQUEST['valoare_factura']).'")', 'IN', false );
        } );
}

if ($_SESSION['user']['user_role']==5) {
        if (isset($_REQUEST['operator_necompletat']) && $_REQUEST['operator_necompletat'] ==1){         
                $editor->where( function ( $q ) {
                        $q->where( 'firma_id', '(SELECT firma_id FROM firma WHERE nume_mobil = "")', 'IN', false );
                } );
        }
        
        if (isset($_REQUEST['firma_needitata']) && $_REQUEST['firma_needitata'] ==1){   
                if (isset($_REQUEST['firma_editat_date']) && $_REQUEST['firma_editat_date'] !=''){         
                        $editor->where( function ( $q ) {
                                $q->where( 'firma_id', "(SELECT firma_id FROM editare_firma WHERE DATE (date) >= '".$_REQUEST['firma_editat_date']."')", 'NOT IN', false );
                        } );
                } else {      
                        $editor->where( function ( $q ) {
                                $q->where( 'firma_id', '(SELECT firma_id FROM editare_firma)', 'NOT IN', false );
                        } );
                }

        }
}

if ($_SESSION['user']['user_role']==6) {
        $editor->where( function ( $q ) {
                $q->where( 'firma_id', '(SELECT firma_id FROM firma WHERE nume_mobil = "")', 'IN', false );
        } );

        $editor->where( function ( $q ) {
                $q->where( 'firma_id', '(SELECT firma_id FROM firma WHERE cifra_afaceri>= "500000")', 'NOT IN', false );
        } );
        
}

// changed tabela contactare in rezultat_apel ca sa apara si firmele contactate dara fara rezultat completat
if (isset($_REQUEST['contactare']) && $_REQUEST['contactare'] != '0'){
        $c= $_REQUEST['contactare'];
        if ($c==1){
                $editor->where( function ( $q ) {
                        $q->where( 'firma_id', '(SELECT firma_id FROM rezultat_apel)', ' NOT IN', false );
                } );

        } else if ($c==2){
                $editor->where( function ( $q ) {
                        $q->where( 'firma_id', '(SELECT firma_id FROM rezultat_apel)', 'IN', false );
                } );
        } else if ($c==3){
                $editor->where( function ( $q ) {
                        $q->where( 'firma_id', "(SELECT firma_id FROM rezultat_apel WHERE user_id = '".$_SESSION['user']['id']."')", 'IN', false );
                } );
        }
} else {
        if (isset($_SESSION['user']['user_role']) && $_SESSION['user']['user_role'] ==1){
                $editor->where( function ( $q ) {
                        $q->where( 'firma_id', "(SELECT firma_id FROM rezultat_apel WHERE user_id != '".$_SESSION['user']['id']."')", 'NOT IN', false );
                } ); 
        } 
}

if (isset($_REQUEST['contract_start_date'])){
        $editor->where('firma.data_final',$_REQUEST['contract_start_date'],'>=');
        $editor->where('firma.data_final','0000-00-00','<>');
    }    

if (isset($_REQUEST['contract_end_date'])){
        $editor->where('firma.data_final',$_REQUEST['contract_end_date'],'<=');
        $editor->where('firma.data_final','0000-00-00','<>');
    }
        
if (isset($_REQUEST['judet']) && $_REQUEST['judet'] !='')
        $editor->where('judet',$_REQUEST['judet']);

if (isset($_REQUEST['localitate']) && $_REQUEST['localitate'])
        $editor->where('localitate',$_REQUEST['localitate']);

$editor->process( $_REQUEST )
->json();

?>