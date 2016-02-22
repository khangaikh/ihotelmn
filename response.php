<?php
    require_once 'includes/Twig/Autoloader.php';
    require_once "config.php";
    use Parse\ParseObject;
    use Parse\ParseClient;
    use Parse\ParseQuery;
    use Parse\ParseUser;

    session_start();
    //register autoloader
    Twig_Autoloader::register();
    //loader for template files
    $loader = new Twig_Loader_Filesystem('templates');
    //twig instance
    $twig = new Twig_Environment($loader, array(
        'cache' => 'cache',
    ));
    //load template file
    $twig->setCache(false);
    if(isset($_GET)){

        $query = new ParseQuery("orders");
        $query->equalTo("objectId",$_GET['trans_number']);
        $order = $query->first();
        $createdAt = $order->getCreatedAt();

        if(isset($_SESSION['user'])){
            $user = $_SESSION['user']; 
        }

        $success = $_GET['success'];
        $trans_number = $_GET['trans_number'];
        $Usernamesoap = "70972";
        $Passwordsoap = "iHto11";
        $trans_date = $createdAt->format('Y-m-d');
        $trans_amount = $order->get('total');
        require_once('lib/nusoap.php');
        $wsdl = "https://m.egolomt.mn:7073/persistence.asmx?WSDL";
        $client = new nusoap_client($wsdl, 'wsdl');
        $client_err = $client->getError();
        if ($client_err) {
            echo '<h2>Error</h2><pre>' . htmlspecialchars($client->getDebug(), ENT_QUOTES) . '</pre>'; 
            exit();
        }
        $client->soap_defencoding ="UTF-8";
        $params = array(
            'v0'=>$Usernamesoap,
            'v1'=>$Passwordsoap,
            'v2'=>$trans_number,
            'v3'=>$trans_date,
            'v4'=>$trans_amount
        );
        $result = $client->call('Get_new', $params, '', '', false, true);
        if ($client->fault) {
            echo '<h2>Error</h2><pre>' . $client_err . '</pre>';
        }else {
            $client_err = $client->getError();
            if ($client_err) {
            } else {
                $responseCode = $result[Get_newResult];
            }
        }
        if ($_GET["success"] == 0 && strlen($responseCode) == 6) 
        {
            $order->set('status', 1);
            $order->save();

            sendmail($user, $orders);

            $template = $twig->loadTemplate('success-payment.html');
            $query = new ParseQuery("orders");
            $query->descending("createdAt");
            $query->equalTo("user",$user);
            $query->includeKey('hotel');
            $orders = $query->find();

            echo $template->render(array('title' => 'iHotel', 'user' => $user,
                'nav' => 2, 'result'=> 1, 'orders'=>$orders, 'message'=> 'Гүйлэгээ амжилттай боллоо.', 'mtype'=> 1)); 
       }
        elseif($_GET["success"] == 1)
        {
            $log_msg = "";
            if ($_GET["error_code"]==202) {
                $log_msg = "И код буруу байна.";
            }
            if ($_GET["error_code"]=='203') {
                $log_msg = "Карт гаргагч банкнаас гүйлгээг цуцалсан.";
            }
            if ($_GET["error_code"]=='300-05') {
                $log_msg = "Гүйлгээ хийх эрхгүй карт";
            }
            if ($_GET["error_code"]=='300-12') {
                $log_msg = "Картын мэдээлэл буруу";
            }
            if ($_GET["error_code"]=='300-14') {
                $log_msg = "Ийм карт байхгүй байна.";
            }
            if ($_GET["error_code"]=='300-51') {
                $log_msg = "Үлдэгдэл хүрэлцэхгүй байна.";
            }
            if ($_GET["error_code"]=='300-54') {
                $log_msg = "Картын хугаа дууссан/Буруу оруулсан";
            }
            if ($_GET["error_code"]=='300-58') {
                $log_msg = "Зөвшөөрөгдөөгүй гүйлгээ байна.";
            }
            if ($_GET["error_code"]=='300-89') {
    //            Mage::log("Aldaatai terminal.");
            }
            if ($_GET["error_code"]=='300-91') {
    //            Mage::log("TIMEOUT");
            }
            if ($_GET["error_code"]=='300-96') {
    //            Mage::log("System error");
            }
            if ($responseCode == 2) {
                $log_msg.="Гүйлгээ амжилтгүй болсон байна.";
            }
            if ($responseCode == 0) {
                $log_msg.="Ийм дугаар болон гүйлгээний дүнтэй гүйлгээ баазад бүртгээгүй байна.";
            }
            if ($responseCode == 3) {
                $log_msg.="Hereglegchiin ner esvel nuuts ug buruu baina";
            }
            $log_msg .= $_GET['error_desc'];

            $order->destroy();

            $template = $twig->loadTemplate('success-payment.html');
            $query = new ParseQuery("orders");
            $query->descending("createdAt");
            $query->equalTo("user",$user);
            $query->includeKey('hotel');
            $orders = $query->find();
            echo $template->render(array('title' => 'iHotel', 'user' => $user, 'nav' => 2, 'orders'=>$orders, 
                'result'=>1, 'message'=> $log_msg, 'mtype'=> 0)); 
        }
    }

    function sendmail($user, $orders){
        $query = new ParseQuery("orders");
        $query->equalTo("objectId",$_GET['trans_number']);
        $order = $query->first();

        $query = new ParseQuery("hotel");
        $query->equalTo("objectId",$order->get('hotel')->getObjectId());
        $hotel = $query->first();
 
        $query = new ParseQuery("rooms");
        $query->equalTo("hotel",$hotel);
        $room = $query->first();

        $content = '
            <div style="box-shadow: 0 2px 1px rgba(0,0,0,0.1);border: 1px solid rgba(0,0,0,0.15); width: 50%;">
                <header style="padding: 10px 15px;background: #f7f7f7;">
                    <h5 style="font-size: 14px; margin-bottom: 0; float: right; margin-right: 50%;">
                        <a style="color: #03629a;text-decoration: none;"href="#">'.$hotel->get('name').'</a></h5>
                    <a style="booking-item-payment-img" href="#">
                        <img src="'.$hotel->get('cover_image').'" style="width:28%" alt="Image Alternative text" title="hotel 1" />
                    </a>
                </header>
                <ul style="list-style: none;margin: 0; padding: 15px; border-top: 1px solid #d9d9d9; border-bottom: 1px solid #d9d9d9;">

                    <h5 style="margin: 0 0 12px; margin-bottom: 8px; font-size: 18.2px; font-weight: 300;line-height: 1em;
                        color: #565656;">Order number: '.$order->getObjectId().'</h5>
                    <li style="margin-bottom: 20px;
                            overflow: hidden;
                            display: list-item;
                            list-style: none;
                            margin: 0;
                            padding: 15px;">
                        <h5 style="margin: 0 0 12px; margin-bottom: 8px;
                                font-size: 18.2px; font-weight: 300;line-height: 1em;color: #565656;">Total days of stay: '.$order->get('days').'</h5>
                        <div style="float: left;">
                            <p style="margin-bottom: 5px; line-height: 1em; color: #686868;">'.$order->get('start').'</p>
                            <p style="margin-bottom: 5px; line-height: 1em; color: #686868;">'.date('l', strtotime($order->get('start'))).'</p>
                        </div>
                        <div style="float: left; font-size: 50px;">&rarr;</div>
                        <div style="float: left;">
                            <p style="margin-bottom: 5px; line-height: 1em; color: #686868;">'.$order->get('end').'</p>
                            <p style="margin-bottom: 5px; line-height: 1em; color: #686868;">'.date('l', strtotime($order->get('end'))).'</p>
                        </div>
                    </li>
                    <li style="margin-bottom: 20px;
                            overflow: hidden;
                            display: list-item;
                            list-style: none;
                            margin: 0;
                            padding: 15px;">

                        <h5 style="margin: 0 0 12px; font-size: 18.2px; font-weight: 300;line-height: 1em;color: #565656;">Room</h5>
                        <ul style="margin: 0; padding: 0; list-style: none;">
                            <li style="width: 78%; overflow: hidden;
                            font-size: 12px; border-bottom: 1px dashed #d9d9d9; margin:0;">
                                <p style="float: left; margin: 5px;">'.$order->get('days').' Nights</p>
                                <p style="float: right; line-height: 0;">$'.$room->get('night_price').'<small>/per day</small>
                                </p>
                            </li>
                            <li style="width: 78%; overflow: hidden;
                            font-size: 12px; border-bottom: 1px dashed #d9d9d9; margin:0;">
                                <p style="float: left; margin: 5px;">Taxes</p>
                                <p style="float: right; line-height: 0;">(incluled)<small>/per day</small>
                                </p>
                            </li>
                        </ul>
                    </li>
                </ul>
                <p style="margin: 0 0 0; padding: 8px 30px 8px 15px;
                            font-size: 12px;">Total amount: <span style="font-size: 24px; color: #686868; font-weight: 400;
                            letter-spacing: -2px;">US$ '.$order->get('total').'</span>
                </p>
            </div>
        ';

        require 'lib/PHPMailer/PHPMailerAutoload.php';
        $body = $content;
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
    }
?>
