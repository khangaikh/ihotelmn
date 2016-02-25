<?php
    require_once "config.php";
    use Parse\ParseException;
    use Parse\ParseUser;
 
    //$date = null;
    $email =  $_POST['email'];
    $pass = $_POST['password'];
    $username = $_POST['username'];

    $user = new ParseUser();
    $user->set("username", $username);
    $user->set("email", $email);
    $user->set("password", $pass);
    $user->set("status", 1);
    $user->set("role", 1);
    $result = false;

    try {
        $user->signUp();
        $result =true;
    } catch (ParseException $ex) {
        echo "Error: " . $ex->getCode() . " " . $ex->getMessage();
    }
    
    if($result){
        echo 1;
    }else{
        echo 0;
    }
    
?>
