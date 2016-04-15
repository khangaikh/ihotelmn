<?php
    require_once "config.php";
    use Parse\ParseException;
    use Parse\ParseObject;
    use Parse\ParseUser;
    use Parse\ParseQuery;
    
    $length = 35;
    $randomString = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
    
    $query = new ParseQuery("general");
    $query->equalTo("objectId","hLnoaR9C1J");
    $general = $query->first();

    $general->set('openssl_pass',$randomString);

    try {
        $general->save();
        //$randomString ="test";
        $field_string='';

        $md5 = md5($randomString."Ih0t3lMongol!4PWD"); 

        $fields =  array(
            'stringId' => urlencode($randomString),
            'controlKey' => urldecode($md5)
        );

        foreach ($fields as $key=>$value){$field_string.=$key.'='.$value.'&';}
        rtrim($field_string,'&');
    
        $ch = curl_init('http://asemaccreditation.net/ihApi.aspx');
        
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
        curl_setopt($ch, CURLOPT_POST, count($fields));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $field_string);                                                                  
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
                                                                                                                                      
        $response = curl_exec($ch);
        curl_close($ch);
        echo $response;

    } catch (ParseException $ex) {
        echo "Error: " . $ex->getCode() . " " . $ex->getMessage();
    }

?>
