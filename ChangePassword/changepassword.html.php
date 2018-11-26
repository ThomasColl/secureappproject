<?php 
	session_start();
	header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
	header("Cache-Control: post-check=0, pre-check=0", false);
	header("Pragma: no-cache");
	if(!isset($_SESSION["token"])) {
		header("Location: ../LogIn/login.html.php");
		exit;
	}
?>
<html>
	<head>
		<title> Security Assignment </title>
		<link rel="stylesheet" href="../base.css">
		<script type="text/javascript" src="../base.js"></script>
	</head>
</html>