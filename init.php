<?php

	/*ini_set("display_errors","on");
	error_reporting('E_ALL');*/

    include 'connect.php';   // connect to database 
    include 'includes/langs/eng.php';  // multilanguage
   
    $temp = 'includes/temples/';
    $js   = 'layout/js/';
    $css  = 'layout/css/';
    $func = 'includes/functions/';

    include $func . 'functions.php';    // include functions 
    include $temp .'header.php';        //include header

    $sessionUser ='';
    if(isset($_SESSION['user'])){
    	$sessionUser = $_SESSION['user'];
    }

?>