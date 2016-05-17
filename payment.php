 <?php

     require_once "config.php";
     use Parse\ParseException;
     use Parse\ParseObject;
     use Parse\ParseUser;
     use Parse\ParseQuery;

     session_start();

     $days=$_POST["days"];
     $start=$_POST["start"];
     $end=$_POST["end"];

     $pickup = $_POST["pickup"];

     //$rooms = explode(',', $_POST["rooms"]);
     $rooms = explode(',', $_POST["rooms"]);
     $qtys = array_map('intval', explode(',', $_POST["qtys"]));
     $subs = array_map('intval', explode(',', $_POST["subs"]));

     $order_ids = [];

     if (isset($_SESSION['user'])){
         $user = $_SESSION['user'];
         $result = true;
         $c = count($rooms);

         for ($i = 0; $i <$c; $i++) {
             $room_id = explode('-', $rooms[$i])[0];
             $qty = $qtys[$i];
             $subtotal = $subs[$i];

             $query = new ParseQuery("hotel");
             $query->equalTo("objectId",$_POST["hotel"]);
             $hotel = $query->first();

             $query = new ParseQuery("rooms");
             $query->equalTo("objectId",$room_id);
             $room = $query->first();

             $order = new ParseObject("orders");
             $order->set("hotel", $hotel);
             $order->set("room", $room);
             $order->set("user", $user);
             $order->set("start", $start);
             $order->set("end", $end);
             $order->set("days", (int)$days);
             $order->set("qty", (int)$qty);
             $order->set("total", (string)$subtotal);
             $order->set("pickup", (string)$pickup);
             $order->set("status", 0);

             try {
                 $order->save();
                 array_push($order_ids,$order->getObjectId());
                 $_SESSION['hotel'] = $hotel;
             } catch (ParseException $ex) {  
                 $result = false;
                 // Execute any logic that should take place if the save fails.
                 // error is a ParseException object with an error code and message.
                 echo $ex;
             }
         }
         $_SESSION['orders'] = $order_ids;
         if($result){
             echo 1;
         }else{
             echo -2;
         }
     }
     else{
         $c = count($rooms);
         $result = true;
        

         for ($i = 0; $i < $c; $i++) {

             $room_id = $rooms[$i];
             $qty = $qtys[$i];
             $subtotal = $subs[$i];

             $query = new ParseQuery("hotel");
             $query->equalTo("objectId",$_POST["hotel"]);
             $hotel = $query->first();

             $query = new ParseQuery("rooms");
             $query->equalTo("objectId",$room_id);
             $room = $query->first();

             $order = new ParseObject("orders");
             $order->set("hotel", $hotel);
             $order->set("room", $room);
             $order->set("start", $start);
             $order->set("end", $end);
             $order->set("days", (int)$days);
             $order->set("qty", (int)$qty);
             $order->set("total", (string)$subtotal);
             $order->set("pickup", (string)$pickup);
             $order->set("status", 0);

             try {
                 $order->save();
                 $_SESSION['hotel'] = $hotel;
                 array_push($order_ids,$order->getObjectId());
             } catch (ParseException $ex) {  
                 $result = false;
                 // Execute any logic that should take place if the save fails.
                 // error is a ParseException object with an error code and message.
                 echo $ex;
             }
             $room = null;

         }
         $_SESSION['orders'] = $order_ids;
         if($result){
             echo 1;
         }else{
             echo -3;
         }

     }
     $_SESSION['days'] = $days;
     $_SESSION['start'] = $start;
     $_SESSION['end'] = $end;
     $_SESSION['pickup'] = $pickup;

?>
