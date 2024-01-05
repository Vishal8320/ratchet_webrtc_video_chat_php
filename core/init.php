<?php


    session_start();
    require 'classes/DB.php';
    require 'classes/user.php';
    
    $userObj = new \MyApp\User;
    define('BASE_URL','http://localhost/webrtc');
    // define('BASE_URL',' http://192.168.43.31/webrtc');

?>