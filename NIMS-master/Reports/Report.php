<?php 
	if(session_id() == '') { session_start(); }       
	date_default_timezone_set('Australia/Sydney');
	// check if the user is logged in
	if (!$_SESSION["login"])
	{
	    header("location: /NIMS/staffmenu.php");
	} 
	if($_SESSION['store'] == "All Stores")
	{
		$EditorPath = "/NIMS/Reports/AddOns/Edit_Report_All.php";
	}
	else
	{
		$EditorPath = "/NIMS/Reports/AddOns/Edit_Report.php";
	}
	
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1.0, user-scalable=no">
	<title>Unit <?php echo $_SESSION['store']; ?></title>
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
	.DateCols{
		min-width: 68px !important;
	}

	/* .dtsp-searchPanes{
		background-color: black;
	} */
	.dt-button-background{
		background-color: black;
		opacity: 0.5;
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
<link rel="stylesheet" type="text/css" href="/NIMS/css/editor.bootstrap4.min.css">


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

<script type="text/javascript" language="javascript" src="/NIMS/js/dataTables.editor.min.js"></script>
<script type="text/javascript" language="javascript" src="/NIMS/js/editor.bootstrap4.min.js"></script> 







<script type="text/javascript" language="javascript" class="init">
	


	function createChild(row) {
		var rowData = row.data();
	
		// This is the table we'll convert into a DataTable
		var table = $('<table class="display border border-primary" width="20%" height="20%"/>');
	
		// Display it the child row
		row.child(table).show();
	
		// Child row DataTable configuration, always passes the parent row's id to server
		var usersTable = table.DataTable({
			dom: "rt<i>p",
			pageLength: 5,
			ajax: {
				url: "AddOns/Edit_Report_Child.php",
				type: "post",
				data: function(d) {
					d.Shp_Id = rowData.Shp_Id;
				}
			},
			columns: [
				{ title: "Csm_Id", 			data: "consignment.Csm_Id" },
				{ title: "Csm_ConsigneeCode", 	data: "consignment.Csm_ConsigneeCode" },
				{ title: "Csm_ConsignorCode", 	data: "consignment.Csm_ConsignorCode" },
				{ title: "Csm_Stat", 		data: "consignment.Csm_Status" }
			]
		});
	}
	
	function updateChild(row) {
		$("table", row.child())
			.DataTable()
			.ajax.reload();
	}
	
	function destroyChild(row) {
		// Remove and destroy the DataTable in the child row
		var table = $("table", row.child());
		table.detach();
		table.DataTable().clear().destroy();
	
		// And then hide the row
		row.child.hide();
	}
	
	$(document).ready(function() {
		var editor = new $.fn.dataTable.Editor({
			ajax: {
				url: "<?php echo $EditorPath; ?>",
				type: "post",
				data: function(d) {
					d.offset = "<?php echo date("Y-m-d", strtotime("- 30 day")); ?>";
				}
			},			table: "#mainTable",
			fields: [
				{
					label: "LU NO:",
					name: "master_file.LuTr_LuId",
					type: "readonly"
				},
				{
					label: "Delivery_Time:",
					name: "manual_input_transport.Delivery_Time",
					type:  'datetime',
					format: 'HH:mm',
					fieldInfo: '24 hour clock format',
					opts: {
						minutesIncrement: 30
					}
				},
				{
					label: "Delivery Date:",
					name: "DeliveryDate",
					type:  'datetime',
					  def:   function () { return new Date(); }
				},

				{
					label: "MILKRUN:",
					name: "manual_input_transport.MILKRUN"
				},
				{
					label: "Dock_No:",
					name: "manual_input_transport.Dock_No"
				},
				{
					label: "Unload_Time:",
					name: "manual_input_transport.Unload_Time",
					type:  'datetime',
					format: 'HH:mm',
					fieldInfo: '24 hour clock format',
					opts: {
						minutesIncrement: 30
					}
				},
				{
					label: "Unload Date:",
					name: "manual_input_transport.Unload_Date",
					type:  'datetime',
					  def:   function () { return new Date(); }
				},
				{
					label: "Transport_Comment:",
					name: "manual_input_transport.Transport_Comment"
				},
				{
					label: "pkey",
					name: "manual_input_transport.Shp_Id",
					type: "hidden"
				}
			]
		});
		
		
		// Activate an inline edit on click of a table cell
		$('#mainTable').on( 'click', 'tbody td.editable', function (e) {
			editor.inline( this, {onBlur: 'submit'} );
		} );
	
	
		// Auto submit for date-picker
		// MUST CHANGE FIELD
		editor.on('open', function (e, mode, action) {
			if (mode === 'inline') {
				// Need to break the 'thread' otherwise it will immediately submit
				setTimeout( function () {
					// CHANGE FIELD NAME HERE
					editor.field( 'manual_input_transport.Unload_Date' ).input().on( 'change.submit', function (e,d) {
						editor.submit();
					});
				}, 10 );
			}
		});
		
		editor.on('close', function () {
			// CHANGE FIELD NAME HERE
			editor.field( 'manual_input_transport.Unload_Date' ).input().off( 'change.submit' );
		});
	
	
		var mainTable = $("#mainTable").DataTable({
			dom: "Bfrtip",
			ajax: {
				url: "<?php echo $EditorPath; ?>",
				type: "post",
				data: function(d) {
					d.offset = "<?php echo date("Y-m-d", strtotime("- 60 day")); ?>";
				}
			},
			// How to sort, ignoring empty cells
			// https://datatables.net/blog/2016-12-22
			order: [26, "desc"],
			// fixedColumns: {
			// 	leftColumns: 3
			// },
			columns: [
				{
					className: "details-control",
					title:'CSM', 
					// Alternative display. Shows COUNT(Csm_Id) associated with Shp_Id
					// data: "consignment",
					// render: function(data) {
					// 	return data.length;
					// }
					"orderable":      false,
					"data":           null,
					"defaultContent": ''
				},
				{ title:'LU_NO', 		data: 'master_file.LuTr_LuId'},
				{ title:'ShpId_Main_Carriage', data: 'Shp_Id'},
				{ title:'BL_Number', 	data: 'master_file.ShpCar_BLid'},
				{ title:'BU', 		data: 'master_file.Csm_ConsigneeCode'},

				{ title:'LU_SIZE', 		data: 'master_file.LuTr_LutCode'},
				{ title:'LU_SEAL', 		data: 'master_file.LuTr_SealNo'},
				{ title:'SHP_TYPE', 		data: 'master_file.Shipment_Type'},
				{ title:'Carrier_Vessel', 	data: 'au_cus_dec.Carrier_Vessel'},
				{ title:'Ocean_Carrier', 	data: 'master_file.Shp_CarName'},

				{ title:'Land_Carrier', 	data: 'master_file.Land_Carrier'},
				{ title:'Ocean_Carrier_VOY',data: 'au_cus_dec.Ocean_Carrier_VOY' },
				{ title:'ETD', 		data: 'master_file.Shp_DateStart'},
				{ title:'POL', 		data: 'master_file.ShpRou_StartCode'},
				{ title:'ETA', 		data: 'ETA'},


				{ title:'POD', 		data: 'master_file.ShpRou_EndCode'},
				{ title:'Wharf_Availability',data: 'master_file.Wharf_Availability'},
				{ title:'Stevedore', 	data: 'master_file.Stevedore'},
				{ title:'IN_YARD_DATE', 	data: 'master_file.IN_YARD_DATE'},
				{ title:'LAST_DAY_DET', 	data: 'LAST_DAY_DET'},

				{ title:'DET_COST', 		data: 'DET_COST',
					render: function(data, type, row)
					{
						return (row.DET_COST > 0)
						? $.fn.dataTable.render.number( ',', '.', 0, '$' ).display(data)
						: ""      
					}
				},
				{ title:'EARLIEST_TD', 	data: 'EARLIEST_TD'},
				{ title:'TARGET_DATE', 	data: 'TARGET_DATE'},
				{ title:'LATEST_TD', 	data: 'LATEST_TD'},
				{ title:'TD_OTD', 		data: 'TD_OTD'},

				{ title:'TD_ORDER_SPREAD', 	data: 'TD_ORDER_SPREAD'},
				{ title:'PRIO', 		data: 'master_file.PRIO'},
				{ title:'VOLUME', 		data: 'VOLUME'},
				{ title:'GROSS_WEIGHT', 	data: 'GROSS_WEIGHT'},

				{ title:'CUSTOMS_STAT', 	data: 'CUSTOMS_STAT'},
				{ title:'CUST_STAT_DESC', 	data: 'CUST_STAT_DESC'},
				{ title:'CUST_STAT_DATE', 	data: 'CUST_STAT_DATE'},

				// Manual Inputs
				{ title:'Delivery_Time', 	data: 'manual_input_transport.Delivery_Time', 	className: 'editable' },
				{ title:'DeliveryDate', 	data: 'DeliveryDate', 				className: 'editable' },

				{ title:'Dock_No', 		data: 'manual_input_transport.Dock_No', 		className: 'editable' },
				{ title:'MILKRUN', 		data: 'manual_input_transport.MILKRUN', 		className: 'editable' },
				{ title:'Unload_Time', 	data: 'manual_input_transport.Unload_Time', 	className: 'editable' },
				{ title:'Unload_Date', 	data: 'manual_input_transport.Unload_Date', 	className: 'editable' },
				{ title:'Unload_Week', 	data: 'Unload_Week' },		

				{ title:'Comment', 		data: 'manual_input_transport.Transport_Comment', className: 'editable' },
				
				{ title:'Last_Editor', 	data: 'manual_input_transport.Last_Editor'}
			],
			select: {
				style: "os",
				selector: "td:nth-child(2)"
			},
			buttons: [
				{ extend: "excel" },
				{ extend: "edit", 		editor: editor },
				{ extend: "colvis", 		text: "Column Visibility" },
				{
				   extend: 'colvisGroup',
				   key: {
					key: 'p'
				   },
				   text: 'Filter Group(p)',
				   show: [ 0, 1, 14, 16, 17, 18, 19, 22, 26, 29, 32, 33, 34, 35, 36, 37, 38, 39],
				   hide: [ 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 15, 17, 20, 21, 23, 24, 25, 27, 28, 30, 31, 40]
			      },
			      {
					extend: 'colvisGroup',
					text: 'Show all(y)',
					show: ':hidden',
					key: {
						key: 'y'
					}
			      },
			      {
					extend: 'searchPanes',
					config: 
					{
						cascadePanes: true
					}
				}
			],
			keys: {
				columns: ':not(:first-child, :nth-child(2))',
				editor:  editor
			},
			scrollY:       	"60vh",
			scrollX: 		"100%",
			scrollCollapse:	true,
			paging:         	false,
			language: {
				searchPanes: {
					// clearMessage: 'Obliterate Selections',
					collapse: {0: 'Filter Week', _: 'Filter Week (%d)'}
				}
			},
			columnDefs: [
				{  className: "DetCost", targets: [20] },
				{  className: "DateCols", targets: [12, 14] },
				{
					searchPanes: 
					{
						show: true
					},
					targets: [38]
				},
				{
					searchPanes: 
					{
						show: false
					},
					targets: [0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,39,40]
				}
			]
		});
	
		// Add event listener for opening and closing details
		$("#mainTable tbody").on("click", "td.details-control", function() {
			var tr = $(this).closest("tr");
			var row = mainTable.row(tr);
	
			if (row.child.isShown()) {
				// This row is already open - close it
				destroyChild(row);
				tr.removeClass("shown");
			} else {
				// Open this row
				createChild(row);
				tr.addClass("shown");
			}
		});
	
		// When updating a site label, we want to update the child table's site labels as well
		editor.on("submitSuccess", function() 
		{
			mainTable.rows().every(function() 
			{
				if (this.child.isShown()) 
				{
					updateChild(this);
				}
			});
		});
	});
	window.onload = function () 
	{
		var backgroundImg=[	
					"/NIMS/img/Boat.jpg",
					"/NIMS/img/container.jpg",
					"/NIMS/img/Door.jpg",
					"/NIMS/img/kinsey-cB8YiJt_0Y0-unsplash.jpg",
					"/NIMS/img/chuttersnap-9cCeS9Sg6nU-unsplash.jpg",
					"/NIMS/img/chuttersnap-eqwFWHfQipg-unsplash.jpg",
					"/NIMS/img/frank-mckenna-tjX_sniNzgQ-unsplash.jpg",
					"/NIMS/img/jake-givens-iR8m2RRo-z4-unsplash.jpg",
					"/NIMS/img/jakub-nawrot-63jZ-G8FgNg-unsplash.jpg",
					"/NIMS/img/joey-kyber-45FJgZMXCK8-unsplash.jpg",
					"/NIMS/img/nick-fewings-br9D5K3UTRQ-unsplash.jpg",
					"/NIMS/img/shaah-shahidh--subrrYxv8A-unsplash.jpg",
					"/NIMS/img/vidar-nordli-mathisen-y8TMoCzw87E-unsplash.jpg"
				]
	
		setInterval(changeImage, 60000);
		function changeImage() 
		{   
			var i = Math.floor((Math.random() * 13));
		
			document.body.style.backgroundImage = "url('"+backgroundImg[i]+"')";
			document.body.style.backgroundRepeat = "no-repeat";
			document.body.style.backgroundSize = "cover";
		}
	}
</script>


</head>
<body class="dt-mainTable php" style="  background-image: url('/NIMS/img/Boat.jpg'); background-repeat: no-repeat; background-size: cover;">
	<div class="CantRemember" style="padding:30px;">
		<div class="card"style="background:rgba(255,255,255,0.5);">
              <div class="card-header">
		<div class="card-body"style="background:rgba(255,255,255,0.5);">  
              	<a href="../staffmenu.php" class="homeLink shadow-lg"><img src="/NIMS/img/Home.jpg" class="homeLinkImage shadow-lg"></img></a>                  
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




