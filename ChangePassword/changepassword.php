<!-- Name: Thomas Coll -->
<!-- Student Number: C00204384 -->

<?php 
	session_start();
	header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
	header("Cache-Control: post-check=0, pre-check=0", false);
	header("Pragma: no-cache");
	if(!isset($_SESSION["token"])) {
		header("Location: ../LogIn/login.html.php");
		exit;
	}

	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		//include the connection to the database
		include('../utilities.php');
		$utilities = new Filters();
		$crypto = new Encryption();
		$isTomfuckeryAtPlay = false;
		$oldPassword = "";
		$pass = "";
		
		//Check to see if the username inputted has illegal characters
		if($utilities->filterValidation($_POST['oldpassword'])) {
			$oldPassword = $utilities->sanitise($_POST['oldpassword']);
		}
		else {
			$_SESSION["errorsforChangePasswordPHP"] = "<br> The old password has dodgey shit in it";
			$isTomfuckeryAtPlay = true;
		}
		//Check to see if the password inputted has illegal characters
		if($utilities->filterValidation($_POST['password'])) {
			if ($_POST['password'] == $_POST['cpassword']) {
				if($utilities->determinePasswordStrength($_POST['password'])) {
					$pass = $utilities->sanitise($_POST['password']);
				}
				else {
					$_SESSION["errorsforChangePasswordPHP"] = "<br> The password is not strong enough <br>";
					$isTomfuckeryAtPlay = true; 
				}
			}
			else {
				$_SESSION["errorsforChangePasswordPHP"] = "<br> The passwords do not align"; 
				$isTomfuckeryAtPlay = true;
			}
		}
		else {
			$_SESSION["errorsforChangePasswordPHP"] = "<br> The password has dodgey shit in it"; 
			$isTomfuckeryAtPlay = true;
		}

		if($oldPassword == "" || $pass == "") {
			$_SESSION["errorsforChangePasswordPHP"] = "<br> No input"; 
			echo "<br> The fucking username or password didnt get assigned";
			echo "<br> Old PASSWORD: " . $oldPassword;
			echo "<br> PASSWORD: " . $pass;
			$isTomfuckeryAtPlay = true;
		}
		
		if(!$isTomfuckeryAtPlay) {

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
					header("Location: ../MainMenu/logout.php");
					exit;
				}
			}
		}
		//if there are errors then add them to an input to post back
		else {
			echo "There is some tomfuckery at play!";
		}
		
		//close the connection
		$con->close();
	}

	header("Location: changepassword.html.php");
	exit;
?>