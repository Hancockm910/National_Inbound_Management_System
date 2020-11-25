# FOLDER PATH \\retauso-nt8006\NIMS
# WEBSITE PATH http://retauso-nt8006/NIMS/

#
# This script is designed to import data from .csv files and INSERT the values into a MySQL Database
#

$AssemblyPath = "\\retauso-nt8006\NIMS\MySQL Connector Net 8.0.20\Assemblies\v4.5.2\MySql.Data.dll"
[void][system.reflection.Assembly]::LoadFrom($AssemblyPath) # Loads assemblies for powershell to connect to the MySQL DB
[string]$sMySQLUserName = 'nims'      # DB Username
[string]$sMySQLPW = 'nims123'         # DB Password
[string]$sMySQLDB = 'NIMS'            # DB Name
[string]$sMySQLHost = '10.71.145.56'  # DB IP Address
[string]$sConnectionString = "server=" + $sMySQLHost + ";port=3306;uid=" + $sMySQLUserName + ";pwd=" + $sMySQLPW + ";database=" + $sMySQLDB
$oConnection = New-Object MySql.Data.MySqlClient.MySqlConnection($sConnectionString)
$Error.Clear()
try {
  $oConnection.Open()
}
catch {
  throw ("Could not open a connection to Database $sMySQLDB on Host $sMySQLHost. Error: " + $Error[0].ToString())
}

# Function to pass parameter to Import-CSV
Function ImportCSV { 
  Param(
    [String] [Parameter(Mandatory = $true)] $csv_path
  )
  try {
    $csv_data = Import-Csv -Path $csv_path
    return $csv_data
  }
  catch {
    throw "Could not get data from CSV $error[0]"
  }
}

# Function that removes month names and replaces with the month's number.
Function DateSort {
  Param(
    [String] [Parameter(Mandatory = $true)] $old_date
  )

  try {
    switch -wildcard ($old_date) {
      "*jan*" { 
        $temp = $old_date.split(' ')
        $month = '01'
        $new_date = $temp[2] + "/" + "$month" + "/" + $temp[1].trim(',')
        return $new_date
      } 
      "*feb*" { 
        $temp = $old_date.split(' ')
        $month = '02'
        $new_date = $temp[2] + "/" + "$month" + "/" + $temp[1].trim(',')
        return $new_date
      }
      "*mar*" { 
        $temp = $old_date.split(' ')
        $month = '03'
        $new_date = $temp[2] + "/" + "$month" + "/" + $temp[1].trim(',')
        return $new_date
      }
      "*apr*" { 
        $temp = $old_date.split(' ')
        $month = '04'
        $new_date = $temp[2] + "/" + "$month" + "/" + $temp[1].trim(',')
        return $new_date
      }
      "*may*" { 
        $temp = $old_date.split(' ')
        $month = '05'
        $new_date = $temp[2] + "/" + "$month" + "/" + $temp[1].trim(',')
        return $new_date
      }
      "*jun*" { 
        $temp = $old_date.split(' ')
        $month = '06'
        $new_date = $temp[2] + "/" + "$month" + "/" + $temp[1].trim(',')
        return $new_date
      }
      "*jul*" { 
        $temp = $old_date.split(' ')
        $month = '07'
        $new_date = $temp[2] + "/" + "$month" + "/" + $temp[1].trim(',')
        return $new_date
      }
      "*aug*" { 
        $temp = $old_date.split(' ')
        $month = '08'
        $new_date = $temp[2] + "/" + "$month" + "/" + $temp[1].trim(',')
        return $new_date
      }
      "*sep*" { 
        $temp = $old_date.split(' ')
        $month = '09'
        $new_date = $temp[2] + "/" + "$month" + "/" + $temp[1].trim(',')
        return $new_date
      }
      "*oct*" { 
        $temp = $old_date.split(' ')
        $month = '10'
        $new_date = $temp[2] + "/" + "$month" + "/" + $temp[1].trim(',')
        return $new_date
      }
      "*nov*" { 
        $temp = $old_date.split(' ')
        $month = '11'
        $new_date = $temp[2] + "/" + "$month" + "/" + $temp[1].trim(',')
        return $new_date
      }
      "*dec*" { 
        $temp = $old_date.split(' ')
        $month = '12'
        $new_date = $temp[2] + "/" + "$month" + "/" + $temp[1].trim(',')
        return $new_date
      }
      default { throw "Non Matching Month. Edit switch please." }

    }

  }
  catch {
    # throw "Could not determine Month - Invalid Month is : $old_date"
  }

}


# Shuffles values to change format to SQL accepted format yyyy/mm/dd
Function DateFormatSwap {
  Param
  (
    [String] [Parameter(Mandatory = $true)] $old_date
  )
  $temp = $old_date.split('/')
  return $temp[2] + "/" + $temp[1] + "/" + $temp[0]
}

# Prints out values in $Error
Function ErrorReport {
  $timestamp = Get-Date -Format o | ForEach-Object { $_ -replace ":", "." }
  $P = "\\retauso-nt8006\NIMS\Files\Errors\$timestamp.txt"
  New-Item -Path $P
  foreach ($item in $Error) {
    # Write all errors to report.
    Add-Content -Path $P -Value $item
  }

}

#
#
# Functions below are to import to their respective table.
#
#
Function ImportOcean { # CNS_Ocean-en.csv
  Param
  (
    [String] [Parameter(Mandatory = $true)] $file_path
  )
  # Set file path and variable   
  $excel_data = Import-CSV -Delimiter "`t" -Path $file_path

  Write-Host "Clearing old rows from master_file"
  $sql_statement="DELETE FROM master_file WHERE ShpRou_RecPldArrDate < now() - INTERVAL 90 DAY;"

  Write-Host "Clearing master_file data older than 90 days"
  $oMYSQLCommand = New-Object MySql.Data.MySqlClient.MySqlCommand
  $oMYSQLCommand.Connection = $oConnection
  $oMYSQLCommand.CommandText = $sql_statement
  $oMYSQLCommand.CommandTimeout = 0
  $oMYSQLCommand.ExecuteNonQuery()

  Write-Host "Data clear complete"




  # Create variables for loading bar
  $stopwatch = [system.diagnostics.stopwatch]::StartNew()
  $e = Import-CSV -path $file_path | Measure-Object
  $i = 1
  $totalLines = $e.count

  foreach ($row in $excel_data) 
  { # Cycle through lines
    # Variables updated each loop for loading bar
    $p = $i / $e.count * 100
    $time = $stopwatch.elapsed
    Write-Progress -Activity "Importing: $file_path || Elapsed: $time" -Status "$i / $totalLines Complete:" -PercentComplete $p;
    $i++
    # End of loading bar section


    # Error checking, posting $row to console.
    #write-host $row

    # Values are extracted into a PSObject
    $record = New-Object PSObject -Property @{
      "UniqueNo"             = $row."Unique";
      "Shp Id"               = $row."Shp Id";
      "Shp CarName"          = $row."Shp CarName";
      "ShpRou_RecPldArrDate" = $row."ShpRou RecPldArrDate";
      "LuTr LuId"            = $row."LuTr LuId";
      "ShpCar BLid"          = $row."ShpCar BLid";
      "LuTr LutCode"         = $row."LuTr LutCode";
      "Csm ConsigneeCode"    = $row."Csm ConsigneeCode";
      "ShpRou StartCode"     = $row."ShpRou StartCode";
      "ShpRou StartType"     = $row."ShpRou StartType";
      "ShpRou EndCode"       = $row."ShpRou EndCode";
      "ShpRou EndType"       = $row."ShpRou EndType";
      "Shp_DateStart"        = $row."Shp DateStart";
      "LuTr SealNo"          = $row."LuTr SealNo";
      "Shipment_Type"        = $null; 
      "Land_Carrier"         = $row."Land_Carrier";
      "Ccs_SumWeiGkg"        = $row."Ccs SumWeiGkg";
      "Ccs SumVolGroAct"     = $row."Ccs SumVolGroAct";
    }
      
    # Set Shipment_Type based on available information
    if($record."Shp Id" -Contains "CP")
    {
      $record.Shipment_Type = "CP"
    }
    else
    {
      $record.Shipment_Type = "DD"
    }

      
    # if($record.ShpRou_RecPldArrDate)
    # {
    #   $record.ShpRou_RecPldArrDate = DateSort -old_date $record.ShpRou_RecPldArrDate
    # }
    # if ($record.Shp_DateStart) 
    # {
    #   $record.Shp_DateStart = DateSort -old_date $record.Shp_DateStart
    # }

    # Error checking. Writing $record's values to console.
    write-host $record

    # MySQL statement
    # Statement INSERTs into NIMS.master_file
    # NIMS.manual_input_transport table sets its primary keys unless one already exists.
    $sql_statement = 'INSERT INTO NIMS.master_file(UniqueNo, Shp_Id, Shp_CarName, ShpRou_RecPldArrDate, LuTr_LuId, ShpCar_BLid, LuTr_LutCode, Csm_ConsigneeCode, ShpRou_StartCode, ShpRou_StartType, ShpRou_EndCode, ShpRou_EndType, LuTr_SealNo, Shp_DateStart, Shipment_Type, Land_Carrier, Ccs_SumVolGroAct, Ccs_SumWeiGkg, Csm_ConsignorCode)  
      VALUES("'+$record.UniqueNo+'", "' + $record."Shp Id" + '","' + $record."Shp CarName" + '",' + $(if (!$record.ShpRou_RecPldArrDate) { "NULL" }else { '"' + $record.ShpRou_RecPldArrDate + '"' }) + ',"' + $record."LuTr LuId" + '","' + $record."ShpCar BLid" + '","' + $record."LuTr LutCode" + '","' + $record."Csm ConsigneeCode" + '","' + $record."ShpRou StartCode" + '","' + $record."ShpRou StartType" + '","' + $record."ShpRou EndCode" + '","' + $record."ShpRou EndType" + '","' + $record."LuTr SealNo" + '",' + $(if (!$record.Shp_DateStart) { "NULL" }else { '"' + $record.Shp_DateStart + '"' }) + ',"' + $record."Shipment_Type" + '","' + $record."Land_Carrier" + '","' + $record."Ccs SumVolGroAct" + '", "'+$record.Ccs_SumWeiGkg+'", "'+$record.Csm_ConsignorCode+'")
      ON DUPLICATE KEY UPDATE Shp_Id="'+$record."Shp Id"+'", Shp_CarName="' + $record."Shp CarName" + '", ShpRou_RecPldArrDate=' + $(if (!$record.ShpRou_RecPldArrDate) { "NULL" }else { '"' + $record.ShpRou_RecPldArrDate + '"' }) + ', LuTr_LuId="' + $record."LuTr LuId" + '", ShpCar_BLid="' + $record."ShpCar BLid" + '", LuTr_LutCode="' + $record."LuTr LutCode" + '", Csm_ConsigneeCode="' + $record."Csm ConsigneeCode" + '", ShpRou_StartCode="' + $record."ShpRou StartCode" + '", ShpRou_StartType="' + $record."ShpRou StartType" + '", ShpRou_EndCode="' + $record."ShpRou EndCode" + '", ShpRou_EndType="' + $record."ShpRou EndType" + '", LuTr_SealNo="' + $record."LuTr SealNo" + '", Shp_DateStart=' + $(if (!$record.Shp_DateStart) { "NULL" }else { '"' + $record.Shp_DateStart + '"' }) + ', Shipment_Type="' + $record."Shipment_Type" + '", Land_Carrier="' + $record."Land_Carrier" + '", Ccs_SumVolGroAct="' + $record."Ccs SumVolGroAct" + '", Ccs_SumWeiGkg="'+$record.Ccs_SumWeiGkg+'";
      INSERT IGNORE INTO NIMS.manual_input_transport(Shp_Id) VALUES("' + $record."Shp Id" + '");'    

    # Write host error checking
    # write-host $sql_statement

    #Execute MySQL statement
    $oMYSQLCommand = New-Object MySql.Data.MySqlClient.MySqlCommand
    $oMYSQLCommand.Connection = $oConnection
    $oMYSQLCommand.CommandText = $sql_statement

    $iRowsAffected = $oMYSQLCommand.ExecuteNonQuery()

  }
  write-host "Finished ImportOcean"
  # Stopwatch stopped and time written to console.
  $stopwatch.stop()
  $TotalTime = $stopwatch.elapsed
  write-host "Total time taken" $TotalTime

}

Function ImportConsignment { # CNS_Consignment-en.csv
  Param
  (
    [String] [Parameter(Mandatory = $true)] $file_path
  )
  #-Delimiter "`t"
  $excel_data = Import-CSV -Delimiter "`t" -Path $file_path

  Write-Host "Clearing old rows from consignment"
  $ClearConsignment = "DELETE FROM consignment WHERE UniqueNo NOT IN(SELECT UniqueNo FROM master_file);"
  $oMYSQLCommand = New-Object MySql.Data.MySqlClient.MySqlCommand
  $oMYSQLCommand.Connection = $oConnection
  $oMYSQLCommand.CommandText = $ClearConsignment
  $oMYSQLCommand.CommandTimeout = 0
  $oMYSQLCommand.ExecuteNonQuery()



  $stopwatch = [system.diagnostics.stopwatch]::StartNew()
  $e = Import-CSV  -Path $file_path | Measure-Object
  $i = 1
  $totalLines = $e.count

  foreach ($row in $excel_data) 
  {
    $p = $i / $e.count * 100
    $time = $stopwatch.elapsed
    Write-Progress -Activity "Importing: $file_path || Elapsed: $time" -Status "$i / $totalLines Complete:" -PercentComplete $p;
    $i++



    $record = New-Object psobject -Property @{
      "UniqueNo"          = $row.Unique;
      "Shp_Id"            = $row."Shp Id";
      "Csm_Id"            = $row."Csm Id";
      "Csm_ConsignorCode" = $row."Csm ConsignorCode";
      "Csm_ConsigneeCode" = $row."Csm ConsigneeCode";
      "Csm_Status"        = $row."Csm Stat";
      "LuTr_LuId"         = $row."LuTr LuId";
    }

    write-host $record

    $sql_statement = 'INSERT into NIMS.consignment(UniqueNo, Csm_Id, Csm_ConsignorCode, Csm_ConsigneeCode, Csm_Status, Shp_Id, LuTr_LuId)  
      VALUES("' + $record.UniqueNo + '","' + $record."Csm_Id" + '","' + $record."Csm_ConsignorCode" + '","' + $record."Csm_ConsigneeCode" + '","'+$record.Csm_Status+'", "'+$record.Shp_Id+'", "'+$record.LuTr_LuId+'")
      ON DUPLICATE KEY UPDATE UniqueNo="' + $record."UniqueNo" + '", Csm_Id="' + $record."Csm_Id" + '", Csm_ConsignorCode="' + $record."Csm_ConsignorCode" + '", Csm_ConsigneeCode="' + $record."Csm_ConsigneeCode" + '", Csm_Status="'+$record.Csm_Status+'"'
    $oMYSQLCommand = New-Object MySql.Data.MySqlClient.MySqlCommand
    $oMYSQLCommand.Connection = $oConnection
    $oMYSQLCommand.CommandText = $sql_statement
    $oMYSQLCommand.CommandTimeout = 0
    [Void]$oMYSQLCommand.ExecuteNonQuery()
  }
  $SetConsignor = "UPDATE master_file m, consignment c SET m.Shipment_Type='LF' 
  WHERE m.UniqueNo=c.UniqueNo 
  AND (c.Csm_ConsignorCode='294' 
  OR c.Csm_ConsignorCode='319');
  
  UPDATE master_file m, consignment c SET m.Shipment_Type='HF' 
  WHERE m.UniqueNo=c.UniqueNo 
  AND (c.Csm_ConsignorCode='277' 
  OR c.Csm_ConsignorCode='258');"  
  $oMYSQLCommand = New-Object MySql.Data.MySqlClient.MySqlCommand
  $oMYSQLCommand.Connection = $oConnection
  $oMYSQLCommand.CommandText = $SetConsignor
  $oMYSQLCommand.CommandTimeout = 0
  $oMYSQLCommand.ExecuteNonQuery()


  write-host "Finished ImportConsignment"
  $stopwatch.stop()
  $TotalTime = $stopwatch.elapsed
  write-host "Total time taken" $TotalTime
}

Function ImportInboundTD { # AU_Inbound_rolling_TD_File.csv
  Param
  (
    [String] [Parameter(Mandatory = $true)] $file_path
  )
  try {
    $excel_data = Get-Content -Path $file_path | Select-Object | ConvertFrom-Csv
  }
  catch {
    throw "Could not get data from CSV $error[0]"
  }

  Write-Host "Clearing Target_Dates"
  $CleanInboundTD = "DELETE FROM target_dates 
  WHERE Target_Date < now() - INTERVAL 90 DAY
  AND Shp_Id NOT IN (SELECT Shp_Id FROM master_file);"
  $oMYSQLCommand = New-Object MySql.Data.MySqlClient.MySqlCommand
  $oMYSQLCommand.Connection = $oConnection
  $oMYSQLCommand.CommandText = $CleanInboundTD

  $oMYSQLCommand.ExecuteNonQuery()



  $stopwatch = [system.diagnostics.stopwatch]::StartNew()
  $e = Import-CSV -path $file_path | Measure-Object
  $i = 1
  $totalLines = $e.count

  foreach ($row in $excel_data) {
    $p = $i / $e.count * 100
    $time = $stopwatch.elapsed
    Write-Progress -Activity "Importing: $file_path || Elapsed: $time" -Status "$i / $totalLines Complete:" -PercentComplete $p;
    $i++


    $record = New-Object psobject -Property @{
      "Shp_Id"       = $row."SHP_ID";
      "WeekNo"       = $row."Week";
      "Consignee"    = $row."Consignee";
      "UL_Number"    = $row."UL Number";
      "Earliest_TD"  = $row."Earliest TD";
      "Target_Date"  = $row."Target Date";
      "Latest_TD"    = $row."Latest TD";
      "Max_OTD"      = $row."Max OTD%";
      "Order_Spread" = $row."Order Spread";
    }
    

    if ($record.Earliest_TD) 
    {
      $record.Earliest_TD = DateFormatSwap -old_date $record.Earliest_TD
    }
    if ($record.Target_Date) 
    {
      #===========================TWO FORMATS IN SAME COLUMN. NEEDS FIXING=======================================
      $record.Target_Date = DateFormatSwap -old_date $record.Target_Date
    }
    if ($record.Latest_TD) 
    {
      $record.Latest_TD = DateFormatSwap -old_date $record.Latest_TD
    }
    if ($record.Max_OTD) 
    {
      $record.Max_OTD = $record.Max_OTD.trim("%")
    }
    
    write-host $record
    
    
    $sql_statement = 'INSERT into NIMS.target_dates(Shp_Id, WeekNo, Consignee, UL_Number, Earliest_TD, Target_Date, Latest_TD, Max_OTD, Order_Spread)  
    VALUES("' + $record."Shp_Id" + '","' + $record."WeekNo" + '","' + $record."Consignee" + '","' + $record."UL_Number" + '",' + $(if (!$record."Earliest_TD") { "NULL" }else { '"' + $record."Earliest_TD" + '"' }) + ',' + $(if (!$record."Target_Date") { "NULL" }else { '"' + $record."Target_Date" + '"' }) + ',' + $(if (!$record."Latest_TD") { "NULL" }else { '"' + $record."Latest_TD" + '"' }) + ',"' + $record."Max_OTD" + '","' + $record."Order_Spread" + '")
    ON DUPLICATE KEY UPDATE WeekNo="' + $record."WeekNo" + '", Consignee="' + $record."Consignee" + '", UL_Number="' + $record."UL_Number" + '", Earliest_TD=' + $(if (!$record."Earliest_TD") { "NULL" }else { '"' + $record."Earliest_TD" + '"' }) + ', Target_Date=' + $(if (!$record."Target_Date") { "NULL" }else { '"' + $record."Target_Date" + '"' }) + ', Latest_TD=' + $(if (!$record."Latest_TD") { "NULL" }else { '"' + $record."Latest_TD" + '"' }) + ', Max_OTD="' + $record."Max_OTD" + '", Order_Spread="' + $record."Order_Spread" + '";'
    #write-host $sql_statement
    $oMYSQLCommand = New-Object MySql.Data.MySqlClient.MySqlCommand
    $oMYSQLCommand.Connection = $oConnection
    $oMYSQLCommand.CommandText = $sql_statement



    [Void]$oMYSQLCommand.ExecuteNonQuery()
  }

  write-host "==============Finished ImportInboundTD($file_path)=============="
  $stopwatch.stop()
  $TotalTime = $stopwatch.elapsed
  write-host "Total time taken" $TotalTime
}


Function ImportOnCarriage { # CNS_On_Carriage-en.csv
  Param
  (
    [String] [Parameter(Mandatory = $true)] $file_path
  )
  try {
    #-Delimiter "`t" 
    $excel_data = Import-Csv -Delimiter "`t" -Path $file_path -Header Unique, header2, header3, Csm_ConsigneeCode, Shp_Id, Csm_Stat | Select-Object
  }
  catch {
    throw "Could not get data from CSV $error[0]"
  }



  $stopwatch = [system.diagnostics.stopwatch]::StartNew()
  $e = Import-CSV -path $file_path -Header Unique, header2, header3, Csm_ConsigneeCode, Shp_Id, Csm_Stat | Measure-Object
  $i = 1
  $totalLines = $e.count

  foreach ($row in $excel_data) {
    $p = $i / $e.count * 100
    $time = $stopwatch.elapsed
    Write-Progress -Activity "Importing: $file_path || Elapsed: $time" -Status "$i / $totalLines Complete:" -PercentComplete $p;
    $i++

    $record = New-Object psobject -Property @{
      "UniqueNo"                  = $row."Unique";
      "Shp_Id_On_Carriage"        = $row."Shp_Id";
      "Csm_Status"                = $row."Csm_Stat";
      "Csm_ConsigneeCode"         = $row."Csm_ConsigneeCode";
    }
    
    write-host $record

    $sql_statement = 'UPDATE consignment SET Shp_Id_On_Carriage="' + $record.Shp_Id_On_Carriage + '", Csm_Status="' + $record.Csm_Status + '" WHERE UniqueNo="' + $record.UniqueNo + '";'
    
    #write-host $sql_statement

    $oMYSQLCommand = New-Object MySql.Data.MySqlClient.MySqlCommand
    $oMYSQLCommand.Connection = $oConnection
    $oMYSQLCommand.CommandText = $sql_statement

    [Void]$oMYSQLCommand.ExecuteNonQuery()
  }
  write-host "==============Finished ImportOnCarriage($file_path)=============="
  $stopwatch.stop()
  $TotalTime = $stopwatch.elapsed
  write-host "Total time taken" $TotalTime
}

Function ImportDES { # AU Retail DES Slot Database.csv
  Param
  (
    [String] [Parameter(Mandatory = $true)] $file_path
  )
  try {
    $excel_data = Get-Content -Path $file_path | ConvertFrom-Csv
  }
  catch {
    throw "Could not get data from CSV $error[0]"
  }


    $stopwatch = [system.diagnostics.stopwatch]::StartNew()
    $e = Import-CSV -path $file_path | Measure-Object
    $i = 1
    $p = $i / $e.count * 100
    $totalLines = $e.count

    # Reset table before filling
    $trunc = "TRUNCATE TABLE NIMS.au_des_receiving_capacity;"
    $oMYSQLCommand = New-Object MySql.Data.MySqlClient.MySqlCommand
    $oMYSQLCommand.Connection = $oConnection
    $oMYSQLCommand.CommandText = $trunc

    [Void]$oMYSQLCommand.ExecuteNonQuery()

    foreach ($row in $excel_data) {
      $time = $stopwatch.elapsed
      Write-Progress -Activity "Importing: $file_path || Elapsed: $time" -Status "$i / $totalLines Complete:" -PercentComplete $p;
      $i++

      $record = New-Object psobject -Property @{
            "BU"       = $row."BU";
            "Sequence" = $row."Sequence #";
            "Week"     = $row."WEEK";
            "Day"      = $row."Day";
            "HF"       = $row."HF";
            "LF"       = $row."LF";
            "DD"       = $row."DD";}
          
      write-host $record



      $sql_statement = 'INSERT into NIMS.au_des_receiving_capacity(BU, Sequence, Week, Day, HF, LF, DD)  
          VALUES("' + $record."BU" + '","' + $record."Sequence" + '","' + $record."Week" + '","' + $record."Day" + '", "' + $record."HF" + '","' + $record."LF" + '", "' + $record."DD" + '")'
          
      #write-host $sql_statement

      $oMYSQLCommand = New-Object MySql.Data.MySqlClient.MySqlCommand
      $oMYSQLCommand.Connection = $oConnection
      $oMYSQLCommand.CommandText = $sql_statement

      [Void]$oMYSQLCommand.ExecuteNonQuery()
    }
    write-host "==============Finished ImportDES($file_path)=============="
    $stopwatch.stop()
    $TotalTime = $stopwatch.elapsed
    write-host "Total time taken" $TotalTime
}

Function ImportCNSDS { # CNS_DS.csv
  Param
  (
    [String] [Parameter(Mandatory = $true)] $file_path
  )
  # Set file path and variable
  $excel_data = Import-CSV -Delimiter "`t" -Path $file_path


  Write-Host "Clearing CNSDS"
  $CleanCNSDS = "DELETE FROM cns_ds WHERE Csm_Id NOT IN (SELECT Csm_Id FROM consignment);"
  $oMYSQLCommand = New-Object MySql.Data.MySqlClient.MySqlCommand
  $oMYSQLCommand.Connection = $oConnection
  $oMYSQLCommand.CommandText = $CleanCNSDS
  $oMYSQLCommand.CommandTimeout = 0
  $oMYSQLCommand.ExecuteNonQuery()

  # Create variables for loading bar
  $stopwatch = [system.diagnostics.stopwatch]::StartNew()
  $e = Import-CSV -path $file_path | Measure-Object
  $i = 1
  $totalLines = $e.count

  foreach ($row in $excel_data) 
  { # Cycle through lines
    # Variables updated each loop for loading bar
    $p = $i / $e.count * 100
    $time = $stopwatch.elapsed
    Write-Progress -Activity "Importing: $file_path || Elapsed: $time" -Status "$i / $totalLines Complete:" -PercentComplete $p;
    $i++
    # End of loading bar section


    
    $record = New-Object PSObject -Property @{
    "Csm_Id"            = $row.csm;
    "DC_Transit"			  = $row.DC_Transit;
    "DC_High_Bay"			  = $row.DC_High_Bay;
    "DC_Low_Bay"			  = $row.DC_Low_Bay;
    "CDC_Low_Bay"			  = $row.CDC_Low_Bay;
    "CDC_Mixed_Lines"		= $row.CDC_Mixed_Lines;
    }
      
    # Error checking. Writing $record's values to console.
    write-host $record
      

    $sql_statement = 'INSERT into cns_ds(Csm_Id, DC_Transit, DC_High_Bay, DC_Low_Bay, CDC_Low_Bay, CDC_Mixed_Lines)   
      VALUES("' + $record.Csm_Id + '","' + $record."DC_Transit" + '","' + $record."DC_High_Bay" + '","' + $record."DC_Low_Bay" + '","' + $record."CDC_Low_Bay" + '","' + $record."CDC_Mixed_Lines"+'")
      ON DUPLICATE KEY UPDATE Csm_Id ="' + $record."Csm_Id" + '", DC_Transit="' + $record."DC_Transit" + '", DC_Low_Bay="' + $record."DC_Low_Bay" + '", CDC_Low_Bay="' + $record."CDC_Low_Bay" + '", CDC_Mixed_Lines="' + $record."CDC_Mixed_Lines"+'"'
      
      
    #write-host $sql_statement

    #Execute MySQL statement
    $oMYSQLCommand = New-Object MySql.Data.MySqlClient.MySqlCommand
    $oMYSQLCommand.Connection = $oConnection
    $oMYSQLCommand.CommandText = $sql_statement

    [Void]$oMYSQLCommand.ExecuteNonQuery()

  }
  write-host "Finished ImportCNSDS"
  # Stopwatch stopped and time written to console.
  $stopwatch.stop()
  $TotalTime = $stopwatch.elapsed
  write-host "Total time taken" $TotalTime

}


Function ImportAUCustoms { # au_cus_dec.csv
  Param
  (
    [String] [Parameter(Mandatory = $true)] $file_path
  )
  # Set file path and variable
  $excel_data = Import-CSV -Path $file_path


  Write-Host "Clearing au_cus_dec"
  $CleanAUCustoms = "DELETE FROM au_cus_dec 
  WHERE ETA < now() - INTERVAL 90 DAY
  AND Shp_Id NOT IN (SELECT Shp_Id FROM master_file);"
  $oMYSQLCommand = New-Object MySql.Data.MySqlClient.MySqlCommand
  $oMYSQLCommand.Connection = $oConnection
  $oMYSQLCommand.CommandText = $CleanAUCustoms
  $oMYSQLCommand.CommandTimeout = 0
  $oMYSQLCommand.ExecuteNonQuery()



  # Create variables for loading bar
  $stopwatch = [system.diagnostics.stopwatch]::StartNew()
  $e = Import-CSV -path $file_path | Measure-Object
  $i = 1
  $totalLines = $e.count

  foreach ($row in $excel_data) 
  { # Cycle through lines
    # Variables updated each loop for loading bar
    $p = $i / $e.count * 100
    $time = $stopwatch.elapsed
    Write-Progress -Activity "Importing: $file_path || Elapsed: $time" -Status "$i / $totalLines Complete:" -PercentComplete $p;
    $i++
    # End of loading bar section


    
    $record = New-Object PSObject -Property @{
    "PrimaryKey"             	        = $row."Waybill";
    "Customs_Status"			            = $row."Customs Status";
    "Customs_Status_Description"			= $row."Customs Status Description";
    "Status_Date"			                = $row."Status Date";
    "ETA"                             = $row."Planned Arrival Date";  
    "Carrier_Vessel"                  = $row."Vessel Name";
    "Ocean_Carrier_VOY"               = $row."Voyage Number";       
    }

    if ($record.ETA) 
    {
      $record.ETA = DateFormatSwap -old_date $record.ETA
    }
    if ($record.Status_Date) 
    {
      $record.Status_Date = DateFormatSwap -old_date $record.Status_Date
    }

    # Error checking. Writing $record's values to console.
    write-host $record
      

    $sql_statement = 'INSERT IGNORE INTO au_cus_dec(Shp_Id, Customs_Status, Customs_Status_Description, Status_Date, ETA, Carrier_Vessel, Ocean_Carrier_VOY)   
    VALUES("'+$record.PrimaryKey+'", "'+$record.Customs_Status+'", "'+$record.Customs_Status_Description+'", "'+$record.Status_Date+'", "'+$record.ETA+'","'+$record.Carrier_Vessel+'", "'+$record.Ocean_Carrier_VOY+'")
    ON DUPLICATE KEY UPDATE Customs_Status="'+$record.Customs_Status+'", Customs_Status_Description="'+$record.Customs_Status_Description+'", Status_Date="'+$record.Status_Date+'", ETA="'+$record.ETA+'", Carrier_Vessel="'+$record.Carrier_Vessel+'", Ocean_Carrier_VOY="'+$record.Ocean_Carrier_VOY+'";'
    
 

    #write-host $sql_statement

    #Execute MySQL statement
    $oMYSQLCommand = New-Object MySql.Data.MySqlClient.MySqlCommand
    $oMYSQLCommand.Connection = $oConnection
    $oMYSQLCommand.CommandText = $sql_statement

    [Void]$oMYSQLCommand.ExecuteNonQuery()
  }
  write-host "Finished ImportAUCustoms"
  # Stopwatch stopped and time written to console.
  $stopwatch.stop()
  $TotalTime = $stopwatch.elapsed
  write-host "Total time taken" $TotalTime

}


Function ImportTSPReport { # AU_TSP_Report.csv
  Param
  (
    [String] [Parameter(Mandatory = $true)] $file_path
  )
  # Set file path and variable
  $excel_data = Import-CSV -Path $file_path

  # Create variables for loading bar
  $stopwatch = [system.diagnostics.stopwatch]::StartNew()
  $e = Import-CSV -path $file_path | Measure-Object
  $i = 1
  $totalLines = $e.count

  foreach ($row in $excel_data) 
  { # Cycle through lines
    # Variables updated each loop for loading bar
    $p = $i / $e.count * 100
    $time = $stopwatch.elapsed
    Write-Progress -Activity "Importing: $file_path || Elapsed: $time" -Status "$i / $totalLines Complete:" -PercentComplete $p;
    $i++
    # End of loading bar section


    
    $record = New-Object PSObject -Property @{
      "Shp_Id"              = $row."Shp Id";
      "Stevedore"		    	  = $row."Stevedore";
      "Wharf_Availability"  = $row."Wharf Availability Date";     
      "IN_YARD_DATE"			  = $row."In Yard Date";      
    }


    # Error checking. Writing $record's values to console.
    write-host $record
      
    # Prepare MySQL statement
    $sql_statement = 'UPDATE master_file SET Stevedore="' + $record.Stevedore + '", Wharf_Availability="' + $record.Wharf_Availability + '", IN_YARD_DATE="' + $record.IN_YARD_DATE + '" WHERE Shp_Id="' + $record.Shp_Id + '";'

    #write-host $sql_statement

    #Execute MySQL statement
    $oMYSQLCommand = New-Object MySql.Data.MySqlClient.MySqlCommand
    $oMYSQLCommand.Connection = $oConnection
    $oMYSQLCommand.CommandText = $sql_statement

    
    [Void]$oMYSQLCommand.ExecuteNonQuery()
  }
  write-host "Finished ImportTSPReport"
  # Stopwatch stopped and time written to console.
  $stopwatch.stop()
  $TotalTime = $stopwatch.elapsed
  write-host "Total time taken" $TotalTime

}



Function ImportAUPrio { # AU_PRIO_GIT_Report.csv
  Param
  (
    [String] [Parameter(Mandatory = $true)] $file_path
  )
  # Set file path and variable
  $excel_data = Import-CSV -Path $file_path

  # Create variables for loading bar
  $stopwatch = [system.diagnostics.stopwatch]::StartNew()
  $e = Import-CSV -path $file_path | Measure-Object
  $i = 1
  $totalLines = $e.count

  foreach ($row in $excel_data) 
  { # Cycle through lines
    # Variables updated each loop for loading bar
    $p = $i / $e.count * 100
    $time = $stopwatch.elapsed
    Write-Progress -Activity "Importing: $file_path || Elapsed: $time" -Status "$i / $totalLines Complete:" -PercentComplete $p;
    $i++
    # End of loading bar section


    
    $record = New-Object PSObject -Property @{
      "Shp_Id"    = $row."SHP_ID";
      "PRIO"			= $row."AU_PRIO Score";
    }


    # Error checking. Writing $record's values to console.
    write-host $record
      
    # Prepare MySQL statement
    $sql_statement = 'UPDATE master_file SET PRIO="' + $record.PRIO + '" WHERE Shp_Id="' + $record.Shp_Id + '";'

    #write-host $sql_statement

    #Execute MySQL statement
    $oMYSQLCommand = New-Object MySql.Data.MySqlClient.MySqlCommand
    $oMYSQLCommand.Connection = $oConnection
    $oMYSQLCommand.CommandText = $sql_statement

    [Void]$oMYSQLCommand.ExecuteNonQuery()
  }
  write-host "Finished ImportGITPRIO"
  # Stopwatch stopped and time written to console.
  $stopwatch.stop()
  $TotalTime = $stopwatch.elapsed
  write-host "Total time taken" $TotalTime

}


Function ImportDCOutbound { # AU_PRIO_GIT_Report.csv
  Param
  (
    [String] [Parameter(Mandatory = $true)] $file_path
  )
  
  
  $trunc = "TRUNCATE TABLE dc_outbound;"
  $oMYSQLCommand = New-Object MySql.Data.MySqlClient.MySqlCommand
  $oMYSQLCommand.Connection = $oConnection
  $oMYSQLCommand.CommandText = $trunc

  [Void]$oMYSQLCommand.ExecuteNonQuery()
  
  
  


  # Set file path and variable
  $excel_data = Import-CSV -Path $file_path 

  # Create variables for loading bar
  $stopwatch = [system.diagnostics.stopwatch]::StartNew()
  $e = Import-CSV -path $file_path | Measure-Object 
  $i = 1
  $totalLines = $e.count

  foreach ($row in $excel_data) 
  { 
    # Cycle through lines
    # Variables updated each loop for loading bar
    $p = $i / $e.count * 100
    $time = $stopwatch.elapsed
    Write-Progress -Activity "Importing: $file_path || Elapsed: $time" -Status "$i / $totalLines Complete:" -PercentComplete $p;
    $i++
    # End of loading bar section


    
    $record = New-Object PSObject -Property @{
      "Csm_Id"            = $row."CSM ID";
      "BU"			          = $row."BU";
      "Trailer_Number"		= $row."Trailer Number"; 
      "Dispatch_Number"		= $row."Dispatch Number";      
      "Dispatch_Note"			= $row."Dispatch Note";      
      "Trailer_Type"			= $row."Trailer Type";
      "DC_Gate"           = $row."DC Gate";
      "Load_Time"         = $row."Load Time";
      "Unload_Date"			  = $row."Unload Date";  
      "Seal_No"           = $row."Seal Number";
    }
    if ($record.Unload_Date) 
    {
      $record.Unload_Date = DateFormatSwap -old_date $record.Unload_Date
    }


    # Error checking. Writing $record's values to console.
    write-host $record
      
    if($record.Csm_Id -ne "")
    {
      # Prepare MySQL statement
      $sql_statement = 'INSERT IGNORE INTO dc_outbound(Csm_Id, BU, Trailer_Number, Dispatch_Number, Dispatch_Note, Trailer_Type, DC_Gate, Load_Time, Unload_Date, Seal_No)
      VALUES("'+$record.Csm_Id+'","'+$record.BU+'","'+$record.Trailer_Number+'","'+$record.Dispatch_Number+'","'+$record.Dispatch_Note+'","'+$record.Trailer_Type+'","'+$record.DC_Gate+'","'+$record.Load_Time+'","'+$record.Unload_Date+'", "'+$record.Seal_No+'");'
       
      #write-host $sql_statement

      #Execute MySQL statement
      $oMYSQLCommand = New-Object MySql.Data.MySqlClient.MySqlCommand
      $oMYSQLCommand.Connection = $oConnection
      $oMYSQLCommand.CommandText = $sql_statement

      [Void]$oMYSQLCommand.ExecuteNonQuery() 
    }

  }

  $sql_statement="delete from dc_outbound where Unload_Date < now() - interval 60 DAY;
  UPDATE dc_outbound 
  SET BU='006' WHERE BU='6';

  UPDATE dc_outbound 
  SET BU='017' WHERE BU='17';

  UPDATE dc_outbound 
  SET BU='034' WHERE BU='34';

  UPDATE dc_outbound 
  SET BU='044' WHERE BU='44';"
  $oMYSQLCommand = New-Object MySql.Data.MySqlClient.MySqlCommand
  $oMYSQLCommand.Connection = $oConnection
  $oMYSQLCommand.CommandText = $sql_statement
  $oMYSQLCommand.CommandTimeout = 0
  $oMYSQLCommand.ExecuteNonQuery()

  write-host "Finished ImportDCOutbound"
  # Stopwatch stopped and time written to console.
  $stopwatch.stop()
  $TotalTime = $stopwatch.elapsed
  write-host "Total time taken" $TotalTime
}




Function SUMcns_ds
{
  $sql_statement="TRUNCATE TABLE cns_ds_sum;

  INSERT INTO cns_ds_sum(Shp_Id, DC_Transit,DC_High_Bay,DC_Low_Bay,CDC_Low_Bay,CDC_Mixed_Lines)
  SELECT c.Shp_Id,
  sum(d.DC_Transit),
  sum(d.DC_High_Bay),
  sum(d.DC_Low_Bay),
  sum(d.CDC_Low_Bay),
  sum(d.CDC_Mixed_Lines)
  FROM consignment c, cns_ds d
  WHERE c.Csm_Id=d.Csm_Id
  GROUP BY c.Shp_Id;"

  Write-Host "Creating/Updating cns_ds_sum."
  $oMYSQLCommand = New-Object MySql.Data.MySqlClient.MySqlCommand
  $oMYSQLCommand.Connection = $oConnection
  $oMYSQLCommand.CommandText = $sql_statement
  $oMYSQLCommand.CommandTimeout = 0
  $oMYSQLCommand.ExecuteNonQuery()


  Write-Host "cns_ds_sum update complete"
}

Function ClearOldLogs
{
    $sql_statement="DELETE FROM userlogs WHERE dateChanged < now() - INTERVAL 30 DAY;"

    Write-Host "Clearing logs older than 30 days"
    $oMYSQLCommand = New-Object MySql.Data.MySqlClient.MySqlCommand
    $oMYSQLCommand.Connection = $oConnection
    $oMYSQLCommand.CommandText = $sql_statement
    $oMYSQLCommand.CommandTimeout = 0
    $oMYSQLCommand.ExecuteNonQuery()

    Write-Host "Log clear complete"

    $sql_statement="INSERT INTO archive(Shp_Id, MILKRUN, Unload_Date, Unload_Time, Dock_No, Delivery_Date, Delivery_Time, Transport_Comment, Last_Editor)
    SELECT Shp_Id, MILKRUN, Unload_Date, Unload_Time, Dock_No, Delivery_Date, Delivery_Time, Transport_Comment, Last_Editor
    FROM manual_input_transport
    WHERE Shp_Id NOT IN(SELECT Shp_Id FROM master_file)
    AND Unload_Date < now() - interval 90 DAY;"

    Write-Host "Adding items to archive."
    $oMYSQLCommand = New-Object MySql.Data.MySqlClient.MySqlCommand
    $oMYSQLCommand.Connection = $oConnection
    $oMYSQLCommand.CommandText = $sql_statement
    $oMYSQLCommand.CommandTimeout = 0
    $oMYSQLCommand.ExecuteNonQuery()

    Write-Host "Archive complete"

    $sql_statement="DELETE FROM manual_input_transport 
    WHERE Unload_Date < now() - INTERVAL 90 DAY 
    AND Shp_Id NOT IN(SELECT Shp_Id FROM master_file);"

    Write-Host "Clearing manual_input_transport data older than 90 days"
    $oMYSQLCommand = New-Object MySql.Data.MySqlClient.MySqlCommand
    $oMYSQLCommand.Connection = $oConnection
    $oMYSQLCommand.CommandText = $sql_statement
    $oMYSQLCommand.CommandTimeout = 0
    $oMYSQLCommand.ExecuteNonQuery()

    Write-Host "Data clear complete"

}

##################
#                #
# Function calls #
#                #
##################



ImportOcean       -file_path "\\retauso-nt8006\NIMS\Files\CNS_Ocean\*.csv"
ImportConsignment -file_path "\\retauso-nt8006\NIMS\Files\CNS_Consignment\*.csv"
ImportCNSDS       -file_path "\\retauso-nt8006\NIMS\Files\CNS_Report_DS\*.csv"
ImportOnCarriage  -file_path "\\retauso-nt8006\NIMS\Files\CNS_On_Carriage\*.csv"
ImportInboundTD   -file_path "\\retauso-nt8006\NIMS\Files\AU_Target_Dates\*.csv"
ImportDES         -file_path "\\retauso-nt8006\NIMS\Files\AU_DES\*.csv"
ImportAUCustoms   -file_path "\\retauso-nt8006\NIMS\Files\AU_Customs\*.csv"
ImportDCOutbound  -file_path "\\retauso-nt8006\NIMS\Files\DC015_Outbound\*.csv"
ImportTSPReport   -file_path "\\retauso-nt8006\NIMS\Files\TSP_Report\*.csv"
ImportAUPrio      -file_path "\\retauso-nt8006\NIMS\Files\AU_PRIO\*.csv"
  
SUMcns_ds
ClearOldLogs

# Saves errors to a file
ErrorReport