<?php 
    ob_start();
    // ensure the page is not cached
	require_once("nocache.php");
	require_once("common/dbconn.php");
    include("common/header.php"); 
    // get access to the session variables
    if(session_id() == '') 
    {
         session_start();
    }
    // check if the user is logged in
    if (!$_SESSION["login"])
    {
        header("location: logoff.php");
    }

    $BU= array("006", "015", "017", "034", "044", "377", "384", "385", "446", "451", "460", "556", "557", "919");

   	$fname = $_SESSION['name'];
	$lname = $_SESSION['lname'];
	
    $sqlQuery = "SELECT BU, 
    Sequence, 
    WEEK, 
    Day, 
    HF, 
    LF, 
    DD 
    FROM au_des_receiving_capacity";

    $data = $dbConn->query($sqlQuery);
	$tablesAndTheirData = array();
	array_push($tablesAndTheirData, array(
		'fields' => $data->fetch_fields(),
		'data' => $data
    ));

    $BUSelection = "SELECT BU FROM au_des_receiving_capacity GROUP BY BU";

    $BUdata = $dbConn->query($BUSelection);
	$tablesAndTheirDataBU = array();
	array_push($tablesAndTheirDataBU, array(
		'fields' => $BUdata->fetch_fields(),
		'data' => $BUdata
    ));
    
    // $RevSelection = "SELECT Revision 
    // FROM au_des_receiving_capacity 
    // GROUP BY Revision";

    // $Revdata = $dbConn->query($RevSelection);
	// $tablesAndTheirDataRev = array();
	// array_push($tablesAndTheirDataRev, array(
	// 	'fields' => $Revdata->fetch_fields(),
	// 	'data' => $Revdata
    // ));
    

    if($_SERVER["REQUEST_METHOD"] == "POST")
    {
        if(isset($_POST['TransportSelection']))
        {
            if($_POST['TransportSelection'] == "015" || $_POST['TransportSelection'] == "017")
            {
                $_SESSION['store'] = $_POST['TransportSelection'];
                echo "<script>window.location.href='Reports/Report_DC.php';</script>";
                exit;
            }
            $_SESSION['store'] = $_POST['TransportSelection'];
            echo "<script>window.location.href='Reports/Report.php';</script>";
            exit;
        }
    }


?>

<!DOCTYPE html>
<html>
    <head>
    <meta charset="utf-8">
    <title>Menu</title>
    <link rel="stylesheet" type="text/css" href="/NIMS/Styles/styles.css">
    <!--Load the AJAX API-->
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4-4.1.1/jq-3.3.1/jszip-2.5.0/dt-1.10.21/af-2.3.5/b-1.6.3/b-colvis-1.6.3/b-flash-1.6.3/b-html5-1.6.3/b-print-1.6.3/cr-1.5.2/fc-3.3.1/fh-3.1.7/kt-2.5.2/r-2.2.5/rg-1.1.2/rr-1.2.7/sc-2.0.2/sp-1.1.1/sl-1.3.1/datatables.min.css"/>
 
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/v/bs4-4.1.1/jq-3.3.1/jszip-2.5.0/dt-1.10.21/af-2.3.5/b-1.6.3/b-colvis-1.6.3/b-flash-1.6.3/b-html5-1.6.3/b-print-1.6.3/cr-1.5.2/fc-3.3.1/fh-3.1.7/kt-2.5.2/r-2.2.5/rg-1.1.2/rr-1.2.7/sc-2.0.2/sp-1.1.1/sl-1.3.1/datatables.min.js"></script>
    
    <style>
        .FilterOp
        {
            color: #fff;
            background-color: #6c757d;
            padding:5px;
            margin-bottom:3px;
            margin-right:3px;
            border-radius: 5px;
            height:40px;
        }

        /* #chart_area{
            background-image: url('/NIMS/img/ikealogo.jpg');
            background-repeat: no-repeat; 
            background-size: cover;
            background-
        } */
    </style>

</head>

<body class="menuBody">

    <!-- <h1 class="menuHeader"> NIMS - National Inbound Management System <img style="width:40px;height:30px;position:relative;bottom:2px;" src="/NIMS/img/NIMSlogo.png"/></h1> -->
	<div class="sidenav">
        <div class="buffer">
            <img style="height: Auto; width:100%;" src="/NIMS/img/NIMSlogo.png"></img>
        </div>
        <?php 
        if($_SESSION['level'] == 1)
        {
            echo '<a href="/NIMS/Admin/updateacc.php"><i class="far fa-address-card"></i> Admin Section</a>';
        }
        
        ?>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="sideNav">


            <?php 

            
            //Transport view
            echo '<a class="nav-link collapsed" href="#" data-toggle="collapse" aria-expanded="false" data-target="#submenu" aria-controls="submenu"><i class="fas fa-building"></i> Stores</a>';
            // Transport planners pages
            $stores= array("006", "015", "017", "034", "044", "377", "384", "385", "446", "451", "460", "556", "557", "919");
            echo "<div id='submenu' class='submenu collapse'>";
                echo "<ul class='nav flex-column'>";
                foreach( $stores as $i)
                {
                    echo "<li class='nav-item'><button type='submit' name='TransportSelection' value='$i' class='storesList nav-link'>$i</button></li>";
                }
                echo "<ul class='nav-item'><button type='submit' name='TransportSelection' value='All Stores' class='nav-link storesList'>Country-AU</button></ul>";
                echo "</ul>";
            echo "</div>";
            ?>	
        </form>
        <a href="logoff.php" style="width:250px;"><i class="fas fa-coffee"></i> Log off</a>
	</div>
	<div class="main">




        <?php 
        //  echo '<pre>';
        // var_dump($_SESSION);
        // echo '</pre>';
        ?>



        <!-- ============================================================== -->
        <!-- Chart  -->
        <!-- ============================================================== -->
        <div class="card"style="background:rgba(255,255,255,0.5);">
            <div class="card-body"style="background:rgba(255,255,255,0.8);"> 
            <h2 class="card-title text-center font-weight-bold">Inbound Containers</h2>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-md-3">
                                <select name="year" class="form-control FilterOp" id="year">
                                    <option value="" hidden>Select BU</option>
                                <?php
                                foreach($BU as $row)
                                {
                                    echo '<option value="'.$row.'">'.$row.'</option>';
                                }
                                ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div id="chart_area" style="height: 300px;"><img src="/NIMS/img/ikealogo.jpg" class="rounded mx-auto d-block" style="height:100%; width:auto;"/></div>
                    </div>
                </div>
            </div>
        </div>
        <!-- ============================================================== -->
        <!-- data table  -->
        <!-- ============================================================== -->
        <div class="card"style="background:rgba(255,255,255,0.5);">
            <div class="card-body"style="background:rgba(255,255,255,0.8);">  
                <h2 class="card-title text-center font-weight-bold">DES Slot Capacity</h2>
 
                <div id="CustomCodes_filter">                 
                <div class="table-responsive" style="padding-left:15px; margin-top:25px;">
                    <?php foreach($tablesAndTheirData as $table): ?>
                            <?php if($table['data']->num_rows):?>
                                <table id="CustomCodes" class="table table-striped table-bordered second hover" style="width:100%;">

                                    <thead>
                                        <tr>
                                        <?php foreach($table['fields'] as $field): ?>

                                            <th class="<?php echo $field->name;?>"><?php echo $field->name;?></th>

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
    </div>








    <script type="text/javascript">
        $(document).ready(function() {
            $('#CustomCodes').DataTable( {
                dom: 'Brti',
                buttons: [
                    'copy', 'excel'
                ],
                "scrollY":       "65vh",
                "scrollCollapse": true,
                "scrollX":        true,
                "paging":         false,
                select:           true,


//https://datatables.net/examples/api/multi_filter_select.html
//https://datatables.net/examples/api/multi_filter.html
//http://live.datatables.net/mucevape/1/edit
                initComplete: function () 
                {
                    this.api().columns([2]).every( function () 
                    {
                        var column = this;
                        var br = $('<br>')
                            .prependTo('#CustomCodes_wrapper');

                        var select = $('<select name="WeekFilter" class="selectpicker FilterOp"></select>')
                            .prependTo( "#CustomCodes_wrapper" )
                            .on( 'change', function () 
                            {
                                var val = $.fn.dataTable.util.escapeRegex($(this).val());
        
                                column
                                    .search( val ? '^'+val+'$' : '', true, false )
                                    .draw();
                            } );
                        select.append( '<option value="" hidden>Filter by Week:</option>');
                        select.append( '<option value="" >All</option>');
                        column.data().unique().sort().each( function ( d, j ) 
                        {
                            select.append( '<option value="'+d+'">'+d+'</option>' )
                        } );

                    } );

                    this.api().columns([0]).every( function () 
                    {
                        var column = this;

                        var select = $('<select name="BUFilter" class="selectpicker FilterOp"></select>')
                            .prependTo( "#CustomCodes_wrapper" )
                            .on( 'change', function () 
                            {
                                var val = $.fn.dataTable.util.escapeRegex($(this).val());
        
                                column
                                    .search( val ? '^'+val+'$' : '', true, false )
                                    .draw();
                            } );
                        // var label = $('<label for="BUFilter"> BU:  </label>')
                        //     .prependTo('#CustomCodes_wrapper');
                        select.append( '<option value="" hidden>Filter by BU:</option>');
                        select.append( '<option value="" >All</option>');
                        column.data().unique().sort().each( function ( d, j ) {
                            select.append( '<option value="'+d+'">'+d+'</option>' )
                        } );
                    } );

                }


            } );
        } );



    </script>

<!--==============================================================================-->
<!--==============================================================================-->
<!--==============================================================================-->
<!--==============================================================================-->

<script type="text/javascript">
    // https://www.webslesson.info/2018/07/create-dynamic-column-chart-using-php-ajax-with-google-charts.html
    google.charts.load('current', {'packages':['line']});
    google.charts.setOnLoadCallback();

    function load_monthwise_data(BU, title)
    {
        var temp_title = title + ' '+BU+'';
        $.ajax({
            url:"Chart_Input.php",
            method:"POST",
            data:{BU:BU},
            dataType:"JSON",
            success:function(data)
            {
                drawMonthwiseChart(data, temp_title);
            }
        });
    }

    function processDate(adate) {
        var splitArray = new Array();
        splitArray = adate.split("-");
        var year = splitArray[0];
        var month = splitArray[1] - 1;
        var day = splitArray[2];
        return new Date(year, month, day);
    }

    function drawMonthwiseChart(chart_data, chart_main_title)
    {
        var jsonData = chart_data;
        var data = new google.visualization.DataTable();
        data.addColumn('date', 'ETA');
        data.addColumn('number', 'Containers');
        $.each(jsonData, function(i, jsonData){
            var ETA = new Date(jsonData.ETA);
            var Ship = parseInt(jsonData.Ship);
            data.addRows([[ETA, Ship]]);
        });
        var options = {
            title:chart_main_title,
            hAxis: {
                title: "Date"
            },
            vAxis: {
                title: 'Containers'
                // viewWindow: {
                //     min: 0,
                //     max: 60
                // }
                // },
                // ticks: []
            }
        };

        var chart = new google.charts.Line(document.getElementById('chart_area'));
        chart.draw(data, google.charts.Line.convertOptions(options));
    }

</script>

<script>
    
    $(document).ready(function(){

        $('#year').change(function(){
            var year = $(this).val();
            if(year != '')
            {
                load_monthwise_data(year, 'BU data for:');
            }
        });

    });

</script>

  
</body>
</html>
