<?php

    require_once "config.php";
    use Parse\ParseException;
    use Parse\ParseObject;
    use Parse\ParseQuery;
    
    session_start();

    class Event {}
    $e = new Event();
    $id = (string)$_POST['data'];
    $query = new ParseQuery("rooms");
    $query->equalTo("objectId",$id);
    $room = $query->first();

    if($_POST['action']==1){
        $query = new ParseQuery("room_closing");
        $query->equalTo("room",$room);
        $closings = $query->find();
        $starts = array();
        $ends = array();
        $ids = array();
        $e->id = $room->getObjectId();
        $e->num_of_guest = $room->get('num_of_guest');
        $e->price = $room->get('night_price');
        $e->price2 = $room->get('night_price2');
        $e->desc = $room->get('short_desc');
        $e->fac = $room->get('facilities');
        $e->size = $room->get('roomt_size');
        $e->bed_size = $room->get('bed_size');
        $e->type = $room->get('room_type');
        $e->num_rooms = $room->get('num_rooms');
        $e->beds = $room->get('num_beds');
        $e->images = $room->get('images');
        
        foreach ($closings as $closing) {
            array_push($starts,$closing->get("start")->format('Y-m-d'));
            array_push($ends,$closing->get("end")->format('Y-m-d'));
            array_push($ids,$closing->getObjectId());
        }

        $e->starts = $starts;
        $e->ends = $ends;
        $e->ids = $ids;
        echo json_encode($e);
    } 
    if($_POST['action']==2){
        $arr = $room->get('images');
        $i = $_POST['order'];
        try {

            exec('rm -rf '.$arr[$i]);

        } catch (Exception $e) {
            die();
        }
        unset($arr[$i]);
        $arr = array_values($arr);
        $room->setArray('images',$arr);

        try {
            $room->save();
            echo 1;
        } catch (ParseException $ex) {  
            // Execute any logic that should take place if the save fails.
            // error is a ParseException object with an error code and message.
            echo  $ex;
        }
    }  
    if($_POST['action']==3) {
        $data = $_POST['values'];
       
        if($data['room_detail_edit_roomtype_custom']!=""){
            $room->set("room_type",$data['room_detail_edit_roomtype_custom']);
        }else{
           $room->set("room_type",$data['room_detail_edit_roomtype_id']); 
        }

        $room->set("short_desc", $data['room_detail_edit_room_decs']);
        $room->set("num_beds", (int)$data['room_detail_edit_bed_number_SINGLE_1']);
        $room->set("adult_occupancy", (int)$data['room_detail_edit_num_guests']);
        $room->set("roomt_size", $data['room_detail_edit_room_size']);
        $room->set("bed_size", $data['room_detail_edit_bedtype_id_SINGLE_1']);
        $room->set("num_of_guest", (int)$data['room_detail_edit_num_guests']);
        $room->set("num_rooms", (int)$data['room_detail_edit_room_number']);
        if(isset($_POST['facilities'])){
            $room->setArray('facilities',$_POST['facilities']);  
        }
        $room->set("night_price", (int)$data['room_detail_edit_room_price_x_persons']);
        $room->set("night_price2", (int)$data['room_detail_edit_room_price_2x_persons']);
        $paths= $room->get('images');
        if(isset($_POST['images'])){
            foreach ($_POST['images'] as $key=>$value){
                $random = substr( md5(rand()), 0, 7);
                $data = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $value));
                $path = 'img/room/'.$random.'.jpg';
                file_put_contents($path, $data);
                if (filesize($path) > 184320) {
                    $imagick = new \Imagick(realpath($path));
                    $imagick->setImageCompressionQuality(23);
                    $imagick->writeImage($path);
                }
                array_push($paths, $path);
            }
        }
        $room->setArray('images',$paths);

        try {
            $room->save();
            $hotel = $_SESSION['hotel'];
            $query = new ParseQuery("rooms");
            $query->equalTo("hotel",$hotel);
            $query->ascending("night_price");
            $e = $query->first();
            $min = $e->get('night_price'); 

            $hotel->set('min_rate', intval($min));


            try {
                $hotel->save();
                echo 1;
            } catch (ParseException $ex) {  
                // Execute any logic that should take place if the save fails.
                // error is a ParseException object with an error code and message.
                echo  $ex;
            }
        } catch (ParseException $ex) {  
            // Execute any logic that should take place if the save fails.
            // error is a ParseException object with an error code and message.
            echo  $ex;
        }
    }
    if($_POST['action']==4) {
        $room->destroy();

        $hotel = $_SESSION['hotel'];
        $query = new ParseQuery("rooms");
        $query->equalTo("hotel",$hotel);
        $rooms = $query->find();
        $count = $query->count();

        $total = 0;

        foreach ($rooms as $key => $value) {
            $total = $total + $value->get('night_price');
        }

        $average = (int)($total/$count);

        $hotel->set('average_rate', (string)$average);
        try {
            $hotel->save();
            echo 1;
        } catch (ParseException $ex) {  
            // Execute any logic that should take place if the save fails.
            // error is a ParseException object with an error code and message.
            echo  $ex;
        }
    }
    if($_POST['action']==5) {

        $closing = new ParseObject("room_closing");
        $closing->set('room',$room);

        $closing->set('start',new DateTime($_POST['start']));
        $closing->set('end',new DateTime($_POST['end']));
        
        try {
            $closing->save();
            echo $closing->getObjectId();
        } catch (ParseException $ex) {  
            // Execute any logic that should take place if the save fails.
            // error is a ParseException object with an error code and message.
            echo  $ex;
        }
    }
    if($_POST['action']==6) {

        $query = new ParseQuery("room_closing");
        $query->equalTo("objectId",$id);
        $closing = $query->first();;
        
        try {
            $closing->destroy();
        } catch (ParseException $ex) {  
            // Execute any logic that should take place if the save fails.
            // error is a ParseException object with an error code and message.
            echo  $ex;
        }
    }
    if($_POST['action']==7) {

        $a = new Event();
        $id = (string)$_POST['data'];
        $query = new ParseQuery("hotel");
        $query->equalTo("objectId",$id);
        $hotel = $query->first();
        $arr = $hotel->get('images');
        $index = array_search($_POST["name"], $arr);
        try {
            exec('rm -rf '.$arr[$index]);

        } catch (Exception $e) {
            die();
        }
        if (gettype($index) == "integer") {
            unset($arr[$index]);
            $arr = array_values($arr);
        }

        $hotel->setArray('images',$arr);

        try {
            $hotel->save();
            $_SESSION['hotel'] = $hotel;
            $_SESSION['section'] = $hotel->get("section");
            echo 1;
        } catch (ParseException $ex) {  
            // Execute any logic that should take place if the save fails.
            // error is a ParseException object with an error code and message.
            echo  $ex;
        }
    }

?>
