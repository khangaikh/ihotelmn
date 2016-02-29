<?php
	require_once "config.php";
    use Parse\ParseObject;
    use Parse\ParseClient;
    use Parse\ParseQuery;
    use Parse\ParseUser;
	session_start();

	//function rs_api_create_res(){

		$start = $_SESSION['start'];
	    $end = $_SESSION['end'];
	    $days = $_SESSION['days'];
	    $orders = $_SESSION['orders'];
	    $user = $_SESSION['user'];

	    for ($i = 0; $i < count($orders); ++$i){
	        $order_id = $orders[$i];
	        $query = new ParseQuery("orders");
	        $query->equalTo("objectId",$order_id);
	        $query->includeKey("room");
	        $query->includeKey("hotel");

	        $order = $query->first();
	        $room = $order->get('room');
	        $room_type = $room->get('room_rs_type');
	        $room_qty = $order->get('qty');
	        $room_sub = $order->get('total');
	        $rate = $room->get('night_price');
	        $room_name = $room->get('room_type');
	        $hotel = $order->get('hotel');

	        $post = [
		    	'hotel' => $hotel->get("rs_name"),
		    	'order_id' => $order->get("order_id"),
		    	'name' => $user->get('name'),
		    	'email' => $user->get('email'),
		    	'country' => $user->get('country'),
		    	'surname' => $user->get('surname'),
		    	'room_type'   => $room_type,
		    	'room_qty'   => $room_qty,
		    	'room_sub'   => $room_sub,
		    	'room_name'   => $room_name,
		    	'rate'   => $rate,
		    	'start'   => $start,
		    	'end'   => $end,
		    	'days'   => $days,
			];

			$ch = curl_init();

			curl_setopt($ch, CURLOPT_URL,"http://localhost/ihotel/api.php");
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS,$post);

			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

			$server_output = curl_exec ($ch);
			echo $server_output;
			curl_close ($ch);
			
	    }
	//}
?>