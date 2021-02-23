<?php 
	
	//require_once 'purchaseInitiate.php'; 
	require_once 'payment_functions.php';
	require_once 'PaymentsRequest.php';
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
$_POST['msisdn'] = '233273206468';//233273593525  233269425035 0277505736 233273206468
$_POST['amount'] = '0.5';
$_POST['item']   = 'book';
$_POST['paymentReference']   = '1234';
$_POST['password']   = '0852';

// echo "hello";

//PIN: 0852  MCC PIN  FROM KELVIN....Mon 8th Feb 2021
// PIN: 0852

//https://mycloud.com.gh/payment/airtel_tigo/tigo_request_endpoint.php


if(isset($_POST['msisdn']) && isset($_POST['amount']) && isset($_POST['item']) && isset($_POST['password'])) 
{
	$targetNumber = htmlspecialchars(trim($_POST['msisdn']));
	$amount = htmlspecialchars(trim($_POST['amount']));
	// $item   = htmlspecialchars(trim($_POST['item']));
	// $paymentReference = htmlspecialchars(trim($_POST['paymentReference'])); get it from user later............ 
	$payer_pin = htmlspecialchars(trim($_POST['password']));

	$sourceNumber = "233277231999";
	$randomize = rand(1000000000,100);
	$paymentReference = rand(1000000000,100);//"MCC-TIGO-".$randomize;


	// create an intance of the data handler model...............
	$transaction_Obj  = new PaymentsRequest();
	$data_handler_Obj = new payment_data_auth();

	#pass the tranasctionid
	// $transaction_id = $transaction_Obj->generate_transaction_id();

	$unique_value = date("YmdHis");
	$payeeWallet   ="233273593525"; //233277231999
	$payerWallet       = "233277231999";


	$response_value   = $transaction_Obj->sendPaymentRequest($payerWallet, $payeeWallet, $amount, $unique_value, $payer_pin);//23327750573 6233577665092

	 var_dump($response_value);
	// echo $response_value;

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
	// $paymentId 			= $requestXML->xpath('//v11:paymentId/text()');


	// $amount  			= $requestXML->xpath('//v11:amount/text()');
	$result_transaction_id     = $requestXML->xpath('//v11:transactionId/text()');
	// $transactionDate  	= $requestXML->xpath('//v11:transactionDate/text()');
	$utibaTransactionId = $requestXML->xpath('//v11:utibaTransactionId/text()');

	//error saving script.................
	$correlation_id     = $requestXML->xpath('//cmn:correlationID/text()');
	$error_descriptions = $requestXML->xpath('//cmn:description/text()');
	$error_codes		= $requestXML->xpath('//cmn:code/text()');
	$error_status		= $requestXML->xpath('//cmn:status/text()');
	$additionalInfo		= $requestXML->xpath('//cmn:additionalInfo/text()');

	$initiate_date		= date('Y-m-d');
	// if(empty($error_descriptions) || empty($error_codes) || empty($error_status))
	// {
	// 	var_dump( "EMPTY ooo boss, error occured!");
	// }else{
	// 	var_dump('VALUES dey inside ooo bros, was successful');
	// }

	$transactionID      = $transactionID[0];
	$correlationID      = $correlationID[0];
	$status		        = $status[0];
	$code 		        = $code[0];
	$description        = $description[0];

	// $soaTransactionId   = $soaTransactionId[0];
	// $paymentReference   = $paymentReference[0];
	// $customerAccount    = $customerAccount[0];
	// $initiatorAccount   = $initiatorAccount[0];
	// $amount  			= $amount[0];
	$result_transaction_id = $result_transaction_id[0];
	// $transactionDate  	= $transactionDate[0];
	// $utibaTransactionId = $utibaTransactionId[0];
	//$initiate_date    = $initiate_date[0];

	$correlation_id     = $correlation_id[0];
	$error_descriptions = $error_descriptions[0];
	$error_codes        = $error_codes[0];
	$error_status		= $error_status[0];
	$additionalInfo     = $additionalInfo[0];


	//get the initiator correlation id......................
	if( ! isset( $_SESSION['correlator'] ) ):
	  session_start();
	endif;
	// echo '<h1>' . __FILE__  .'</h1>';
	echo '<br>' .$_SESSION['correlator'].'<br>';
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
		// $success = $data_handler_Obj->save_purcase_details_request($transactionID, $utibaTransactionId, $soaTransactionId, $correlationID, $customerAccount, $initiatorAccount, $amount, $status, $paymentReference, $description, $code, $purchaseStatus, $transactionDate);
	}


	$Correlation = "";
    $Descriptions= "";
	$Codes       = "";
	$Status		 = "";
    if($correlation_id) 
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
	


	$success = $data_handler_Obj->save_purcase_details_request($transactionID, $unique_value, $Correlation, $result_transaction_id, $unique_value, $payerWallet, $payeeWallet, $amount, $Codes, $status, 'code_type', $description, $additional_info);


	
	// check if saving was successful............
	if($success > 0) 
	{
		echo "New initiation saved!";
	}else
	{
		echo "Sorry couldn't save details!";
	}


	$createdTime = date("Y-m-d");
    $file        = fopen("logs/request_respone_data-$createdTime.log", 'a');
    $request_log = "transaction_id : $transactionID, correlationID: $correlationID , msisdn: $msisdn, amount : $amount, item : $item, status: '".$status."', ResponseCode: '".$code."', description: '".$description."', paymentId: '".$paymentId."', paymentReference: $paymentReference, initiate_date: '".$createdTime."' \n";
    fwrite($file, "$request_log");
    fclose($file);

    unset($_SESSION['correlator']);
}
	
?>