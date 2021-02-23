<?php 

include_once 'includes/autoloader.inc.php';
/**------------------------------------------------------------------------------------------------------------------------------------------------
 * @@Name:              payment_request_processor
 
 * @@Author: 			Lordgreat - Adri Emmanuel <'rexmerlo@gmail.com'>
 
 * @Date:   			2018-09-27 11:00:30
 * @Last Modified by:   Lordgreat -  Adri Emmanuel
 * @Last Modified time: 2018-09-27 02:35:30

 * @Copyright: 			MobileContent.Com Ltd <'owner'>
 
 * @Website: 			https://mobilecontent.com.gh
 *---------------------------------------------------------------------------------------------------------------------------------------------------
 */




if($_SERVER['REQUEST_METHOD'] == 'POST') 
{

	// create an intance of the data handler model...............
	$transaction_Obj    = new PaymentsRequest();
	$data_handler_Obj   = new Payment_Data_Auths();
	$authorization_code = $data_handler_Obj->get_authorization_code();

	if(trim($_POST['AuthorizationCode']) == trim($authorization_code)) 
	{
		// check if required value were provided...............
		if(isset($_POST['TargetNumber']) && isset($_POST['Amount']) && isset($_POST['PaymentReference']) && isset($_POST['Network']) && $_POST['Network'] == "AIRTEL-TIGO-GH") 
		{
			$targetNumber     = htmlspecialchars(trim($_POST['TargetNumber']));
			$amount           = htmlspecialchars(trim($_POST['Amount']));
			$ItemName         = htmlspecialchars(trim($_POST['Service']));
			$paymentReference = htmlspecialchars(trim($_POST['PaymentReference'])); //get it from user later............ 


			#pass the tranasctionid
			$unique_transaction_id   = $transaction_Obj->generate_transaction_id();

			$unique_value     = date("YmdHis");

			// make the msisdn is in the right formart...............
			$targetmsisdn = $transaction_Obj->_formart_number($targetNumber);


			$response_value   = $transaction_Obj->sendPaymentRequest($paymentReference, $targetmsisdn, $amount, $unique_transaction_id);


			file_put_contents("paymentRespo.xml",$response_value);//024cfcf2-6d55-4e48-b09f-cd152c273201

			//reload extracted xlm response file................*713*1#
			$requestXML = simplexml_load_file("paymentRespo.xml");

			$requestXML->registerXPathNamespace('v31', 'http://xmlns.tigo.com/ResponseHeader/V3');
			$requestXML->registerXPathNamespace('cor', 'http://soa.mic.co.af/coredata_1');
			$requestXML->registerXPathNamespace('v11', 'http://xmlns.tigo.com/MFS/PaymentsResponse/V1');

			//if the return is error, get values............
			$requestXML->registerXPathNamespace('cmn', 'http://xmlns.tigo.com/ResponseHeader/V3');

			//fetch value from xml file to variable.....................
			$transactionID		= $requestXML->xpath('//cor:SOATransactionID/text()');
			$correlationID      = $requestXML->xpath('//v31:correlationID/text()');
			$status				= $requestXML->xpath('//v31:status/text()');
			$code 				= $requestXML->xpath('//v31:code/text()');
			$description        = $requestXML->xpath('//v31:description/text()');

			$result_transaction_id  = $requestXML->xpath('//v11:transactionId/text()');
			// $utibaTransactionId = $requestXML->xpath('//v11:utibaTransactionId/text()');

			//error saving script.................
			$correlation_id     = $requestXML->xpath('//cmn:correlationID/text()');
			$error_descriptions = $requestXML->xpath('//cmn:description/text()');
			$error_codes		= $requestXML->xpath('//cmn:code/text()');
			$error_status		= $requestXML->xpath('//cmn:status/text()');
			$additionalInfo		= $requestXML->xpath('//cmn:additionalInfo/text()');

			$initiate_date		= date('Y-m-d');

			$transactionID      = $transactionID[0];
			$correlationID      = $correlationID[0];
			$status		        = $status[0];
			$code 		        = $code[0];
			$description        = $description[0];
			$result_transaction_id = $result_transaction_id[0];


			$correlation_id     = $correlation_id[0];
			$error_descriptions = $error_descriptions[0];
			$error_codes        = $error_codes[0];
			$error_status		= $error_status[0];
			$additionalInfo     = $additionalInfo[0];


			//get the initiator correlation id......................
			if( ! isset( $_SESSION['correlator'] ) ):
			  session_start();
			endif;

			$correlator_value = $_SESSION['correlator'];



			if($error_codes == '' || $error_status == '' || $error_descriptions == '') 
			{
				// when result returns and there is positive vallue
				//update initiator status if there is an error..............................
				// $success_update = $data_handler_Obj->update_initiator_status($correlation_id, $codes, $ini_status, $descriptions);

			}else{
				//  when result returns and there is positive vallue
				//update initiator status if there is no error..............................
				// $success_update = $data_handler_Obj->update_initiator_status($correlationID, $code, $status, $description);
				// var_dump($success_update);
				// $success = $data_handler_Obj->save_purcase_details_request($transactionID, $soaTransactionId, $correlationID, $customerAccount, $initiatorAccount, $amount, $status, $paymentReference, $description, $code, $purchaseStatus, $transactionDate);
			}


			$Correlation = "";
		    $Descriptions= "";
			$Codes       = "";
			$Status		 = "";
		    if(empty($correlation_id)) 
		    {
		    	$Correlation = $correlation_id[0];
		    	$Descriptions= $error_descriptions[0];
				$Codes       = $error_codes[0];
				$Status		 = $error_status[0];
		    } else 
		    {
		    	$Correlation = $correlationID[0];
		    	$Descriptions= $description[0];
				$Codes       = $code[0];
				$Status		 = $status[0];
		    }
			

		    // save request details................
			$success = $data_handler_Obj->save_purcase_details_request($transactionID, $unique_transaction_id, $Correlation, $result_transaction_id, $paymentReference, $targetmsisdn, $amount, $Codes, $Status, 'code_type', $Descriptions, $additionalInfo, $ItemName);


			$created_at = date("Y-m-d");
			$today_time = date("YmdHis");
		    $file        = fopen("logs/request_respone_data-$created_at.log", 'a');
		    $request_log = "transaction_id : $transactionID, correlationID: $Correlation , msisdn: $targetmsisdn, amount : $amount, item : $ItemName, status: '".$Status."', ResponseCode: '".$Codes."', description: '".$Descriptions."', mytransactionId: '".$unique_transaction_id."', paymentReference: $paymentReference, initiate_date: '".$today_time."' \n";
		    fwrite($file, "$request_log");
		    fclose($file);

		    unset($_SESSION['correlator']);



		    $CorrelationId 		= "";
		    $TransactionId 		= "";
		    $ExternalTransactionId = "";
		    $StatusVal     		= "";
		    $ResponseCode  		= "";
		    $Description   		= "";
		    $PaymentId     		= "";
		    $PaymentReference   = "";
		    $result_transaction_id = "";

		    foreach ($data_handler_Obj->get_current_transaction_details($Correlation) as $key) 
		    {
		    	$CorrelationId 		= $key["correlation_id"];
		    	$TransactionId 		= $key["transaction_id"];
		    	$ExternalTransactionId = $key["ext_transaction_id"];
		    	$StatusVal     		= $key["status"];
		    	$ResponseCode  		= $key["response_code"];
		    	$Description   		= $key["description"];
		    	$result_transaction_id = $key["result_transaction_id"];
		    	$PaymentReference   = $key["user_reference"];
		    }
		    

		    // send response to user...........$unique_value     = date("YmdHis");
		    $feedback = array();
		    $response['Success'] = true;
		    $response['ResponseCode']  = $ResponseCode;
		    $feedback['Network'] = "AIRTEL-TIGO-GH";
		    $feedback['CorrelationId'] = $CorrelationId;
		    $feedback['TransactionId'] = $TransactionId;
		    $feedback['ExternalTransactionId'] = $ExternalTransactionId;
		    $feedback['Amount'] = $amount;
		    $feedback['Status'] = $StatusVal;
		    $feedback['Description'] = $Description;
		    $feedback['UtibaTransactionId'] = $result_transaction_id;
		    $feedback['PaymentReference'] = $PaymentReference;
		    $feedback['TransactionDate'] = date("Y-m-d H:i:s");
		    $response['Data'] = $feedback;
		    header('Content-Type: application/json');
	    	echo json_encode($response);
		}
	} else 
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