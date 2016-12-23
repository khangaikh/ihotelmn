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

        if (isset($_POST['answer_mn']) && isset($_POST['question_mn']) && isset($_POST['submit'])) {
           $answer_mn = $_POST['answer_mn'];
           $question_mn = $_POST['question_mn'];

           $answer_en = $_POST['answer_en'];
           $question_en = $_POST['question_en'];

           $faq = new ParseObject('faq');
           $faq->set('header_mn', $answer_mn);
           $faq->set('content_mn', $question_mn);
           $faq->set('header_en', $answer_en);
           $faq->set('content_en', $answer_en);

           $faq->set('user', $user);
           $result = false;

           try{
                $faq->save();
                $result = true;
           }catch(ParseException $ex){
                echo 'Failed:'.$ex->getMessage();
           }
           if($result){
              $template = $twig->loadTemplate('user_faq.html');
              echo $template->render(array('title' => 'Асуулт, хариулт', 'user' => $user, 'nav' => 9, 'result'=> 1));
           }else{
              echo 0;
           }
        }elseif(isset($_POST['edit_faq_form'])){

            $query = new ParseQuery("faq");
            $query->equalTo("objectId",$_POST['edit_faq_form']);
            $faq = $query->first();
            $e['id'] = $faq->getObjectId();
            $e['edit_answer_mn'] = $faq->get('header_mn');
            $e['edit_question_mn'] = $faq->get('content_mn');
            $e['edit_answer_en'] = $faq->get('header_en');
            $e['edit_question_en'] = $faq->get('content_en');
            echo json_encode($e);

        }elseif(isset($_POST['edit_question_mn']) && isset($_POST['edit_answer_mn']) && isset($_POST['submit'])){
            $answer = $_POST['edit_answer_mn'];
            $question = $_POST['edit_question_mn'];
            $answer_en = $_POST['edit_answer_en'];
            $question_en = $_POST['edit_question_en'];
            
            $query = new ParseQuery("faq");
            $query->equalTo("objectId",$_POST['edit_faq_id']);
            $faq = $query->first();
            $faq->set("header_mn", $answer);
            $faq->set("content_mn", $question);
            $faq->set("header_en", $answer_en);
            $faq->set("content_en", $question_en);

            $result = false;

            try {
                $faq->save();
                $result = true;
            } catch (ParseException $ex) {  
                echo 'Failed to create new object, with error message: ' . $ex->getMessage();
            }

            if($result){
                $template = $twig->loadTemplate('user_faq.html');
                echo $template->render(array('title' => 'Асуулт хариулт', 'user' => $user, 'nav' => 8, 'result'=> 1));
            }else{
                echo 0;
            }
        }elseif(isset($_POST['delete_faq'])){
          
            $query = new ParseQuery("faq");
            $query->equalTo("objectId",$_POST['delete_faq']);
            $faq = $query->first();
            $faq->destroy();

        }elseif(isset($_POST['answer_asem']) && isset($_POST['question_asem'])){
          $question = $_POST['question_asem'];
          $answer = $_POST['answer_asem'];

          $faq = new ParseObject('faq_en');
          $faq->set('header_en', $question);
          $faq->set('content_en', $answer);

          $faq->set('user', $user);
          $result = false;

           try{
                $faq->save();
                $result = true;
           }catch(ParseException $ex){
                echo 'Failed:'.$ex->getMessage();
           }
           if($result){
              $template = $twig->loadTemplate('user_faq.html');
              echo $template->render(array('title' => 'Асуулт, хариулт', 'user' => $user, 'nav' => 9, 'result'=> 1));
           }else{
              echo 0;
           }
        }elseif(isset($_POST['edit_faq_asem'])){
            $query = new ParseQuery("faq_en");
            $query->equalTo("objectId",$_POST['edit_faq_asem']);
            $faq = $query->first();
            $e['id'] = $faq->getObjectId();
            $e['edit_question_asem'] = $faq->get('header_en');
            $e['edit_answer_asem'] = $faq->get('content_en');
            echo json_encode($e);

          }
          elseif(isset($_POST['edit_question_asem']) && isset($_POST['edit_answer_asem']) && isset($_POST['submit'])){
            $answer = $_POST['edit_answer_asem'];
            $question = $_POST['edit_question_asem'];
            
            $query = new ParseQuery("faq_en");
            $query->equalTo("objectId",$_POST['edit_id_asem']);
            $faq = $query->first();
            $faq->set("header_en", $question);
            $faq->set("content_en", $answer);

            $result = false;

            try {
                $faq->save();
                $result = true;
            } catch (ParseException $ex) {  
                echo 'Failed to create new object, with error message: ' . $ex->getMessage();
            }

            if($result){
                $template = $twig->loadTemplate('user_faq.html');
                echo $template->render(array('title' => 'Асуулт хариулт', 'user' => $user, 'nav' => 8, 'result'=> 1));
            }else{
                echo 0;
            }
        }elseif(isset($_POST['delete_faq_asem'])){
          
            $query = new ParseQuery("faq_en");
            $query->equalTo("objectId",$_POST['delete_faq_asem']);
            $faq = $query->first();
            $faq->destroy();

        }
          else{
            $template = $twig->loadTemplate('user_faq.html');
            echo $template->render(array('title' => 'Асуулт, хариулт', 'nav' => 8, 'result'=> 1));
          }
    }
?>
