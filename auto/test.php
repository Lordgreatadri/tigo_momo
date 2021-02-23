<?php 
	// require "includes/autoload.php";


	// $val = new class1();

	// $ge = $val->getname();

	// // echo $ge;




			$uniqueVal    = date("YmdHis");
	        $paymentRef   = rand(1000000000,1000);
	        $rand_        = rand(25, 9999999);
			$token        = "233244".$paymentRef;


			$correlator   = $token.$uniqueVal.$rand_;

			var_dump($correlator);


			$used_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	
			$txn_ref = substr(str_shuffle($used_chars), 0, 7).time();
			
	        $token_suffix = substr(md5(time()), 0, 32);
	        $full_token   = $token_suffix;//$token_prefix . 

	        $trans = $txn_ref.$token_suffix;
	        var_dump($txn_ref );


	        var_dump($trans);
 ?>