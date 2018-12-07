<!-- Name: Thomas Coll -->
<!-- Student Number: C00204384 -->

<?php 
	session_start();
	error_reporting(E_ERROR | E_PARSE);
	header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
	header("Cache-Control: post-check=0, pre-check=0", false);
	header("Pragma: no-cache");
	include('../utilities.php');
	$validity = new Activity();
	if(!isset($_SESSION['id'])){
		$_SESSION["errorsForSignUpPHP"] = "<br> Now now, you shouldnt be trying that sorta thing!";
		header("Location: signup.html.php");
		exit;
	}
	else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		//include the connection to the database
		$utilities = new Filters();
		$crypto = new Encryption();
		$isTheHTMLInputInvalid = false;
		$name = "";
		$pass = "";
		
		$name = $utilities->sanitise($_POST['username']);
		
		//Check to see if the password inputted has illegal characters and is the same as the confirm password
		if ($_POST['password'] == $_POST['cpassword']) {
			if($utilities->determinePasswordStrength($_POST['password'])) {
				$pass = $utilities->sanitise($_POST['password']);
			}
			else {
				$_SESSION["errorsForSignupPHP"] = "<br> The password is not strong enough <br>";
				$isTheHTMLInputInvalid = true; 
			}
		}
		else {
			$_SESSION["errorsForSignupPHP"] = "<br> The passwords do not align"; 
			$isTheHTMLInputInvalid = true;
		}
		if($name == "" || $pass == "") {
			$_SESSION["errorsForSignupPHP"] = "<br> There was no input"; 
			$isTheHTMLInputInvalid = true;
		}
		if(!$isTheHTMLInputInvalid) {
			include("../db.inc.php");

			$query = mysqli_query($con, "SELECT * FROM users WHERE username='" . $name . "'");
			if(!$query) {
			}
			else {
				if(mysqli_num_rows($query) > 0) {
					$_SESSION["errorsForSignupPHP"] = "<br> This username already exists"; 
				}
				else {
					$salt = $crypto->generateSalt(strlen($pass));
					$hashedPass = $crypto->encrypt($pass, $salt);
					//If there is nothing up then insert a new entry
					$sql = $con->prepare( "INSERT INTO users (username, password, salt) VALUES (?, ?, ?)");
					//bind the parameters
					$sql->bind_param("sss", $name, $hashedPass, $salt);
					
					//if the execution is sucessful then create an input to post back
					if($sql->execute()) {
						$_SESSION["sucessforLoginPHP"] = "<br> The name " . $name . " was added successfully";
						$con->close();
						header("Location: ../Login/login.html.php");
						exit;
					}
					//if the execution fails then kill the program and display errors
					else {
						die("An Error in the SQL Query : " . mysqli_error($con));
					}
					$con->close();
				}
			}
		}
		//if there are errors then add them to an input to post back
		else {
			echo "There is messing at play!";
		}
	}
	header("Location: signup.html.php");
	exit;
?>