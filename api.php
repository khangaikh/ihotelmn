<?php

    require_once "config.php";
    use Parse\ParseQuery;
    
    session_start();

    function strtohex($x) 
    {
        $s='';
        foreach (str_split($x) as $c) $s.=sprintf("%02X",ord($c));
        return($s);
    }

    $query = new ParseQuery("general");
    $query->equalTo("objectId","hLnoaR9C1J");
    $general = $query->first();

    $source = 'TestBilguudei';

    $iv = "ihotelmnasem2016";
    $pass = $general->get("openssl_pass");
    $method = 'aes-128-cbc';

    $encrypted = openssl_encrypt ($source, $method, $pass, true, $iv);

    echo strtohex($encrypted);
    echo "<br />";

    $decrypted = openssl_decrypt ($encrypted, $method , $pass, true , $iv);
    echo $decrypted;
    echo "<br />";
    //echo strtohex($iv);
   
?>
