<?php 
	

	require_once 'transactionInitiate.php';
	require_once 'data_auth.php';
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
$_POST['user_wallet'] = '233277231999';//233273593525  233269425035 0277505736
$_POST['request_transaction_id']   = '20210210112947';//previous transaction ID in purchase/payment request you initiated
$_POST['request_correlator_id']    = "20210210112947";//previous correlator ID in purchase/payment request you initiated
$_POST['utibaTransactionId']       = 'BM210210.1128.C00024'; //unique mfs id received in the purchase/payment callback
$_POST['paymentReference']         = "20210210112947"; //paymentReference for the previous transaction to be reversed
$_POST['apiType']                  = "purchase"; //this should be the pyament type to be reversed {purchase/payment}




if(isset($_POST['apiType']) && isset($_POST['paymentReference']) && isset($_POST['utibaTransactionId']) && !empty($_POST['paymentReference'])) 
{
	$utibaTransactionId = htmlspecialchars(trim($_POST['utibaTransactionId']));
	$apiType = htmlspecialchars(trim($_POST['apiType']));
	$request_transaction_id   = htmlspecialchars(trim($_POST['request_transaction_id']));
	$request_correlator_id   = htmlspecialchars(trim($_POST['request_correlator_id']));
	$reverseReference = htmlspecialchars(trim($_POST['paymentReference'])); //get it from user later............ 
	$transactionType = "CANCEL TRANSACTION"; //This field signifies the type of transaction which has to be cancelled

	$randomize = rand(1000000000,100);
	//"MCC-TIGO-".$randomize;


	// create an intance of the data handler model...............
	$transaction_Obj  = new tigo();
	$data_handler_Obj = new data_auth();

	#pass the tranasctionid
	$transaction_id = $transaction_Obj->generate_transaction_id();



	$response_value   = $transaction_Obj->reverseTransactionRequest($request_correlator_id, $request_transaction_id, $transactionType, $apiType, $utibaTransactionId, $reverseReference);
	 var_dump($response_value);
	// echo $response_value;

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

	$initiate_date		= date('Y-m-d');
	// if(!empty($descriptions) || !empty($codes) || !empty($ini_status))
	// {
	// 	var_dump( "EMPTY ooo boss, error occured!");
	// }else{
	// 	var_dump('VALUES dey inside ooo bros, was successful');
	// }

	$SOATransactionID = $SOATransactionID[0];
	$correlationID    = $correlationID[0];
	$status		      = $status[0];
	$code 		      = $code[0];
	$description      = $description[0];
	// $paymentId        = $paymentId[0];
	// $paymentReference = $paymentReference[0];
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
// $external_transaction_id, $correlator_value, $response_code, $status, $description
	if($codes == '' || $ini_status == '' || $descriptions == '') 
	{
		// when result returns and there is positive vallue
		//update initiator status if there is an error..............................
		$success_update = $data_handler_Obj->update_reversed_transactions_status($SOATransactionID, $correlation_id, $codes, $ini_status, $descriptions);
		// var_dump($success_update);
	}else{
		//  when result returns and there is positive vallue
		//update initiator status if there is no error..............................
		$success_update = $data_handler_Obj->update_reversed_transactions_status($SOATransactionID, $correlationID, $code, $status, $description);
		// var_dump($success_update);
	}
	

	//save_transaction_initiate_response        
	// $success = 0;
	// $success = $data_handler_Obj->save_transaction_initiate_response($transaction_id, $SOATransactionID, $correlationID, $status, $code, $description, $paymentId, $paymentReference);

	
	//check if saving was successful............
	// if($success > 0) 
	// {
	// 	echo "New initiation saved!";
	// }else
	// {
	// 	echo "Sorry couldn't save details!";
	// }


	// $createdTime = date("Y-m-d");
 //    $file        = fopen("logs/request_respone_data-$createdTime.log", 'a');
 //    $request_log = "transaction_id : $transactionID, correlationID: $correlationID , msisdn: $user_wallet, amount : $amount, item : $item, status: '".$status."', ResponseCode: '".$code."', description: '".$description."', paymentId: '".$paymentId."', paymentReference: $paymentReference, initiate_date: '".$createdTime."' \n";
 //    fwrite($file, "$request_log");
 //    fclose($file);

    unset($_SESSION['correlator']);
}
	
?>