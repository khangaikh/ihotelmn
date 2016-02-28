<?php

$ch = curl_init();

$post = [
    'username' => 'user1',
    'password' => 'passuser1',
    'gender'   => 1,
];

curl_setopt($ch, CURLOPT_URL,"http://localhost/ihotel/api.php");
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS,$post);

// in real life you should use something like:
// curl_setopt($ch, CURLOPT_POSTFIELDS, 
//          http_build_query(array('postvar1' => 'value1')));

// receive server response ...
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$server_output = curl_exec ($ch);

curl_close ($ch);


?>