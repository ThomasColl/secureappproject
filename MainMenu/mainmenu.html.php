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
?>
<html>
	<head>
		<title> Security Assignment </title>
		<link rel="stylesheet" href="../base.css">
		<script type="text/javascript" src="../base.js"></script>
	</head>
	<body>	
		<h1> Welcome to my project! Main Menu </h1>

		<?php 
			if(!empty($_SESSION["errorsFormainmenuPHP"])) {
				echo "<br><br>";
				echo $_SESSION["errorsFormainmenuPHP"];
				unset($_SESSION["errorsFormainmenuPHP"]);
			}
			else if(!empty($_SESSION["sucessformainmenuPHP"])) {
				echo "<br><br>";
				echo $_SESSION["sucessformainmenuPHP"];
				unset($_SESSION["sucessformainmenuPHP"]);
			}
		?>
		Welcome <?php echo $_SESSION["sucessforLoginPHP"]; ?> to the project! <br><br>

		So... you probaly want to know what it is you can do while you're here. I mean you could go and <a href="../ChangePassword/changepassword.html.php"> change your password </a>.<br>
		You could alternatvely go and read this <a href="../SecretLink/secret.html.php"> go to the secret link </a> OR <a href="../Game/game.html.php"> play a game </a> <br>
		<br><br>
		
		<a href='logout.php'><button>Log Out</button></a>
	</body>
</html>