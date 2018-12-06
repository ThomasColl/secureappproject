<!-- Name: Thomas Coll -->
<!-- Student Number: C00204384 -->

<?php 
	session_start();
	header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
	header("Cache-Control: post-check=0, pre-check=0", false);
	header("Pragma: no-cache");
	
	include('../utilities.php');
	$validity = new Activity();
	if(!isset($_SESSION['id'])) {
		$validity->createSesh();
		$_SESSION['lastActivityTime'] = date("U");
	}
	else if( $validity->checkIfUserNeedsToBeLoggedOut()) {
		$validity->killSession();
	}
	else {
		$_SESSION['lastActivityTime'] = date("U");
	}
	
	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		if(!isset($_SESSION['lockout'])) {
			//include the connection to the database
			$utilities = new Filters();
			$crypto = new Encryption();
			$isValid = false;
			$name = "";
			$pass = "";
			
			//Check to see if the username inputted has illegal characters
			if($utilities->filterValidation($_POST['username'])) {
				$name = $utilities->sanitise($_POST['username']);
			}
			else {
				$_SESSION["errorsForLoginPHP"] = "<br> The username has dodgey shit in it";
				$isValid = true;
			}
			//Check to see if the password inputted has illegal characters
			if($utilities->filterValidation($_POST['password'])) {
				$pass = $utilities->sanitise($_POST['password']);
			}
			else {
				$_SESSION["errorsForLoginPHP"] = "<br> The password has dodgey shit in it"; 
				$isValid = true;
			}

			if($name == "" || $pass == "") {
				$_SESSION["errorsForLoginPHP"] = "<br> No input"; 
				echo "<br> The username or password didnt get assigned";
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
			else {
				echo "There is something dodgey at play!";
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