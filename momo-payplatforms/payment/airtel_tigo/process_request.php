<?php 
	
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
$_POST['msisdn'] = '233273593525';//233273593525  233269425035
$_POST['amount'] = '0.5';
$_POST['item']   = 'book';
// $_GET['paymentReference']   = '1234';
$_POST['password']   = '2018';




//https://mycloud.com.gh/payment/airtel_tigo/tigo_request_endpoint.php


if(isset($_POST['msisdn']) && isset($_POST['amount']) && isset($_POST['item']) && isset($_POST['password'])) 
{
	$msisdn = htmlspecialchars(trim($_POST['msisdn']));
	$amount = htmlspecialchars(trim($_POST['amount']));
	$item   = htmlspecialchars(trim($_POST['item']));
	// $paymentReference = htmlspecialchars(trim($_GET['paymentReference'])); get it from user later............ 
	$password = htmlspecialchars(trim($_POST['password']));

	$randomize = rand(1000000000,100);
	$paymentReference = "MCC-TIGO-".$randomize;


	// create an intance of the data handler model...............
	$transaction_Obj  = new tigo();
	$data_handler_Obj = new data_auth();

	#pass the tranasctionid
	$tranasction_value = $transaction_Obj->generate_transaction_id();

	var_dump($paymentReference.' '.$msisdn. ' '.$item.' '.$amount. ' '.$tranasction_value);

	$response_value   = $transaction_Obj->sendRequest($msisdn, $amount, $item, $tranasction_value, trim($paymentReference), $password);//23327750573 6233577665092

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
	if(empty($descriptions) || empty($codes) || empty($ini_status))
	{
		var_dump( "EMPTY ooo boss, error occured!");
	}else{
		var_dump('VALUES dey inside ooo bros, was successful');
	}

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
	echo '<br>' .$_SESSION['correlator'].'<br>';
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
	

	//save_transaction_initiate_response        
	$success = 0;
	$success = $data_handler_Obj->save_transaction_initiate_response($transactionID, $correlationID, $status, $code, $description, $paymentId, $paymentReference);

	
	//check if saving was successful............
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