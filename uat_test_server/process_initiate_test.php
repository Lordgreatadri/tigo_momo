<?php 
	
	require_once 'Pcabinet_db_files.php';
	//require_once 'purchaseInitiate.php'; 
	require_once 'transactionInitiate.php';
	require_once 'db_file.php';
/**------------------------------------------------------------------------------------------------------------------------------------------------
 * @@Name:              purchase process_request
 
 * @@Author: 			Lordgreat - Adri Emmanuel <'rexmerlo@gmail.com'>
 
 * @Date:   			2018-09-27 11:00:30
 * @Last Modified by:   Lordgreat -  Adri Emmanuel
 * @Last Modified time: 2018-09-27 02:35:30

 * @Copyright: 			MobileContent.Com Ltd <'owner'>
 
 * @Website: 			https://mobilecontent.com.gh
 *---------------------------------------------------------------------------------------------------------------------------------------------------
 */
// $_POST['CustomerMsisdn'] = '233273593525';//233273593525  233269425035 0277505736
// $_POST['Amount'] = '0.1';
// $_POST['Product']   = 'book';
// // $_GET['paymentReference']   = '1234';
// $_POST['RefNo']   = '2018';

// echo "hello";


//https://mycloud.com.gh/payment/airtel_tigo/tigo_request_endpoint.php


if(isset($_POST['CustomerMsisdn']) && isset($_POST['Amount']) && isset($_POST['Product']) && isset($_POST['RefNo'])) 
{
    $Pcabinet_db = new Pcabinet_functions();




	$msisdnss = htmlspecialchars(trim($_POST['CustomerMsisdn']));
	$amount = htmlspecialchars(trim($_POST['Amount']));
	$item   = htmlspecialchars(trim($_POST['Product']));
	// $paymentReference = htmlspecialchars(trim($_GET['paymentReference'])); get it from user later............ 
	$Narration = htmlspecialchars(trim($_POST['Narration']));

	// $contestant_num = htmlspecialchars(trim($_POST['contestant_code']));
	// $contestant_name = htmlspecialchars(trim($_POST['contestant_name']));
	// $cabinet = htmlspecialchars(trim($_POST['cabinet']));

	$randomize = rand(1000000000,100);
	$paymentReference = rand(1000000000,100);//"MCC-TIGO-".$randomize;


	// create an intance of the data handler model...............
	$transaction_Obj  = new tigo();
	$data_handler_Obj = new data_auth();

	#pass the tranasctionid
	$tranasctionid = $transaction_Obj->generate_transaction_id();


	// var_dump($paymentReference.' =>'.$msisdn. ' =>'.$item.' =>'.$amount. ' =>'.$unique_value);

	$unique_value = date("YmdHis");

	$msisdn = $Pcabinet_db->_formart_number($msisdnss);


	$response_value   = $transaction_Obj->sendRequest($unique_value, $msisdn, 0.01, $item, trim($unique_value));//23327750573 6233577665092

	 // var_dump($response_value);
	// echo $response_value;

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
	$paymentReference   = $requestXML->xpath('//v11:paymentReference/text()');

	//error saving script.................
	$correlation_id     = $requestXML->xpath('//cmn:correlationID/text()');
	$descriptions     	= $requestXML->xpath('//cmn:description/text()');
	$codes				= $requestXML->xpath('//cmn:code/text()');
	$ini_status			= $requestXML->xpath('//cmn:status/text()');

	$initiate_date		= date('Y-m-d');

	$transactionID    = $transactionID[0];
	$correlationID    = $correlationID[0];
	$status		      = $status[0];
	$code 		      = $code[0];
	$description      = $description[0];
	$paymentId        = $paymentId[0];
	$paymentReference = $paymentReference[0];
	//$initiate_date    = $initiate_date[0];

	$correlation_id   = $correlation_id[0];
	$descriptions     = $descriptions[0];
	$codes            = $codes[0];
	$ini_status		  = $ini_status[0];


	//get the initiator correlation id......................
	// $correlator_value$data_handler_Obj->
	if( ! isset( $_SESSION['correlator'] ) ):
	  session_start();
	endif;
	// echo '<h1>' . __FILE__  .'</h1>';
	// echo '<br>' .$_SESSION['correlator'].'<br>';
	$correlator_value = $_SESSION['correlator'];

	if($codes == '' || $ini_status == '' || $descriptions == '') 
	{
		// when result returns and there is positive vallue
		//update initiator status if there is an error..............................
		$success_update = $data_handler_Obj->update_initiator_status($correlation_id, $codes, $ini_status, $descriptions);
		// var_dump($success_update);
	}else{
		//  when result returns and there is positive vallue
		//update initiator status if there is no error..............................
		$success_update = $data_handler_Obj->update_initiator_status($correlationID, $code, $status, $description);
		// var_dump($success_update);
	}



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
	$success = $data_handler_Obj->save_transaction_initiate_response($unique_value, $transactionID, $Correlation, $Status, $Codes, $Descriptions, $paymentId, $paymentReference);

    // // insert contestants details here....................
    // $Pcabinet_db->logUserPaymentRequest($unique_value, $transactionID, $Correlation, $Status, $Codes, $Descriptions, $paymentId, $unique_value, $contestant_num, $contestant_name, $cabinet, $msisdn, $amount);
    
    $CorrelationId 		= "";
    $TransactionId 		= "";
    $ExternalTransactionId = "";
    $StatusVal     		= "";
    $ResponseCode  		= "";
    $Description   		= "";
    $PaymentId     		= "";
    $PaymentReference   = "";

    foreach ($data_handler_Obj->fetch_transaction_detail_for_customer() as $key) 
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
    $response['Success'] = true;
    $feedback['CorrelationId'] = $CorrelationId;
    $feedback['TransactionId'] = $TransactionId;
    $feedback['ExternalTransactionId'] = $ExternalTransactionId;
    $feedback['CustomerMsisdn'] = $msisdn;
    $feedback['Amount'] = $amount;
    $feedback['Narration'] = $Narration;
    $feedback['Status'] = $StatusVal;
    $feedback['ResponseCode'] = $ResponseCode;
    $feedback['Description'] = $Description;
    $feedback['PaymentId'] = $PaymentId;
    $feedback['PaymentReference'] = $PaymentReference;
    $response['Data'] = $feedback;
    header('Content-Type: application/json');
    echo json_encode($response);
}
	
?>