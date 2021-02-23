<?php 
$message = "Test message now. tigo momo";
$msisdn = "233273593525";

	$message = urlencode($message);//200.2.168.175:2199 
	$url = "http://34.230.90.80:2789/Receiver?User=tv3quiz&Pass=T3Q3v3&From=1470&To=$msisdn&Text=$message";
	            // $url = "http://54.163.215.114:2788/Receiver?User=mycloudhttp&Pass=M1C2T3&From=1470&To=$msisdn&Text=$message";
	            $curl = curl_init();
	            curl_setopt_array($curl, array(
	                CURLOPT_RETURNTRANSFER => 1,
	                CURLOPT_URL => $url
	            ));
	           
	            $result = curl_exec($curl);
	            $error = curl_error($curl);

	            echo $result;

	            var_dump($result);
 ?>