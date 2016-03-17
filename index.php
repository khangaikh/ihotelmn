<?php
    require_once 'includes/Twig/Autoloader.php';
    require_once "config.php";
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

    if(isset($_SESSION['user'])){
        $user = $_SESSION['user'];
        $username = $user->get("username");

        if(isset($_GET['tour'])){
            $query = new ParseQuery("hotel");
            $query->equalTo("status",1);
            $query->equalTo("asem",0);
            $query->equalTo("type","Resort");
            $query->descending("stars");
            $results = $query->find();
            $date = new DateTime();
            $checkin = $date->format('Y-m-d');
            $date = new DateTime();
            $checkout = $date->format('Y-m-d');
            $count = $query->count();
            $template = $twig->loadTemplate('tour.html');
            echo $template->render(array('title' => 'Амралтын газар', 'nav' => 1, 'location' => 'Ulaanbaatar', 'results' =>$results,'start' => $checkin, 'end' => $checkout, 'count' => $count, 'country' => 'iHotel', 'user' => $user));
        }
        else if(isset($_GET['news'])){
            $template = $twig->loadTemplate('news.html');
            $query = new ParseQuery("news");
            $query->includeKey("user");
            $news = $query->find();
            $query->equalTo('category', 'Travel Advices');
            $travel_advice = $query->find();
            $data['travel_advice']=count($travel_advice);
            $query->equalTo('category', 'Hotels');
            $hotels = $query->find();
            $data['hotels']=count($hotels);
            $query->equalTo('category', 'Places to Go');
            $place_to_go = $query->find();
            $data['place_to_go']=count($place_to_go);
            echo $template->render(array('title' => 'Мэдээ мэдээлэл', 'news'=>$news, 'category'=>$data));
        }
        else if(isset($_GET['contact'])){
            $template = $twig->loadTemplate('contact-us.html');
            echo $template->render(array('title' => 'Холбоо барих'));
        }
        else if(isset($_GET['about'])){
            $template = $twig->loadTemplate('about.html');
            echo $template->render(array('title' => 'Бидний тухай'));
        }
        else if(isset($_GET['faq'])){
            $query = new ParseQuery("faq");
            $faqs = $query->find();
            $template = $twig->loadTemplate('faq.html');
            echo $template->render(array('title' => 'Асуулт хариулт', 'faqs'=>$faqs));
        }
        else if(isset($_GET['asem1'])){
            $template = $twig->loadTemplate('asem1.html');
            echo $template->render(array('title' => 'Бидний тухай', 'type' => $_GET['type']));
        }
        else if(isset($_GET['terms'])){
            $template = $twig->loadTemplate('terms.html');
            echo $template->render(array('title' => 'Үйлчилгээний нөхцөл'));
        }
        else if(isset($_GET['asem-news'])){
            $template = $twig->loadTemplate('asem_news.html');
            echo $template->render(array('title' => 'News'));
        }
        else if(isset($_GET['asem-tours'])){
            $template = $twig->loadTemplate('asem-tours.html');
            echo $template->render(array('title' => 'Tours'));
        }
        else if(isset($_GET['asem-faq'])){
            $query = new ParseQuery("faq");
            $faqs = $query->find();
            $template = $twig->loadTemplate('asem-faq.html');
            echo $template->render(array('title' => 'FAQ','nav' => 3,'user' => $user, 'faqs'=>$faqs));
        }
        else if(isset($_GET['asem'])){
            $query = new ParseQuery("hotel");
            $query->equalTo("type","Hotel");
            $query->equalTo("status",1);
            $query->equalTo("asem",1);

            $query->descending("stars");

            $query->equalTo("city",'Ulaanbaatar');
            $results = $query->find();
            $count = $query->count();

            $template = $twig->loadTemplate('asem_list.html');
            //render a template
            echo $template->render(array('title' => 'Search', 'nav' => 1, 'user' => $user, 'results' =>$results));
        }
        else if(isset($_GET['logout'])){
            session_unset();
            session_destroy();
            $template = $twig->loadTemplate('home.html');
            $query = new ParseQuery("hotel");
            $query->equalTo("status",1);
            $query->equalTo("type","Hotel");
            $query->equalTo("homepage",1);
            $query->equalTo("city","Ulaanbaatar");
            $query->descending("name");
            $query->limit(2);
            $hotels = $query->find();

            $query = new ParseQuery("hotel");
            $query->equalTo("type","Hotel");
            $query->equalTo("status",1);
            $query->equalTo("homepage",1);
            $query->equalTo("city","Ulaanbaatar");
            $query->ascending("name");
            $query->limit(2);
            $hotels2 = $query->find();
            echo $template->render(array('title' => '', 'nav' => 1, 'hotels'=>$hotels, 'hotels2'=>$hotels2));
        }
        else if(isset($_GET['add_general'])){
            $template = $twig->loadTemplate('add.html');
            $query = new ParseQuery("hotel");
            $query->equalTo("status",1);
            $results = $query->find();

            //render a template
            echo $template->render(array('title' => 'Буудал нэмэх', 'results'=>$results, 'user' => $user, 'nav' => 1));
        }
        else if(isset($_GET['hotels'])){
            $template = $twig->loadTemplate('user_hotels.html');
            $query = new ParseQuery("hotel");
            $query->equalTo("user",$user);
            $hotels = $query->find();
            //render a template
            echo $template->render(array('title' => 'Буудлууд', 'hotels'=>$hotels, 'user' => $user, 'nav' => 4));
        }
        else if(isset($_GET['news_admin'])){
            $template = $twig->loadTemplate('user_news.html');
            $query = new ParseQuery("news");
            $query->equalTo("user",$user);
            $news = $query->find();
            //render a template
            echo $template->render(array('title' => 'Мэдээ мэдээлэл', 'news'=>$news, 'user' => $user, 'nav' => 8));
        }
        else if(isset($_GET['faq_admin'])){
            $template = $twig->loadTemplate('user_faq.html');
            $query = new ParseQuery("faq");
            $query->equalTo("user",$user);
            $faqs = $query->find();

            $query2 = new ParseQuery('faq_en');
            $faqs_en = $query2->find();
            //render a template
            echo $template->render(array('title' => 'Асуулт хариулт', 'faqs'=>$faqs, 'user' => $user, 'nav' => 9, 'faqs_en'=> $faqs_en));
        }
        else if(isset($_GET['hotel_add'])){
            $template = $twig->loadTemplate('add_hotel.html');
            $section = $_SESSION['section'];
            $hotel = $_SESSION['hotel'];
            $query = new ParseQuery("rooms");
            $query->equalTo("hotel",$hotel);
            $rooms = $query->find();
            $count = 0;
            foreach ($rooms as $key => $room) {
                $count = $count + $room->get('num_rooms');
            }

            echo $template->render(array('title' => 'Буудал нэмэх', 'hotel'=>$hotel, 'user' => $user, 'nav' => 1, 'section' => $section, 'rooms' => $rooms, 'count' =>$count));
        }
        else if(isset($_GET['settings'])){
            $template = $twig->loadTemplate('user_settings.html');
            //render a template
            echo $template->render(array('title' => 'Нууц үг солих',  'user' => $user, 'nav' => 5));
        }
        else if(isset($_GET['start'])){
            $query = new ParseQuery("hotel");
            $query->equalTo("status",1);
            $query->equalTo("type","Hotel");
            $query->equalTo("asem",0);
            $query->descending("stars");
            $location = $_GET['location'];

            $start = $_GET['start'];
            $end = $_GET['end'];

            $date = new DateTime($start);
            $checkin = $date->format('Y-m-d');

            $date = new DateTime($end);
            $checkout = $date->format('Y-m-d');

            $pieces = explode(",", $location);
            $query->equalTo("city",$pieces[0]);
            $results = $query->find();
            $count = $query->count();

            $template = $twig->loadTemplate('list.html');
            //render a template
            echo $template->render(array('title' => 'Хайлт', 'nav' => 1, 'location' => $pieces[0], 'results' =>$results,'start' => $checkin, 'end' => $checkout, 'count' => $count, 'country' => $pieces[1],'user' => $user));
        }
        else if(isset($_GET['start_1'])){
            $query = new ParseQuery("hotel");
            $query->equalTo("status",1);
            $query->equalTo("type", "Hotel");
            $query->equalTo("asem",1);
            $query->descending("stars");

            $start = $_GET['start_1'];
            $end = $_GET['end_1'];
            $guests = $_GET['guests'];
            $rooms = $_GET['rooms'];

            $date = new DateTime($start);
            $checkin = $date->format('Y-m-d');

            $date = new DateTime($end);
            $checkout = $date->format('Y-m-d');

            $query->equalTo("city",'Ulaanbaatar');
            $results = $query->find();

            $template = $twig->loadTemplate('asem_list.html');
            //render a template
            echo $template->render(array('title' => 'Search results', 'nav' => 1, 'results' =>$results,'start' => $checkin, 'end' => $checkout,  'guests'=>$guests, 'rooms'=>$rooms));
        }
        else if(isset($_GET['search'])){
            $template = $twig->loadTemplate('home.html');
            $query = new ParseQuery("hotel");
            $query->equalTo("status",1);
            $query->equalTo("homepage",1);
            $query->equalTo("city","Ulaanbaatar");
            $query->descending("name");
            $query->limit(2);
            $hotels = $query->find();

            $query = new ParseQuery("hotel");
            $query->equalTo("status",1);
            $query->equalTo("homepage",1);
            $query->equalTo("city","Ulaanbaatar");
            $query->ascending("name");
            $query->limit(2);
            $hotels2 = $query->find();
            echo $template->render(array('title' => 'iHotel', 'nav' => 1, 'hotels'=>$hotels, 'hotels2'=>$hotels2));
        }
        else if(isset($_GET['detail'])){
            $query = new ParseQuery("hotel");
            $query->equalTo("objectId",$_GET['detail']);
            $hotel = $query->first();

            $query = new ParseQuery("rooms");
            $query->equalTo("hotel",$hotel);
            $rooms = $query->find();

            $query = new ParseQuery("hotel_images");
            $query->equalTo("hotel",$hotel);
            $query->equalTo("main",1);
            $main = $query->first();

            $query = new ParseQuery("hotel_images");
            $query->equalTo("hotel",$hotel);
            $query->equalTo("main",0);
            $images = $query->find();

            $start = $_GET['depart'];
            $end = $_GET['end'];

            $template = $twig->loadTemplate('detail.html');
            //render a template
            echo $template->render(array('title' => 'Дэлгэрэнгүй', 'nav' => 1,  'hotel' =>$hotel, 'start' => $start, 'end' => $end, 'rooms' => $rooms, 'main' => $main, 'images' =>$images));
        }
        else if(isset($_GET['order'])){
            $query = new ParseQuery("orders");
            $query->equalTo("objectId",$_GET['order']);
            $query->includeKey('hotel');
            $order = $query->first();
            $hotel = $order->get("hotel");

            $template = $twig->loadTemplate('order.html');
            //render a template
            echo $template->render(array('title' => 'Захиалга', 'order' => $order, 'hotel' =>$hotel,'user' => $user ));
        }
        else if(isset($_GET['payment'])){
            if(isset($_SESSION['orders'])){
                $start = $_SESSION['start'];
                $end = $_SESSION['end'];
                $days = $_SESSION['days'];
                $hotel = $_SESSION['hotel'];
                $pickup = $_SESSION['pickup'];
                $day_start = date('l', strtotime( $start));
                $day_end = date('l', strtotime( $end));
                $day_end = date('l', strtotime( $end));
                $orders = $_SESSION['orders'];
                class Event {}
                $rooms = array();
                $total = 0;
                for ($i = 0; $i < count($orders); ++$i){
                    $e = new Event();
                    $order_id = $orders[$i];
                    $query = new ParseQuery("orders");
                    $query->equalTo("objectId",$order_id);
                    $query->includeKey("room");
                    $order = $query->first();
                    $room = $order->get('room');
                    $e->name = $room->get('room_type');
                    $e->qty = $order->get('qty');
                    $e->sub = $order->get('total');
                    $total = $total + (int)$order->get('total');
                    array_push($rooms,$e);
                }
                $total= $total * $days;

                if ($pickup == "budget") { $total += 25; } else if($pickup == "vip"){ $total += 200; }

                $_SESSION['total'] = $total;
                $template = $twig->loadTemplate('payment.html');
                echo $template->render(array('title' => '', 'hotel' =>$hotel,  'start' => $start, 'end' => $end, 'rooms' => $rooms, 'days' => $days, 'total' =>$total ,'day_start' => $day_start, 'day_end' => $day_end, 'pickup' => $pickup, 'user' => $user));
            }else{

            }
        }
        else if(isset($_GET['asem_payment'])){
            if(isset($_SESSION['orders'])){
                $start = $_SESSION['start'];
                $end = $_SESSION['end'];
                $days = $_SESSION['days'];
                $pickup = $_SESSION['pickup'];
                $hotel = $_SESSION['hotel'];
                $day_start = date('l', strtotime( $start));
                $day_end = date('l', strtotime( $end));
                $orders = $_SESSION['orders'];
                class Event {}
                $rooms = array();
                $total = 0;
                for ($i = 0; $i < count($orders); ++$i){
                    $e = new Event();
                    $order_id = $orders[$i];
                    $query = new ParseQuery("orders");
                    $query->equalTo("objectId",$order_id);
                    $query->includeKey("room");
                    $order = $query->first();
                    $room = $order->get('room');
                    $e->name = $room->get('room_type');
                    $e->qty = $order->get('qty');
                    $e->sub = $order->get('total');
                    $total = $total + (int)$order->get('total');
                    array_push($rooms,$e);
                }
                $total= $total * $days;

                if ($pickup == "budget") { $total += 25; } else if($pickup == "vip"){ $total += 200; }

                $_SESSION['total'] = $total;
                $template = $twig->loadTemplate('asem_payment.html');
                //render a template
                echo $template->render(array('title' => '', 'hotel' =>$hotel,  'start' => $start, 'end' => $end, 'rooms' => $rooms, 'days' => $days, 'total' =>$total ,'day_start' => $day_start, 'day_end' => $day_end, 'pickup' => $pickup, 'user' => $user));
            }else{

            }
        }
        else if(isset($_GET['city'])){
            $query = new ParseQuery("hotel");
            $query->equalTo("type","Hotel");
            $query->equalTo("status",1);
            $query->equalTo("city",$_GET['city']);
            $query->equalTo("asem",0);
            $query->descending("stars");
            $results = $query->find();
            $date = new DateTime();
            $checkin = $date->format('Y-m-d');
            $date = new DateTime();
            $checkout = $date->format('Y-m-d');
            $count = $query->count();
            $template = $twig->loadTemplate('list.html');
            //render a template
            echo $template->render(array('title' => 'Хайлт', 'nav' => 1, 'location' => $_GET['city'], 'results' =>$results,'start' => $checkin, 'end' => $checkout, 'count' => $count, 'country' => 'iHotel', 'user' =>$user));
        }
        else if(isset($_GET['card'])){
            $query = new ParseQuery("user_cards");
            $query->equalTo("status",1);
            $query->equalTo("user",$user);
            $cards = $query->find();

            $template = $twig->loadTemplate('user_cards.html');
            //render a template
            echo $template->render(array('title' => 'Картууд', 'user' => $user, 'cards' => $cards, 'nav' =>3));
        }
        else if(isset($_POST['message'])){
            $template = $twig->loadTemplate('success-payment.html');
            $query = new ParseQuery("orders");
            $query->descending("createdAt");
            $query->equalTo("user",$user);
            $query->includeKey('hotel');
            $orders = $query->find();
            if(isset($_POST['message'])){
                echo $template->render(array('title' => 'iHotel', 'user' => $user, 'nav' => 2, 'orders'=>$orders,
                    'message'=> $_POST['message'], 'mtype'=>$_POST['mtype']));
            }
            else{
                echo $template->render(array('title' => 'iHotel', 'user' => $user, 'nav' => 2, 'orders'=>$orders));
            }
        }
        else if(isset($_POST['mapGeoId'])){
            $query = new ParseQuery("hotel");
            $query->equalTo("objectId",$_POST['mapGeoId']);
            $hotel = $query->first();
            $e['cover_image'] = $hotel->get('cover_image');
            $e['average_rate'] = $hotel->get('average_rate');
            $e['address'] = $hotel->get('address');
            $e['stars'] = $hotel->get('stars');
            $e['name'] = $hotel->get('name');
            $e['id'] = $hotel->getObjectId();
            $e['latitude'] = $hotel->get('geolocation')->getLatitude();
            $e['longitude'] = $hotel->get('geolocation')->getLongitude();
            echo json_encode($e);
        }
        else if(isset($_GET['asemdetail'])){
            $query = new ParseQuery("hotel");
            $query->equalTo("objectId",$_GET['asemdetail']);
            $hotel = $query->first();

            $query = new ParseQuery("rooms");
            $query->equalTo("hotel",$hotel);
            $rooms = $query->find();

            $query = new ParseQuery("hotel_images");
            $query->equalTo("hotel",$hotel);
            $query->equalTo("main",1);
            $main = $query->first();

            $query = new ParseQuery("hotel_images");
            $query->equalTo("hotel",$hotel);
            $query->equalTo("main",0);
            $images = $query->find();

            $start = $_GET['depart'];
            $end = $_GET['end'];
            $guests = $_GET['guests'];
            $rooms_1 = $_GET['rooms'];

            $template = $twig->loadTemplate('asem_detail.html');
            //render a template
            echo $template->render(array('title' => 'Choose room', 'nav' => 1, 'user' => $user, 'hotel' =>$hotel,'guests' => $guests, 'rooms_1' =>$rooms_1, 'start' => $start, 'end' => $end, 'rooms' => $rooms, 'main' => $main, 'images' =>$images));
        }
        else if(isset($_POST['contact_us'])){
            require 'lib/Mailer/PHPMailerAutoload.php';
            $content='<h4>Нэр: '.$_POST['name'].'</h4><h4>И-мэйл хаяг: '
                .$_POST['email']. '</h4><h4> Захидал: </h4><p>'.$_POST['content'].'</p>';
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
            $mail->addAddress('khangai@alchemist.mn', 'Contact us');
            $mail->Subject = 'Contact us';
            $mail->msgHTML($content);
            $mail->AltBody = '';
            $result = false;
            if (!$mail->send()) { $result=false; } 
            else { 
                $contact_us = new ParseObject("contact_us");
                $contact_us->set('email', $_POST['email']);
                $contact_us->set('name', $_POST['name']);
                $contact_us->set('content', $_POST['content']);
                $contact_us->save();
                $result=true; 
            }

            if($result){ echo 1; }else{ echo 0; }
        }else{
            $template = $twig->loadTemplate('user_dashboard.html');
            $query = new ParseQuery("orders");
            $query->descending("createdAt");
            $query->equalTo("user",$user);
            $query->notEqualTo("status",0);
            $query->includeKey('hotel');
            $old_orders = $query->find();

            if(isset($_SESSION['orders'])){
                $start = $_SESSION['start'];
                $end = $_SESSION['end'];
                $days = $_SESSION['days'];
                $hotel = $_SESSION['hotel'];
                $day_start = date('l', strtotime( $start));
                $day_end = date('l', strtotime( $end));
                $orders = $_SESSION['orders'];
                class Event {}
                $rooms = array();
                $total = 0;
                for ($i = 0; $i < count($orders); ++$i){
                    $e = new Event();
                    $order_id = $orders[$i];
                    $query = new ParseQuery("orders");
                    $query->equalTo("objectId",$order_id);
                    $query->includeKey("room");
                    $order = $query->first();
                    $room = $order->get('room');
                    $e->name = $room->get('room_type');
                    $e->qty = $order->get('qty');
                    $e->sub = $order->get('total');
                    $total = $total + (int)$order->get('total');
                    array_push($rooms,$e);
                }
                $total= $total * $days;
                //render a template
                echo $template->render(array('title' => 'iHotel', 'user' => $user, 'start' => $start, 'end' => $end, 'rooms' => $rooms, 'days' => $days, 'total' =>$total ,'day_start' => $day_start, 'hotel' =>$hotel, 'day_end' => $day_end,'nav' => 2, 'orders'=>$old_orders));
            }else{
                echo $template->render(array('title' => 'iHotel', 'user' => $user, 'nav' => 2, 'orders'=>$old_orders));
            }
        }
    }else{
        if(isset($_GET['register'])){
            $template = $twig->loadTemplate('register.html');
            //render a template
            echo $template->render(array('title' => 'Бүртгүүлэх'));
        }
        else if(isset($_POST['contact_us'])){
            require 'lib/Mailer/PHPMailerAutoload.php';
            $content='<h4>Нэр: '.$_POST['name'].'</h4><h4>И-мэйл хаяг: '
                .$_POST['email']. '</h4><h4> Захидал: </h4><p>'.$_POST['content'].'</p>';
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
            $mail->addAddress('khangai@alchemist.mn', 'Contact us');
            $mail->Subject = 'Contact us';
            $mail->msgHTML($content);
            $mail->AltBody = '';
            $result = false;
            if (!$mail->send()) { $result=false; } 
            else { 
                $contact_us = new ParseObject("contact_us");
                $contact_us->set('email', $_POST['email']);
                $contact_us->set('name', $_POST['name']);
                $contact_us->set('content', $_POST['content']);
                $contact_us->save();
                $result=true; 
            }

            if($result){ echo 1; }else{ echo 0; }
        }
        else if(isset($_GET['asem_login'])){
            $template = $twig->loadTemplate('asem_register.html');
            //render a template
            echo $template->render(array('title' => 'Asem Login'));
        }
        else if(isset($_GET['start'])){
            $query = new ParseQuery("hotel");
            $query->equalTo("status",1);
            $query->equalTo("asem",0);
            $query->equalTo("type", "Hotel");


            $query->descending("stars");
            $location = $_GET['location'];

            $start = $_GET['start'];
            $end = $_GET['end'];
            $persons = $_GET['persons'];
            $guests = $_GET['guests'];


            $date = new DateTime($start);
            $checkin = $date->format('Y-m-d');

            $date = new DateTime($end);
            $checkout = $date->format('Y-m-d');

            $pieces = explode(",", $location);
            $query->equalTo("city",$pieces[0]);
            $query->limit(25);
            $results = $query->find();
            $count = $query->count();
            $pages = (int)($count / 25);

            $template = $twig->loadTemplate('list.html');
            //render a template
            echo $template->render(array('title' => 'Хайлт', 'nav' => 1, 'location' => $location, 'results' =>$results,'start' => $checkin, 'end' => $checkout, 'count' => $count, 'country' => $pieces[1], 'pages' => $pages, 'guests'=>$guests, 'persons'=>$persons));
        }
        else if(isset($_GET['start_1'])){
            $query = new ParseQuery("hotel");
            $query->equalTo("status",1);
            $query->equalTo("type", "Hotel");
            $query->equalTo("asem",1);
            $query->descending("stars");

            $start = $_GET['start_1'];
            $end = $_GET['end_1'];
            $guests = $_GET['guests'];
            $rooms = $_GET['rooms'];

            $date = new DateTime($start);
            $checkin = $date->format('Y-m-d');

            $date = new DateTime($end);
            $checkout = $date->format('Y-m-d');

            $query->equalTo("city",'Ulaanbaatar');
            $results = $query->find();

            $template = $twig->loadTemplate('asem_list.html');
            //render a template
            echo $template->render(array('title' => 'Search results', 'nav' => 1, 'results' =>$results,'start' => $checkin, 'end' => $checkout,  'guests'=>$guests, 'rooms'=>$rooms));
        }
        else if(isset($_POST['mapGeoId'])){
            $query = new ParseQuery("hotel");
            $query->equalTo("objectId",$_POST['mapGeoId']);
            $hotel = $query->first();
            $e['cover_image'] = $hotel->get('cover_image');
            $e['average_rate'] = $hotel->get('average_rate');
            $e['address'] = $hotel->get('address');
            $e['stars'] = $hotel->get('stars');
            $e['name'] = $hotel->get('name');
            $e['id'] = $hotel->getObjectId();
            $e['latitude'] = $hotel->get('geolocation')->getLatitude();
            $e['longitude'] = $hotel->get('geolocation')->getLongitude();
            echo json_encode($e);
        }
        else if(isset($_POST['news'])){
            $template = $twig->loadTemplate('user_news.html');
            $query = new ParseQuery("news");
            $query->descending("createdAt");
            $query->equalTo("user",$user);
            $news = $query->find();
            echo $template->render(array('title' => 'iHotel', 'user' => $user, 'nav' => 2, 'news'=>$news)); 
        }
        else if(isset($_GET['detail'])){
            $query = new ParseQuery("hotel");
            $query->equalTo("objectId",$_GET['detail']);
            $hotel = $query->first();

            $query = new ParseQuery("rooms");
            $query->equalTo("hotel",$hotel);
            $rooms = $query->find();

            $query = new ParseQuery("hotel_images");
            $query->equalTo("hotel",$hotel);
            $query->equalTo("main",1);
            $main = $query->first();

            $query = new ParseQuery("hotel_images");
            $query->equalTo("hotel",$hotel);
            $query->equalTo("main",0);
            $images = $query->find();

            $start = $_GET['depart'];
            $end = $_GET['end'];
            //$location = $_GET['location'];
            //$country = $_GET['country'];

            $template = $twig->loadTemplate('detail.html');
            //render a template
            echo $template->render(array('title' => 'Дэлгэрэнгүй', 'nav' => 1,  'hotel' =>$hotel, 'start' => $start, 'end' => $end, 'rooms' => $rooms, 'main' => $main, 'images' =>$images));
        }
        else if(isset($_GET['asemdetail'])){
            $query = new ParseQuery("hotel");
            $query->equalTo("objectId",$_GET['asemdetail']);
            $hotel = $query->first();

            $query = new ParseQuery("rooms");
            $query->equalTo("hotel",$hotel);
            $rooms = $query->find();

            $query = new ParseQuery("hotel_images");
            $query->equalTo("hotel",$hotel);
            $query->equalTo("main",1);
            $main = $query->first();

            $query = new ParseQuery("hotel_images");
            $query->equalTo("hotel",$hotel);
            $query->equalTo("main",0);

            $images = $query->find();

            $start = $_GET['depart'];
            $end = $_GET['end'];
            $guests = $_GET['guests'];
            $rooms_1 = $_GET['rooms'];

            $template = $twig->loadTemplate('asem_detail.html');
            //render a template
            echo $template->render(array('title' => 'Choose room', 'nav' => 1,  'hotel' =>$hotel,'guests' => $guests, 'rooms_1' =>$rooms_1, 'start' => $start, 'end' => $end, 'rooms' => $rooms, 'main' => $main, 'images' =>$images));
        }
        else if(isset($_GET['tour'])){
            $query = new ParseQuery("hotel");
            $query->equalTo("status",1);
            $query->equalTo("asem",0);
            $query->equalTo("type",'Resort');
            $query->descending("stars");
            $results = $query->find();
            $date = new DateTime();
            $checkin = $date->format('Y-m-d');
            $date = new DateTime();
            $checkout = $date->format('Y-m-d');
            $count = $query->count();
            $template = $twig->loadTemplate('tour.html');
            echo $template->render(array('title' => 'Амралтын газар', 'nav' => 1, 'location' => 'Ulaanbaatar', 'results' =>$results,'start' => $checkin, 'end' => $checkout, 'count' => $count, 'country' => 'iHotel'));
        }
        else if(isset($_GET['news'])){
            $template = $twig->loadTemplate('news.html');
            $query = new ParseQuery("news");
            $query->includeKey("user");
            $news = $query->find();
            $query->equalTo('category', 'Travel Advices');
            $travel_advice = $query->find();
            $data['travel_advice']=count($travel_advice);
            $query->equalTo('category', 'Hotels');
            $hotels = $query->find();
            $data['hotels']=count($hotels);
            $query->equalTo('category', 'Places to Go');
            $place_to_go = $query->find();
            $data['place_to_go']=count($place_to_go);
            echo $template->render(array('title' => 'Мэдээ мэдээлэл', 'news'=>$news, 'category'=>$data));
        }
        else if(isset($_GET['newsdetail'])){
            $template = $twig->loadTemplate('news_detail.html');
            $query = new ParseQuery("news");
            $query->includeKey("user");
            $query->equalTo("objectId",$_GET['newsdetail']);
            $news = $query->first();

            $query->equalTo('category', 'Travel Advices');
            $travel_advice = $query->find();
            $data['travel_advice']=count($travel_advice);
            $query->equalTo('category', 'Hotels');
            $hotels = $query->find();
            $data['hotels']=count($hotels);
            $query->equalTo('category', 'Places to Go');
            $place_to_go = $query->find();
            $data['place_to_go']=count($place_to_go);
            echo $template->render(array('title' => 'Мэдээ мэдээлэл', 'news'=>$news, 'category'=>$data));
        }
        else if(isset($_GET['news_category'])){
            $template = $twig->loadTemplate('news_category.html');
            $query = new ParseQuery("news");
            $query->includeKey("user");
            $query->equalTo("category",$_GET['news_category']);
            $news = $query->find();
            $query->equalTo('category', 'Travel Advices');
            $travel_advice = $query->find();
            $data['travel_advice']=count($travel_advice);
            $query->equalTo('category', 'Hotels');
            $hotels = $query->find();
            $data['hotels']=count($hotels);
            $query->equalTo('category', 'Places to Go');
            $place_to_go = $query->find();
            $data['place_to_go']=count($place_to_go);
            echo $template->render(array('title' => 'Мэдээ мэдээлэл', 'news'=>$news, 'category'=>$data));
        }
        else if(isset($_GET['news_search'])){
            $template = $twig->loadTemplate('news_search.html');
            $query = new ParseQuery("news");
            $query->includeKey("user");
            $query->containedIn("subject",[$_GET['news_search']]);
            $news = $query->find();

            $query->equalTo('category', 'Travel Advices');
            $travel_advice = $query->find();
            $data['travel_advice']=count($travel_advice);
            $query->equalTo('category', 'Hotels');
            $hotels = $query->find();
            $data['hotels']=count($hotels);
            $query->equalTo('category', 'Places to Go');
            $place_to_go = $query->find();
            $data['place_to_go']=count($place_to_go);
            echo $template->render(array('title' => 'Мэдээ мэдээлэл', 'news'=>$news, 'category'=>$data));
        }
        else if(isset($_GET['contact'])){
            $template = $twig->loadTemplate('contact-us.html');
            echo $template->render(array('title' => 'Холбоо барих'));
        }
        else if(isset($_GET['about'])){
            $template = $twig->loadTemplate('about.html');
            echo $template->render(array('title' => 'Бидний тухай'));
        }
        else if(isset($_GET['faq'])){
            $query = new ParseQuery("faq");
            $faqs = $query->find();
            $template = $twig->loadTemplate('faq.html');
            echo $template->render(array('title' => 'Асуулт хариулт', 'faqs'=>$faqs));
        }
        else if(isset($_GET['asem1'])){
            if ($_GET['type'] == 1) {
                $template = $twig->loadTemplate('asem1.html');
                echo $template->render(array('title' => 'Partnership', 'type' => $_GET['type']));
            }else{
                $template = $twig->loadTemplate('asem_register.html');
                echo $template->render(array('title' => 'Asem Login'));
            }
        }
        else if(isset($_GET['help'])){
            $template = $twig->loadTemplate('help.html');
            echo $template->render(array('title' => ''));
        }
        else if(isset($_GET['terms'])){
            $template = $twig->loadTemplate('terms.html');
            echo $template->render(array('title' => ''));
        }
        else if(isset($_GET['asem_terms'])){
            $template = $twig->loadTemplate('asem_terms.html');
            echo $template->render(array('title' => ''));
        }
        else if(isset($_GET['asem-news'])){
            $template = $twig->loadTemplate('asem_news.html');
            echo $template->render(array('title' => ''));
        }
        else if(isset($_GET['asem-tours'])){
            $template = $twig->loadTemplate('asem-tours.html');
            echo $template->render(array('title' => ''));
        }
        else if(isset($_GET['asem-faq'])){
            $query = new ParseQuery("faq_en");
            $faqs = $query->find();
            $template = $twig->loadTemplate('asem-faq.html');
            echo $template->render(array('title' => 'FAQ', 'nav' => 3, 'faqs'=>$faqs));
        }
        else 
            if(isset($_GET['payment'])){
                if(isset($_SESSION['orders'])){
                    $start = $_SESSION['start'];
                    $end = $_SESSION['end'];
                    $days = $_SESSION['days'];
                    $hotel = $_SESSION['hotel'];
                    $pickup = $_SESSION['pickup'];
                    $day_start = date('l', strtotime( $start));
                    $day_end = date('l', strtotime( $end));
                    $orders = $_SESSION['orders'];
                    class Event {}
                    $rooms = array();
                    $total = 0;
                    for ($i = 0; $i < count($orders); ++$i){
                        $e = new Event();
                        $order_id = $orders[$i];
                        $query = new ParseQuery("orders");
                        $query->equalTo("objectId",$order_id);
                        $query->includeKey("room");
                        $order = $query->first();
                        $room = $order->get('room');
                        $e->name = $room->get('room_type');
                        $e->qty = $order->get('qty');
                        $e->sub = $order->get('total');
                        $total = $total + (int)$order->get('total');
                        array_push($rooms,$e);
                    }
                    $total= $total * $days;

                    if ($pickup == "budget") { $total += 25; } else if($pickup == "vip"){ $total += 200; }

                    $_SESSION['total'] = $total;
                    $template = $twig->loadTemplate('payment.html');
                    //render a template
                    echo $template->render(array('title' => '', 'hotel' =>$hotel,  'start' => $start, 'end' => $end, 'rooms' => $rooms, 'days' => $days, 'total' =>$total ,'day_start' => $day_start, 'pickup' => $pickup, 'day_end' => $day_end));
                }else{

                }
            }
            else if(isset($_GET['asem_payment'])){

                if(isset($_SESSION['orders'])){
                    $start = $_SESSION['start'];
                    $end = $_SESSION['end'];
                    $days = $_SESSION['days'];
                    $hotel = $_SESSION['hotel'];
                    $pickup = $_SESSION['pickup'];
                    $day_start = date('l', strtotime( $start));
                    $day_end = date('l', strtotime( $end));
                    $orders = $_SESSION['orders'];
                    class Event {}
                    $rooms = array();
                    $total = 0;
                    for ($i = 0; $i < count($orders); ++$i){
                        $e = new Event();
                        $order_id = $orders[$i];
                        $query = new ParseQuery("orders");
                        $query->equalTo("objectId",$order_id);
                        $query->includeKey("room");
                        $order = $query->first();
                        $room = $order->get('room');
                        $e->name = $room->get('room_type');
                        $e->qty = $order->get('qty');
                        $e->sub = $order->get('total');
                        $total = $total + (int)$order->get('total');
                        array_push($rooms,$e);
                    }
                    $total= $total * $days;

                    if ($pickup == "budget") { $total += 25; } else if($pickup == "vip"){ $total += 200; }

                    $template = $twig->loadTemplate('asem_payment.html');
                    //render a template
                    echo $template->render(array('title' => '', 'hotel' =>$hotel,  'start' => $start, 'end' => $end, 'rooms' => $rooms, 'days' => $days, 'total' =>$total ,'day_start' => $day_start, 'day_end' => $day_end, 'pickup' => $pickup));
                }else{

                }
            }
            else if(isset($_GET['order'])){
                $query = new ParseQuery("orders");
                $query->equalTo("objectId",$_GET['order']);
                $query->includeKey('hotel');
                $order = $query->first();
                $hotel = $order->get("hotel");
                $template = $twig->loadTemplate('order.html');
                //render a template
                echo $template->render(array('title' => 'Захиалга', 'order' => $order, 'hotel' =>$hotel ));
            }
            else if(isset($_GET['search'])){
                $template = $twig->loadTemplate('home.html');
                $query = new ParseQuery("hotel");
                $query->equalTo("status",1);
                $query->equalTo("homepage",1);
                $query->equalTo("city","Ulaanbaatar");
                $query->descending("name");
                $query->limit(2);
                $hotels = $query->find();

                $query = new ParseQuery("hotel");
                $query->equalTo("status",1);
                $query->equalTo("homepage",1);
                $query->equalTo("city","Ulaanbaatar");
                $query->ascending("name");
                $query->limit(2);
                $hotels2 = $query->find();
                echo $template->render(array('title' => 'iHotel', 'nav' => 1, 'hotels'=>$hotels, 'hotels2'=>$hotels2));
            }
            else if(isset($_GET['city'])){
                $query = new ParseQuery("hotel");
                $query->equalTo("status",1);
                $query->equalTo("asem",0);
                $query->equalTo("city",$_GET['city']);
                $query->descending("stars");
                $results = $query->find();
                $date = new DateTime();
                $checkin = $date->format('Y-m-d');
                $date = new DateTime();
                $checkout = $date->format('Y-m-d');
                $count = $query->count();
                $template = $twig->loadTemplate('list.html');
                //render a template
                echo $template->render(array('title' => 'Хайлт', 'nav' => 1, 'location' => $_GET['city'], 'results' =>$results,'start' => $checkin, 'end' => $checkout, 'count' => $count, 'country' => 'iHotel'));
            }
            else if(isset($_GET['asem'])){
                /*
                $query = new ParseQuery("hotel");
                $query->equalTo("type", 'Hotel');
                $query->equalTo("status",1);
                $query->equalTo("asem",1);
                $query->descending("stars");

                $query->equalTo("city",'Ulaanbaatar');
                $results = $query->find();
                $count = $query->count();

                $template = $twig->loadTemplate('asem_list.html');
                //render a template
                echo $template->render(array('title' => 'Asem 2016 Hotels', 'nav' => 1, 'results' =>$results));
                 */

                $template = $twig->loadTemplate('asem_register.html');
                echo $template->render(array('title' => 'Asem Login', 'list' => 1));
            }
            else{
                $template = $twig->loadTemplate('home.html');
                $query = new ParseQuery("hotel");
                $query->equalTo("status",1);
                $query->equalTo("homepage",1);
                $query->equalTo("city","Ulaanbaatar");
                $query->descending("name");
                $query->limit(2);
                $hotels = $query->find();

                $query = new ParseQuery("hotel");
                $query->equalTo("status",1);
                $query->equalTo("homepage",1);
                $query->equalTo("city","Ulaanbaatar");
                $query->ascending("name");
                $query->limit(2);
                $hotels2 = $query->find();
                echo $template->render(array('title' => 'iHotel', 'nav' => 1, 'hotels'=>$hotels, 'hotels2'=>$hotels2));
            } 
    }
?>
