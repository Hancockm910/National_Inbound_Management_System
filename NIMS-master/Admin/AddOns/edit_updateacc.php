<?php

// DataTables PHP library
include( "../../lib/DataTables.php" );

if(session_id() == '') 
{
     session_start();
}

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
       


       
    function logChange ( $db, $action, $id, $values ) {
       $db->insert( 'userlogs', array(
           'userName'   => $_SESSION['who'],
           'action' => $action,
           'value' => json_encode( $values ),
           'row'    => $id,
           'dateChanged'   => date('Y-m-d h:m:s')
       ) );
   }

// Build our Editor instance and process the data coming from _POST
Editor::inst( $db, 'usersinfo', 'username' )
	->fields(
              Field::inst( 'usersinfo.username' )
                     ->name('username'),
              Field::inst( 'usersinfo.password' )
                     ->get(false)
                     ->setFormatter( function ( $val, $data ) 
                     {
                            return hash('sha256',  $val);
                     } ),
              Field::inst( 'usersinfo.fName' )
                     ->name('fName'),
              Field::inst( 'usersinfo.lName' )
                     ->name('lName'),
              Field::inst( 'usersinfo.level' )	
                     ->name('level')
	)

	->process( $_POST )
	->json();
