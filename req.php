<?php

session_start();

function rs_api_create_res(){

	$start = $_SESSION['start'];
    $end = $_SESSION['end'];
    $days = $_SESSION['days'];
    $hotel = $_SESSION['hotel'];
    $orders = $_SESSION['orders'];
    $user = $_SESSION['user'];
    
    for ($i = 0; $i < count($orders); ++$i){
        $order_id = $orders[$i];
        $query = new ParseQuery("orders");
        $query->equalTo("objectId",$order_id);
        $query->includeKey("room");

        $order = $query->first();
        $room = $order->get('room');
        $room_type = $room->get('room_rs_type');
        $room_qty = $order->get('qty');
        $room_sub = $order->get('total');

        $post = [
	    	'hotel' => $hotel->get("rs_name"),
	    	'customer' => $user,
	    	'room_type'   => $room_type,
	    	'room_qty'   => $room_type,
	    	'room_sub'   => $room_type,
	    	'start'   => $start
	    	'end'   => $end,
	    	'days'   => $days,
		];

		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL,"http://localhost/ihotel/api.php");
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS,$post);

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		$server_output = curl_exec ($ch);

		curl_close ($ch);
		
    }
}




?>