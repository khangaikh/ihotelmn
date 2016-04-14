<?php
    require_once "config.php";
    
    session_start();

    function strtohex($x) 
    {
        $s='';
        foreach (str_split($x) as $c) $s.=sprintf("%02X",ord($c));
        return($s);
    }

    $source = 'Khangai';

    $iv = "ihotelmnasem2016";
    $pass = 'ihotelMongolia123$';
    $method = 'aes-128-cbc';

    $encrypted = openssl_encrypt ($source, $method, $pass, true, $iv);

    echo strtohex($encrypted);
    echo "<br />";

    $decrypted = openssl_decrypt ($encrypted, $method , $pass, true , $iv);
    echo $decrypted;
    echo "<br />";
    //echo strtohex($iv);
   
?>
