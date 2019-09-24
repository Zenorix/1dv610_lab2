<?php

// Only showing error output when on localhost
if ('localhost' == $_SERVER['SERVER_NAME']) {
    error_reporting(E_ALL);
    ini_set('display_errors', 'On');
}
