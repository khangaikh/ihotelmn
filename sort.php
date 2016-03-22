<?php
    require_once 'includes/Twig/Autoloader.php';
    require_once "config.php";
    use Parse\ParseQuery;
    use Parse\ParseFile;

    session_start();

    $query = new ParseQuery("hotel");
    
    $sort = $_POST['data'];

    if ($_POST['filter1']!=null) {
        $datas = $_POST['filter1'];
        $pieces = array_map('intval', explode(',', $datas));
        $query->containedIn("stars",$pieces);
    }

    if ($_POST['filter2']!=null) {
        $filter2 = $_POST['filter2'];
        $filter_2 = explode(',', $filter2);
        for ($i = 0; $i < count($filter_2); $i++) {
            $query->equalTo($filter_2[$i], 1);
        }
    }
    
    $query->equalTo("city",$_POST['city']);
    
    if(isset($_POST['asem'])){
        $query->equalTo("asem",1);
    }else{
        $query->equalTo("asem",0);
    }
    
    $query->equalTo("status",1);

    if($sort=='starup'){
        $query->descending("stars");
    }
    if($sort=='stardown'){
        $query->ascending("stars");
    }
    if($sort=='priceup'){
        $query->descending("min_rate");
    }
    if($sort=='pricedown'){
        $query->ascending("min_rate");
    }
    $results = $query->find();
    $count = $query->count();
    $_SESSION['sort'] =$sort;
    class Event {}
    $events = array();
   
    $checkArr = false;

    $filterCount = 0;

    foreach ($results as $row) {

        if ($_POST['miscellaneous']) {
            for ($i = 0; $i < count($row->get('others')); $i++) {
                $filter = $_POST['miscellaneous'];
                $filter_ = explode(',', $filter);
                for ($j = 0; $j < count($filter_); $j++) {
                    if(strcmp($row->get('others')[$i],$filter_[$j])===0) {
                        $checkArr = true;
                        $filterCount++;
                    }
                }
            }
        }
        if ($_POST['transport']) {
            for ($i = 0; $i < count($row->get('transportation')); $i++) {
                $filter = $_POST['transport'];
                $filter_ = explode(',', $filter);
                for ($j = 0; $j < count($filter_); $j++) {
                    if(strcmp($row->get('transportation')[$i],$filter_[$j])===0) {
                        $checkArr = true;
                        $filterCount++;
                    }
                }
            }
        }
        if ($_POST['pool_spa']) {
            for ($i = 0; $i < count($row->get('pool_spa')); $i++) {
                $filter = $_POST['pool_spa'];
                $filter_ = explode(',', $filter);
                for ($j = 0; $j < count($filter_); $j++) {
                    if(strcmp($row->get('pool_spa')[$i],$filter_[$j])===0) {
                        $checkArr = true;
                        $filterCount++;
                    }
                }
            }
        }
        if ($_POST['entertainment']) {
            for ($i = 0; $i < count($row->get('entertainment')); $i++) {
                $filter = $_POST['entertainment'];
                $filter_ = explode(',', $filter);
                for ($j = 0; $j < count($filter_); $j++) {
                    if(strcmp($row->get('entertainment')[$i],$filter_[$j])===0) {
                        $checkArr = true;
                        $filterCount++;
                    }
                }
            }
        }
        if ($_POST['food_drink']) {
            for ($i = 0; $i < count($row->get('food_drink')); $i++) {
                $filter = $_POST['food_drink'];
                $filter_ = explode(',', $filter);
                for ($j = 0; $j < count($filter_); $j++) {
                    if(strcmp($row->get('food_drink')[$i],$filter_[$j])===0) {
                        $checkArr = true;
                        $filterCount++;
                    }
                }
            }
        }
        if ($checkArr) {
            $e = new Event();
            $e->id = $row->getObjectId();
            $e->name = $row->get('name');
            $e->stars = $row->get('stars');
            $e->short_desc = $row->get('short_desc');
            $e->address = $row->get('address');
            $e->cover =$row->get('cover_image');
            $e->rate = $row->get('min_rate');
            $e->latitude = $row->get('geolocation')->getLatitude();
            $e->longitude = $row->get('geolocation')->getLongitude();
            $e->sold_out = $row->get('sold_out');
            $events[] = $e; 
            $checkArr = false;
        }
        elseif($filterCount == 0){
            $e = new Event();
            $e->id = $row->getObjectId();
            $e->name = $row->get('name');
            $e->stars = $row->get('stars');
            $e->short_desc = $row->get('short_desc');
            $e->address = $row->get('address');
            $e->cover =$row->get('cover_image');
            $e->rate = $row->get('min_rate');
            $e->latitude = $row->get('geolocation')->getLatitude();
            $e->longitude = $row->get('geolocation')->getLongitude();
            $e->sold_out = $row->get('sold_out');
            $events[] = $e; 
            $filterCount = 0;
        }
    }

    $count = count($events);

    $data = array();
    $data['events'] =  $events;
    $data['sort'] =  $sort;
    $data['pages'] = (int)($count / 25);

    header('Content-Type: application/json');
    echo json_encode($data);
?>

