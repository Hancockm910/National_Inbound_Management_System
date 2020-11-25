<?php
	// if(session_id() == '') { session_start(); }       
       // require_once("/NIMS/common/dbconn.php");
	date_default_timezone_set('Australia/Sydney');


	
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1.0, user-scalable=no">
	<title>Trailer Inbound</title>
	<link rel="shortcut icon" href="/NIMS/img/NIMSlogo.png" type="image/x-icon"/>


	<style>
	/* Setting the drop down button */
	td.details-control {
	background: url('https://raw.githubusercontent.com/DataTables/DataTables/1.10.7/examples/resources/details_open.png') no-repeat center center;
	cursor: pointer;
	}
	tr.shown td.details-control {
	background: url('https://raw.githubusercontent.com/DataTables/DataTables/1.10.7/examples/resources/details_close.png') no-repeat center center;
	}

       .dataTables_wrapper .dataTables_info,
       .dataTables_wrapper .dataTables_paginate 
	{
              float: left;
              text-align: center;
              padding-right: 40px;
	}
	html
	{
		font-size: 12px;
	}
	.DetCost{
		font-weight:bold;
		color:red;
	}


	.homeLink
	{
		height:30px;
		width: 30px;
		display: inline-block;
		margin-top: 10px;
		margin-bottom: 10px;
		padding-right: 10px;
		padding-left: 5px;

	}

	.homeLinkImage
	{
		height:30px;
		width: 30px;
	}

	.homeLink a
	{
		color:black;
		width: 100%;
		height: auto;
	}
	/* table.dataTable thead th,
	table.dataTable tfoot th {
		text-align: left;
	}
	table.dataTable thead th,
	table.dataTable thead td {
		padding: 10px 18px 10px 5px;
		border-right: 1px solid #dddddd;
	}
	table.dataTable tbody th,
	table.dataTable tbody td {
		padding: 10px 18px 10px 5px;
		border-right: 1px solid #dddddd;
	} */
	.DateCols
       {
		min-width: 68px !important;
	}
       .FilterOp{
              color: #fff;
              background-color: #6c757d;
              padding:5px;
              margin-bottom:3px;
              margin-right:3px;
              border-radius: 5px;
       }
	</style>
	
	<!-- Bootstrap4 -->
	
	 <link rel="stylesheet" href="/NIMS/assets/vendor/fonts/fontawesome/css/fontawesome-all.css">




<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.1/css/bootstrap.min.css"/>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css"/>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/autofill/2.3.5/css/autoFill.bootstrap4.css"/>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.6.3/css/buttons.bootstrap4.min.css"/>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/colreorder/1.5.2/css/colReorder.bootstrap4.min.css"/>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/fixedcolumns/3.3.1/css/fixedColumns.bootstrap4.min.css"/>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/fixedheader/3.1.7/css/fixedHeader.bootstrap4.min.css"/>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/keytable/2.5.2/css/keyTable.bootstrap4.min.css"/>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.5/css/responsive.bootstrap4.min.css"/>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/rowgroup/1.1.2/css/rowGroup.bootstrap4.min.css"/>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/rowreorder/1.2.7/css/rowReorder.bootstrap4.min.css"/>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/scroller/2.0.2/css/scroller.bootstrap4.min.css"/>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/searchpanes/1.1.1/css/searchPanes.bootstrap4.min.css"/>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/select/1.3.1/css/select.bootstrap4.min.css"/>


<script type="text/javascript" src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.1/js/bootstrap.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/autofill/2.3.5/js/dataTables.autoFill.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/autofill/2.3.5/js/autoFill.bootstrap4.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.6.3/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.6.3/js/buttons.bootstrap4.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.6.3/js/buttons.colVis.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.6.3/js/buttons.flash.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.6.3/js/buttons.html5.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.6.3/js/buttons.print.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/colreorder/1.5.2/js/dataTables.colReorder.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/fixedcolumns/3.3.1/js/dataTables.fixedColumns.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/fixedheader/3.1.7/js/dataTables.fixedHeader.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/keytable/2.5.2/js/dataTables.keyTable.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/responsive/2.2.5/js/dataTables.responsive.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/responsive/2.2.5/js/responsive.bootstrap4.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/rowgroup/1.1.2/js/dataTables.rowGroup.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/rowreorder/1.2.7/js/dataTables.rowReorder.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/scroller/2.0.2/js/dataTables.scroller.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/searchpanes/1.1.1/js/dataTables.searchPanes.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/searchpanes/1.1.1/js/searchPanes.bootstrap4.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/select/1.3.1/js/dataTables.select.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>









<script type="text/javascript" language="javascript" class="init">
	
	
	$(document).ready(function() 
       {
		var mainTable = $("#mainTable").DataTable(
              {
			dom: "Bfrtip",
			ajax: "Trailer_View.php",
			order: [1, "asc"],
			columns: [
                            { title:'Csm_Id', 		data: 'Csm_Id'},
                            { title:'BU', 		data: 'BU'},
                            { title:'Trailer_Number', 	data: 'Trailer_Number'},
                            { title:'Dispatch_Number', 	data: 'Dispatch_Number'},
                            { title:'Dispatch_Note', 	data: 'Dispatch_Note'},

                            { title:'Trailer_Type', 	data: 'Trailer_Type'},
                            { title:'Seal_No', 		data: 'Seal_No'},
                            { title:'Unload_Date', 	data: 'Unload_Date'},
							{ title:'Unload_Week', 	data: 'Unload_Week'}
			],
			buttons: [
				{ extend: "excel", text:"Export To Excel"}			  
			],
			scrollY:       	"60vh",
			scrollX: 		"100%",
			scrollCollapse:	true,
			paging:         	false,
			columnDefs: [
                            // { "visible": false, "targets": [0, 1] }
			],


                     initComplete: function () 
                     {
                            this.api().columns([8]).every( function () 
                            {
                                   var column = this;
                                   var br = $('<br>')
                                          .prependTo('#mainTable_wrapper');

                                   var select = $('<select name="WeekFilter" class="selectpicker FilterOp"></select>')
                                          .prependTo( "#mainTable_wrapper" )
                                          .on( 'change', function () 
                                          {
                                          var val = $.fn.dataTable.util.escapeRegex(
                                                 $(this).val()
                                          );
                     
                                          column
                                                 .search( val ? '^'+val+'$' : '', true, false )
                                                 .draw();
                                          } );
                                   // var label = $('<label for="WeekFilter" class="FilterOp"> Week:  </label>')
                                   //        .prependTo('#mainTable_wrapper');
                                   select.append( '<option value="" hidden>Filter by Week:</option>');
                                   select.append( '<option value="" >All</option>');

                                   column.data().unique().sort().each( function ( d, j ) {
                                          select.append( '<option value="'+d+'">'+d+'</option>' )
                                   } );

                            } );

                            this.api().columns([1]).every( function () 
                            {
                                   var column = this;

                                   var select = $('<select name="BUFilter" class="selectpicker FilterOp"></select>')
                                          .prependTo( "#mainTable_wrapper" )
                                          .on( 'change', function () 
                                          {
                                          var val = $.fn.dataTable.util.escapeRegex(
                                                 $(this).val()
                                          );
                     
                                          column
                                                 .search( val ? '^'+val+'$' : '', true, false )
                                                 .draw();
                                          } );
                                   // var label = $('<label for="BUFilter" class="FilterOp"> BU:  </label>')
                                   //        .prependTo('#mainTable_wrapper');
                                   select.append( '<option value="" hidden>Filter by BU:</option>');
                                   select.append( '<option value="" >All</option>');

                                   column.data().unique().sort().each( function ( d, j ) {
                                          select.append( '<option value="'+d+'">'+d+'</option>' )
                                   } );
                            } );
                     }
		});
	

	

	});


</script>


</head>

<body class="dt-mainTable php" style="  background-image: url('/NIMS/img/Boat.jpg'); background-repeat: no-repeat; background-size: cover;">
	<div class="CantRemember" style="padding:30px;">
		<div class="card"style="background:rgba(255,255,255,0.5);">
              <div class="card-header">
		<div class="card-body"style="background:rgba(255,255,255,0.5);">  
			<a href="Index.php" class="homeLink shadow-lg"><img src="/NIMS/img/Home.jpg" class="homeLinkImage shadow-lg"></img></a>                  
			<div class="table-responsive" style="padding-left:15px; margin-top:25px;">
				<table id="mainTable" class="table table-striped table-bordered table-hover" style="width:100%;  background-color:white;">
					<thead>
						<tr></tr>
					</thead>
				</table>
			</div>
              </div>
		</div>
		</div>
	</div>
</body>
</html>




