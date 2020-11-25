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
Editor::inst( $db, 'dc_outbound', 'Csm_Id' )
    ->fields(
        Field::inst( 'Csm_Id' ),
        Field::inst( 'BU' ),
        Field::inst( 'Trailer_Number' ),
        Field::inst( 'Dispatch_Number' ),
        Field::inst( 'Dispatch_Note' ),
        Field::inst( 'Trailer_Type' ),
        Field::inst( 'Seal_No' ),
        Field::inst( 'Unload_Date' ),
        Field::inst( 'DATE_FORMAT(Unload_Date,"%x%v")' )
              ->name('Unload_Week')

    )

    ->process( $_POST )
    ->json();