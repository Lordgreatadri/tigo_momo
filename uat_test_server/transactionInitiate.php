<?php

	require_once 'data_auth.php';
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
  		
  		protected $transactionID;
  		protected $expiring_time;
  		protected $AuthorizationCode;
  		private   $data_access_Obj;


  		function __construct () 
  		{
  			$data_access_Obj = new data_auth();
  			//get user credentials.............
		 	// $user_user_detail = $data_access_Obj->fetch_user_details();
		 	$user_name = '';
		 	$user_password = '';
		 		
		 	// foreach ($user_user_detail as $users) 
		 	// {
		 	// 	$user_name     = $users['user_name'];
		 	// 	$user_password = $users['password'];	
		 	// 	$webuser 	   = $users['webuser'];
		 	// 	$web_password  = $users['web_password'];
		 	// }

		 	//fetch customer details...............
		 	$customer_detail = $data_access_Obj->fetch_customer_details();
		 	$customer_id ='';
			$msisdn = '';
			$country ='';
			$merchant_name = '';
			$authorizationCode = "";

		 	foreach ($customer_detail as $customer) 
		 	{
		 		$customer_id   = $customer['consumer_id'];
		 		$msisdn        = $customer['customer_account'];
				$country       = $customer['country'];
				$merchant_name = $customer['merchant_name'];

				$user_name     = $customer['username'];
		 		$user_password = $customer['userpassword'];	
		 		$webuser 	   = $customer['webuser'];
		 		$web_password  = $customer['web_password'];
		 		$authorizationCode = $customer['authorization_code'];
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
			$this->AuthorizationCode = $authorizationCode;

			// $this->correlationID = 'mcc_correlator';
			$this->expiring_time = '20';
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

		public function sendRequest($unique_value, $tigoNumber, $amount, $item, $userReference) 
		{
			try 
			{
				$data_handler_Obj = new data_auth();
				//get the correlation id for the initiator
				$this->correlationID = $this->generate_correlation_token();

				$correlator = $this->correlationID;

				//passing correlator id for transaction update.........

				$this->transactionID = $this->generate_transaction_id();
				//saving the initiator details..............................				
				$initiate_date    = date('Y-m-d');

				$save_initiator   = $data_handler_Obj->save_new_initiator($unique_value, $tigoNumber, $amount, $item, $unique_value, $userReference, $initiate_date);

				$createdTime = date("Y-m-d");

				
				$Username         = "live_mw_mcontent";
				$Password         = "P@ssw0rd@12345";//THYyT783!#Mcon
				$consumerID       = "MContent";//"mcontent";
				$merchantName     = "Mobile Content";
				$country1         = "GHA";
				$webUser          = "MCONTENT_report";//MOBILE_CONTENT
				$webPassword      = "Cashme@21";//"Dealer@1357";
				$merchantNumber   ="233277231999"; //233277231999
				$AuthorizationCode = "bGl2ZV9td19tY29udGVudFBAc3N3MHJkQDEyMzQ1";

				$minutesToExpire  = "20";
				$tigoNumber       = "233273593525";
				$correlationID    = "2334".substr(time(), 0, 15).rand(25, 9999999);
				$transactionID    = substr(md5(time()), 0, 32);
				$paymentReference = rand(1000000000,100);


				if( ! isset($_SESSION['correlator']) ):
				 	session_start();
				endif;				
				$_SESSION['correlator'] = $unique_value;
				


				// $url = "https://accessgw.tigo.com.gh:8443/live/PurchaseInitiate";
				$url = "https://41.215.168.138:4443/osb/services/PurchaseInitiate_2_0";

				$data = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:v1="http://xmlns.tigo.com/MFS/PurchaseInitiateRequest/V1" xmlns:v3="http://xmlns.tigo.com/RequestHeader/V3" xmlns:v2="http://xmlns.tigo.com/ParameterType/V2">
					   <soapenv:Header xmlns:wsse="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd">
					      <cor:debugFlag xmlns:cor="http://soa.mic.co.af/coredata_1">true</cor:debugFlag>
					      <wsse:Security>
					         <wsse:UsernameToken>
					            <wsse:Username>'.trim($Username).'</wsse:Username>
					            <wsse:Password>'.trim($Password).'</wsse:Password>
					         </wsse:UsernameToken>
					      </wsse:Security>
					   </soapenv:Header>
					   <soapenv:Body>
					      <v1:PurchaseInitiateRequest>
					         <v3:RequestHeader>
					            <v3:GeneralConsumerInformation>
					               <v3:consumerID>'.trim($consumerID).'</v3:consumerID>
					               
					               <v3:transactionID>'.trim($unique_value).'</v3:transactionID>
					               <v3:country>'.$country1.'</v3:country>
					               <v3:correlationID>'.trim($unique_value).'</v3:correlationID>
					            </v3:GeneralConsumerInformation>
					         </v3:RequestHeader>
					         <v1:requestBody>
					            <v1:customerAccount>
					               
					               <v1:msisdn>'.trim($tigoNumber).'</v1:msisdn>
					            </v1:customerAccount>
					            <v1:initiatorAccount>
					               
					               <v1:msisdn>'.trim($merchantNumber).'</v1:msisdn>
					            </v1:initiatorAccount>
					            <v1:paymentReference>'.trim($userReference).'</v1:paymentReference>
					            
					            <v1:externalCategory>API Testing</v1:externalCategory>
					             
					            <v1:externalChannel>MContent Portal</v1:externalChannel>
					             
					            <v1:webUser>'.trim($webUser).'</v1:webUser>
					             
					            <v1:webPassword>'.trim($webPassword).'</v1:webPassword>
					             
					            <v1:merchantName>'.trim($merchantName).'</v1:merchantName>
					             
					            <v1:merchantMsisdn>'.trim($merchantNumber).'</v1:merchantMsisdn>
					             
					            <v1:itemName>'.trim($item).'</v1:itemName>
					            <v1:amount>'.trim($amount).'</v1:amount>
					             
					            <v1:minutesToExpire>'.trim($minutesToExpire).'</v1:minutesToExpire>
					            <v1:notificationChannel>2</v1:notificationChannel>
					               <v1:AuthorizationCode>'.trim($AuthorizationCode).'</v1:AuthorizationCode>
					         </v1:requestBody>
					      </v1:PurchaseInitiateRequest>
					   </soapenv:Body>
					</soapenv:Envelope>';

						file_put_contents("logs/transactions/InitiateRequest_-$createdTime.xml", print_r($data, true));


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

	            return $response;
			} catch (Exception $exc) 
			{
				echo __LINE__ . $exc->getMessage();
			}			
		}
	




		#REVERSE TRANSACTION REQUEST..............
		public function reverseTransactionRequest($request_correlator_id, $request_transaction_id, $transactionType, $apiType, $utibaTransactionId, $reverseReference)
		{
			try 
			{
				$data_handler_Obj = new data_auth();
				//get the correlation id for the initiator
				$this->correlationID = $this->generate_correlation_token();
				$correlator = $this->correlationID ;

				$this->$transactionID = $this->generate_transaction_id();
				//passing correlator id for transaction update...............
				if( ! isset($_SESSION['correlator']) ):
				 	session_start();
				endif;
				
				$_SESSION['correlator'] = $correlator;
				// $pass_value = $data_handler_Obj->get_initiator_id($correlator);


				//saving the initiator details..............................				

				// $save_reverse = $data_handler_Obj->save_reverse_transaction($this->$transactionID, $request_correlator_id, $request_transaction_id, $user_wallet, $amount, $transactionType, $reverseReference, $apiType);

				$createdTime = date("Y-m-d");


				// $Username = "live_mw_mcontent";
				// $Password = "THYyT783!#Mcon";
				// $consumerID = "mcontent";
				// $country1   = "GHA";
				// $webUser    = "MOBILE_CONTENT";
				// $webPassword = "Dealer@1357";

				$Username = "live_mw_mcontent";
				$Password = "P@ssw0rd@12345";//THYyT783!#Mcon
				$consumerID = "MContent";//"mcontent";
				$country1   = "GHA";
				$webUser    = "MCONTENT_report";//MOBILE_CONTENT
				$webPassword = "Cashme@21";//"Dealer@1357";
				$merchantNumber ="233277231999"; //233277231999
				$AuthorizationCode = "bGl2ZV9td19tY29udGVudFBAc3N3MHJkQDEyMzQ1";

				// $url = "https://accessgw.tigo.com.gh:8443/live/PurchaseInitiate";
				$url = "https://41.215.168.138:4443/osb/services/ReverseTransaction_2_0";
				$data = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:cor="http://soa.mic.co.af/coredata_1">
				   <soapenv:Header xmlns:wsse="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd">
				      <cor:debugFlag>true</cor:debugFlag>
				      <wsse:Security>
				         <wsse:UsernameToken>
				            <wsse:Username>'.trim($Username).'</wsse:Username>
				            <wsse:Password>'.trim($Password).'</wsse:Password>
				         </wsse:UsernameToken>
				      </wsse:Security>
				   </soapenv:Header>
				   <soapenv:Body>
				      <v1:ReverseTransactionRequest xmlns:v3="http://xmlns.tigo.com/RequestHeader/V3" xmlns:v1="http://xmlns.tigo.com/MFS/ReverseTransactionRequest/V1" xmlns:v2="http://xmlns.tigo.com/ParameterType/V2">
				         <v3:RequestHeader>
				            <v3:GeneralConsumerInformation>
				               <v3:consumerID>'.trim($this->consumerID).'</v3:consumerID>
				            
				               <v3:transactionID>'.trim($request_transaction_id).'</v3:transactionID>
				               <v3:country>GHA</v3:country>
				               <v3:correlationID>'.trim($request_correlator_id).'</v3:correlationID>
				            </v3:GeneralConsumerInformation>
				         </v3:RequestHeader>
				         <v1:requestBody>
				            <v1:userWallet>
				               
				               <v1:msisdn>'.trim($this->msisdn).'</v1:msisdn>
				            </v1:userWallet>
				            <v1:transactionId>'.trim($utibaTransactionId).'</v1:transactionId>
				            <v1:transactionType>'.trim($transactionType).'</v1:transactionType>
				            <v1:paymentReference>'.trim($reverseReference).'</v1:paymentReference>
				            <v1:apiType>'.trim($apiType).'</v1:apiType>
				            <v1:AuthorizationCode>'.trim($AuthorizationCode).'</v1:AuthorizationCode>
				         </v1:requestBody>
				      </v1:ReverseTransactionRequest>
				   </soapenv:Body>
				</soapenv:Envelope>';

				$createdTime = date("Y-m-d");
				file_put_contents("logs/reverse/reverseData-$createdTime.xml", print_r($data, true));


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

				// if (curl_exec($curl)) 
				// {
				// 	$response = curl_exec($curl);
				// } 
				// else 
				// {
				// 	$response = curl_error($curl);
		  //   	}
				
				$response = curl_exec($curl);
				curl_close($curl);
				

				$createdTime = date("Y-m-d");
	            $file        = fopen("logs/reverse/reverse_response-$createdTime.log", 'a');
	            $request_log = '[ '.$response." ], \n";
	            fwrite($file, "$request_log");
	            fclose($file);


	            $createdTime = date("Y-m-d");
	            $files        = fopen("logs/reverse/reverse_data-$createdTime.log", 'a');
	            $request_data = '[ '.$data." ], \n";
	            fwrite($files, "$request_data");
	            fclose($files);

	            return $response;
			} catch (Exception $exc) 
			{
				echo __LINE__ . $exc->getMessage();
			}		
		}
	
	}
?>
