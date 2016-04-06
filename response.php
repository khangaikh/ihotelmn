<?php
    require_once 'includes/Twig/Autoloader.php';
    require_once "config.php";
    require_once "req.php";
    use Parse\ParseObject;
    use Parse\ParseClient;
    use Parse\ParseQuery;
    use Parse\ParseUser;

    session_start();

    Twig_Autoloader::register();
    $loader = new Twig_Loader_Filesystem('templates');
    $twig = new Twig_Environment($loader, array(
        'cache' => 'cache',
    ));

    $twig->setCache(false);
    $total = 0;

    if(isset($_GET)){
        $query = new ParseQuery("orders");
        $query->equalTo("objectId",$_GET['trans_number']);
        $order = $query->first();
        $createdAt = $order->getCreatedAt();

        if(isset($_SESSION['user'])){
            $user = $_SESSION['user']; 

            $success = $_GET['success'];
            $trans_number = $_GET['trans_number'];
            $Usernamesoap = "70972";
            $Passwordsoap = "iHto11";
            $trans_date = $createdAt->format('Y-m-d');
            $trans_amount = $_SESSION['total'];
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
            if ($_GET["success"] == 0) 
            {
                $query = new ParseQuery("orders");
                $query->equalTo("order_id",$_GET['trans_number']);
                $orders = $query->find();

                for ($i = 0; $i < count($orders); $i++) {
                    $orders[$i]->set('user',$user);
                    $orders[$i]->set('status',-1);
                    $orders[$i]->save();
                }
                sendmail($user, $orders);
                //                rs_api_create_res();

                unset($_SESSION['orders']);
                unset($_SESSION['start']);
                unset($_SESSION['end']);

                $template = $twig->loadTemplate('success-payment.html');
                $query = new ParseQuery("orders");
                $query->descending("createdAt");
                $query->equalTo("user",$user);
                $query->includeKey('hotel');
                $orders = $query->find();

                if ($user->get('asem') == 0) {
                    echo $template->render(array('title' => 'iHotel', 'user' => $user,
                        'nav' => 2, 'result'=> 1, 'orders'=>$orders, 'message'=> 'Гүйлэгээ амжилттай боллоо.', 'mtype'=> 1)); 
                }
                else{
                    echo $template->render(array('title' => 'iHotel', 'user' => $user,
                        'nav' => 2, 'result'=> 1, 'orders'=>$orders, 'message'=> 'Approved, balances available', 'mtype'=> 1)); 
                }
            }
            elseif($_GET["success"] == 1)
            {
                $log_msg = "";
                if ($_GET["error_code"]==202) {
                    $log_msg = "И код буруу байна.";
                    $log_msg_en = "Pin code incorrect .";
                }
                if ($_GET["error_code"]=='203') {
                    $log_msg = "Карт гаргагч банкнаас гүйлгээг цуцалсан.";
                    $log_msg_en = "Administrative transactions not supported.";
                }
                if ($_GET["error_code"]=='300-05') {
                    $log_msg = "Гүйлгээ хийх эрхгүй карт";
                    $log_msg_en = "Card not supported";
                }
                if ($_GET["error_code"]=='300-12') {
                    $log_msg = "Картын мэдээлэл буруу";
                    $log_msg_en = "Invalid card";
                }
                if ($_GET["error_code"]=='300-14') {
                    $log_msg = "Ийм карт байхгүй байна.";
                    $log_msg_en = "Lost or stolen card";
                }
                if ($_GET["error_code"]=='300-51') {
                    $log_msg = "Үлдэгдэл хүрэлцэхгүй байна.";
                    $log_msg_en = "Invalid advance amount";
                }
                if ($_GET["error_code"]=='300-54') {
                    $log_msg = "Картын хугаа дууссан/Буруу оруулсан";
                    $log_msg_en = "Expired card";
                }
                if ($_GET["error_code"]=='300-58') {
                    $log_msg = "Зөвшөөрөгдөөгүй гүйлгээ байна.";
                    $log_msg_en = "Зөвшөөрөгдөөгүй гүйлгээ байна.";
                }
                if ($responseCode == 2) {
                    $log_msg.="Гүйлгээ амжилтгүй болсон байна.";
                    $log_msg_en.="Approved, no balances available";
                }
                if ($responseCode == 0) {
                    $log_msg.="Ийм дугаар болон гүйлгээний дүнтэй гүйлгээ баазад бүртгээгүй байна.";
                    $log_msg_en.="Approved, no balances available";
                }
                if ($responseCode == 3) {
                    $log_msg.="Hereglegchiin ner esvel nuuts ug buruu baina";
                    $log_msg_en.="Incorrect username or password";
                }
                $log_msg .= $_GET['error_desc'];

                $query = new ParseQuery("orders");
                $query->descending("createdAt");
                $query->equalTo("order_id",$_GET['trans_number']);
                $orders = $query->find();

                for ($i = 0; $i < count($orders); $i++) {
                    $orders[$i]->destroy();
                }
                unset($_SESSION['orders']);
                unset($_SESSION['start']);
                unset($_SESSION['end']);

                $template = $twig->loadTemplate('success-payment.html');
                $query = new ParseQuery("orders");
                $query->descending("createdAt");
                $query->equalTo("user",$user);
                $query->includeKey('hotel');
                $orders = $query->find();

                if ("".$user->get('asem') == "1") {
                    echo $template->render(array('title' => 'iHotel', 'user' => $user, 'nav' => 2, 'orders'=>$orders, 
                        'result'=>1, 'message'=> $log_msg_en, 'mtype'=> 0)); 
                }
                else{
                    echo $template->render(array('title' => 'iHotel', 'user' => $user, 'nav' => 2, 'orders'=>$orders, 
                        'result'=>1, 'message'=> $log_msg, 'mtype'=> 0)); 
                }
            }
        }
    }

    function sendmail($user, $orders){
        $query = new ParseQuery("orders");
        $query->equalTo("order_id",$_GET['trans_number']);
        $query->includeKey('hotel');
        $order = $query->find();
        $content = '<div style="margin-right: 13%; margin-left: 13%; padding: 1%; border: 1px solid #d9d9d9;">
            <header style="padding: 5px;background: #f7f7f7;"><a style="booking-item-payment-img" href="https://ihotel.mn/index.php?register">
            <img src="https:ihotel.mn/'.$order[0]->get('hotel')->get('cover_image').'" style="width:15%" alt="Image Alternative text" title="hotel 1" />
            </a></header><h2><a href="https://ihotel.mn/index.php?register"style="text-decoration: none; color:#5a6b77;">'
            .$order[0]->get('hotel')->get('name').'</a></h2><p>Thank you for choosing us. Your order has confirmed</p>
            <ul style="list-style: none;margin: 0; padding: 15px; border-top: 1px solid #d9d9d9; border-bottom: 1px solid #d9d9d9;">
            <h5 style="margin: 0 0 12px; margin-bottom: 8px; font-size: 18.2px; font-weight: 300;line-height: 1em;color: #565656;">Order number: '
            .$order[0]->getObjectId().'</h5><li style="margin-bottom: 20px;overflow: hidden;display: list-item;list-style: none;margin: 0;padding: 15px;">
            <h5 style="margin: 0 0 12px; margin-bottom: 8px;font-size: 18.2px; font-weight: 300;line-height: 1em;color: #565656;">Total days of stay: '
            .$order[0]->get('days').'</h5><div style="float: left;"><p style="margin-bottom: 5px; line-height: 1em; color: #686868;">'
            .$order[0]->get('start').'</p><p style="margin-bottom: 5px; line-height: 1em; color: #686868;">'
            .date('l', strtotime($order[0]->get('start'))).'</p></div><div style="float: left; font-size: 50px;">&rarr;</div><div style="float: left;">
            <p style="margin-bottom: 5px; line-height: 1em; color: #686868;">'.$order[0]->get('end').'</p>
            <p style="margin-bottom: 5px; line-height: 1em; color: #686868;">'.date('l', strtotime($order[0]->get('end'))).'</p></div></li>';

        for ($i = 0; $i < count($order); $i++) {
            if ($order[$i]->get('pickup') == "vip") {
                $pickup = "Vip";
                $pickupcur = "$150";
            }
            elseif($order[$i]->get('pickup') == "budget"){
                $pickup = "Budget";
                $pickupcur = "$25";
            }
            elseif($order[$i]->get('pickup') == "empty"){
                $pickup = "";
                $pickupcur = "";
            }
            $content .='<ul style="list-style: none;margin: 0; padding: 15px; border-top: 1px solid #d9d9d9; border-bottom: 1px solid #d9d9d9;">
                <li style="margin-bottom: 20px;overflow: hidden;display: list-item;list-style: none;margin: 0;padding: 15px;">
                <h5 style="margin: 0 0 12px; font-size: 18.2px; font-weight: 300;line-height: 1em;color: #565656;">Room</h5>
                <ul style="margin: 0; padding: 0; list-style: none;"><li style="width: 78%; overflow: hidden;
            font-size: 12px; border-bottom: 1px dashed #d9d9d9; margin:0;"><p style="float: left; margin: 5px;">'.$order[$i]->get('days').' Nights</p>
                <p style="float: right; line-height: 0;">$'.$order[$i]->get('total').'<small>/per day</small></p></li><li style="width:78%; overflow: hidden;
            font-size: 12px; border-bottom: 1px dashed #d9d9d9; margin:0;"><p style="float: left; margin: 5px;">Taxes</p>
                <p style="float: right; line-height: 0;">(incluled)<small>/per day</small></p></li></ul><br />
                <h5 style="margin: 0 0 12px; font-size: 18.2px; font-weight: 300;line-height: 1em;color: #565656;">Pickup</h5>
                <ul style="margin: 0; padding: 0; list-style: none;"><li style="width: 78%; overflow: hidden;
            font-size: 12px; border-bottom: 1px dashed #d9d9d9; margin:0;"><p style="float: left; margin: 5px;">'.$pickup.
                '</p><p style="float: right; line-height: 0;">'.$pickupcur.'<small></small></p></li></ul></li></ul>';

            $total +=intval($order[$i]->get('total'));
        }
        $content .= '<p style="margin: 0 0 0; padding: 8px 30px 8px 15px; font-size: 12px;">
            Total amount: <span style="font-size: 24px; color: #686868; font-weight: 400;letter-spacing: -2px;">US$ '
            .$total*intval($order[0]->get('days')).'</span></p>';

        $content .= '<br/><div style="padding:10px 15px;background:#f7f7f7"><p style="margin: 0 0 0; padding: 8px 30px 8px 15px; font-size: 12px;">
            Please contact us +976-88021087</p></div></div>';

        require 'lib/Mailer/PHPMailerAutoload.php';
        $body = $content;
        date_default_timezone_set('Etc/UTC');
        $mail = new PHPMailer;
        $mail->isSMTP();
        //        $mail->SMTPDebug = 2;
        $mail->Debugoutput = 'html';
        $mail->Host = 'us2.smtp.mailhostbox.com';
        //        $mail->Host = 'smtp.ihotel.mn';
        $mail->Port = 25;
        $mail->SMTPSecure = '';
        $mail->SMTPAuth = true;
        $mail->Username = "sales@ihotel.mn";
        $mail->Password = "UWALWpaz8";
        $mail->setFrom('sales@ihotel.mn', 'iHotel.mn');
        $mail->addAddress($user->get('email'), 'Customer');
        $mail->Subject = 'Order confirm';
        $mail->msgHTML($body);
        $mail->AltBody = '';
        if (!$mail->send()) {
            echo "Mailer Error: " . $mail->ErrorInfo;
        } else {
            echo "Message sent!";
        }
    }
?>
