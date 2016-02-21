<?php
    require_once "config.php";
    use Parse\ParseException;
    use Parse\ParseUser;
    use Parse\ParseQuery;
    session_start();
    //$date = null;   
    $query = new ParseQuery("orders");
    $query->equalTo("objectId",$_POST['data']);
    $order = $query->first();
    
    $order->set('status',0);

    $result = false;
    try {
        $order->save();
        $result = true;
    } catch (ParseException $ex) {  
        // Execute any logic that should take place if the save fails.
        // error is a ParseException object with an error code and message.
    }

    if($result){
        echo 1;
    }else{
        echo 0;
    }

?>