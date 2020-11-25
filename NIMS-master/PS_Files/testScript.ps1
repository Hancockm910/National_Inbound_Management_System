write-host Starting testScript.ps1
$stopwatch =  [system.diagnostics.stopwatch]::StartNew()

[void][system.reflection.Assembly]::LoadFrom("\\retauso-nt8006\NIMS\MySQL Connector Net 8.0.20\Assemblies\v4.5.2\MySql.Data.dll")
[string]$sMySQLUserName = 'nims'
[string]$sMySQLPW = 'nims123'
[string]$sMySQLDB = 'NIMS'
[string]$sMySQLHost = '10.71.145.56'
[string]$sConnectionString = "server="+$sMySQLHost+";port=3306;uid=" + $sMySQLUserName + ";pwd=" + $sMySQLPW + ";database="+$sMySQLDB
$oConnection = New-Object MySql.Data.MySqlClient.MySqlConnection($sConnectionString)
$Error.Clear()
try
{
    $oConnection.Open()
}
catch
{
    throw ("Could not open a connection to Database $sMySQLDB on Host $sMySQLHost. Error: "+$Error[0].ToString())
}




FUNCTION Request
{

    # Read from database and fill table.



     $sql="SELECT * FROM master_file Limit 10"





    Write-Host "Running SQL"
    # $cmd = New-Object MySql.Data.MySqlClient.MySqlCommand
    # $cmd.Connection = $oConnection
    # $cmd.CommandText = $sql
    # $cmd.CommandTimeout = 0
    # $cmd.ExecuteNonQuery()


    # "https://www.liquidweb.com/kb/mysql-optimization-how-to-leverage-mysql-database-indexing/#:~:text=Indexing%20is%20a%20powerful%20structure,be%20used%20to%20enforce%20uniqueness."



    $command = New-Object MySql.Data.MySqlClient.MySqlCommand($sql, $oConnection)
    
    $dataAdapter = New-Object MySql.Data.MySqlClient.MySqlDataAdapter($command)
    
    # $DataSet = New-Object System.Data.DataSet  
    # $dataAdapter.Fill($DataSet)  
    # $DataSet.Tables[0] | export-csv -Path "\\retauso-nt8006\NIMS\Files\SAVE\Something.csv" -NoTypeInformation -Force

    
    
   $table = New-Object System.Data.DataTable
   $recordCount = $dataAdapter.Fill($table)
   Write-Host "Total lines: " $recordCount
   Write-Output $table | Out-GridView

   Write-Host "SQL Comlete"

}



FUNCTION Send
{
   # ############### Send data to database ####################

   $sql_statement = "SELECT * from master_file LIMIT 1"


    #$sql_statement = "DROP INDEX IndexCNS ON cns_information"

    #Execute MySQL statement
    $oMYSQLCommand = New-Object MySql.Data.MySqlClient.MySqlCommand
    $oMYSQLCommand.Connection=$oConnection
    $oMYSQLCommand.CommandText=$sql_statement

    [Void]$oMYSQLCommand.ExecuteNonQuery() 
}


# Send
Request

$stopwatch.stop()
$TotalTime = $stopwatch.elapsed
write-host "Total time taken" $TotalTime


