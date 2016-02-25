<?php
    require_once "config.php";
    use Parse\ParseException;
    use Parse\ParseUser;
 
    //$date = null;
    if (isset($_POST['email'])) {
        $email =  $_POST['email'];
        $pass = $_POST['password'];
        $username = $_POST['username'];

        $user = new ParseUser();
        $user->set("username", $email);
        $user->set("name", $username);
        $user->set("email", $email);
        $user->set("password", $pass);
        $user->set("status", 0);
        $user->set("role", 1);
        $result = false;

        try {
            $user->signUp();
            $result =true;
  /*          require 'lib/Mailer/PHPMailerAutoload.php';
            $random = substr( md5(rand()), 0, 7);
            $body = 'http://localhost/ihotelmn/user.php?user_confirm_email='.$random;
            date_default_timezone_set('Etc/UTC');
            $mail = new PHPMailer;
            $mail->isSMTP();
            //$mail->SMTPDebug = 2;
            $mail->Debugoutput = 'html';
            $mail->Host = 'smtp.gmail.com';
            $mail->Port = 587;
            $mail->SMTPSecure = 'tsl';
            $mail->SMTPAuth = true;
            $mail->Username = "ihotelmn@gmail.com";
            $mail->Password = "99095102";
            $mail->setFrom('ihotelmn@gmail.com', 'iHotel.mn');
            $mail->addAddress($user->getEmail(), 'Customer');
            $mail->Subject = 'iHotel order email!';
            $mail->msgHTML($body);
            $mail->AltBody = '';
            if (!$mail->send()) {
                echo "Mailer Error: " . $mail->ErrorInfo;
            } else {
                echo "Message sent!";
            }
   */
        } catch (ParseException $ex) {
            echo "Error: " . $ex->getCode() . " " . $ex->getMessage();
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
            echo "Error: " . $ex->getCode() . " " . $ex->getMessage();
        } 
        if($result){
            echo 1;
        }else{
            echo 0;
        }
    }
/*    elseif(isset($_GET['user_confirm_email']) && strlen($_GET['user_confirm_email']) == 7){
        echo 'confirm';
        $query = ParseUser::query();
        $query->equalTo("username", 'esoninod@gmail.com'); 
        $user = $query->first();
        echo $user->get('name');
        $user->set("emailVerified", false); 
        $user->save();
    }
 */  
?>
