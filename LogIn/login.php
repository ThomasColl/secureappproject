<!-- Name: Thomas Coll -->
<!-- Student Number: C00204384 -->

<?php 
	session_start();
	header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
	header("Cache-Control: post-check=0, pre-check=0", false);
	header("Pragma: no-cache");
	
	include('../utilities.php');
	$validity = new Activity();
	
	if(!isset($_SESSION['id'])){
		$_SESSION["errorsForLoginPHP"] = "<br> Now now, you shouldnt be trying that sorta thing!";
		header("Location: login.html.php");
		exit;
	}
	else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		if(!isset($_SESSION['lockout'])) {
			//include the connection to the database
			$utilities = new Filters();
			$crypto = new Encryption();
			$isValid = false;
			$name = "";
			$pass = "";
			
			//Check to see if the username inputted has illegal characters
			$name = $utilities->sanitise($_POST['username']);
			//Check to see if the password inputted has illegal characters
			$pass = $utilities->sanitise($_POST['password']);

			if($name == "") {
				$_SESSION["errorsForLoginPHP"] = "<br> The username or password didnt get assigned 1"; 
				$isValid = true;
			}
			else if(!$utilities->determinePasswordStrength($pass)) {
				$_SESSION["errorsForLoginPHP"] = "<br> The username or password didnt get assigned 2"; 
				$isValid = true;
			}
			if(!$isValid) {

				include("../db.inc.php");

				$sql = "SELECT * FROM users WHERE username= '" . $name . "'";
				$result = mysqli_query($con, $sql);
				$row = mysqli_fetch_array($result);
				if(!$result)
				{
					die("An Error in the SQL Query : " . mysqli_error($con));
				}

				if($row['username'] != $name || $row['password'] != $crypto->encrypt($pass, $row['salt'])) {
					$_SESSION["errorsForLoginPHP"] = "<br> The username (". $name . ")/password combination does not match";
				}
				else {
					$_SESSION["sucessforLoginPHP"] = $name;
					$_SESSION["token"] = random_bytes(32);
					if(isset($_SESSION["token"])) {
						header("Location: ../MainMenu/mainmenu.html.php");
						exit;
					}
				}

				//close the connection
				$con->close();


			}
		}
		else {
			header("Location: lockout.php");
			exit;
		}
	}
	header("Location: login.html.php");
	exit;
?>