<?php
    require_once "config.php";
    use Parse\ParseQuery;
    
    session_start();
    $user = $_SESSION['user'];

    $query = new ParseQuery("orders");
    $query->equalTo("user",$user);
    $query->includeKey('hotel');
    if($_POST['data'] == 1){
        $query->equalTo("status",1); 
    }if ($_POST['data'] == 2) {
        $query->equalTo("status",0); 
    }
    $results = $query->find();
   
    class Event {}
    $events = array();
   
    foreach ($results as $row) {
        $e = new Event();
        $e->id = $row->get('objectId');
        $e->name = $row->get('hotel')->get('name');
        $e->start = $row->get('start');
        $e->end = $row->get('end');
        $e->location = $row->get('location');
        $e->orderDate = $row->get('start');
        $e->total = $row->get('total');
        $events[] = $e; 
    }


    header('Content-Type: application/json');
    echo json_encode($events);

?>

