<?php 

	include_once 'includes/autoloader.inc.php';
	/**------------------------------------------------------------------------------------------------------------------------------------------------
	 * @@Name:              GetPurchaseTransFunctions
	 
	 * @@Author: 			Lordgreat - Adri Emmanuel <'rexmerlo@gmail.com'>
	 
	 * @Date:   			2018-09-27 11:00:30
	 * @Last Modified by:   Lordgreat -  Adri Emmanuel
	 * @Last Modified time: 2021-02-02 02:35:30

	 * @Copyright: 			MobileContent.Com Ltd <'owner'>
	 
	 * @Website: 			https://mobilecontent.com.gh
	 *---------------------------------------------------------------------------------------------------------------------------------------------------
	 */

	class GetPurchaseTransFunctions //extends get_purchase_trans_functions
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
  		protected $paymentReference;

  		protected $payerPin;
  		
  		public    $transactionID;
  		protected $expiring_time;
  		protected $AuthorizationCode;
  		private   $data_access_Obj;


  		protected $get_purchase_details_url;


  		function __construct () 
  		{
  			$data_access_Obj = new Get_Purchase_Trans_Data_Auths();
  			
		 	$customer_id   ='';
			$msisdn        = '';
			$country       ='';
			$merchant_name = '';
			$authorizationCode = "";
			$get_purchase_details_url = "";
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
				$payerPin      = $customer['mw_code'];
				$user_name     = $customer['username'];
		 		$user_password = $customer['userpassword'];	
		 		$webuser 	   = $customer['webuser'];
		 		$web_password  = $customer['web_password'];
		 		$authorizationCode   = $customer['authorization_code'];
		 		$get_purchase_details_url = $customer['get_purchase_details_url'];
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
			$this->payerPin = $payerPin;

			$this->get_purchase_details_url = $get_purchase_details_url;


			$this->expiring_time = '20';
		}

		// generate transaction id................
		public function generate_correlation_token()
    	{
	        $uniqueVal    = date("YmdHis");
	        $paymentRef   = rand(1000000000,1000);
	        $rand_        = rand(25, 9999999);
			$token        = "233244".$paymentRef;


			$correlator   = $token.$uniqueVal.$rand_;
	        return $correlator;//full_token
    	}

    	//generate transaction id..
    	public function generate_transaction_id()
    	{
	        //$token_prefix = '085';
	        $used_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';	
			$txn_ref = $txn.substr(str_shuffle($used_chars), 0, 7).time();

	        $token_suffix = substr(md5(time()), 0, 32);

	        $trans = $txn_ref.$token_suffix;
	        return $trans;
    	}





    	public function sendGetPurchaseDetailsRequest($userReference, $apiType, $transaction_id, $correlation_token)//$UserNumber, $targetNumber, $amount, $transaction_id, , $payer_pin
    	{
    		try 
			{
				//get the correlation id for the initiator
				$this->correlationID = $correlation_token; //$this->generate_correlation_token();
				// // var_dump($this->correlationID);

				$this->transactionID = $transaction_id; //$this->generate_transaction_id();
				// //passing correlator id for transaction update...............
				if( ! isset($_SESSION['correlator']) ):
				 	session_start();
				endif;
				
				$_SESSION['correlator'] = $this->correlationID;


				//saving the initiator details..............................				
				$initiate_date    = date('Y-m-d');

				$createdTime = date("YmdHis");

				$unique_value = date("YmdHis");
				

				$data = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:v1="http://xmlns.tigo.com/GetPurchaseTransDetailsRequest/V1" xmlns:v3="http://xmlns.tigo.com/RequestHeader/V3" xmlns:v2="http://xmlns.tigo.com/ParameterType/V2" xmlns:cor="http://soa.mic.co.af/coredata_1">
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
				      <v1:GetPurchaseTransDetailsRequest>
				         <v3:RequestHeader>
				            <v3:GeneralConsumerInformation>
				               <v3:consumerID>'.trim($this->consumerID).'</v3:consumerID> 
				               <v3:transactionID>'.trim($transaction_id).'</v3:transactionID>
				               <v3:country>GHA</v3:country>
				               <v3:correlationID>'.trim($correlation_token).'</v3:correlationID>
				            </v3:GeneralConsumerInformation>
				         </v3:RequestHeader>
				         <v1:requestBody>
				            <v1:customerAccount>				                
				               <v1:msisdn>'.trim($this->msisdn).'</v1:msisdn>
				            </v1:customerAccount>
				            <v1:paymentReference>'.trim($userReference).'</v1:paymentReference>
				            <v1:apiType>'.$apiType.'</v1:apiType>
				            <v1:AuthorizationCode>'.trim($this->AuthorizationCode).'</v1:AuthorizationCode>
				         </v1:requestBody>
				      </v1:GetPurchaseTransDetailsRequest>
				   </soapenv:Body>
				</soapenv:Envelope>';


				$createdTime = date("Y-m-d");
				file_put_contents("logs/getPurchaseData-$createdTime.xml", print_r($data, true));


				$curl = curl_init();
		  		curl_setopt($curl, CURLOPT_URL, $this->get_purchase_details_url);
				curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: text/xml', 'SOAPaction: https://41.215.168.138:4443/osb/services/GetPurchaseTransDetails_2_0','Content-Length: ' . strlen($data),'Cache-Control: no-cache','Pragma: no-cache'));

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
				$error = curl_error($curl);
				// echo $response;
				curl_close($curl);
				

				$createdTime = date("Y-m-d");
	            $file        = fopen("logs/getdetail_response-$createdTime.xml", 'a');
	            $request_log = '[ '.$response." ], \n";
	            fwrite($file, "$request_log");
	            fclose($file);


	            $createdTime = date("Y-m-d");
	            $files        = fopen("logs/request_data-$createdTime.xml", 'a');
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