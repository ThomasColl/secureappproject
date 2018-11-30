<?php
    if(!isset($_SESSION)) {
        session_start();
    }
    class Filters {
        function stripUndesirables($stringToStripTagsFrom) {
            if(strpos($stringToStripTagsFrom, "<") || strpos($stringToStripTagsFrom, ">")) {
                echo("You bastard");
                $stringToStripTagsFrom = str_replace(array('<','>'), '', $stringToStripTagsFrom);
            }
            
            if(strpos($stringToStripTagsFrom, "'")) {
                $stringToStripTagsFrom = str_replace(array('\''), '', $stringToStripTagsFrom);
            }
            if(strpos($stringToStripTagsFrom, "\"")) {
                echo("You bastard");
                $stringToStripTagsFrom = str_replace('"', '', $stringToStripTagsFrom);
            }
            if(strpos($stringToStripTagsFrom, "&")) {
                echo("You bastard");
                $stringToStripTagsFrom = str_replace('>', '', $stringToStripTagsFrom);
            }
            return $stringToStripTagsFrom;
        }
        function sanitise($stringWhichRichardMayHaveFuckedWith) {
            if(strpos($stringWhichRichardMayHaveFuckedWith, "<")) {
                echo("You bastard");
                $stringWhichRichardMayHaveFuckedWith = str_replace('<', '&gt;', $stringWhichRichardMayHaveFuckedWith);
            }
            if(strpos($stringWhichRichardMayHaveFuckedWith, ">")) {
                echo("You bastard");
                $stringWhichRichardMayHaveFuckedWith = str_replace('>', '&lt;', $stringWhichRichardMayHaveFuckedWith);
            }
            if(strpos($stringWhichRichardMayHaveFuckedWith, "'")) {
                echo("You bastard");
                $stringWhichRichardMayHaveFuckedWith = str_replace('\'', '&#039;', $stringWhichRichardMayHaveFuckedWith);
            }
            if(strpos($stringWhichRichardMayHaveFuckedWith, "\"")) {
                echo("You bastard");
                $stringWhichRichardMayHaveFuckedWith = str_replace('"', '&quot;', $stringWhichRichardMayHaveFuckedWith);
            }
            if(strpos($stringWhichRichardMayHaveFuckedWith, "&")) {
                echo("You bastard");
                $stringWhichRichardMayHaveFuckedWith = str_replace('>', '&amp;', $stringWhichRichardMayHaveFuckedWith);
            }
            
            return $stringWhichRichardMayHaveFuckedWith;
        }
    
        function filterValidation($stringToFilter) {
            $utilities = new Filters();
            $strippedString = $utilities->stripUndesirables($stringToFilter);
            if($strippedString != $stringToFilter || strlen($stringToFilter) == 0) {
                return false;
            }
            else {
                return true;
            }
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
            session_unset($_SESSION);
            session_destroy();
            session_abort();
            session_write_close();
        }
    }
?>