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
    //        redirect
        }

    }
?>
