<?php

    require 'js/parse/autoload.php';
    require_once "lib/recaptchalib.php";
    use Parse\ParseException;
    use Parse\ParseUser;
    use Parse\ParseSessionStorage;
    use Parse\ParseClient;

    session_start();

     ParseClient::initialize(
        'j13CpiqJOwsLbvpyAidYXqW4JcTn0cGZqeCGcd56',
        '8oxWBZ7LHEY9Zarmv4AtPYhIgJr6UQiUFHX31C8z',
        '0SRUYzAOtx5nf98Ubupi0BaGpavysHdIC4PdLrCJ'
    );
    ParseClient::setServerURL('https://pg-app-1ex789tzvc1jldehvv05hbsqx7q9dh.scalabl.cloud', '1');
    
    $storage = new ParseSessionStorage();
    ParseClient::setStorage($storage);
    
    $result =false;

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
        try {
            $query = ParseUser::query();
            $query->equalTo("username", $_POST['email']); 
            if (isset($_POST['asem'])) {
                $query->equalTo("asem", 1); 
            }
            $query->equalTo("emailVerified", true); 
            $results = $query->find();

            if ($results) {
                $user = ParseUser::logIn($_POST['email'], $_POST['password']);
                $user->save();
                $result = true;
            }
            elseif(isset($_POST['facebook']) && $_POST['facebook'] == '1'){
                $user = ParseUser::logIn($_POST['email'], $_POST['password']);
                $user->save();
                $result = true;
            }
        } catch (ParseException $error) {
            echo $error;
        }
        if($result){
            $user = ParseUser::getCurrentUser();
            $_SESSION['user'] = $user;
            echo 1;
        }else{
            echo 0;
        }
    } else {
        echo 2;
    }
    
?>
