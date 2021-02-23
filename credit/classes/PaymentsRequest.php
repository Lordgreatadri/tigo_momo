
<?php 
    /**------------------------------------------------------------------------------------------------------------------------------------------------
 * @@Name:              PaymentsRequest file
 
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
	class PaymentsRequest 
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


  		protected $service_payment_url;


  		function __construct () 
  		{
  			$data_access_Obj = new Payment_Data_Auths();

		 	$customer_id   ='';
			$msisdn        = '';
			$country       ='';
			$merchant_name = '';
			$authorizationCode = "";
			$service_payment_url = "";
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
		 		$service_payment_url = $customer['service_payment_url'];
		 	}

    		$this->userName   = $user_name;     //"";
			$this->password   = $user_password;    //"THYyT783!#Mcon";
			$this->consumerID = $customer_id;   //"";
			$this->webUser 	  = $webuser;    //"";
			$this->wPassword  = $web_password;  //"Dealer@1357";
			$this->msisdn     = $msisdn;     //"";
			$this->country    = $country;
			$this->merchant_Name = $merchant_name;
			$this->notificationChannel = '2';
			$this->AuthorizationCode = $authorizationCode;
			$this->payerPin = $payerPin;

			$this->service_payment_url = $service_payment_url;


			$this->expiring_time = '20';
		}

		// generate transaction id................
		public function generate_correlation_token()
    	{
	        $token_prefix = 'MCC';
	        $token_suffix = substr(md5(time()), 0, 20);
	        $full_token   = $token_prefix . $token_suffix;


	        $uniqueVal    = date("YmdHis");
	        $paymentRef   = rand(1000000000,100);
	        $rand_        = rand(25, 9999999);
			$token        = "2334".$paymentRef;


			$correlator   = $token.$uniqueVal.$rand_;
	        return $correlator;
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




    	// format the user msisdn to the required format...........
	    public function _formart_number($voter_number)
	    {
	        $myNew_value = null;
	        $voting_number = '';
	        if(strlen($voter_number) == 10)
            {   
                //convert your string into array
                $array_num = str_split($voter_number);

                for($i = 1; $i <count($array_num) ; $i++)
                {        
                    $myNew_value .= $array_num[$i];
                }
                 
                $voting_number = '233'. $myNew_value;
            }else
            {
                $voting_number = $voter_number; 
            }

	        return $voting_number;
	    }


    	// process request............................
    	public function sendPaymentRequest($paymentReference, $targetNumber, $amount, $unique_transaction_id)
    	{
    		try 
			{
				//get the correlation id for the initiator
				$this->correlationID = $this->generate_correlation_token();
				
				//passing correlator id for transaction update...............
				if( ! isset($_SESSION['correlator']) ):
				 	session_start();
				endif;
				
				$_SESSION['correlator'] = $this->correlationID ;



				// $unique_value = date("YmdHis");

				$data = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:v1="http://xmlns.tigo.com/MFS/PaymentsRequest/V1" xmlns:v3="http://xmlns.tigo.com/RequestHeader/V3" xmlns:v2="http://xmlns.tigo.com/ParameterType/V2" xmlns:cor1="http://soa.mic.co.af/coredata_1">
				   <soapenv:Header xmlns:wsse="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd">
				      <cor1:debugFlag>true</cor1:debugFlag>
				      <wsse:Security>
				         <wsse:UsernameToken>
				            <wsse:Username>'.trim($this->userName).'</wsse:Username>
				            <wsse:Password>'.trim($this->password).'</wsse:Password>
				         </wsse:UsernameToken>
				      </wsse:Security>
				   </soapenv:Header>
				   <soapenv:Body>
				      <v1:payBillRequest xmlns:v1="http://xmlns.tigo.com/MFS/PaymentsRequest/V1">
				         <v3:RequestHeader>
				            <v3:GeneralConsumerInformation>
				               <v3:consumerID>'.trim($this->consumerID).'</v3:consumerID>				                
				               <v3:transactionID>'.trim($unique_transaction_id).'</v3:transactionID>
				               <v3:country>GHA</v3:country>
				               <v3:correlationID>'.trim($this->correlationID).'</v3:correlationID>
				            </v3:GeneralConsumerInformation>
				         </v3:RequestHeader>
				         <v1:requestBody>
				            <v1:paymentreference>'.trim($paymentReference).'</v1:paymentreference>
				            <v1:sourceWallet>				               
				               <v1:msisdn>'.trim($this->msisdn).'</v1:msisdn>
				            </v1:sourceWallet>
				            <v1:targetWallet>				               
				               <v1:msisdn>'.trim($targetNumber).'</v1:msisdn>
				            </v1:targetWallet>				             
				            <v1:pin>'.trim($this->payerPin).'</v1:pin>				             
				            <v1:companyId></v1:companyId>
				            <v1:authorizationcode>'.trim($this->AuthorizationCode).'</v1:authorizationcode>
				            <v1:amount>'.trim($amount).'</v1:amount>
				         </v1:requestBody>
				      </v1:payBillRequest>
				   </soapenv:Body>
				</soapenv:Envelope>';


				$createdTime = date("Y-m-d");
				file_put_contents("logs/Paymentrequest-$createdTime.xml", print_r($data, true));


				$curl = curl_init();
		  		curl_setopt($curl, CURLOPT_URL, $this->service_payment_url);
				curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: text/xml', 'SOAPaction: https://41.215.168.138:4443/osb/services/Payments_3_0','Content-Length: ' . strlen($data),'Cache-Control: no-cache','Pragma: no-cache'));

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

				curl_close($curl);
				

				$createdTime = date("Y-m-d");
	            $file        = fopen("logs/trans_response-$createdTime.xml", 'a');
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