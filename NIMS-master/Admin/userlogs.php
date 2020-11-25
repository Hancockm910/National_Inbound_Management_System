<?php 
    ob_start();

    if(session_id() == '') { session_start(); }       

    if (!$_SESSION["login"] || $_SESSION["level"] != 1)
    {
        header("location: /NIMS/logoff.php");
    } 
    // Include config files
    require_once("../nocache.php");
    require_once("../common/dbconn.php");
    include_once("../common/header.php");


    $sqlQuery = "SELECT * FROM userlogs";
    $data = $dbConn->query($sqlQuery);
    $tablesAndTheirData = array();
    array_push($tablesAndTheirData, array(
        'fields' => $data->fetch_fields(),
        'data' => $data
    ));
/*
    TODO: Need to setup test.manual
    SQL needs to display the username, datetime field_name and field_value 
    Do they want options to delete or a button to wipe anything from X amount of days prior?
    
*/


?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Section</title>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>  
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />  
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
<link rel="stylesheet" type="text/css" href="/NIMS/Styles/styles.css">

</head>
<body class="updateaccBody" style="background-image: url('/NIMS/img/Door.jpg'); background-repeat: no-repeat; background-size: cover;">

<div class="AdminSidenav">
    <div class="buffer">
        <img style="height: Auto; width:100%;" src="/NIMS/img/NIMSlogo.png"></img>
    </div>
    <a href="../staffmenu.php"><i class="fas fa-arrow-alt-circle-left"></i> Menu</a>
    <a href="updateacc.php"><i class="far fa-address-card"></i> Account details</a>
</div>

<div class="main">

    <div class="card"style="background:rgba(255,255,255,0.5);">
        <div class="card-body"style="background:rgba(255,255,255,0.5);">  
            <div class="table-responsive" style="padding-left:15px; margin-top:25px;">
            <?php foreach($tablesAndTheirData as $table): ?>
                    <?php if($table['data']->num_rows):?>
                        <table id="data_table" class="table table-striped" style="width:100%;">

                            <thead>
                                <tr>
                                <?php foreach($table['fields'] as $field): ?>

                                    <th><?php echo $field->name;?></th>

                                <?php endforeach; ?>
                                </tr>
                            </thead>
                            <tbody>
                        <?php while($row = $table['data']->fetch_assoc()): ?>
                            <tr>
                                <?php foreach($row as $key => $value):?>
                                    <td><?php echo $value; ?>
                                    </td>
                                <?php endforeach; ?>
                            </tr>
                        <?php endwhile;?>
                            </tbody>
                        </table>
                    <?php else:?>
                        <p>Table does not have any data</p>
                    <?php endif;?>
                <?php endforeach;
            ?>
            </div>
        </div>
    </div>
    
</div> 
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4-4.1.1/jq-3.3.1/jszip-2.5.0/dt-1.10.21/af-2.3.5/b-1.6.3/b-colvis-1.6.3/b-flash-1.6.3/b-html5-1.6.3/b-print-1.6.3/cr-1.5.2/fc-3.3.1/fh-3.1.7/kt-2.5.2/r-2.2.5/rg-1.1.2/rr-1.2.7/sc-2.0.2/sp-1.1.1/sl-1.3.1/datatables.min.css"/>
 
 <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
 <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
 <script type="text/javascript" src="https://cdn.datatables.net/v/bs4-4.1.1/jq-3.3.1/jszip-2.5.0/dt-1.10.21/af-2.3.5/b-1.6.3/b-colvis-1.6.3/b-flash-1.6.3/b-html5-1.6.3/b-print-1.6.3/cr-1.5.2/fc-3.3.1/fh-3.1.7/kt-2.5.2/r-2.2.5/rg-1.1.2/rr-1.2.7/sc-2.0.2/sp-1.1.1/sl-1.3.1/datatables.min.js"></script>
 



<script type="text/javascript">
        $(document).ready(function() {
            $('#data_table').DataTable( {
                dom: 'frtip',
                // buttons: [
                //     'copy', 'csv', 'excel', 'pdf', 'print', 'colvis'
                // ],
                "scrollY":       "80vh",
                "scrollCollapse": true,
                "scrollX":        true,
                "paging":         false,
                rowReorder:       true,
                select:           true,
                colReorder:       true,
                keys:             true
            } );
        } );
    </script>


</body>
</html>	

