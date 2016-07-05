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

    if(isset($_SESSION['user'])){
        $user = $_SESSION['user']; 
        $username = $user->get("username");
        
        if(isset($_POST['message'])){
            $template = $twig->loadTemplate('success-payment.html');
            $query = new ParseQuery("orders");
            $query->descending("createdAt");
            $query->equalTo("user",$user);
            $query->includeKey('hotel');
            $orders = $query->find();
            echo $template->render(array('title' => 'iHotel', 'user' => $user, 'nav' => 2, 'orders'=>$orders, 
                'message'=> $_POST['message'], 'mtype'=>$_POST['mtype'])); 
        }
        else{
            $query = new ParseQuery("hotel");
            $query->equalTo("type","Hotel");
            $query->equalTo("status",1);
            $query->equalTo("asem",0);

            $query->descending("stars");

            $query->equalTo("city",'Ulaanbaatar');
            $results = $query->find();
            $count = $query->count();

            $template = $twig->loadTemplate('list.html');

            $query->ascending("min_rate");
            $e = $query->first();
            $min = $e->get('min_rate'); 

            $query->descending("min_rate");
            $e = $query->first();
            $max = $e->get('min_rate'); 

            $date1 = new DateTime();
            $checkin = $date1->format('Y-m-d');

            $date2 = new DateTime();
            $date2->modify('+5 day');
            $checkout = $date2->format('Y-m-d');

            echo $template->render(array('title' => 'Search', 'nav' => 1, 'start' => $checkin, 'end' => $checkout, 
                'user' => $user, 'results' =>$results, 'max' => $max, 'min' => $min));
        }
    }
?>
