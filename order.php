<?php
    require_once "config.php";
    use Parse\ParseException;
    use Parse\ParseObject;
    use Parse\ParseUser;
    use Parse\ParseQuery;

    session_start();

    if (isset($_SESSION['user'])){
        $user = $_SESSION['user'];

        if(isset($_POST['end'])){
            if(isset($_SESSION['orders'])){
                $orders = $_SESSION['orders'];
                class Event {}
                $rooms = array();
                $total = 0;
                $trans_num = 0;
                for ($i = 0; $i < count($orders); ++$i){
                    $e = new Event();
                    $order_id = $orders[$i];
                    if ($i == 0) {
                        $trans_num = $order_id;
                    }
                    $query = new ParseQuery("orders");
                    $query->equalTo("objectId",$order_id);
                    $query->includeKey("room");
                    $order = $query->first();
                    $order->set('order_id', $trans_num);
                    $order->save();
                }
                try {
                    echo $order->get('order_id');
                } catch (Exception $e) {
                    echo 0; 
                }
            }
        }
    }

    if(isset($_POST['order_update_form'])){
        $query = new ParseQuery("orders");
        $query->equalTo("objectId",$_POST['order_update_form']);
        $query->includeKey("room");
        $query->includeKey("user");
        $query->includeKey("card");
        $query->includeKey("hotel");
        $orders = $query->first();
        $e['id'] = $orders->getObjectId();
        $e['hotel'] = $orders->get('hotel')->get('name');
        $e['room'] = $orders->get('room')->get('room_type');
        $e['user_name'] = $orders->get('user')->get('name');
        $e['createAt'] = $orders->getCreatedAt()->format('Y-m-d');
        $e['start'] = $orders->get('start');
        $e['end'] = $orders->get('end');
        $e['days'] = $orders->get('days');
        $e['qty'] = $orders->get('qty');
        $e['total'] = $orders->get('total');
        $e['status'] = $orders->get('status');
        $e['order_id'] = $orders->get('order_id');
        $user = $orders->get('user');
        $e['meeting'] = $user->get('meeting_type');
        $e['sim'] = $orders->get('sim');
        $e['pickup'] = $orders->get('pickup');
        $e['country'] = $orders->get('user')->get('country');
        if ($orders->get('card')!=NULL) {
            $card = $orders->get('card');
            $e['card_number'] = $card->get('card');
            $e['cvc'] = $card->get('cvc');
            $e['valid'] = $card->get('valid');
            $e['holder'] = $card->get('card_name');
        }
        echo json_encode($e);
    }

    if(isset($_POST['delete_order'])){
        $query = new ParseQuery("orders");
        $query->equalTo("objectId",$_POST['delete_order']);
        $order = $query->first();
        $order->set('status',0);
        $order->save();
    }

    if(isset($_POST['update_order_id'])){
        $query = new ParseQuery("orders");
        $query->equalTo("objectId",$_POST['update_order_id']);
        $query->includeKey("user");
        $orders = $query->first();
        $orders->set('status', intval($_POST['view_status']));
        $orders->save();
        $result = false;

        try {
            $orders->save();
            $result = true;
        } catch (ParseException $ex) {  
            echo 'Failed to create new object, with error message: ' . $ex->getMessage();
        }

        if($result){
            echo 1;
        }else{
            echo 0;
        }
    }
?>
