<?php
    if(!isset($_SESSION)) {
        session_start();
    }
    class Filters {
        function sanitise($stringWhichRichardMayHaveFuckedWith) {
            if(strpos($stringWhichRichardMayHaveFuckedWith, "<")) {
                $stringWhichRichardMayHaveFuckedWith = str_replace('<', '&gt;', $stringWhichRichardMayHaveFuckedWith);
            }
            if(strpos($stringWhichRichardMayHaveFuckedWith, ">")) {
                $stringWhichRichardMayHaveFuckedWith = str_replace('>', '&lt;', $stringWhichRichardMayHaveFuckedWith);
            }
            if(strpos($stringWhichRichardMayHaveFuckedWith, "'")) {
                $stringWhichRichardMayHaveFuckedWith = str_replace('\'', '&#039;', $stringWhichRichardMayHaveFuckedWith);
            }
            if(strpos($stringWhichRichardMayHaveFuckedWith, "\"")) {
                $stringWhichRichardMayHaveFuckedWith = str_replace('"', '&quot;', $stringWhichRichardMayHaveFuckedWith);
            }
            if(strpos($stringWhichRichardMayHaveFuckedWith, "&")) {
                $stringWhichRichardMayHaveFuckedWith = str_replace('>', '&amp;', $stringWhichRichardMayHaveFuckedWith);
            }
            
            return $stringWhichRichardMayHaveFuckedWith;
        }
    
        function determinePasswordStrength($password) {
            $isItStrong = true;
            if(strlen($password) < 8) {
                $_SESSION["errorsForSignupPHP"] .= "<br> The password is too short!";
                $isItStrong =  false;
            }
            if(!preg_match("#[0-9]+#", $password)) {
                $_SESSION["errorsForSignupPHP"] .= "<br> The password does not contain a number";
                $isItStrong = false;
            }
            if(!preg_match("#[A-Z]+#", $password)) {
                $_SESSION["errorsForSignupPHP"] .= "<br> The password does not contain a capital letter";
                $isItStrong = false;
            }
            if(!preg_match("#[a-z]+#", $password)) {
                $_SESSION["errorsForSignupPHP"] .= "<br> The password does not contain a non capital letter";
                $isItStrong = false;
            }
            
            return $isItStrong;
        }
    }
    class Encryption {
        function encrypt($stringToEncrypt, $salt) {
            $hashed = hash('sha256', $salt . $stringToEncrypt);
            return $hashed;
        }
        function generateSalt($len) {
            $e = new Encryption();
            return $e->generateRandomString($len);
        }
        function generateRandomString($length){ 
            $characters = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
            $charsLength = strlen($characters) -1;
            $ranString = "";
            for($i=0; $i<$length; $i++){
                $randNum = mt_rand(0, $charsLength);
                $ranString .= $characters[$randNum];
            }
            return $ranString;
        }
    }
    class Activity {
        function createSesh() {
            echo "<br> mark 2!";
            $e = new Encryption();

            $seshCon = mysqli_connect("localhost", "root", "", "sessions");
            if(!$seshCon) {
                if(!$seshCon->query("CREATE DATABASE sessions")) {
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
            if(!isset($_SESSION['id'])) {
                echo "<br> mark 3! session id created";
                $seshID = session_id();
                $_SESSION['id'] = $seshID;
                $seshQL = "INSERT INTO sessions (id, attempts, lockout) VALUES ('" . $_SESSION['id'] . "', 3, 2)";
                echo "<br> " . $seshQL;
                $seshResult = mysqli_query($seshCon, $seshQL);
                if(!$seshResult) {
                    die("it died");
                }    
            }
            $seshCon->close();
        }
        function checkLockout() {
            $a = new Activity();
            $seshCon = mysqli_connect("localhost", "root", "", "sessions");

            $seshQL = "SELECT * FROM sessions WHERE id= '" . $_SESSION['id'] . "'";
            $seshResult = mysqli_query($seshCon, $seshQL);
            if(!$seshResult) {
                die(" <br> Cannot Find Session ID");
            }
            else {
                $row = mysqli_fetch_array($seshResult);
                if ($row['lockout'] == 1) {
                    $seshCon->close();
                    return true;
                }
                $seshCon->close();
                return false;
            }

            $seshCon->close();
        }
        function endLockout() {
            $seshCon = mysqli_connect("localhost", "root", "", "sessions");
            $seshQL = "UPDATE sessions SET lockout=2, attempts=3 WHERE id= '" . $_SESSION['id'] . "'";
            if(!$seshCon->query($seshQL)) {
                die("damn");
            }
            unset($_SESSION['errorsForLoginPHP']);
        }
        function depreciateAttempts() {
            $seshCon = mysqli_connect("localhost", "root", "", "sessions");
            $seshQL = "SELECT * FROM sessions WHERE id= '" . $_SESSION['id'] . "'";
            $seshResult = mysqli_query($seshCon, $seshQL);
            if(!$seshResult) {
                die("No such session exists");
            }
            else {
                $row = mysqli_fetch_array($seshResult);
                if($row['attempts'] == 1) {
                    $seshQL = "UPDATE sessions SET lockout=1 WHERE id= '" . $_SESSION['id'] . "'";
                    if(!$seshCon->query($seshQL)) {
                        die("damn");
                    }
                }
                $newSeshQL = "UPDATE sessions SET attempts=" . ($row['attempts'] - 1) . " WHERE id='" . $_SESSION['id'] . "'";
                echo "<br> " . $newSeshQL;
                $seshResult = mysqli_query($seshCon, $newSeshQL);
                if(!$seshResult) {
                    die("damn");
                }
                else {
                    $_SESSION['loginAttempts'] = ($row['attempts'] - 1);
                }
            }
        }
        function checkIfUserNeedsToBeLoggedOut() {
            $time = date("U");
            $timeOfTimeout = $_SESSION['lastActivityTime'] + (5 * 60);

            if($time > $timeOfTimeout) {
                return true;
            }
            else {
                return false;
            }
        }
        function killSession() {
            try {
                session_destroy();
            }
            catch(Exception $e) {
            }
        }
    }
?>