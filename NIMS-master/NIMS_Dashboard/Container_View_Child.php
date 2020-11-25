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

Editor::inst( $db, 'consignment', 'Csm_Id' )
    ->field(
        Field::inst( 'consignment.UniqueNo' ),
        Field::inst( 'consignment.Shp_Id' ),

        Field::inst( 'consignment.Csm_Id' ),
        Field::inst( 'consignment.Csm_ConsigneeCode' ),
        Field::inst( 'consignment.Csm_ConsignorCode' ),

        Field::inst( 'consignment.Csm_Status' )

    )
    ->where( 'consignment.Shp_Id', $_POST['Shp_Id'] )
    ->process($_POST)
    ->json();

