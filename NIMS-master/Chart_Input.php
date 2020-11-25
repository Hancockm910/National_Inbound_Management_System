<?php

include('common/dbconn.php');

if(isset($_POST["BU"]))
{
       $BU = $_POST["BU"];
       $query = "SELECT COUNT(m.Shp_Id) AS Ship, m.Csm_ConsigneeCode AS BU, c.ETA AS ETA FROM master_file m, au_cus_dec c   WHERE c.Shp_Id = m.Shp_Id AND m.Csm_ConsigneeCode='$BU' GROUP BY c.ETA, m.Csm_ConsigneeCode";

       $output = array();

       $statement = $dbConn->query($query);
       if ($statement->num_rows > 0) {
              // output data of each row
              while($row = $statement->fetch_assoc()) 
              {
                     array_push($output, array(
                            'ETA'   => $row["ETA"],
                            'Ship'  => $row["Ship"]
                     ));
              }
       }
       echo json_encode($output);
}

