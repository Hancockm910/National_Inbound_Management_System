<?php

/*
 * Example PHP implementation used for the index.html example
 */

// DataTables PHP library
include( "../lib/DataTables.php" );


// Alias Editor classes so they are easy to use
use
	DataTables\Editor,
	DataTables\Editor\Field,
	DataTables\Editor\Format,
	DataTables\Editor\Mjoin,
	DataTables\Editor\Options,
	DataTables\Editor\Upload,
	DataTables\Editor\Validate,
	DataTables\Editor\ValidateOptions;

// Build our Editor instance and process the data coming from _POST
Editor::inst( $db, 'AU_Detention_Cost', 'DET_COST' )
    ->readTable('AU_Detention_Cost') // The VIEW to read data from

    ->fields(
        Field::inst( 'BU' ),
        Field::inst( 'WEEK' ),
        Field::inst( 'DET_COST' )
    )

 
    ->process( $_POST )
    ->json();