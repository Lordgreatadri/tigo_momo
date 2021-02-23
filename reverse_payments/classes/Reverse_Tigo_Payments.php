<?php 

/**------------------------------------------------------------------------------------------------------------------------------------------------
 * @@Name:              Reverse_Tigo_Payments file
 
 * @@Author: 			Lordgreat - Adri Emmanuel <'rexmerlo@gmail.com'>
 
 * @Date:   			2018-09-27 11:00:30
 * @Last Modified by:   Lordgreat -  Adri Emmanuel
 * @Last Modified time: 2018-09-27 02:35:30

 * @Copyright: 			MobileContent.Com Ltd <'owner'>
 
 * @Website: 			https://mobilecontent.com.gh
 *---------------------------------------------------------------------------------------------------------------------------------------------------
 */
	include_once 'includes/autoloader.inc.php';



	/**
	 * 
	 */
	class Reverse_Tigo_Payments //extends MCC_CI_Database
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

  		protected $paymentReference;
  		protected $payerPin;
  		protected $reverse_transaction_url;


  		function __construct() 
  		{
  			$data_access_Obj = new Reverse_Transaction_Data_Auths();

		 	$customer_id   ='';
			$msisdn        = '';
			$country       ='';
			$merchant_name = '';
			$authorizationCode = "";
			$reverse_transaction_url = "";
			$payerPin      = "";
			$web_password  = "";
			$webuser 	   = "";
			$user_password = "";
			$user_name     = "";

		 	foreach ($data_access_Obj->fetch_customer_details() as $customer) 
		 	{
		 		$customer_id   = $customer['consumer_id'];
		 		$msisdn        = $customer['customer_account'];
				$country       = $customer['country'];
				$merchant_name = $customer['merchant_name'];
				$user_name     = $customer['username'];
		 		$user_password = $customer['userpassword'];	
		 		$webuser 	   = $customer['webuser'];
		 		$web_password  = $customer['web_password'];
		 		$authorizationCode   = $customer['authorization_code'];
		 		$reverse_transaction_url = $customer['reverse_transaction_url'];
		 	}

    		$this->userName   = $user_name;     //" ";
			$this->password   = $user_password;    //"THYyT783!#Mcon";
			$this->consumerID = $customer_id;   //" ";
			$this->webUser 	  = $webuser;    //" ";
			$this->wPassword  = $web_password;  //"Dealer@1357";
			$this->msisdn     = $msisdn;     //" ";
			$this->country    = $country;
			$this->merchant_Name = $merchant_name;
			$this->notificationChannel = '2';
			$this->AuthorizationCode = $authorizationCode;

			$this->reverse_transaction_url = $reverse_transaction_url;


			$this->expiring_time = '20';
		}



		//generate transaction id..
    	public function generate_transaction_id()
    	{
	        $used_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';	
			$txn_ref = $txn.substr(str_shuffle($used_chars), 0, 7).time();

	        $token_suffix = substr(md5(time()), 0, 32);

	        $trans = $txn_ref.$token_suffix;
	        return $trans;
    	}


		//process reverse request....................
		#REVERSE TRANSACTION REQUEST..............
		public function reverseTransactionRequest($request_correlator_id, $request_transaction_id, $transactionType, $apiType, $utibaTransactionId, $reverseReference)
		{
			try 
			{
				
				//passing correlator id for transaction update...............
				if( ! isset($_SESSION['correlator']) ):
				 	session_start();
				endif;
				
				$_SESSION['correlator'] = $request_correlator_id;


				$data = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:cor="http://soa.mic.co.af/coredata_1">
				   <soapenv:Header xmlns:wsse="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd">
				      <cor:debugFlag>true</cor:debugFlag>
				      <wsse:Security>
				         <wsse:UsernameToken>
				            <wsse:Username>'.trim($this->userName).'</wsse:Username>
				            <wsse:Password>'.trim($this->password).'</wsse:Password>
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
				            <v1:AuthorizationCode>'.trim($this->AuthorizationCode).'</v1:AuthorizationCode>
				         </v1:requestBody>
				      </v1:ReverseTransactionRequest>
				   </soapenv:Body>
				</soapenv:Envelope>';

				$createdTime = date("Y-m-d");
				file_put_contents("logs/reverseData-$createdTime.xml", print_r($data, true));


				$curl = curl_init();
		  		curl_setopt($curl, CURLOPT_URL, $this->reverse_transaction_url);
				curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: text/xml', 'SOAPaction: https://41.215.168.138:4443/osb/services/ReverseTransaction_2_0','Content-Length: ' . strlen($data),'Cache-Control: no-cache','Pragma: no-cache'));

				curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
				curl_setopt($curl, CURLOPT_HEADER, 0);
		    	curl_setopt($curl, CURLOPT_POST, 1);
		    	curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

		    	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		    	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
				curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);

				curl_setopt($curl, CURLOPT_SSLCERT, getcwd().'/ag_partner/ag_partner.pem');
		    	curl_setopt($curl, CURLOPT_SSLCERTPASSWD, 'tigo123!');
				
				$response = curl_exec($curl);
				$error    = curl_error($curl);
				curl_close($curl);				

				$createdTime = date("Y-m-d");
	            $file        = fopen("logs/reverse_response-$createdTime.xml", 'a');
	            $request_log = '[ '.$response." ], \n";
	            fwrite($file, "$request_log");
	            fclose($file);


	            $createdTime = date("Y-m-d");
	            $files        = fopen("logs/reverse_Reqdata-$createdTime.xml", 'a');
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