<?php
echo "hello hubtel.......";
$number = '0543645688';

$receive_momo_request = array(
          'CustomerName' => 'GA VOTE',
		  'CustomerMsisdn'=> $number,//'0273206468',
		  'CustomerEmail'=> 'info@mobilecontent.com.gh',
		  'Channel'=> 'mtn-gh',
		  'Amount'=> 1.00,
		  'PrimaryCallbackUrl'=> 'http://139.162.242.100/adri/gadangme_beauty_pegeant/payment-api/momo/receive_pay_callback.php', 
		  'Description'=> 'GA VOTE TRANSACTION',
		  'ClientReference'=> '23214',
		);



		//API Keys

		$clientId = 'MQrOl8B';
		$clientSecret = 'bb2deaa043434aae8bfcb39569278a8d';
		//$clientId = 'JoWp0R';
		//$clientSecret = '59b9caba-3f7a-437e-834c-81f5f67e14ed';
		$basic_auth_key =  'Basic ' . base64_encode($clientId . ':' . $clientSecret);
		$request_url = 'https://rmp.hubtel.com/merchantaccount/merchants/HM0102180009/receive/mobilemoney/';
		$receive_momo_request = json_encode($receive_momo_request);

		$ch =  curl_init($request_url);  
				curl_setopt( $ch, CURLOPT_POST, true );  
				curl_setopt( $ch, CURLOPT_POSTFIELDS, $receive_momo_request);  
				curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );  
				curl_setopt( $ch, CURLOPT_HTTPHEADER, array(
				    'Authorization: '.$basic_auth_key,
				    'Cache-Control: no-cache',
				    'Content-Type: application/json',
				  ));

		$result = curl_exec($ch); 
		$err = curl_error($ch);
		curl_close($ch);

		echo $result;