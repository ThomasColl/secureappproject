<!-- Name: Thomas Coll -->
<!-- Student Number: C00204384 -->

<?php 
	session_start();
	header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
	header("Cache-Control: post-check=0, pre-check=0", false);
	header("Pragma: no-cache");
	include('../utilities.php');
	$validity = new Activity();
	if(isset($_SESSION['lockout'])) {
		header("Location: lockout.php");
		exit;
	}
	else if(!isset($_COOKIE['id'])) {
		echo "<br> mark 1!";
		$_SESSION['lastActivityTime'] = date("U");
		$validity->createSesh();
	}
	else if(isset($_SESSION['errorsForLoginPHP'])) {
		$validity->depreciateAttempts();
	}

	if($validity->checkLockout() == true) {
		$_SESSION['lockout'] = date("U") + (5 * 60 );
		header("Location: lockout.php");
		exit;
	}
	if(!isset($_SESSION['lastActivityTime'])) {
		$_SESSION['lastActivityTime'] = date("U");
	}
	else if( $validity->checkIfUserNeedsToBeLoggedOut()) {
		$validity->killSession();
	}
	else {
		$_SESSION['lastActivityTime'] = date("U");
	}
	
	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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
			$_SESSION["errorsForSignupPHP"] = "<br> No input"; 
			echo "<br> The username or password didnt get assigned a value";
			$isTheHTMLInputInvalid = true;
		}
		
		if(!$isTheHTMLInputInvalid) {

			include("../db.inc.php");

			$query = mysqli_query($con, "SELECT COUNT(1) FROM users WHERE username=" . $name);
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
					echo($name . " was added successfully");
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
		//if there are errors then add them to an input to post back
		else {
			echo "There is messing at play!";
		}
	}
	header("Location: signup.html.php");
	exit;
?>