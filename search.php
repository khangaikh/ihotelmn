    <?php
    require_once 'includes/Twig/Autoloader.php';
    require_once "config.php";
    use Parse\ParseQuery;
    use Parse\ParseFile;

    $query = new ParseQuery("hotel");
    
    $datas = $_POST['data'];
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
    
    
    //$pieces = explode(",", $location);
    $query->equalTo("city",$_POST['city']);
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

