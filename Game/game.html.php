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
	if(!isset($_SESSION['lastActivityTime'])) {
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
</html>