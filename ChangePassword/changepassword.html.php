<?php 
	session_start();
	header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
	header("Cache-Control: post-check=0, pre-check=0", false);
	header("Pragma: no-cache");
	if(!isset($_SESSION["token"])) {
		header("Location: ../LogIn/login.html.php");
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
		<script type="text/javascript" src="changepassword.js"></script>
	</head>
	<body>
	<a href= "../MainMenu/mainmenu.html.php"> Go Back </a>
	<?php 
			if(isset($_SESSION)) {
				if(isset($_SESSION["errorsforChangePasswordPHP"])) {
					echo "<br><br>";
					echo $_SESSION["errorsforChangePasswordPHP"];
					unset($_SESSION["errorsforChangePasswordPHP"]);
				}
				else if(isset($_SESSION["sucessforChangePasswordPHP"])) {
					echo "<br><br>";
					echo $_SESSION["sucessforChangePasswordPHP"];
					unset($_SESSION["sucessforChangePasswordPHP"]);
				}
			}
		?>
		<form name="changepasswordform" action="changepassword.php" onsubmit="return confirmCheck(this)" method="POST">
			<h4> Enter your details Your Details </h4>

			<!-- Create label for, input box and pattern of Password -->
			<BR><BR>
			<label for="oldpassword" > Old Password </label>
			<input type="password" name="oldpassword" id="oldpassword" title="Insert A Password, UpperCase, LowerCase, Number/SpecialChar and min 8 Chars" pattern="(?=^.{8,150}$)((?=.*\d)|(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$"  required/>

			<!-- Create label for, input box and pattern of Password -->
			<BR><BR>
			<label for="password" > New Password </label>
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
	</body>
</html>