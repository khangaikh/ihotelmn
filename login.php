<?php
    require 'js/parse/autoload.php';
    use Parse\ParseException;
    use Parse\ParseUser;
    use Parse\ParseSessionStorage;
    use Parse\ParseClient;

    session_start();

    $app_id = 'j13CpiqJOwsLbvpyAidYXqW4JcTn0cGZqeCGcd56';
    $rest_key = '8oxWBZ7LHEY9Zarmv4AtPYhIgJr6UQiUFHX31C8z';
    $master_key = '0SRUYzAOtx5nf98Ubupi0BaGpavysHdIC4PdLrCJ';
    ParseClient::initialize( $app_id, $rest_key, $master_key );
    $storage = new ParseSessionStorage();
    ParseClient::setStorage($storage);
    
    $result =false;

    try {
        $query = ParseUser::query();
        $query->equalTo("username", $_POST['email']); 
        $query->equalTo("emailVerified", true); 
        $results = $query->find();
        if ($results) {
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
    
?>
