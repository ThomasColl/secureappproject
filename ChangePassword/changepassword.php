<!-- Name: Thomas Coll -->
<!-- Student Number: C00204384 -->

<?php 
	session_start();
	header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
	header("Cache-Control: post-check=0, pre-check=0", false);
	header("Pragma: no-cache");

	include('../utilities.php');
	$validity = new Activity();
	if(!isset($_SESSION["token"])) {
		header("Location: ../LogIn/login.html.php");
		exit;
	}
	if( $validity->checkIfUserNeedsToBeLoggedOut()) {
		$validity->killSession();
	}
	else {
		$_SESSION['lastActivityTime'] = date("U");
	}

	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		//include the connection to the database
		include('../utilities.php');
		$utilities = new Filters();
		$crypto = new Encryption();
		$isValidAndNotMessedWith = false;
		$oldPassword = "";
		$pass = "";
		
		//Check to see if the password inputted has illegal characters
		$oldPassword = $utilities->sanitise($_POST['oldpassword']);
		//Check to see if the password inputted has illegal characters
		if ($_POST['password'] == $_POST['cpassword']) {
			if($utilities->determinePasswordStrength($_POST['password'])) {
				$pass = $utilities->sanitise($_POST['password']);
			}
			else {
				$_SESSION["errorsforChangePasswordPHP"] = "<br> The password is not strong enough <br>";
				$isValidAndNotMessedWith = true; 
			}
		}
		else {
			$_SESSION["errorsforChangePasswordPHP"] = "<br> The passwords do not align"; 
			$isValidAndNotMessedWith = true;
		}

		if($oldPassword == "" || $pass == "") {
			$_SESSION["errorsforChangePasswordPHP"] = "<br> No input"; 
			$isValidAndNotMessedWith = true;
		}
		
		if(!$isValidAndNotMessedWith) {

			include("../db.inc.php");

			$sql = "SELECT * FROM users WHERE username= '" . $_SESSION["sucessforLoginPHP"] . "'";
			$result = mysqli_query($con, $sql);
			$row = mysqli_fetch_array($result);
			if(!$result)
			{
				die("An Error in the SQL Query : " . mysqli_error($con));
			
			}
			if($row['password'] != $crypto->encrypt($oldPassword, $row['salt'])) {
				$_SESSION["errorsforChangePasswordPHP"] = "<br> The old password is incorrect";
				$con->close();
				header("Location: changepassword.html.php");
				exit;
			}
			else {
				$sql = "UPDATE users SET password='" . $crypto->encrypt($pass, $row['salt']) . "' WHERE username= '" . $_SESSION["sucessforLoginPHP"] . "'";
				$result = mysqli_query($con, $sql);
				$row = mysqli_fetch_array($result);
				if(!$result)
				{
					die("An Error in the SQL Query : " . mysqli_error($con));
				
				}
				else {
					$con->close();
					header("Location: ../MainMenu/logout.php");
					exit;
				}
			}
		}
		//if there are errors then add them to an input to post back
		else {
			echo "<br> There is something incorrect here!";
		}
		
		//close the connection
		$con->close();
	}

	header("Location: changepassword.html.php");
	exit;
?>