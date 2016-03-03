<?php
    require_once "config.php";
    use Parse\ParseException;
    use Parse\ParseObject;
    
    session_start();

    $hotel = new ParseObject("hotel");
    $hotel->set("name", $_POST['name']);
    $hotel->set("type", $_POST['type']);
    $hotel->set("asem", (int)$_POST['asem']);
    $hotel->set("user", $_SESSION['user']);
    $hotel->set("min_rate", 1000000);
    $hotel->set("status",0);
    $hotel->set("homepage",0);
    $average = rand(5, 15);
    $hotel->set("average_rate",(string)$average);
    $result = false;

    try {
        $hotel->save();
        $_SESSION['hotel'] = $hotel;
        $_SESSION['section'] = 0;
        $result = true;
    } catch (ParseException $ex) {  
        // Execute any logic that should take place if the save fails.
        // error is a ParseException object with an error code and message.
        echo 'Failed to create new object, with error message: ' . $ex->getMessage();
    }
    
    if($result){
        echo $hotel->getObjectId();
    }else{
        echo 0;
    }
    
    $("#payment_rooms").append('<ul name="order_room" roomid="'+roomid+'"class="booking-item-payment-price"><li><p class="booking-item-payment-price-title">'+room_name+'</p><p class="booking-item-payment-price-title" style="margin-left:10px"><span name="qtys">'+$(this).val()+'</span><small>/өрөө</small></p><p class="booking-item-payment-price-amount">$<span name="subtotal">'+subtotal+'</span><small>/хоногийн</small></p></li></ul>'
?>
