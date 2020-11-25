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
Editor::inst( $db, 'StoreView', 'Shp_Id' )
       ->readTable('StoreView') // The VIEW to read data from

    ->fields(
        Field::inst( 'StoreView.Shp_Id' )
            ->name('Shp_Id'),
        Field::inst( 'StoreView.LuTr_LuId' ),
        Field::inst( 'StoreView.Csm_ConsigneeCode' ),
        Field::inst( 'StoreView.LuTr_LutCode' ),
        Field::inst( 'StoreView.LuTr_SealNo' ),



        Field::inst( 'StoreView.LAST_DAY_DET'),


 
    
        Field::inst( 'StoreView.Customs_Status' )
            ->name('CUSTOMS_STAT'),
        Field::inst( 'StoreView.Customs_Status_Description' )
            ->name('CUST_STAT_DESC'),

    


        Field::inst( 'StoreView.MILKRUN' ),
    
        Field::inst( 'StoreView.Unload_Date' ),
        Field::inst( 'StoreView.Unload_Week'),
        Field::inst( 'StoreView.Unload_Time' ),
        Field::inst( 'StoreView.Dock_No' ),

        Field::inst( 'StoreView.Shp_Id' ),
        Field::inst( 'StoreView.Shipment_Type' )

        


    )



    ->join(
        Mjoin::inst( 'consignment' )
            ->link( 'StoreView.Shp_Id', 'consignment.Shp_Id' )
            ->fields(
                Field::inst( 'Shp_Id' )
            )
    )



    ->process( $_POST )
    ->json();