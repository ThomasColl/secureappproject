<!-- Name: Thomas Coll -->
<!-- Student Number: C00204384 -->
<?php 
	session_start();
	header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
	header("Cache-Control: post-check=0, pre-check=0", false);
	header("Pragma: no-cache");
	if(isset($_SESSION['lockout'])) {
		header("Location: lockout.php");
		exit;
	}
	else if(!isset($_SESSION['loginAttempts'])) {
		$_SESSION['loginAttempts'] = 3;
	}
	else if(isset($_SESSION['errorsForLoginPHP'])) {
		$_SESSION['loginAttempts'] -= 1;
	}

	if($_SESSION['loginAttempts'] <= 0) {
		unset($_SESSION['loginAttempts']);
		$_SESSION['lockout'] = date("U") + (5 * 60 );
		header("Location: lockout.php");
		exit;
	}

	if (!isset($_SESSION['lastActivityTime'])) {
		$_SESSION['lastActivityTime'] = date("U");
	}
	else if( $validity->checkIfUserNeedsToBeLoggedOut()) {
		$validity->killSession();
	}
	else {
		$_SESSION['lastActivityTime'] = date("U");
	}
?>
<html>
	<head>
		<title> Security Assignment </title>
		<link rel="stylesheet" href="../base.css">
		<script type="text/javascript" src="../base.js"></script>
	</head>
	<body>	
		<h1> Welcome to my project! </h1>

		<?php 
			if(isset($_SESSION)) {
				if(isset($_SESSION["errorsForLoginPHP"])) {
					echo "<br><br>";
					echo $_SESSION["errorsForLoginPHP"];
					unset($_SESSION["errorsForLoginPHP"]);
					try {
						echo "You have " . $_SESSION['loginAttempts'] . " attempts left";
					}
					catch (Exception $e) {
						$_SESSION['lockout'] = date("U") + (5 * 60 );
						header("Location: lockout.php");
						exit;
					}
				}
			}
		?>
		<form name="loginform" action="login.php" onsubmit="return confirmCheck(this)" method="POST">
			<h4> Log In Your Details </h4>
						
			<!-- Create label for, input box and pattern of name -->
			<BR><BR>
			<label for="username" > Name </label>
			<input type="text" name="username" id="username" pattern="[A-Za-z- 0-9]{1,50}" title="Letters, numbers and spaces only up to 50 characters" required/>
			
			<!-- Create label for, input box and pattern of Password -->
			<BR><BR>
			<label for="password" > Password </label>
			<input type="password" name="password" id="password" title="Insert A Password, UpperCase, LowerCase, Number/SpecialChar and min 8 Chars" pattern="(?=^.{8,150}$)((?=.*\d)|(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$"  required/>
			
			<br><br>
			<BR><BR>
			
			<!-- Use div and CSS to centre buttons -->
			<div id="centre">
				<input type="reset" class="button" value="Cancel">
				<input type="submit" class="button" value="Login"/>
			</div>
			
			<br><br>
			
		</form>
		<br><br>

		<div id="centre">
			No user yet? <a href="../SignUp/signup.html.php"> click here to sign up </a>
		</div>

	</body>
</html>