<?php 


	require_once 'db_file.php';
	/**
	* Tigo API integration
	*/
	class tigo_balance 
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
			$this->expiring_time = '30';
		}

		// generate transaction id................
		public function generate_transaction_token()
    	{
	        $token_prefix = 'MCC';
	        $token_suffix = substr(md5(time()), 0, 18);
	        $full_token   = $token_prefix . $token_suffix;
	        return $full_token;
    	}

    	//generate transaction id..
    	public function generate_transaction_id()
    	{
	        //$token_prefix = '085';
	        $token_suffix = substr(md5(time()), 0, 32);
	        $full_token   = $token_suffix;//$token_prefix . 
	        return $full_token;
    	}





    	public function send_balance_request($tigoNumber, $password, $e_wallet, $transaction_id) //0269425035 - terrance
    	{
    		try 
			{
				$data_handler_Obj = new data_auth();
				//get the correlation id for the initiator
				$this->correlationID = $this->generate_transaction_token();
				// var_dump($this->correlationID);
				$correlator = $this->correlationID ;

				//passing correlator id for transaction update...............
				if( ! isset( $_SESSION ) ):
				 	session_start();
				endif;
				
				$_SESSION['correlator'] = $correlator;
				$pass_value = $data_handler_Obj->get_initiator_id($correlator);


				//saving the initiator details..............................				
				$initiate_date = date('Y-m-d');

				$save_balance  = $data_handler_Obj->save_balance_request($correlator, $tigoNumber, $e_wallet, $transaction_id);

				// var_dump($save_initiator);
				// var_dump('balance: corelata ='.$this->correlationID.' number ='.$tigoNumber.' e_wallet = '.$e_wallet.' transaction_id ='.$transaction_id.')

// https://accessgw.tigo.com.gh:8443/osb/services/GetBalance_2_0
				//load balance request xml data file
				$url = " https://accessgw.tigo.com.gh:8443/osb/services/GetBalance_2_0";
				$data = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:v1="http://xmlns.tigo.com/MFS/GetBalanceRequest/V1" xmlns:v3="http://xmlns.tigo.com/RequestHeader/V3" xmlns:v2="http://xmlns.tigo.com/ParameterType/V2" xmlns:cor="http://soa.mic.co.af/coredata_1">
						<soapenv:Header xmlns:wsse="http://docs.oasisopen.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd">
						<cor:debugFlag>true</cor:debugFlag>
							<wsse:Security>
								<wsse:UsernameToken>
									<wsse:Username>'.$this->userName.'</wsse:Username>
									<wsse:Password>'.$this->password.'</wsse:Password>
								</wsse:UsernameToken>
							</wsse:Security>
						</soapenv:Header>
						<soapenv:Body>
							<v1:GetBalanceRequest>
								<v3:RequestHeader>
									<v3:GeneralConsumerInformation>
										<v3:consumerID>'.$this->consumerID.'</v3:consumerID>
										<!--Optional:-->
										<v3:transactionID>'.$transaction_id.'</v3:transactionID>
										<v3:country>'.$this->country.'</v3:country>
										<v3:correlationID>'.$this->correlationID.'</v3:correlationID>
									</v3:GeneralConsumerInformation>
								</v3:RequestHeader>
								<v1:requestBody>
									<v1:userWallet>
										<v1:msisdn>'.$tigoNumber.'</v1:msisdn>
									</v1:userWallet>
									<v1:password>'.$password.'</v1:password>
									<!--Optional:-->
									<v1:wallet>'.$e_wallet.'</v1:wallet>
								</v1:requestBody>
							</v1:GetBalanceRequest>
						</soapenv:Body>
					</soapenv:Envelope>';   //233271231234   0269425035

				$curl = curl_init();
		  		curl_setopt($curl, CURLOPT_URL, $url);
				curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: text/xml', 'SOAPaction: http://xmlns.tigo.com/Service/PurchaseInitiate/V1/PurchaseInitiatePortType/PurchaseInitiateRequest','Content-Length: ' . strlen($data),'Cache-Control: no-cache','Pragma: no-cache'));

				curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
				curl_setopt($curl, CURLOPT_HEADER, 0);
		    	curl_setopt($curl, CURLOPT_POST, 1);
		    	curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

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
	            $file        = fopen("log/balance_response-$createdTime.log", 'a');
	            $request_log = '[ '.$response." ], \n";
	            fwrite($file, "$request_log");
	            fclose($file);

			} catch (Exception $exc) 
			{
				echo __LINE__ . $exc->getMessage();
			}
    	}
    }

?>