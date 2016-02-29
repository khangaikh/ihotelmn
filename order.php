<?php
    require_once "config.php";
    use Parse\ParseException;
    use Parse\ParseObject;
    use Parse\ParseUser;
    use Parse\ParseQuery;

    session_start();
    if (isset($_SESSION['user'])){
        $user = $_SESSION['user'];

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
?>
