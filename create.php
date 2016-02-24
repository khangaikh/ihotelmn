<?php
    require_once "config.php";
    use Parse\ParseException;
    use Parse\ParseObject;
    use Parse\ParseQuery;
    use Parse\ParseFile;
    
    session_start();
    $hotel = $_SESSION['hotel'];
    $data = $_POST['data'];

    if($_POST['section']==1){
        $hotel->set('country',$data['basic_info_country']);
        $hotel->set('city',$data['basic_info_city']);
        $hotel->set('address',$data['basic_info_address']);
        $hotel->set('phone',$data['basic_info_phone']);
        $hotel->set('stars',(int)$data['basic_info_star_rating']);
        $hotel->set('num_rooms',(int)$data['basic_info_room_number']);
        $hotel->set('website',$data['basic_info_website']);
        $hotel->set('chain_name',$data['basic_info_name_group_chain']);
        $hotel->set('credit_card',(int)$data['basic_info_credit_card']);
        $hotel->set('short_desc',$data['short_desc']);
        $hotel->set('long_desc',$data['long_desc']);
        $hotel->set('name',$data['hotel_name']);
        $hotel->set('zipcode',(string)$data['basic_info_zipcode']);
    }
    if($_POST['section']==2){
        $hotel->set('checkin',$data['property_details_checkin_start']);
        $hotel->set('checkinend',$data['property_details_checkin_end']);
        $hotel->set('checkout',$data['property_details_checkout_start']);
        $hotel->set('wifi',(int)$data['property_details_internet_payment']);
        $hotel->set('breakfast',(int)$data['property_details_breakfast_included']);
        $hotel->set('parking',(int)$data['property_details_parking_available']);
        $hotel->set('children',$data['property_details_children_allowed']);
        $hotel->set('pets',$data['property_details_pets_allowed']);
        $hotel->set('children',$data['property_details_children_allowed']);
        if(isset($_POST['activities'])){
            $hotel->setArray('activities',$_POST['activities']);
        }
        if(isset($_POST['food_drink'])){
            $hotel->setArray('food_drink',$_POST['food_drink']);
        }
        if(isset($_POST['pool_spa'])){
            $hotel->setArray('pool_spa',$_POST['pool_spa']);
        }
        if(isset($_POST['transportation'])){
            $hotel->setArray('transportation',$_POST['transportation']);
        }
        if(isset($_POST['front_desk'])){
            $hotel->setArray('front_desk',$_POST['front_desk']);
        }
        if(isset($_POST['common_area'])){
            $hotel->setArray('common_area',$_POST['common_area']);
        }
        if(isset($_POST['entertainment'])){
            $hotel->setArray('entertainment',$_POST['entertainment']);
        }
        if(isset($_POST['cleaning'])){
            $hotel->setArray('cleaning',$_POST['cleaning']);
        }
        if(isset($_POST['business_fac'])){
            $hotel->setArray('business_fac',$_POST['business_fac']);
        }
        if(isset($_POST['shops'])){
            $hotel->setArray('shops',$_POST['shops']);
        }
        if(isset($_POST['others'])){
            $hotel->setArray('others',$_POST['others']);
        }
    } 
    if($_POST['section']==3){
        $room = new ParseObject("rooms");
        $room->set("hotel",$hotel);
        $room->set("room_type",$data['room_details_roomtype_id']);
        $room->set("short_desc", $data['room_details_room_decs']);
        $room->set("num_beds", (int)$data['room_details_bed_number_SINGLE_1']);
        $room->set("adult_occupancy", (int)$data['room_details_num_guests']);
        $room->set("roomt_size", $data['room_details_room_size']);
        $room->set("bed_size", $data['room_details_bedtype_id_SINGLE_1']);
        $room->set("num_of_guest", (int)$data['room_details_num_guests']);
        $room->set("num_rooms", (int)$data['room_details_room_number']);
        $room->set("child_occupancy", 1);
        if(isset($_POST['facilities'])){
            $room->setArray('facilities',$_POST['facilities']);  
        }
        $room->set("night_price", (int)$data['room_details_room_price_x_persons']);
        $paths=array();

        foreach ($_POST['images'] as $key=>$value){
            $parts = split (",", $value);
            $data = base64_decode($parts[1]);
            $im = imagecreatefromstring($data);
            if ($im !== false) {
                header('Content-Type: image/png');
                $random = substr( md5(rand()), 0, 7);
                $path = 'img/room/'.date('YmdHis').$random.'.png';
                $resp = imagepng($im, $path);
                array_push($paths, $path);
                imagedestroy($im);
            }
            else {
                echo 'An error occurred.';
            }
        }
        $room->setArray('images',$paths);

        try {
            $room->save();
            $query = new ParseQuery("rooms");
            $query->equalTo("hotel",$hotel);
            $rooms = $query->find();
            $count = $query->count();

            $total = 0;

            foreach ($rooms as $value) {
                $total = $total + $value->get('night_price');
            }

            $average = (int)($total/$count);

            $hotel->set('average_rate', (string)$average);
            try {
                $hotel->save();
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
    if($_POST['section']==4){
        
        $paths=array();

        foreach ($_POST['hotel_images'] as $key=>$value){
            $parts = split (",", $value);
            $random = substr( md5(rand()), 0, 7);
            $data = base64_decode($parts[1]);
            $im = imagecreatefromstring($data);
            if ($im !== false) {
                header('Content-Type: image/png');
                $path = 'img/hotel/'.$random.$key.'.png';
                $resp = imagepng($im, $path);
                array_push($paths, $path);
                imagedestroy($im);
            }
            else {
                echo 'An error occurred.';
            }
        }
        $hotel->setArray('images',$paths);


        $parts = split (",", $_POST['cover_images'][0]);
        $random = substr( md5(rand()), 0, 7);
        $data = base64_decode($parts[1]);
        $im = imagecreatefromstring($data);
        if ($im !== false) {
            header('Content-Type: image/png');
            $path = 'img/hotel/'.$random.$key.'.png';
            $resp = imagepng($im, $path);
            array_push($paths, $path);
            imagedestroy($im);
        }
        else {
            echo 'An error occurred.';
        }
        $hotel->set('cover_image',$path);
    }
    if($_POST['section']==5){
        /*Payment part todo*/
    }
    if($_POST['section']==6){
        /*Agreement part todo*/
    }
    if($_POST['section']==7){
        $hotel->set('status',1);
    }
    $sec = $hotel->get('section');
    
    if($sec!=7){
        $hotel->set('section',(int)$_POST['section']);
        $_SESSION['section'] = (int)$_POST['section'];
    }else{
        $_SESSION['section']=7;
    }
    
    
    try {
        $hotel->save();
    } catch (ParseException $ex) {  
        // Execute any logic that should take place if the save fails.
        // error is a ParseException object with an error code and message.
        echo 0;
    }

    echo 1;
?>
