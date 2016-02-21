<?php
    require_once "config.php";
    use Parse\ParseException;
    use Parse\ParseObject;
    use Parse\ParseQuery;
    
    session_start();

    $query = new ParseQuery("hotel");
    $query->equalTo("objectId",$_POST['data']);
    $hotel = $query->first();

    $_SESSION['hotel'] = $hotel;
    $_SESSION['section'] = $hotel->get("section");

?>