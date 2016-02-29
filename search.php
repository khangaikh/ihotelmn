    <?php

    require_once 'includes/Twig/Autoloader.php';
    require_once "config.php";
    use Parse\ParseQuery;
    use Parse\ParseFile;

    $query = new ParseQuery("hotel");
    
    $datas = $_POST['data'];
    $pieces = array_map('intval', explode(',', $datas));
       
    //$pieces = explode(",", $location);
    $query->containedIn("stars",$pieces);
    $query->equalTo("city",$_POST['city']);
    
    if(isset($_POST['asem'])){
        $query->equalTo("asem",1);
    }else{
        $query->equalTo("asem",0);
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
        $e->rate = $row->get('average_rate');
        $events[] = $e; 
    }

    $data = array();
    $data['events'] =  $events;
    $data['pages'] = (int)($count / 25);

    header('Content-Type: application/json');
    echo json_encode($data);

?>

