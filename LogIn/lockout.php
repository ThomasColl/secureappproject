<!-- Name: Thomas Coll -->
<!-- Student Number: C00204384 -->

<?php 
    session_start();
    error_reporting(E_ERROR | E_PARSE);
	header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
	header("Cache-Control: post-check=0, pre-check=0", false);
    header("Pragma: no-cache");
    date_default_timezone_set("UTC");
    $secs = 300;
    $curTime = date("U");

    try {
        $secs = $_SESSION['lockout'] - $curTime;
    }
    catch(Exception $e) {
        echo 'Caught exception: ',  $e->getMessage(), "\n";
    }

    if( $secs > 0) {
        echo "Hi, so... you're probaly wondering why you're here right? <br>";
        echo "Well you see you managed to mess up the password input too much so you have to stay here for " . $secs . " more seconds";
    }
    else {
        unset($_SESSION['lockout']);
        include("../utilities.php");
        $validity = new Activity();
        $validity->endLockout();
        header("Location: login.html.php");
	    exit;
    }
?>