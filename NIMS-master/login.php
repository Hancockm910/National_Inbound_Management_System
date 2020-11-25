<?php 
	ob_start();
	// ensure page is not cached
	require_once("nocache.php");

	$errorMessage = '';

	// check that the form has been submitted
    	if(isset($_POST['submit'])) 
	{
        // check that username and password were entered
		if(empty($_POST['userName']) || empty($_POST['pword'])) 
		{
			$errorMessage = "Both username and password are required";
		} 
		else 
		{
			// connect to the database
			require_once("common/dbconn.php");

			// parse username and password for special characters
			$username = mysqli_escape_string($dbConn, $_POST['userName']);
			$password = mysqli_escape_string($dbConn, $_POST['pword']);
			// Hash the password so it can be compared to the database value.
			$password = hash('sha256',  $password);


			//query the db
			$sql = "SELECT * FROM usersinfo WHERE username = '$username' AND password = '$password'";
			$rs = $dbConn->query($sql);

			if($rs->num_rows) 
			{
				// Start a new session for the user
				session_start();
				$_SESSION['login'] = true;

				// Store the user details in session variables
				$user = $rs->fetch_assoc();
				$_SESSION['who'] = $user['username'];
				$_SESSION['name'] = $user['fName'];
				$_SESSION['lname'] = $user['lName'];
				$_SESSION['level'] = $user['level'];
				
				// Redirect the user to the secure page
				if($_SESSION['login'])
				{
					echo "<script>window.location.href='staffmenu.php';</script>";
					exit;
				}
			} 
			else 
			{ // Set the error message
				$errorMessage = "Invalid Username or Password";
			}	
		}
	}
?>
<!DOCTYPE html>
<html>
<head>
    	<title>NIMS</title>
	<link rel="stylesheet" type="text/css" href="/NIMS/Styles/styles.css">
   	 <meta charset="utf-8">
    	<link rel="shortcut icon" href="/NIMS/img/NIMSlogo.png" type="image/x-icon"/>
</head>
<body class="loginBody">
	<div class="form">
		<div class="loginSide">	
			<h1 class=>NIMS</h1>
			<img style="float:center;" src="/NIMS/img/NIMSlogo.png"/>

		</div>
		<form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
			<div class="container">
				<input type="text" placeholder="Enter Username" name="userName" required>
				<input type="password" placeholder="Enter Password" name="pword" required>
			</div>
			<p style="color:red;"><?php echo $errorMessage;?></p>
			<input type="submit" value="Login" name="submit" class="button">
		</form>
	</div>
</body>
</html>
