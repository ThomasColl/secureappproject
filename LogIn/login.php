<!-- Name: Thomas Coll -->
<!-- Student Number: C00204384 -->

<?php 
	session_start();
	header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
	header("Cache-Control: post-check=0, pre-check=0", false);
	header("Pragma: no-cache");
	
	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		if(!isset($_SESSION['lockout'])) {
			//include the connection to the database
			include('../utilities.php');
			$utilities = new Filters();
			$crypto = new Encryption();
			$isTomfuckeryAtPlay = false;
			$name = "";
			$pass = "";
			
			//Check to see if the username inputted has illegal characters
			if($utilities->filterValidation($_POST['username'])) {
				$name = $utilities->sanitise($_POST['username']);
			}
			else {
				$_SESSION["errorsForLoginPHP"] = "<br> The username has dodgey shit in it";
				$isTomfuckeryAtPlay = true;
			}
			//Check to see if the password inputted has illegal characters
			if($utilities->filterValidation($_POST['password'])) {
				$pass = $utilities->sanitise($_POST['password']);
			}
			else {
				$_SESSION["errorsForLoginPHP"] = "<br> The password has dodgey shit in it"; 
				$isTomfuckeryAtPlay = true;
			}

			if($name == "" || $pass == "") {
				$_SESSION["errorsForLoginPHP"] = "<br> No input"; 
				echo "<br> The fucking username or password didnt get assigned";
				echo "<br> USERNAME: " . $name;
				echo "<br> PASSWORD: " . $pass;
				$isTomfuckeryAtPlay = true;
			}
			
			if(!$isTomfuckeryAtPlay) {

				include("../db.inc.php");

				echo "<br> username " . $name;
				echo "<br> password " . $pass;

				$sql = "SELECT * FROM users WHERE username= '" . $name . "'";
				$result = mysqli_query($con, $sql);
				$row = mysqli_fetch_array($result);
				if(!$result)
				{
					die("An Error in the SQL Query : " . mysqli_error($con));
				}
				if($row['username'] != $name || $row['password'] != $crypto->encrypt($pass, $row['salt'])) {
					$_SESSION["errorsForLoginPHP"] = "<br> The username/password combination does not match";
					header("Location: login.html.php");
					exit;
			}
				else {
					$_SESSION["sucessforLoginPHP"] = $name;
					$_SESSION["token"] = random_bytes(32);
					header("Location: ../MainMenu/mainmenu.html.php");
					exit;
				}

			}
			else {
				echo "There is some tomfuckery at play!";
				// header("Location: login.html.php");
				// exit;
			}
			
			//close the connection
			$con->close();

		}
		else {
			$_SESSION["errorsForLoginPHP"] = "<br> You are locked out for making too many mistakes";
		}
	}
	header("Location: login.html.php");
	exit;
?>