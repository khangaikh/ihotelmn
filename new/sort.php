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

    $array = array();
    foreach ($results as $row) {
        $filterOthers = 0;
        $filterFood = 0;
        $filterTransport = 0;
        $filterEntertainment = 0;
        $filterPool = 0;
        $nullCheck = 0;

        if ($_POST['miscellaneous']) {
            $nullCheck++;
            $array = $row->get('others');
            $filter = $_POST['miscellaneous'];
            $filter_ = explode(',', $filter);
            if($array){
                for ($j = 0; $j < count($filter_); $j++) {
                    $key = array_search($filter_[$j], $row->get('others'));
                    if(gettype($key)=="integer") {
                        $filterOthers++;
                    }
                }
            }
        }
        if ($_POST['transport']) {
            $nullCheck++;
            $array = $row->get('transportation');
            $filter = $_POST['transport'];
            $filter_ = explode(',', $filter);
            if ($array) {
                for ($j = 0; $j < count($filter_); $j++) {
                    $key = array_search($filter_[$j], $array);
                    if(gettype($key)=="integer") {
                        $filterTransport++;
                    }
                }
            }
        }
        if ($_POST['pool_spa']) {
            $nullCheck++;
            $array = $row->get('pool_spa');
            $filter = $_POST['pool_spa'];
            $filter_ = explode(',', $filter);
            if ($array) {
                for ($j = 0; $j < count($filter_); $j++) {
                    $key = array_search($filter_[$j], $array);
                    if(gettype($key)=="integer") {
                        $filterPool++;
                    }
                }
            }
        }
        if ($_POST['entertainment']) {
            $nullCheck++;
            $array = $row->get('entertainment');
            $filter = $_POST['entertainment'];
            $filter_ = explode(',', $filter);
            if ($array) {
                for ($j = 0; $j < count($filter_); $j++) {
                    $key = array_search($filter_[$j], $array);
                    if(gettype($key)=="integer") {
                        $filterEntertainment++;
                    }
                }
            }
        }
        if ($_POST['food_drink']) {
            $nullCheck++;
            $array = $row->get('food_drink');
            $filter = $_POST['food_drink'];
            $filter_ = explode(',', $filter);
            if ($array) {
                for ($j = 0; $j < count($filter_); $j++) {
                    $key = array_search($filter_[$j], $array);
                    if(gettype($key)=="integer") {
                        $filterFood++;
                    }
                }
            }
        }

        $checkCount = 0;
        if ($filterOthers === count(explode(',',$_POST['miscellaneous']))) {
            $checkCount++;
        }
        if ($filterEntertainment === count(explode(',',$_POST['entertainment']))) {
            $checkCount++;
        }
        if ($filterFood === count(explode(',',$_POST['food_drink']))) {
            $checkCount++;
        }
        if ($filterPool === count(explode(',',$_POST['pool_spa']))) {
            $checkCount++;
        }
        if ($filterTransport === count(explode(',',$_POST['transport']))) {
            $checkCount++;
        }

        if ($checkCount === $nullCheck) {
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
    }

    $count = count($events);

    $data = array();
    $data['events'] =  $events;
    $data['pages'] = (int)($count / 25);

    header('Content-Type: application/json');
    echo json_encode($data);
?>

