<?php 


include_once 'includes/autoloader.inc.php';

/**------------------------------------------------------------------------------------------------------------------------------------------------
 * @@Name:              purchase process_request
 
 * @@Author: 			Lordgreat - Adri Emmanuel <'rexmerlo@gmail.com'>
 
 * @Date:   			2018-09-27 11:00:30
 * @Last Modified by:   Lordgreat -  Adri Emmanuel
 * @Last Modified time: 2021-02-02 02:35:30

 * @Copyright: 			MobileContent.Com Ltd <'owner'>
 
 * @Website: 			https://mobilecontent.com.gh
 *---------------------------------------------------------------------------------------------------------------------------------------------------
 */


// $_POST['ApiType']   = 'payment'; // the type of transaction being required for {purchase/payment} only...
// $_POST['paymentReference']   = '818664819';
// $_POST['AuthorizationCode'] = "MskwHaW9(2021028Mc";






if($_SERVER['REQUEST_METHOD'] == 'POST') 
{

	// create an intance of the data handler model...............
	$transaction_Obj    = new GetPurchaseTransFunctions();
	$data_handler_Obj   = new Get_Purchase_Trans_Data_Auths();
	$authorization_code = $data_handler_Obj->get_authorization_code();

	if(trim($_POST['AuthorizationCode']) == trim($authorization_code)) 
	{

		if(isset($_POST['PaymentReference']) && isset($_POST['ApiType']) && !empty($_POST['ApiType']) && !empty($_POST['PaymentReference']) && isset($_POST['Network']) && $_POST['Network'] == "AIRTEL-TIGO-GH") 
		{
			$paymentReference = htmlspecialchars(trim($_POST['PaymentReference']));
			$apiType = htmlspecialchars(trim($_POST['ApiType']));
			
			$randomize = rand(1000000000,100);
			//"MCC-TIGO-".$randomize;

			#pass the tranasctionid
			$transaction_id   = $transaction_Obj->generate_transaction_id();
			$correlation_token= $transaction_Obj->generate_correlation_token();


			$response_value   = $transaction_Obj->sendGetPurchaseDetailsRequest($paymentReference, $apiType, $transaction_id, $correlation_token);//$paymentReference  23327750573 6233577665092

			 // var_dump($response_value);
			// echo $response_value;

			file_put_contents("Get_purchase.xml",$response_value);//024cfcf2-6d55-4e48-b09f-cd152c273201

			//reload extracted xlm response file................*713*1#
			$requestXML = simplexml_load_file("Get_purchase.xml");

			$requestXML->registerXPathNamespace('v31', 'http://xmlns.tigo.com/ResponseHeader/V3');
			$requestXML->registerXPathNamespace('cor', 'http://soa.mic.co.af/coredata_1');
			$requestXML->registerXPathNamespace('v11', 'http://xmlns.tigo.com/GetPurchaseTransDetailsResponse/V1');

			//if the return is error, get values............
			$requestXML->registerXPathNamespace('cmn', 'http://xmlns.tigo.com/ResponseHeader/V3');

			//fetch value from xml file to variable.....................
			$SOATransactionID	= $requestXML->xpath('//cor:SOATransactionID/text()');
			$correlationID      = $requestXML->xpath('//v31:correlationID/text()');
			$status				= $requestXML->xpath('//v31:status/text()');
			$code 				= $requestXML->xpath('//v31:code/text()');
			$description        = $requestXML->xpath('//v31:description/text()');
			// $paymentId 			= $requestXML->xpath('//v11:paymentId/text()');
			$paymentReference   = $requestXML->xpath('//v11:paymentReference/text()');
			$soaTransactionId   = $requestXML->xpath('//v11:soaTransactionId/text()');
			$customerAccount    = $requestXML->xpath('//v11:msisdn/text()');
			$initiatorAccount   = $requestXML->xpath('//v11:msisdn/text()');
			$amount  			= $requestXML->xpath('//v11:amount/text()');
			$purchaseStatus     = $requestXML->xpath('//v11:status/text()');
			$transactionDate  	= $requestXML->xpath('//v11:transactionDate/text()');
			$utibaTransactionId = $requestXML->xpath('//v11:utibaTransactionId/text()');
			$itemName           = $requestXML->xpath('//v11:itemName/text()');
			$mwCode             = $requestXML->xpath('//v11:mwCode/text()');
			$mwDescription      = $requestXML->xpath('//v11:mwDescription/text()');
			//error saving script.................
			$correlation_id     = $requestXML->xpath('//cmn:correlationID/text()');
			$error_descriptions = $requestXML->xpath('//cmn:description/text()');
			$error_codes		= $requestXML->xpath('//cmn:code/text()');
			$error_status		= $requestXML->xpath('//cmn:status/text()');
			$additionalInfo		= $requestXML->xpath('//cmn:additionalInfo/text()');
			

			$SOATransactionID   = $SOATransactionID[0];
			$correlationID      = $correlationID[0];
			$status		        = $status[0];
			$code 		        = $code[0];
			$description        = $description[0];
			$soaTransactionId   = $soaTransactionId[0];
			$paymentReference   = $paymentReference[0];
			$customerAccount    = $customerAccount[0];
			$initiatorAccount   = $initiatorAccount[1];
			$amount  			= $amount[0];
			$purchaseStatus     = $purchaseStatus[0];
			$transactionDate  	= $transactionDate[0];
			$utibaTransactionId = $utibaTransactionId[0];
			$itemName           = $itemName[0];
			$mwCode             = $mwCode[0];
			$mwDescription      = $mwDescription[0];


			// pick value from returned error response here..........
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



			if($error_codes = "" || $error_status = "" || $error_descriptions = "" ) 
			{
				// when result returns and there is positive vallue
				$success = $data_handler_Obj->save_purcase_details_error($transaction_id, $SOATransactionID, $correlation_id, $error_descriptions, $error_codes, $error_status, $additionalInfo);
			}else{
				//  when result returns and there is positive vallue
				$success = $data_handler_Obj->save_purcase_details_request($transaction_id, $SOATransactionID, $utibaTransactionId, $soaTransactionId, $correlationID, $customerAccount, $initiatorAccount, $amount, $status, $paymentReference, $description, $code, $purchaseStatus, $transactionDate, $itemName, $mwCode, $mwDescription);
			}
			
			


			// $createdTime = date("Y-m-d");
		 //    $file        = fopen("logs/request_respone_data-$createdTime.log", 'a');
		 //    $request_log = "transaction_id : $transactionID, correlationID: $correlationID , msisdn: $msisdn, amount : $amount, item : $item, status: '".$status."', ResponseCode: '".$code."', description: '".$description."', paymentId: '".$paymentId."', paymentReference: $paymentReference, initiate_date: '".$createdTime."' \n";
		 //    fwrite($file, "$request_log");
		 //    fclose($file);

		    unset($_SESSION['correlator']);


		    $CorrelationId 		= "";
		    $TransactionId 		= "";
		    $ExternalTransactionId = "";
		    $StatusVal     		= "";
		    $ResponseCode  		= "";
		    $Description   		= "";
		    $PaymentId     		= "";
		    $PaymentReference   = "";
		    $payeeWallet        = "";
		    $payerWallet        = "";
		    $itemNames          = "";
		    $amount      	    = "";
		    $purchaseStatus     = "";
		    $transactionDate    = "";
		    $utibaTransactionId = "";
		    $soaTransactionId   = "";
		    $MwCode             = ""; 
		    $MwDescription      = "";

		    foreach($data_handler_Obj->get_current_transaction_details($Correlation, $transaction_id) as $key) 
		    {
		    	$CorrelationId 		= $key["correlationId"];
		    	$TransactionId 		= $key["tranasactionId"];
		    	$ExternalTransactionId = $key["extTransactionId"];
		    	$StatusVal     		= $key["status"];
		    	$ResponseCode  		= $key["responseCode"];
		    	$Description   		= $key["description"];
		    	$payerWallet        = $key["payerWallet"];
		    	$payeeWallet        = $key["payeeWallet"];
		    	$PaymentReference   = $key["paymentReference"];
		    	$itemNames          = $key["itemName"];
		    	$amount      	    = $key["amount"];
		    	$purchaseStatus     = $key["purchaseStatus"];
		    	$transactionDate    = $key["transactionDate"];
		    	$additionalInfo     = $key["additionalInfo"];
		    	$utibaTransactionId = $key["utibaTransactionId"];
		    	$soaTransactionId   = $key["soaTransactionId"];
		    	$MwCode             = $key["mwCode"];
		    	$MwDescription      = $key["mwDescription"];
		    }


		    
		    $feedback = array();
		    $response['Success'] = TRUE;
		    $response['ResponseCode']  = $ResponseCode;
		    $feedback['Network'] = "AIRTEL-TIGO-GH";
		    $feedback['CorrelationId'] = $CorrelationId;
		    $feedback['TransactionId'] = $TransactionId;
		    $feedback['ExternalTransactionId'] = $ExternalTransactionId;
		    $feedback['Amount'] = $amount;
		    $feedback['ItemName'] = $itemNames;
		    $feedback['Status'] = $StatusVal;
		    $feedback['PayerWallet'] = $payerWallet;
		    $feedback['PayeeWallet'] = $payeeWallet;
		    $feedback['Description']  = $Description;
		    $feedback['UtibaTransactionId'] = $utibaTransactionId;
		    $feedback['SoaTransactionId'] = $soaTransactionId; 
		    $feedback['PaymentReference'] = $PaymentReference;
		    $feedback['PurchaseStatus']   = $purchaseStatus;
		    $feedback['MwCode']           = $MwCode;
		    $feedback['MwDescription']    = $MwDescription;
		    $feedback['AdditionalInfo']   = $additionalInfo;
		    $feedback['TransactionDate']  = $transactionDate;
		    $response['Data'] = $feedback;
		    header('Content-Type: application/json');
	    	echo json_encode($response);


		}

	} else 
	{
		
	    $feedback = array();
	    $response['Success'] = "Failed";
	    $feedback['ResponseCode'] = "2001";
	    $feedback['Description'] = "Non authorize  request";
	    $response['Data'] = $feedback;
	    header('Content-Type: application/json');
    	echo json_encode($response);
	}	

}
	
?>