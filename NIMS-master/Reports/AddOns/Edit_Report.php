<?php

/*
 * Example PHP implementation used for the index.html example
 */

// DataTables PHP library
include( "../../lib/DataTables.php" );
if(session_id() == '') 
{
     session_start();
}
if($_SESSION['store'])
{
    $Store = $_SESSION['store'];
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
Editor::inst( $db, 'master_file', 'Shp_Id' )
    ->fields(
        Field::inst( 'master_file.Shp_Id' )
            ->name('Shp_Id'),
        Field::inst( 'master_file.LuTr_LuId' ),
        Field::inst( 'master_file.ShpCar_BLid' ),
        Field::inst( 'master_file.Csm_ConsigneeCode' ),
        Field::inst( 'master_file.LuTr_LutCode' ),
        Field::inst( 'master_file.LuTr_SealNo' ),
        Field::inst( 'master_file.Shipment_Type' ),
        Field::inst( 'master_file.Shp_CarName' ),
        Field::inst( 'au_cus_dec.Carrier_Vessel' ),
        Field::inst( 'master_file.Land_Carrier' ),
        Field::inst( 'au_cus_dec.Ocean_Carrier_VOY' ),
        Field::inst( 'master_file.Shp_DateStart' ),
        Field::inst( 'master_file.ShpRou_StartCode' ),
        Field::inst( 'master_file.ShpRou_EndCode' ),
        Field::inst( 'master_file.PRIO' ),
        Field::inst( 'master_file.Wharf_Availability' ),
        Field::inst( 'master_file.IN_YARD_DATE' ),
        Field::inst( 'master_file.Stevedore' ),
        Field::inst( 'IF(au_cus_dec.ETA IS NOT NULL,au_cus_dec.ETA,master_file.ShpRou_RecPldArrDate)' )
            ->name('ETA'),
        Field::inst( 'DATE_ADD(IF(au_cus_dec.ETA IS NOT NULL,au_cus_dec.ETA,master_file.ShpRou_RecPldArrDate) , INTERVAL 21 DAY)' )
            ->name('LAST_DAY_DET'),
        Field::inst( '((DATEDIFF(IF(manual_input_transport.Unload_Date IS NOT NULL OR manual_input_transport.Unload_Date<>"", manual_input_transport.Unload_Date, CURRENT_DATE), IF(au_cus_dec.ETA IS NOT NULL,au_cus_dec.ETA,master_file.ShpRou_RecPldArrDate)))-21) * 120' )
            ->name('DET_COST'),
        Field::inst( 'target_dates.Earliest_TD' )
            ->name('EARLIEST_TD'),
        Field::inst( 'target_dates.Target_Date' )
            ->name('TARGET_DATE'),
        Field::inst( 'target_dates.Latest_TD' )
            ->name('LATEST_TD'),
        Field::inst( 'IF(target_dates.MAX_OTD > 0,CONCAT(target_dates.Max_OTD,"%"), "")' )
            ->name('TD_OTD'),
        Field::inst( 'target_dates.Order_Spread' )
            ->name('TD_ORDER_SPREAD'),
    
        Field::inst( 'master_file.Ccs_SumVolGroAct' )
            ->name('VOLUME'),
        Field::inst( 'master_file.Ccs_SumWeiGkg' )
            ->name('GROSS_WEIGHT'),
    
        Field::inst( 'au_cus_dec.Customs_Status' )
            ->name('CUSTOMS_STAT'),
        Field::inst( 'au_cus_dec.Customs_Status_Description' )
            ->name('CUST_STAT_DESC'),
        Field::inst( 'au_cus_dec.Status_Date' )
            ->name('CUST_STAT_DATE'),
    


        Field::inst( 'manual_input_transport.MILKRUN' ),
    
        Field::inst( 'manual_input_transport.Unload_Date' )
            ->validator( Validate::dateFormat( 'Y-m-d' ) )
            ->getFormatter( Format::dateSqlToFormat( 'Y-m-d' ) )
            ->setFormatter( Format::dateFormatToSql('Y-m-d' ) ),
        Field::inst( 'DATE_FORMAT(manual_input_transport.Unload_Date,"%x%v")' )
            ->name('Unload_Week'),
        Field::inst( 'manual_input_transport.Unload_Time' ),
        Field::inst( 'manual_input_transport.Dock_No' ),
        Field::inst( 'manual_input_transport.Delivery_Date' )
            ->validator( Validate::dateFormat( 'Y-m-d' ) )
            ->getFormatter( Format::dateSqlToFormat( 'Y-m-d' ) )
            ->setFormatter( Format::dateFormatToSql('Y-m-d' ) )
            ->name('DeliveryDate'),
        Field::inst( 'manual_input_transport.Delivery_Time' ),

        Field::inst( 'manual_input_transport.Transport_Comment' ),
        Field::inst( 'manual_input_transport.Shp_Id' ),
        Field::inst( 'manual_input_transport.Last_Editor' ),

        Field::inst( 'DATE_SUB(master_file.ShpRou_RecPldArrDate , INTERVAL 90 DAY)' )
            ->name('Offset')
    )
    ->on( 'preEdit', function ( $editor, $values ) 
    {
		$editor
		    ->field( 'manual_input_transport.Last_Editor' )
		    ->setValue( $_SESSION['who'] );
    } )
    ->on( 'postEdit', function ( $editor, $id, $values, $row ) {
        logChange( $editor->db(), 'edit', $id, $values );
    } )

	->leftJoin( 'au_cus_dec', 'au_cus_dec.Shp_Id', '=', 'master_file.Shp_Id' )
	->leftJoin( 'manual_input_transport', 'manual_input_transport.Shp_Id', '=', 'master_file.Shp_Id' )
	->leftJoin( 'target_dates', 'target_dates.Shp_Id', '=', 'master_file.Shp_Id' )


    ->join(
        Mjoin::inst( 'consignment' )
            ->link( 'master_file.Shp_Id', 'consignment.Shp_Id' )
            ->fields(
                Field::inst( 'Shp_Id' )
            )
    )

    ->where( 'master_file.Csm_ConsigneeCode', $Store )
    ->where( 'master_file.Shp_DateStart', null, '!=' )
    ->where( function ( $q ) {
        $q->where( 'master_file.ShpRou_RecPldArrDate', 'DATE_ADD( now(), INTERVAL -60 DAY )', '>', false );
    } )

    ->process( $_POST )
    ->json();