<?php

// Only showing error output when on localhost
if ($_SERVER["SERVER_NAME"] == "localhost"){
    error_reporting(E_ALL);
    ini_set('display_errors', 'On');
}