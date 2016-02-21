<?php
require_once "config.php";
use Parse\ParseException;
use Parse\ParseObject;
use Parse\ParseUser;
use Parse\ParseQuery;


    session_start();
    if (isset($_SESSION['user'])){
        $user = $_SESSION['user'];
        // code...
        $location=$_POST["location"];
        $total=$_POST["total"];
        $days=$_POST["days"];
        $start=$_POST["start"];
        $end=$_POST["end"];

        $query = new ParseQuery("hotel");
        $query->equalTo("objectId",$_POST["hotel"]);
        $hotel = $query->first();

        $query = new ParseQuery("rooms");
        $query->equalTo("objectId",$_POST["room"]);
        $room = $query->first();

        $order = new ParseObject("orders");
        $order->set("hotel", $hotel);
        $order->set("room", $room);
        $order->set("user", $user);
        $order->set("start", $start);
        $order->set("end", $end);
        $order->set("location", $location);
        $order->set("days", (int)$days);
        $order->set("total", $total);
        $order->set("status", 0);

        $result = false;
        try {
            $order->save();
            $result = true;
        } catch (ParseException $ex) {  
            // Execute any logic that should take place if the save fails.
            // error is a ParseException object with an error code and message.
            echo 0;
        }

        if($result){
            echo $order->getObjectId();
        }else{
            echo 0;
        }
    }
    else{
        $name=$_POST["name"];
        $email=$_POST["email"];
        $phone=$_POST["phone"];
        $location=$_POST["location"];
        $total=$_POST["total"];
        $days=$_POST["days"];
        $start=$_POST["start"];
        $end=$_POST["end"];

        $user = new ParseUser();
        $user->set("username", $name);
        $user->set("email", $email);
        $user->set("password", '123456');
        $user->set("role", 1);
        $user->set("status", 1);

    /*
    if($type == 1){
        $user->set("status", 1);
    }else{
        $user->set("status", 0);
    }
     */

        try {
            $user->signUp();
        } catch (ParseException $ex) {
            echo 0;
        }

        $query = new ParseQuery("hotel");
        $query->equalTo("objectId",$_POST["hotel"]);
        $hotel = $query->first();

        $query = new ParseQuery("rooms");
        $query->equalTo("objectId",$_POST["room"]);
        $room = $query->first();

        $order = new ParseObject("orders");
        $order->set("hotel", $hotel);
        $order->set("room", $room);
        $order->set("user", $user);
        $order->set("start", $start);
        $order->set("end", $end);
        $order->set("location", $location);
        $order->set("days", (int)$days);
        $order->set("total", $total);
        $order->set("status", 0);

        $result = false;
        try {
            $order->save();
            $result = true;
        } catch (ParseException $ex) {  
            // Execute any logic that should take place if the save fails.
            // error is a ParseException object with an error code and message.
            echo 0;
        }

        if($result){
            echo $order->getObjectId();
        }else{
            echo 0;
        }

    }
?>
