<?php
	$dbConn = new mysqli("localhost","nims","nims123","NIMS");

	if ($dbConn -> connect_errno) {
	  echo "Failed to connect to MySQL: " . $dbConn -> connect_error;
	  exit();
	}
 ?> 