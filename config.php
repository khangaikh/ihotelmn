<?php

    require 'js/parse/autoload.php';
    use Parse\ParseClient;

    $app_id = 'j13CpiqJOwsLbvpyAidYXqW4JcTn0cGZqeCGcd56';
    $rest_key = '8oxWBZ7LHEY9Zarmv4AtPYhIgJr6UQiUFHX31C8z';
    $master_key = '0SRUYzAOtx5nf98Ubupi0BaGpavysHdIC4PdLrCJ';
    ParseClient::initialize( $app_id, $rest_key, $master_key );
    
    function strhex($string) {
	  $hexstr = unpack('H*', $string);
	  return array_shift($hexstr);
	}

	function hexToStr($hex){
	    $string='';
	    for ($i=0; $i < strlen($hex)-1; $i+=2){
	        $string .= chr(hexdec($hex[$i].$hex[$i+1]));
	    }
	    return $string;
	}
?>
