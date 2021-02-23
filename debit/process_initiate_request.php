<?php 
	
include_once 'includes/autoloader.inc.php';
	
/**------------------------------------------------------------------------------------------------------------------------------------------------
 * @@Name:              purchase process_request
 
 * @@Author: 			Lordgreat - Adri Emmanuel <'rexmerlo@gmail.com'>
 
 * @Date:   			2018-09-27 11:00:30
 * @Last Modified by:   Lordgreat -  Adri Emmanuel
 * @Last Modified time: 2021-02-16 02:35:30

 * @Copyright: 			MobileContent.Com Ltd <'owner'>
 
 * @Website: 			https://mobilecontent.com.gh
 *---------------------------------------------------------------------------------------------------------------------------------------------------
 */


if($_SERVER['REQUEST_METHOD'] == 'POST') 
{
	// create an intance of the data handler model...............
	$transaction_Obj  = new TigoTransactionInitiateProcessor();
	$data_handler_Obj = new Tigo_Data_Auth();
	$authorization_code = $data_handler_Obj->get_authorization_code();

	// check for authorization............
	if(trim($_POST['AuthorizationCode']) == trim($authorization_code)) 
	{
		// check if required parameters are provided...........
		if(isset($_POST['CustomerMsisdn']) && isset($_POST['Amount']) && isset($_POST['Product']) && isset($_POST['AuthorizationCode'])  && isset($_POST['Network']) && $_POST['Network'] == "AIRTEL-TIGO-GH") 
		{

			$customer_msisdn  = htmlspecialchars(trim($_POST['CustomerMsisdn']));
			$amount = htmlspecialchars(trim($_POST['Amount']));
			$item   = htmlspecialchars(trim($_POST['Product']));
			$paymentReference = htmlspecialchars(trim($_POST['PaymentReference']));// get it from user later............ 


			
			#pass the tranasctionid
			$unique_transaction_id = $transaction_Obj->generate_transaction_id();


			$unique_value = date("YmdHis");

			// make the msisdn is in the right formart...............
			$msisdn = $transaction_Obj->_formart_number($customer_msisdn);


			$response_value   = $transaction_Obj->sendRequest($unique_transaction_id, $msisdn, $amount, $item, trim($paymentReference));//23327750573 6233577665092


			file_put_contents("tigocash.xml",$response_value);//024cfcf2-6d55-4e48-b09f-cd152c273201

			//reload extracted xlm response file................*713*1#
			$requestXML = simplexml_load_file("tigocash.xml");

			$requestXML->registerXPathNamespace('v31', 'http://xmlns.tigo.com/ResponseHeader/V3');
			$requestXML->registerXPathNamespace('cor', 'http://soa.mic.co.af/coredata_1');
			$requestXML->registerXPathNamespace('v11', 'http://xmlns.tigo.com/MFS/PurchaseInitiateResponse/V1');

			//if the return is error, get values............
			$requestXML->registerXPathNamespace('cmn', 'http://xmlns.tigo.com/ResponseHeader/V3');

			//fetch value from xml file to variable.....................
			$transactionID		= $requestXML->xpath('//cor:SOATransactionID/text()');
			$correlationID      = $requestXML->xpath('//v31:correlationID/text()');
			$status				= $requestXML->xpath('//v31:status/text()');
			$code 				= $requestXML->xpath('//v31:code/text()');
			$description        = $requestXML->xpath('//v31:description/text()');
			$paymentId 			= $requestXML->xpath('//v11:paymentId/text()');
			$paymentRef         = $requestXML->xpath('//v11:paymentReference/text()');

			//error saving script.................
			$correlation_id     = $requestXML->xpath('//cmn:correlationID/text()');
			$descriptions     	= $requestXML->xpath('//cmn:description/text()');
			$codes				= $requestXML->xpath('//cmn:code/text()');
			$ini_status			= $requestXML->xpath('//cmn:status/text()');


			$transactionID    = $transactionID[0];
			$correlationID    = $correlationID[0];
			$status		      = $status[0];
			$code 		      = $code[0];
			$description      = $description[0];
			$paymentId        = $paymentId[0];
			$paymentRef       = $paymentRef[0];

			$correlation_id   = $correlation_id[0];
			$descriptions     = $descriptions[0];
			$codes            = $codes[0];
			$ini_status		  = $ini_status[0];


			//get the initiator correlation id......................
			if( ! isset( $_SESSION['correlator'] ) ):
			  session_start();
			endif;
			
			$correlator_value = $_SESSION['correlator'];


			// $createdTime = date("Y-m-d");
		 //    $file        = fopen("logs/request_respone_data-$createdTime.log", 'a');
		 //    $request_log = "transaction_id : $transactionID, correlationID: $correlationID , msisdn: $msisdn, amount : $amount, item : $item, status: '".$status."', ResponseCode: '".$code."', description: '".$description."', paymentId: '".$paymentId."', paymentReference: $paymentReference, initiate_date: '".$createdTime."' \n";
		 //    fwrite($file, "$request_log");
		 //    fclose($file);

		    unset($_SESSION['correlator']);


		    $Correlation = "";
		    $Descriptions= "";
			$Codes       = "";
			$Status		 = "";
		    if($correlation_id) 
		    {
		    	$Correlation = $correlation_id[0];
		    	$Descriptions= $descriptions[0];
				$Codes       = $codes[0];
				$Status		 = $ini_status[0];
		    } else 
		    {
		    	$Correlation = $correlationID[0];
		    	$Descriptions= $description[0];
				$Codes       = $code[0];
				$Status		 = $status[0];
		    }


			//save_transaction_initiate_response        
		    $success = 0;
			$success = $data_handler_Obj->save_transaction_initiate_response($unique_transaction_id, $transactionID, $Correlation, $paymentRef, $msisdn, $item, $amount, $Status, $Codes, $Descriptions, $paymentId);


		    // insert contestants details here....................
		    // $Pcabinet_db->logUserPaymentRequest($unique_transaction_id, $transactionID, $Correlation, $Status, $Codes, $Descriptions, $paymentId, $unique_value, $contestant_num, $contestant_name, $cabinet, $msisdn, $amount);
  


			$CorrelationId 		= "";
		    $TransactionId 		= "";
		    $ExternalTransactionId = "";
		    $StatusVal     		= "";
		    $ResponseCode  		= "";
		    $Description   		= "";
		    $PaymentId     		= "";
		    $PaymentReference   = "";

		    foreach ($data_handler_Obj->fetch_transaction_detail_for_customer($Correlation) as $key) 
		    {
		    	$CorrelationId 		= $key["correlation_id"];
		    	$TransactionId 		= $key["transaction_id"];
		    	$ExternalTransactionId = $key["external_transaction_id"];
		    	$StatusVal     		= $key["initiate_status"];
		    	$ResponseCode  		= $key["initiate_code"];
		    	$Description   		= $key["initiate_description"];
		    	$PaymentId     		= $key["payment_id"];
		    	$PaymentReference   = $key["payment_reference"];
		    }
		    
		    $feedback = array();
		    $response['Success'] = TRUE;
		    $response['ResponseCode']  = $ResponseCode;
		    $feedback['Network']              = "AIRTEL-TIGO-GH";
		    $feedback['CorrelationId'] = $CorrelationId;
		    $feedback['TransactionId'] = $TransactionId;
		    $feedback['ExternalTransactionId'] = $ExternalTransactionId;
		    $feedback['Amount'] = $amount;
		    $feedback['Status'] = $StatusVal;
		    $feedback['Description'] = $Description;
		    $feedback['PaymentId'] = $PaymentId;
		    $feedback['PaymentReference'] = $PaymentReference;
		    $feedback['TransactionDate']  = date("Y-m-d H:i:s");
		    $response['Data'] = $feedback;
		    header('Content-Type: application/json');
		    echo json_encode($response);


		    // var_dump($feedback);
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