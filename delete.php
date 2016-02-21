<?php
    require_once "config.php";
    use Parse\ParseException;
    use Parse\ParseQuery;

    if($_POST['action']==1){
        $query = new ParseQuery("user_cards");
        $query->equalTo("objectId",$_POST['data']);
        $card = $query->first();
        
        $card->set('status',0);

        $result = false;
        try {
            $card->save();
            $result = true;
        } catch (ParseException $ex) {  
            // Execute any logic that should take place if the save fails.
            // error is a ParseException object with an error code and message.
            echo $ex;
        }

        if($result){
            echo 1;
        }else{
            echo 0;
        }
    }
    if($_POST['action']==1){
        $query = new ParseQuery("hotel");
        $query->equalTo("objectId",$_POST['data']);
        $hotel = $query->first();
        
        $hotel->set('status',0);

        $result = false;
        try {
            $hotel->save();
            $result = true;
        } catch (ParseException $ex) {  
            // Execute any logic that should take place if the save fails.
            // error is a ParseException object with an error code and message.
            echo $ex;
        }

        if($result){
            echo 1;
        }else{
            echo 0;
        }
    }
    if($_POST['action']==3){
        $query = new ParseQuery("hotel");
        $query->equalTo("objectId",$_POST['data']);
        $hotel = $query->first();
        
        $hotel->set('homepage',1);

        $result = false;
        try {
            $hotel->save();
            $result = true;
        } catch (ParseException $ex) {  
            // Execute any logic that should take place if the save fails.
            // error is a ParseException object with an error code and message.
            echo $ex;
        }

        if($result){
            echo $hotel->getObjectId();
        }else{
            echo 0;
        }
    }
    if($_POST['action']==4){
        $query = new ParseQuery("hotel");
        $query->equalTo("objectId",$_POST['data']);
        $hotel = $query->first();
        
        $hotel->set('homepage',0);

        $result = false;
        try {
            $hotel->save();
            $result = true;
        } catch (ParseException $ex) {  
            // Execute any logic that should take place if the save fails.
            // error is a ParseException object with an error code and message.
            echo $ex;
        }

        if($result){
            echo $hotel->getObjectId();
        }else{
            echo 0;
        }
    }
    

?>