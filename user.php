<?php
    require_once "config.php";
    require_once "lib/recaptchalib.php";
    use Parse\ParseException;
    use Parse\ParseUser;


    $secret = "6LfSXhoTAAAAAPaw-YLaAtKdmrI-amR_xNS6RFmf";

    // empty response
    $response = null;

    // check secret key
    $reCaptcha = new ReCaptcha($secret);

    if ($_POST["g_recaptcha_response"]) {
        $response = $reCaptcha->verifyResponse(
            $_SERVER["REMOTE_ADDR"],
            $_POST["g_recaptcha_response"]
        );
    }
    if ($response != null && $response->success || isset($_POST['facebook'])) {
        if (isset($_POST['email'])) {
            $email =  $_POST['email'];
            $pass = $_POST['password'];
            $username = $_POST['username'];
            $country = $_POST['location'];

            $user = new ParseUser();
            $user->set("username", $email);
            $user->set("name", $username);
            $user->set("email", $email);
            $user->set("password", $pass);
            $user->set("country", $country);
            $user->set("status", 0);

            if(isset($_POST['asem'])){
                $user->set("asem", 1);
            }else{
                $user->set("asem", 0);
            }
            $user->set("role", 1);

            if (isset($_POST['type'])) {
                $user->set("meeting_type", intval($_POST['type']));
            }

            $result = false;

            try {
                $user->signUp();
                $result =true;
            } catch (ParseException $ex) {
                echo $ex->getCode();
                print_r($ex);
            }

            if($result){
                echo 1;
            }else{
                echo 0;
            }
        }elseif(isset($_POST['forgot_password'])){
            $result = false;
            try {
                ParseUser::requestPasswordReset($_POST['forgot_password']);
                $result=true;
            } catch (ParseException $ex) {
                echo $ex->getCode();
            } 
            if($result){
                echo 1;
            }else{
                echo 0;
            }
        }
    }else {
        echo 2;
    }
?>
