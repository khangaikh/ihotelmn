<?php
    require_once "config.php";
    use Parse\ParseException;
    use Parse\ParseObject;
    use Parse\ParseQuery;
    
    session_start();

    if (isset($_POST['data'])) {
        $query = new ParseQuery("hotel");
        $query->equalTo("objectId",$_POST['data']);
        $hotel = $query->first();
        $_SESSION['hotel'] = $hotel;
        $_SESSION['section'] = $hotel->get("section");
    }
    elseif(isset($_POST['news'])){
    
        $query = new ParseQuery("news");
        $query->equalTo("objectId",$_POST['news']);
        $news = $query->first();
        $_SESSION['news'] = $news;
    }
?>
