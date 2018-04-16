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
$editor=Editor::inst( $db, 'stoc','id' )
        ->field(
            Field::inst( 'stoc.denumire' ),
            Field::inst( 'stoc.pret_fmc' ),
            Field::inst( 'stoc.stoc_tkr' ),
            Field::inst( 'stoc.stoc_tkrm' ),
            Field::inst( 'stoc.realimentare' )
        );
$editor->where('last',1);

$editor->process( $_REQUEST )
->json();

?>