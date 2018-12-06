<?php	
	if(!isset($_SESSION)) {
        session_start();
    }
	header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
	header("Cache-Control: post-check=0, pre-check=0", false);
	header("Pragma: no-cache");

	$hostname = "localhost";
	$username = "root";
	$password = "";
	
	$dbname = "users";
	
	$con = mysqli_connect($hostname, $username, $password, $dbname);
	// $con = mysqli_connect($hostname, $username, $password);

	if(!$con)
	{
		$con = mysqli_connect($hostname, $username, $password);
		if(!con) {
			die("Failed to connect to MySQL: " . mysqli_connect_error());
		}
		else if(!$con->query("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = users")) {
			if(!$con->query("CREATE DATABASE users")) {
				die("There was an error in MySql: " . $con->error);
			}
			else {
				$con = mysqli_connect($hostname, $username, $password, $dbname);
				
				$sql = "CREATE TABLE users (
					`username` VARCHAR(50) PRIMARY KEY, 
					`password`  VARCHAR(256) NOT NULL,
					`salt` VARCHAR(256) NOT NULL
					)";
				
				if(!$con->query($sql)) {
					die("There was an error in MySql: " . $con->error);
				}
				else {
					echo "well its done";
				}
			}
			if(!$con->query("CREATE DATABASE sessions")) {
				die("There was an error in MySql: " . $con->error);
			}
			else {
				$con = mysqli_connect($hostname, $username, $password, "sessions");
				
				$sql = "CREATE TABLE sessions (
					`id` VARCHAR(256) PRIMARY KEY, 
					`attempts`  INT() NOT NULL,
					`lockout` INT() NOT NULL
					)";
				
				if(!$con->query($sql)) {
					die("There was an error in MySql: " . $con->error);
				}
				else {
					echo "well its done";
				}
			}
		}
	}
?>