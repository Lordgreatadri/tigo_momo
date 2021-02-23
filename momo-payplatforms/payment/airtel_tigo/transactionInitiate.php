<?php

	require_once 'db_file.php';
	/**
	* Tigo API integration
	*/
	class tigo 
	{	
		protected $userName;
  		protected $password;
  		protected $consumerID;
  		protected $webUser;
  		protected $wPassword;
  		protected $msisdn;
  		protected $country;
  		protected $merchant_Name;
  		protected $correlationID;
  		protected $notificationChannel;
  		
  		public    $transactionID;
  		protected $expiring_time;

  		private   $data_access_Obj;


  		function __construct () 
  		{
  			$data_access_Obj = new data_auth();
  			//get user credentials.............
		 	$user_user_detail = $data_access_Obj->fetch_user_details();
		 	$user_name = '';
		 	$user_password = '';
		 		
		 	foreach ($user_user_detail as $users) 
		 	{
		 		$user_name     = $users['user_name'];
		 		$user_password = $users['password'];	
		 		$webuser 	   = $users['webuser'];
		 		$web_password  = $users['web_password'];
		 	}

		 	//fetch customer details...............
		 	$customer_detail = $data_access_Obj->fetch_customer_details();
		 	$customer_id ='';
			$msisdn = '';
			$country ='';
			$merchant_name = '';
		 	foreach ($customer_detail as $customer) 
		 	{
		 		$customer_id   = $customer['customer_id'];
		 		$msisdn        = $customer['customer_account'];
				$country       = $customer['country'];
				$merchant_name = $customer['merchant_name'];
		 	}

    		$this->userName   = $user_name;     //"live_mw_mcontent";
			$this->password   = $user_password;    //"THYyT783!#Mcon";
			$this->consumerID = $customer_id;   //"mcontent";
			$this->webUser 	  = $webuser;    //"MOBILE_CONTENT";
			$this->wPassword  = $web_password;  //"Dealer@1357";
			$this->msisdn     = $msisdn;     //"233277231999";
			$this->country    = $country;
			$this->merchant_Name = $merchant_name;
			$this->notificationChannel = '2';

			// $this->correlationID = 'mcc_correlator';
			$this->expiring_time = '10';
		}

		// generate transaction id................
		public function generate_correlation_token()
    	{
	        $token_prefix = 'MCC';
	        $token_suffix = substr(md5(time()), 0, 20);
	        $full_token   = $token_prefix . $token_suffix;

	        $rand_ = rand(25, 9999999);
			$token = "2334".substr(time(), 0, 15);
			$correlator  = $token.$rand_;
	        return $correlator;//full_token
    	}

    	//generate transaction id..
    	public function generate_transaction_id()
    	{
	        //$token_prefix = '085';
	        $token_suffix = substr(md5(time()), 0, 32);
	        $full_token   = $token_suffix;//$token_prefix . 
	        return $full_token;
    	}

		public function sendRequest ($tigoNumber, $amount, $item, $transaction_id, $userReference, $password) 
		{
			try 
			{
				$data_handler_Obj = new data_auth();
				//get the correlation id for the initiator
				$this->correlationID = $this->generate_correlation_token();
				// var_dump($this->correlationID);
				$correlator = $this->correlationID ;

				//passing correlator id for transaction update...............
				if( ! isset($_SESSION['correlator']) ):
				 	session_start();
				endif;
				
				$_SESSION['correlator'] = $correlator;
				$pass_value = $data_handler_Obj->get_initiator_id($correlator);


				//saving the initiator details..............................				
				$initiate_date    = date('Y-m-d');

				$save_initiator   = $data_handler_Obj->save_new_initiator($correlator, $tigoNumber, $amount, $item, $transaction_id, $userReference, $initiate_date);

				$createdTime = date("Y-m-d");


				$Username = "live_mw_mcontent";
				$Password = "THYyT783!#Mcon";
				$consumerID = "mcontent";
				$country1   = "GHA";
				$webUser    = "MOBILE_CONTENT";
				$webPassword = "Dealer@1357";

				$url = "https://accessgw.tigo.com.gh:8443/live/PurchaseInitiate";
				$data = '<?xml version="1.0" encoding="UTF-8"?>
						<soapenv:Envelope
						    xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/"
						    xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
						    <soapenv:Header>
						        <wsse:Security
						            soapenv:actor="http://schemas.xmlsoap.org/soap/actor/next"
						            soapenv:mustUnderstand="0" xmlns:wsse="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd">
						            <wsse:UsernameToken>
						                <wsse:Username>'.trim($Username).'</wsse:Username>
						                <wsse:Password>'.trim($Password).'</wsse:Password>
						            </wsse:UsernameToken>
						        </wsse:Security>
						    </soapenv:Header>
						    <soapenv:Body>
						        <PurchaseInitiateRequest xmlns="http://xmlns.tigo.com/MFS/PurchaseInitiateRequest/V1">
						            <ns1:RequestHeader xmlns:ns1="http://xmlns.tigo.com/RequestHeader/V3">
						                <ns1:GeneralConsumerInformation>
						                    <ns1:consumerID>'.trim($consumerID).'</ns1:consumerID>
						                    <ns1:transactionID>'.trim($transaction_id).'</ns1:transactionID>
						                    <ns1:country>'.trim($country1).'</ns1:country>
						                    <ns1:correlationID>'.trim($this->correlationID).'</ns1:correlationID>
						                </ns1:GeneralConsumerInformation>
						            </ns1:RequestHeader>
						            <requestBody>
						                <customerAccount>
						                    <msisdn>'.trim($tigoNumber).'</msisdn>
						                </customerAccount>
						                <initiatorAccount>
						                    <msisdn>'.trim($this->msisdn).'</msisdn>
						                </initiatorAccount>
						                <password>'.trim($password).'</password>
						                <paymentReference>'.trim($userReference).'</paymentReference>
						                <externalCategory>default</externalCategory>
						                <externalChannel>airtel-tigo-gh</externalChannel>
						                <webUser>'.trim($webUser).'</webUser>
						                <webPassword>'.trim($webPassword).'</webPassword>
						                <merchantName>'.trim($this->merchant_Name).'</merchantName>
						                <itemName>'.trim($item).'</itemName>
						                <amount>'.trim($amount).'</amount>
						                <minutesToExpire>'.trim($this->expiring_time).'</minutesToExpire>
						                <notificationChannel>2</notificationChannel>
						            </requestBody>
						        </PurchaseInitiateRequest>
						    </soapenv:Body>
						</soapenv:Envelope>';

						file_put_contents("logs/request_-$createdTime.xml", print_r($data, true));


				$curl = curl_init();
		  		curl_setopt($curl, CURLOPT_URL, $url);
				curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: text/xml', 'SOAPaction: http://xmlns.tigo.com/Service/PurchaseInitiate/V1/PurchaseInitiatePortType/PurchaseInitiateRequest','Content-Length: ' . strlen($data),'Cache-Control: no-cache','Pragma: no-cache'));

				curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
				curl_setopt($curl, CURLOPT_HEADER, 0);
		    	curl_setopt($curl, CURLOPT_POST, 1);
		    	curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
		    	// curl_setopt($curl, "https://mycloud.com.gh/payment/airtel_tigo/tigo_request_endpoint.php");

		    	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		    	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
				curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);

				curl_setopt($curl, CURLOPT_SSLCERT, getcwd().'/ag_partner/ag_partner.pem');
		    	curl_setopt($curl, CURLOPT_SSLCERTPASSWD, 'tigo123!');

				if (curl_exec($curl)) 
				{
					$response = curl_exec($curl);
				} 
				else 
				{
					$response = curl_error($curl);
		    	}
				
				curl_close($curl);
				return $response;

				$createdTime = date("Y-m-d");
	            $file        = fopen("logs/trans_response-$createdTime.log", 'a');
	            $request_log = '[ '.$response." ], \n";
	            fwrite($file, "$request_log");
	            fclose($file);


	            $createdTime = date("Y-m-d");
	            $files        = fopen("logs/request_data-$createdTime.log", 'a');
	            $request_data = '[ '.$data." ], \n";
	            fwrite($files, "$request_data");
	            fclose($files);
			} catch (Exception $exc) 
			{
				echo __LINE__ . $exc->getMessage();
			}			
		}
	
	
}
?>
