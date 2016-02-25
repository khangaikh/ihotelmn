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

        if (isset($_POST['answer_mn']) && isset($_POST['question_mn']) && isset($_POST['submit']) {
           $answer_mn = $_POST['answer_mn'];
           $question_mn = $_POST['question_mn'];

           $faq = new ParseObject('faq');
           $faq->set('header_mn', $answer_mn);
           $faq->set('content_mn', $question_mn);
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
              echo $template->render(array('title' => 'Мэдээ мэдээлэл', 'user' => $user, 'nav' => 6, 'result'=> 1));
           }else{
              echo 0;
           }
        }
    }
    else{
        $template = $twig->loadTemplate('user_faq.html');
        echo $template->render(array('title' => 'Мэдээ мэдээлэл', 'nav' => 6, 'result'=> 1));
    }
?>
