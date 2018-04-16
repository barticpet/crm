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
        Field::inst( 'firma.telefon' ),
        Field::inst( 'rezultat_apel.rezultat' ),
        Field::inst( 'rezultat_apel.observatie' ),
        Field::inst( 'contactare_firma.date' ),
       // Field::inst( 'rezultat_apel_interesat.valoare_propusa' ),
        //Field::inst( 'rezultat_apel_interesat.categorie_produs' ),
        //Field::inst( 'rezultat_apel_interesat.detalii_propunere' ),
        //Field::inst( 'rezultat_apel_interesat.data_estimata' ),
        Field::inst( 'rezultat_apel_de_revenit.data_revenire' ),
        Field::inst( 'users.email' )
        
)
->leftJoin( 'contactare_firma','contactare_firma.firma_id','=','firma.firma_id' )
->leftJoin( 'rezultat_apel','contactare_firma.id', '=', 'rezultat_apel.contactare_id' )
//->leftJoin( 'rezultat_apel_interesat','rezultat_apel_interesat.rezultat_apel_id', '=', 'rezultat_apel.id' )
->leftJoin( 'rezultat_apel_de_revenit','rezultat_apel_de_revenit.rezultat_apel_id', '=', 'rezultat_apel.id' )
->leftJoin( 'users','users.id','=','contactare_firma.user_id' )
;

$editor->where('rezultat_apel.last','1');

if (isset($_REQUEST['raspuns']))
    $editor->where('rezultat_apel.rezultat',$_REQUEST['raspuns']);

if (isset($_REQUEST['user_id']))
    $editor->where('users.id',$_REQUEST['user_id']);

if (isset($_REQUEST['data_revenire']))
    $editor->where('rezultat_apel_de_revenit.data_revenire',$_REQUEST['data_revenire'],'<=');

    if (isset($_REQUEST['start_date']))
    $editor->where('rezultat_apel.date',$_REQUEST['start_date'],'>=');

if (isset($_REQUEST['end_date']))
    $editor->where('rezultat_apel.date',$_REQUEST['end_date'],'<=');


if (isset($_REQUEST['contract_start_date'])){
        $editor->where('firma.data_final',$_REQUEST['contract_start_date'],'>=');
        $editor->where('firma.data_final','0000-00-00','<>');
    }    

if (isset($_REQUEST['contract_end_date'])){
        $editor->where('firma.data_final',$_REQUEST['contract_end_date'],'<=');
        $editor->where('firma.data_final','0000-00-00','<>');
    }
  

if (isset($_REQUEST['valoare_factura']) && $_REQUEST['valoare_factura'] !=''){         
        $editor->where( function ( $q ) {
                $q->where( 'firma.firma_id', '(SELECT firma_id FROM firma WHERE factura>= "'.intval($_REQUEST['valoare_factura']).'")', 'IN', false );
        } );
}


if (isset($_REQUEST['judet']))
    $editor->where('firma.judet',$_REQUEST['judet']);

if (isset($_REQUEST['localitate']))
    $editor->where('firma.localitate',$_REQUEST['localitate']);


$editor->process( $_REQUEST )
->json();

?>