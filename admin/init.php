<?php
    include 'connect.php';   // connect to database 
    include 'includes/langs/eng.php';  // multilanguage
   
    $temp = 'includes/temples/';
    $js   = 'layout/js/';
    $css  = 'layout/css/';
    $func = 'includes/functions/';

    include $func . 'functions.php';    // include functions 
    include $temp .'header.php';        //include header


 
    if(!isset($nonavbar)){
        include $temp . 'navbar.php';
    }

?>