<?php
    require_once "config.php";
    use Parse\ParseException;
    use Parse\ParseUser;
    
    session_start();

    $user = $_SESSION['user'];
    //$date = null;
    
    if($user->get('password')==$_POST['old']){
        $user->set('password', $_POST['newPass']);
        try {
            $user->save();
            $result =true;
        } catch (ParseException $ex) {
            echo "Error: " . $ex->getCode() . " " . $ex->getMessage();
        }
    }
    
?>