<?php
    require_once 'includes/Twig/Autoloader.php';
    require_once "config.php";
    use Parse\ParseQuery;
    use Parse\ParseFile;

    session_start();

    $query = new ParseQuery("hotel");
    
    $sort = $_POST['data'];
    $datas = $_POST['filter1'];
    $pieces = explode(",", $datas);

    if (in_array('0',$pieces) == false) {
        $query->notEqualTo("stars",0); 
    }
    if (in_array('1',$pieces) == false) {
        $query->notEqualTo("stars",1); 
    }
    if (in_array('2',$pieces) == false) {
        $query->notEqualTo("stars",2); 
    }
    if (in_array('3',$pieces) == false) {
        $query->notEqualTo("stars",3); 
    }
    if (in_array('5',$pieces) == false) {
        $query->notEqualTo("stars",5); 
    }
    if (in_array('4',$pieces) == false) {
        $query->notEqualTo("stars",4); 
    }
    
    $query->equalTo("city",$_POST['city']);
    $query->equalTo("status",1);

    if($sort=='starup'){
        $query->ascending("stars");
    }
    if($sort=='stardown'){
        $query->descending("stars");
    }
    if($sort=='priceup'){
        $query->descending("average_rate");
    }
    if($sort=='pricedown'){
        $query->ascending("average_rate");
    }
    $query->limit(25);
    $results = $query->find();
    $count = $query->count();
    $_SESSION['sort'] =$sort;
    class Event {}
    $events = array();
   
    foreach ($results as $row) {
        $e = new Event();
        $e->id = $row->getObjectId();
        $e->name = $row->get('name');
        $e->stars = $row->get('stars');
        $e->short_desc = $row->get('short_desc');
        $e->address = $row->get('address');
        $e->cover =$row->get('cover_image');
        $e->rate = $row->get('average_rate');
        $events[] = $e; 
    }

    $data = array();
    $data['events'] =  $events;
    $data['sort'] =  $sort;
    $data['pages'] = (int)($count / 25);

    header('Content-Type: application/json');
    echo json_encode($data);

?>

