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
$editor=Editor::inst( $db, 'users','id' )
        ->field(
            Field::inst( 'users.fname' ),
            Field::inst( 'users.lname' ),
            Field::inst( 'users.email' )
        );
$editor->where('active',1);

$editor->process( $_REQUEST )
->json();

?>