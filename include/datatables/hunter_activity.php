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
$editor=Editor::inst( $db, 'editare_firma','firma_id' )
        ->field(
            Field::inst( 'editare_firma.date' ),

            Field::inst( 'firma.firma_id' )
                ->options( Options::inst()
                    ->table( 'firma' )
                    ->value( 'firma_id' )
                    ->label( 'firma_id' )
                )
                ->validator( 'Validate::dbValues' ),
            Field::inst( 'firma.nume' ),

            Field::inst( 'editare_firma.id' )
            ->options( Options::inst()
                ->table( 'editare_firma_details' )
                ->value( 'editare_firma_id' )
                ->label( 'field' )
            )
            ->validator( 'Validate::dbValues' ),
            Field::inst( 'editare_firma_details.field'),
            Field::inst( 'editare_firma_details.old' ),
            Field::inst( 'editare_firma_details.new' ),

            Field::inst( 'editare_firma.user_id' )
                ->options( Options::inst()
                    ->table( 'users' )
                    ->value( 'id' )
                    ->label( 'id' )
                )
                ->validator( 'Validate::dbValues' ),
            Field::inst( 'users.email' )
        )
        ->leftJoin( 'firma','editare_firma.firma_id','=','firma.firma_id' )
        ->leftJoin( 'editare_firma_details','editare_firma.id','=','editare_firma_details.editare_firma_id' )
        ->leftJoin( 'users','users.id','=','editare_firma.user_id' )
        ;

//$editor->where("editare_firma_details.old=",null,'=');
    //->where("editare_firma_details.new=",null,'!=');

/*$editor->where( function ( $q ) {
        $q->where( 'editare_firma.id', "(SELECT editare_firma_id FROM editare_firma_details WHERE editare_firma_details.old='' AND editare_firma_details.new != '' )", 'IN', false );
} );*/

if (isset($_REQUEST['user_id']))
    $editor->where('editare_firma.user_id',$_REQUEST['user_id']);

if (isset($_REQUEST['selected_field']))
    $editor->where('editare_firma_details.field',$_REQUEST['selected_field']);

if (isset($_REQUEST['start_date']))
    $editor->where('DATE(editare_firma.date)',$_REQUEST['start_date'],'>=');

if (isset($_REQUEST['end_date']))
    $editor->where('DATE(editare_firma.date)',$_REQUEST['end_date'],'<=');

if ($_SESSION['user']['user_role']==5){
    $editor
        ->where('editare_firma_details.field','nume_mobil')
        ->where('editare_firma_details.new','%orange%','LIKE');
}

$editor->process( $_REQUEST )
->json();

?>