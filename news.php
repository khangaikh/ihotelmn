<?php
    require_once 'includes/Twig/Autoloader.php';
    require_once "config.php";
    use Parse\ParseObject;
    use Parse\ParseClient;
    use Parse\ParseQuery;
    use Parse\ParseUser;

    session_start();
    //register autoloader
    Twig_Autoloader::register();
    //loader for template files
    $loader = new Twig_Loader_Filesystem('templates');
    //twig instance
    $twig = new Twig_Environment($loader, array(
        'cache' => 'cache',
    ));
    //load template file
    $twig->setCache(false);

    if (isset($_SESSION['user'])){
        $user = $_SESSION['user'];

        if (isset($_POST["news_catogry"])) {
            $category=$_POST["news_catogry"];
            $short_desc=$_POST["news_short_desc"];
            $content=$_POST["news_desc"];
            $subject=$_POST["news_subject"];
            $language=$_POST["news_language"];

            $news = new ParseObject("news");
            $news->set("category", $category);
            $random = substr( md5(rand()), 0, 7);
            $data = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $_POST['news_image1']));
            $path = 'img/news/'.$random.'.jpg';
            file_put_contents($path, $data);
            $imagick = new \Imagick(realpath($path));
            $imagick->setImageCompressionQuality(23);
            $imagick->writeImage($path);
            $news->set('header_image',$path);
            $news->set("user", $user);
            $news->set("short_desc", $short_desc);
            $news->set("subject", $subject);
            $news->set("content", $content);
            $news->set("language", $language);
            $result = false;

            try {
                $news->save();
                $result = true;
            } catch (ParseException $ex) {  
                echo 'Failed to create new object, with error message: ' . $ex->getMessage();
            }

            if($result){
                $template = $twig->loadTemplate('user_news.html');
                echo $template->render(array('title' => 'Мэдээ мэдээлэл', 'user' => $user, 'nav' => 6, 'result'=> 1));
            }else{
                echo 0;
            }
        }elseif(isset($_POST['news_update_form'])){

            $query = new ParseQuery("news");
            $query->equalTo("objectId",$_POST['news_update_form']);
            $news = $query->first();
            $e['id'] = $news->getObjectId();
            $e['subject'] = $news->get('subject');
            $e['category'] = $news->get('category');
            $e['short_desc'] = $news->get('short_desc');
            $e['content'] = $news->get('content');
            $e['language'] = $news->get('language');
            $e['header_image'] = $news->get('header_image');
            echo json_encode($e);

        }elseif (isset($_POST["update_news_id"])) {
            $category=$_POST["update_news_catogry"];
            $short_desc=$_POST["update_news_short_desc"];
            $content=$_POST["update_news_desc"];
            $subject=$_POST["update_news_subject"];
            $language=$_POST["update_news_language"];

            $query = new ParseQuery("news");
            $query->equalTo("objectId",$_POST['update_news_id']);
            $news = $query->first();
            $news->set("category", $category);
            if ($_POST['update_news_image1']!=NULL) {
                $random = substr( md5(rand()), 0, 7);
                $data = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $_POST['update_news_image1']));
                $path = 'img/news/'.date('YmdHis').$random.'.jpg';
                file_put_contents($path, $data);
                $imagick = new \Imagick(realpath($path));
                $imagick->setImageCompressionQuality(23);
                $imagick->writeImage($path);
                exec('rm -rf '.$news->get('header_image'));
                $news->set('header_image',$path);
            }
            $news->set("user", $user);
            $news->set("short_desc", $short_desc);
            $news->set("subject", $subject);
            $news->set("content", $content);
            $news->set("language", $language);
            $result = false;

            try {
                $news->save();
                $result = true;
            } catch (ParseException $ex) {  
                echo 'Failed to create new object, with error message: ' . $ex->getMessage();
            }

            if($result){
                $template = $twig->loadTemplate('user_news.html');
                echo $template->render(array('title' => 'Мэдээ мэдээлэл', 'user' => $user, 'nav' => 6, 'result'=> 1));
            }else{
                echo 0;
            }
        }elseif(isset($_POST['delete_news'])){

            $query = new ParseQuery("news");
            $query->equalTo("objectId",$_POST['delete_news']);
            $news = $query->first();
            exec('rm -rf '.$news->get('header_image'));
            $news->destroy();
            echo 'delete';

        }
    }
    else{
        $template = $twig->loadTemplate('user_news.html');
        echo $template->render(array('title' => 'Мэдээ мэдээлэл', 'nav' => 6, 'result'=> 1));
    }
?>
