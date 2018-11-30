<!-- Name: Thomas Coll -->
<!-- Student Number: C00204384 -->
<?php 
	session_start();
	header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
	header("Cache-Control: post-check=0, pre-check=0", false);
	header("Pragma: no-cache");
	$validity = new Activity();
	if (!isset($_SESSION['lastActivityTime'])) {
		$_SESSION['lastActivityTime'] = date("U");
	}
	else if( $validity->checkIfUserNeedsToBeLoggedOut()) {
		$validity->killSession();
	}
	else {
		$_SESSION['lastActivityTime'] = date("U");
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
		<link rel="stylesheet" href="signup.css">
		<script type="text/javascript" src="signup.js"></script>
	</head>
	<body>	
		<h1> Welcome to my project! </h1>

		<?php 
			if(isset($_SESSION)) {
				if(isset($_SESSION["errorsForSignupPHP"])) {
					echo "<br><br>";
					echo $_SESSION["errorsForSignupPHP"];
					unset($_SESSION["errorsForSignupPHP"]);
				}
				else if(isset($_SESSION["sucessforSignUpPHP"])) {
					echo "<br><br>";
					echo $_SESSION["sucessforSignUpPHP"];
					unset($_SESSION["sucessforSignUpPHP"]);
				}
			}
		?>
		<form name="signupform" action="signup.php" onsubmit="return confirmCheck(this)" method="POST">
			<h4> Sign Up Your Details </h4>
						
			<!-- Create label for, input box and pattern of name -->
			<BR><BR>
			<label for="username" > Name </label>
			<input type="text" name="username" id="username" pattern="[A-Za-z- 0-9]{1,50}" title="Letterd, numbers and spaces only up to 50 characters" required/>
			
			<!-- Create label for, input box and pattern of Password -->
			<BR><BR>
			<label for="password" > Password </label>
			<input type="password" name="password" id="password" title="Insert A Password, UpperCase, LowerCase, Number/SpecialChar and min 8 Chars" pattern="(?=^.{8,150}$)((?=.*\d)|(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$"  required/>

            <!-- Create label for, input box and pattern of Confirm Password -->
			<BR><BR>
			<label for="cpassword" > Confirm Password </label>
			<input type="password" name="cpassword" id="cpassword" title="Confirm Password" pattern="(?=^.{8,150}$)((?=.*\d)|(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$"  required/>
			<br><br>
			<BR><BR>
			
			<!-- Use div and CSS to centre buttons -->
			<div id="centre">
				<input type="reset" class="button" value="Cancel">
				<input type="submit" class="button" value="Create record"/>
			</div>
			
			<br><br>
			
		</form>
		<br><br>

		<div id="centre">
			Already signed up? <a href="../LogIn/login.html.php"> click here to login </a>
		</div>
	</body>
</html>