<?php
    require_once 'includes/Twig/Autoloader.php';
    require_once "config.php";
    use Parse\ParseQuery;
    use Parse\ParseFile;

    $query = new ParseQuery("hotel");
    if (isset($_POST['data'])) {

        if ($_POST['data']!=null) {
            $datas = $_POST['data'];
            $pieces = array_map('intval', explode(',', $datas));
            $query->containedIn("stars",$pieces);
        }
        $query->equalTo("city",$_POST['city']);

        if(isset($_POST['asem'])){
            $query->equalTo("asem",1);
        }else{
            $query->equalTo("asem",0);
        }

        if ($_POST['filter']!=null) {
            $filter = $_POST['filter'];
            $filter_ = explode(',', $filter);
            for ($i = 0; $i < count($filter_); $i++) {
                $query->equalTo($filter_[$i], 1);
            }
        }

        if (isset($_POST['type'])) {
            $query->equalTo("type", $_POST['type']);
        }

        $query->equalTo("status",1);
        $query->limit(25);
        $results = $query->find();
        $count = $query->count();

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
            $e->rate = $row->get('min_rate');
            $e->latitude = $row->get('geolocation')->getLatitude();
            $e->longitude = $row->get('geolocation')->getLongitude();
            $events[] = $e; 
        }

        $data = array();
        $data['events'] =  $events;
        $data['pages'] = (int)($count / 25);

        header('Content-Type: application/json');
        echo json_encode($data);

    }elseif(isset($_POST['changed_asem'])){

        $query->equalTo("city",$_POST['city']);

        if(isset($_POST['asem'])){
            $query->equalTo("asem",1);
        }else{
            $query->equalTo("asem",0);
        }
         
        $query->equalTo("type", $_POST['type']);

        $query->equalTo("status",1);
        $query->limit(25);
        $results = $query->find();
        $count = $query->count();
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
            $e->rate = $row->get('min_rate');
            $e->latitude = $row->get('geolocation')->getLatitude();
            $e->longitude = $row->get('geolocation')->getLongitude();
            $events[] = $e; 
        }
        $data = array();
        $data['events'] =  $events;
        $data['pages'] = (int)($count / 25);

        header('Content-Type: application/json');
        echo json_encode($data);
    }
    elseif(isset($_POST['not_checked'])){
    
        $query->equalTo("city",$_POST['city']);

        if(isset($_POST['asem'])){
            $query->equalTo("asem",1);
        }else{
            $query->equalTo("asem",0);
        }

        $query->equalTo("type", $_POST['type']);

        $query->equalTo("status",1);
        $query->limit(25);
        $results = $query->find();
        $count = $query->count();
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
            $e->rate = $row->get('min_rate');
            $e->latitude = $row->get('geolocation')->getLatitude();
            $e->longitude = $row->get('geolocation')->getLongitude();
            $events[] = $e; 
        }
        $data = array();
        $data['events'] =  $events;
        $data['pages'] = (int)($count / 25);

        header('Content-Type: application/json');
        echo json_encode($data);
    }
    elseif(isset($_POST['autocomplete'])){
    
        if (isset($_POST['stars'])) {
            $stars = $_POST['stars'];
            $pieces = array_map('intval', explode(',', $stars));
            $query->containedIn("stars",$pieces);
        }

        $query->equalTo("city",$_POST['city']);

        if(isset($_POST['asem'])){
            $query->equalTo("asem",1);
        }else{
            $query->equalTo("asem",0);
        }

        $query->equalTo("type", $_POST['type']);

        $query->equalTo("status",1);
        $query->limit(25);
        $results = $query->find();
        $count = $query->count();
        $autocomplete = array();

        foreach ($results as $row) {
            array_push($autocomplete, $row->get('name'));
        }
        echo json_encode($autocomplete);
    }
    elseif(isset($_POST['search'])){
        $query->equalTo("city",$_POST['city']);

        $query->equalTo("type", $_POST['type']);

        $query->equalTo("status",1);
        $query->equalTo("name",$_POST['search']);
        $query->limit(25);
        $results = $query->find();
        $count = $query->count();
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
            $e->rate = $row->get('min_rate');
            $e->latitude = $row->get('geolocation')->getLatitude();
            $e->longitude = $row->get('geolocation')->getLongitude();
            $events[] = $e; 
        }
        $data = array();
        $data['events'] =  $events;
        $data['pages'] = (int)($count / 25);

        header('Content-Type: application/json');
        echo json_encode($data);
    }
    elseif(isset($_POST['price'])){
    
        $query->equalTo("city",$_POST['city']);

        if(isset($_POST['asem'])){
            $query->equalTo("asem",1);
        }else{
            $query->equalTo("asem",0);
        }

        $query->lessThanOrEqualTo("min_rate", intval($_POST['to']));
        $query->greaterThanOrEqualTo("min_rate", intval($_POST['from']));

        $query->equalTo("type", $_POST['type']);

        $query->equalTo("status",1);
        $query->limit(25);
        $results = $query->find();
        $count = $query->count();
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
            $e->rate = $row->get('min_rate');
            $e->latitude = $row->get('geolocation')->getLatitude();
            $e->longitude = $row->get('geolocation')->getLongitude();
            $events[] = $e; 
        }
        $data = array();
        $data['events'] =  $events;
        $data['pages'] = (int)($count / 25);

        header('Content-Type: application/json');
        echo json_encode($data);
    }
?>
