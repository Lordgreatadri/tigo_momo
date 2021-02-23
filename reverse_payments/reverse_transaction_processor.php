<?php 
	include_once 'includes/autoloader.inc.php';

/**------------------------------------------------------------------------------------------------------------------------------------------------
 * @@Name:              reverse process_request
 
 * @@Author: 			Lordgreat - Adri Emmanuel <'rexmerlo@gmail.com'>
 
 * @Date:   			2018-09-27 11:00:30
 * @Last Modified by:   Lordgreat -  Adri Emmanuel
 * @Last Modified time: 2021-02-15 02:35:30

 * @Copyright: 			MobileContent.Com Ltd <'owner'>
 
 * @Website: 			https://mobilecontent.com.gh
 *---------------------------------------------------------------------------------------------------------------------------------------------------
 */

// $_POST['RequestTransactionId']     = 'H4YhZ9v16134717794d5e9475ef11e1583dd79c914d2e24c3';//previous transaction ID in purchase/payment request you initiated
// $_POST['RequestCorrelatorId']      = "23324860299815202102161036192431294";//previous correlator ID in purchase/payment request you initiated
// $_POST['UtibaTransactionId']       = 'MM210216.1045.D02408'; //unique mfs id received in the purchase/payment callback
// $_POST['PaymentReference']         = "123408989877855652413456"; //paymentReference for the previous transaction to be reversed
// $_POST['ApiType']                  = "purchase"; //this should be the payment type to be reversed {purchase/payment}
// $_POST['AuthorizationCode'] = "MskwHaW9(2021028M";

// $_POST['Network'] = "AIRTEL-TIGO-GH";

if($_SERVER['REQUEST_METHOD'] == 'POST') 
{
	// create an intance of the data handler model...............
	$transaction_Obj  = new Reverse_Tigo_Payments();
	$data_handler_Obj = new Reverse_Transaction_Data_Auths();

	$authorization_code = $data_handler_Obj->get_authorization_code();

	if(trim($_POST['AuthorizationCode']) == trim($authorization_code)) 
	{		

		if(isset($_POST['ApiType']) && isset($_POST['RequestTransactionId']) && isset($_POST['UtibaTransactionId']) && !empty($_POST['PaymentReference']) && isset($_POST['Network']) && $_POST['Network'] == "AIRTEL-TIGO-GH") 
		{
			$utibaTransactionId     = htmlspecialchars(trim($_POST['UtibaTransactionId']));
			$apiType                = htmlspecialchars(trim($_POST['ApiType']));
			$request_transaction_id = htmlspecialchars(trim($_POST['RequestTransactionId']));
			$request_correlator_id  = htmlspecialchars(trim($_POST['RequestCorrelatorId']));
			$reverseReference       = htmlspecialchars(trim($_POST['PaymentReference'])); //get it from user later............ 
			$transactionType        = "CANCEL TRANSACTION"; //This field signifies the type of transaction which has to be cancelled

			$randomize = rand(1000000000,100);
			//"MCC-TIGO-".$randomize;


			#pass the tranasctionid
			$unique_transaction_id = $transaction_Obj->generate_transaction_id();


			$response_value = $transaction_Obj->reverseTransactionRequest($request_correlator_id, $request_transaction_id, $transactionType, $apiType, $utibaTransactionId, $reverseReference);
			 // var_dump($response_value);


			file_put_contents("reversecash.xml",$response_value);//024cfcf2-6d55-4e48-b09f-cd152c273201

			//reload extracted xlm response file................*713*1#
			$requestXML = simplexml_load_file("reversecash.xml");

			$requestXML->registerXPathNamespace('v3', 'http://xmlns.tigo.com/ResponseHeader/V3');
			$requestXML->registerXPathNamespace('cor', 'http://soa.mic.co.af/coredata_1');
			$requestXML->registerXPathNamespace('v1', 'http://xmlns.tigo.com/MFS/ReverseTransactionResponse/V1');

			//if the return is error, get values............
			$requestXML->registerXPathNamespace('cmn', 'http://xmlns.tigo.com/ResponseHeader/V3');

			//fetch value from xml file to variable.....................
			$SOATransactionID	= $requestXML->xpath('//cor:SOATransactionID/text()');
			$correlationID      = $requestXML->xpath('//v3:correlationID/text()');
			$status				= $requestXML->xpath('//v3:status/text()');
			$code 				= $requestXML->xpath('//v3:code/text()');
			$description        = $requestXML->xpath('//v3:description/text()');
			$transactionId 		= $requestXML->xpath('//v1:transactionId/text()');
			// $paymentReference   = $requestXML->xpath('//v1:paymentReference/text()');

			//error extraction script.................
			$correlation_id     = $requestXML->xpath('//cmn:correlationID/text()');
			$descriptions     	= $requestXML->xpath('//cmn:description/text()');
			$codes				= $requestXML->xpath('//cmn:code/text()');
			$ini_status			= $requestXML->xpath('//cmn:status/text()');
			

			$SOATransactionID = $SOATransactionID[0];
			$correlationID    = $correlationID[0];
			$status		      = $status[0];
			$code 		      = $code[0];
			$description      = $description[0];
			$UtibtransactionId= $transactionId[0];
			

			$correlation_id    = $correlation_id[0];
			$error_descriptions= $descriptions[0];
			$error_codes       = $codes[0];
			$error_status	   = $ini_status[0];


			//get the initiator correlation id......................
			// $correlator_value$data_handler_Obj->
			if( ! isset( $_SESSION['correlator'] ) ):
			  session_start();
			endif;

			$correlator_value = $_SESSION['correlator'];


			//check if process was successful or error occured..................
			$Correlation = "";
		    $Descriptions= "";
			$Codes       = "";
			$Status		 = "";

		    if(empty($correlation_id)) // && empty($error_descriptions)
		    {
		    	$Correlation = $correlation_id;
		    	$Descriptions= $error_descriptions;
				$Codes       = $error_codes;
				$Status		 = $error_status;
		    } else 
		    {
		    	$Correlation = $correlationID;
		    	$Descriptions= $description;
				$Codes       = $code;
				$Status		 = $status;
		    }



		    //save_transaction_response        
			$save_reverse = 0;
			$save_reverse = $data_handler_Obj->save_reverse_transaction($unique_transaction_id, $SOATransactionID, $Correlation, $request_transaction_id, $transactionType, $reverseReference, $apiType, $Status, $Codes, $Descriptions, $UtibtransactionId);



			$createdTime = date("Y-m-d");
			$initiate_date    = date("Y-m-d H:i:s");
		    $file        = fopen("logs/request_respone_data-$createdTime.log", 'a');
		    $request_log = "transId=>$unique_transaction_id,  extransaction_id : $SOATransactionID, correlationID: $Correlation , request_transaction_id: $request_transaction_id, apiType : $apiType, status: '".$Status."', ResponseCode: '".$Codes."', description: '".$Descriptions."', paymentReference: $reverseReference, initiate_date: '".$initiate_date."' \n";
		    fwrite($file, "$request_log");
		    fclose($file);

		    unset($_SESSION['correlator']);


		    $reviewd_data       = $data_handler_Obj->get_current_transaction_details($unique_transaction_id);
			$transaction_id     = "";
			$external_transaction_id = "";
			$request_correlator_id   = "";
			$request_transaction_id  = "";
			$transaction_type   = "";
			$payment_reference  = "";
			$api_type           = "";
			$StatusVal          = "";
			$response_code      = "";
			$description        = "";
			$UtibtransactionId  = ""; 

		    foreach($reviewd_data as $key) 
		    {	
		    	$transaction_id          = $key["transaction_id"];
				$external_transaction_id = $key["external_transaction_id"];
				$request_correlator_id   = $key["request_correlator_id"];
				$request_transaction_id  = $key["request_transaction_id"];
				$transaction_type        = $key["transaction_type"];
				$payment_reference       = $key["payment_reference"];
				$api_type                = $key["api_type"];
				$StatusVal               = $key["status"];
				$response_code           = $key["response_code"];
				$description             = $key["description"];
		    	$UtibtransactionId       = $key["utiba_transaction_id"];
		    }


		    #Father Lordgreat...):.. life is hard... care must be taken not to loose fund unaccountably status
		    if(trim($StatusVal) == "OK") 
		    {
		    	#Eeiii wait o man, you should check what type of transaction this is first oo lol.....
		    	if(trim($apiType) == "payment") 
		    	{
		    		#if it is for payment then make sure this transaction is not calculated as available payment fund...
		    		$data_handler_Obj->reversePaymentFund($request_correlator_id, $utibaTransactionId);
		    	} else {
		    		$data_handler_Obj->reversePurchaseFund($request_correlator_id, $utibaTransactionId);

		    		$data_handler_Obj->reversecallbackStatus($request_correlator_id, $utibaTransactionId);
		    	}
		    	
		    } 




		    // feedback response to user..............

		    $feedback = array();
		    $response['Success'] = TRUE;
		    $response['ResponseCode']         = $response_code;
		    $feedback['Network']              = "AIRTEL-TIGO-GH";
		    $feedback['TransactionId']        = $transaction_id;
		    $feedback['RequestCorrelationId'] = $request_correlator_id;
		    $feedback['RequestTransactionId'] = $request_transaction_id;		    
		    $feedback['ExternalTransactionId'] = $external_transaction_id;
		    $feedback['TransactionType']      = $transaction_type;
		    $feedback['Status']               = $StatusVal;
		    $feedback['ReversedTransactionId']= $UtibtransactionId;
		    $feedback['Description']          = $description;
		    $feedback['ApiType']              = $api_type;
		    $feedback['PaymentReference']     = $payment_reference;
		    $feedback['TransactionDate']      = date("Y-m-d H:i:s");
		    $response['Data']                 = $feedback;
		    header('Content-Type: application/json');
			echo json_encode($response);

		}

	}else 
	{
		
	    $feedback = array();
	    $response['Success'] = "Failed";
	    $feedback['ResponseCode'] = "2001";
	    $feedback['Description'] = "Non authorized request";
	    $response['Data'] = $feedback;
	    header('Content-Type: application/json');
    	echo json_encode($response);
	}	

}
	
?>