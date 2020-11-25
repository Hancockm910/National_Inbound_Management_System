<?php 
    ob_start();
	if(session_id() == '') 
	{
		session_start();
	}
	if (!$_SESSION["login"] || $_SESSION["level"] != 1)
	{
		header("location: /NIMS/logoff.php");
	} 
	// Include config file
	//require_once("/NIMS/nocache.php");
	//require_once("/NIMS/common/dbconn.php");
    	//include("/NIMS/common/header.php"); 


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Section</title>
	<link rel="stylesheet" type="text/css" href="/NIMS/Styles/styles.css">
	<!-- Bootstrap -->
	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">

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
<script type="text/javascript" language="javascript" src="/NIMS/js/dataTables.editor.min.js"></script>
<script type="text/javascript" language="javascript" src="/NIMS/js/editor.bootstrap4.min.js"></script> 

<link rel="shortcut icon" href="/NIMS/img/NIMSlogo.png" type="image/x-icon"/>


</head>

<body class="updateaccBody" style="background-image: url('/NIMS/img/Door.jpg');">





<!-- <h1 class="AdminHeader">User Table <img style="width:40px;height:30px;margin-bottom:5px;" src="/NIMS/img/ikealogo.jpg"/></h1> -->

<div class="AdminSidenav" >
	<div class="buffer">
            	<img style="height: Auto; width:100%;" src="/NIMS/img/NIMSlogo.png"></img>
        	</div>	
	<a href="../staffmenu.php"><i class="fas fa-arrow-alt-circle-left"></i> Menu</a>
	<a href="userlogs.php"><i class="fas fa-align-left"></i> User Logs</a>
</div>

<div class="main">
	
		<div class="card"style="background:rgba(255,255,255,0.5); margin-top: 25px; margin-right: 25px;" >
              <div class="card-header">
		<div class="card-body"style="background:rgba(255,255,255,0.5);">  
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


		<div class="card"style="background:rgba(255,255,255,0.5); width:50%; margin-top:25px;" >
              <div class="card-header">
		<div class="card-body"style="background:rgba(255,255,255,0.5);">  
		<div class="table-responsive" style="padding-left:15px; margin-top:25px;">
			<table id="secondTable" class="table table-striped table-bordered table-hover" style="background-color:white;">
				<thead>
					<tr class="topoftable">
						<th>Access Level</th>
						<th>Group</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>1</td>
						<td>Super User</td>
					</tr>
					<tr>
						<td>2</td>
						<td>Planner</td>
					</tr>
				</tbody>
			</table>
		</div>
              </div>
		</div>
		</div>

</div> 


<script>



	var editor; // use a global for the submit and return data rendering in the examples
 
	$(document).ready(function() {
		editor = new $.fn.dataTable.Editor( {
			ajax: "AddOns/edit_updateacc.php",
			table: "#mainTable",
			fields: [ {
				label: "First name:",
				name: "fName"
			}, {
				label: "Last name:",
				name: "lName"
			}, {
				label: "Username:",
				name: "username"
			}, 
			{
				label: "Password:",
				name: "usersinfo.password",
				type: "password"

			}, 
			{
				label: "Access Level:",
				name: "level",
				type: "select",
				placeholder: "Select an access level",
				options: [ 
					{
						label: "Planner",
						value: "2"
					}, 
					{
						label: "Super User",
						value: "1"
					}
				]
			}
			]
		} );
		
		$('#mainTable').DataTable( {
			dom: "Bfrtip",
			ajax: "AddOns/edit_updateacc.php",
			order: [ 2, "asc" ],
			columns: [
				{ 
					title: "Name",
					data: null, 
					render: function ( data, type, row ) 
					{
						// Combine the first and last names into a single table field
						return data.fName+' '+data.lName;
					} 
				},
				{ title: "Username", data: "username" },
				{ title: "Access Level", data: "level" }
			],
			select: true,
			buttons: [
				{ extend: "create", editor: editor },
				{ extend: "edit",   editor: editor },
				{ extend: "remove", editor: editor }
			]
		} );
	} );




</script>


</body>
</html>	

