<?php 
	
	//require_once 'purchaseInitiate.php'; 
	require_once 'get_balance.php';
	require_once 'db_file.php';
/**------------------------------------------------------------------------------------------------------------------------------------------------
 * @@Name:              balance process_request
 
 * @@Author: 			Lordgreat - Adri Emmanuel <'rexmerlo@gmail.com'>
 
 * @Date:   			2018-09-27 11:00:30
 * @Last Modified by:   Lordgreat -  Adri Emmanuel
 * @Last Modified time: 2018-09-27 02:35:30

 * @Copyright: 			MobileContent.Com Ltd <'owner'>
 
 * @Website: 			https://mobilecontent.com.gh
 *---------------------------------------------------------------------------------------------------------------------------------------------------
 */

//$tigoNumber, $password, $e_wallet, $transaction_id

$_POST['msisdn'] = '0269425035';//233273593525
$_POST['password'] = '2018';
$_POST['wallet']   = 'e_wallet';

if(isset($_POST['msisdn']) && isset($_POST['password']) || isset($_POST['wallet'])) 
{
	$msisdn = htmlspecialchars(trim($_POST['msisdn']));
	$password = htmlspecialchars(trim($_POST['password']));
	$wallet   = htmlspecialchars(trim($_POST['wallet']));


	// create an intance of the data handler model...............
	$balance_Obj  = new tigo_balance();   
	$data_handler_Obj = new data_auth();

	#pass the tranasctionid
	$tranasction_value = $balance_Obj->generate_transaction_id();
	#var_dump($tranasction_value);//e4ad9ce3892c43c9a9734b20306e2149


	$response_value   = $balance_Obj->send_balance_request($msisdn, $password, $wallet, $tranasction_value);//23327750573 233577665092

	// var_dump($response_value); //send_balance_request($tigoNumber, $password, $e_wallet, $transaction_id);

	//dump response here to reuse.................
	file_put_contents("tigo_balance_response.xml",$response_value);


	//reload extracted xlm response file................*713*1#
	$requestXML = simplexml_load_file("tigo_balance_response.xml");


	$requestXML->registerXPathNamespace('v3', 'http://xmlns.tigo.com/RequestHeader/V3');
	$requestXML->registerXPathNamespace('cor', 'http://soa.mic.co.af/coredata_1');
	$requestXML->registerXPathNamespace('v1', 'http://xmlns.tigo.com/MFS/GetBalanceResponse/V1');

	//if the return is error, get values............
	$requestXML->registerXPathNamespace('cmn', 'http://xmlns.tigo.com/ResponseHeader/V3');


	//fetch value from xml file to variable.....................
	$transactionID		= $requestXML->xpath('//cor:SOATransactionID/text()');
	$correlationID      = $requestXML->xpath('//v3:correlationID/text()');

	$status				= $requestXML->xpath('//v3:status/text()');
	$code 				= $requestXML->xpath('//v3:code/text()');
	$description        = $requestXML->xpath('//v3:description/text()');
	$transactionId 		= $requestXML->xpath('//v1:transactionId/text()');
	$walletName         = $requestXML->xpath('//v1:walletName/text()');
	$walletBalance 		= $requestXML->xpath('//v1:walletBalance/text()');

	//error saving script.................
	$correlation_id     = $requestXML->xpath('//cmn:correlationID/text()');
	$descriptions     	= $requestXML->xpath('//cmn:description/text()');
	$codes				= $requestXML->xpath('//cmn:code/text()');
	$balance_status		= $requestXML->xpath('//cmn:status/text()');

	$initiate_date		= date('Y-m-d');
	if(empty($descriptions) || empty($codes) || empty($balance_status))
	{
		var_dump( "EMPTY ooo");
	}else{
		var_dump('VALUES dey inside ooo');
	}



	$transactionID    = $transactionID[0];
	$correlationID    = $correlationID[0];
	$status		      = $status[0];
	$code 		      = $code[0];
	$description      = $description[0];
	$transactionId    = $transactionId[0];
	$walletName       = $walletName[0];
	$walletBalance    = $walletBalance[0];

	$correlation_id   = $correlation_id[0];
	$descriptions     = $descriptions[0];
	$codes            = $codes[0];
	$balance_status	  = $balance_status[0];

	// var_dump($correlation_id.' = '.$balance_status .'=>'.$descriptions.'=>'. $codes);

	//get the initiator correlation id......................
	// $correlator_value$data_handler_Obj->
	if( ! isset( $_SESSION ) ):
	  session_start();
	endif;
	// echo '<h1>' . __FILE__  .'</h1>';
	echo '<br>' .$_SESSION['correlator'];
	$correlator_value = $_SESSION['correlator'];

	if($codes == '' || $balance_status == '' || $descriptions == '') 
	{
		echo "\nEeeii empty oo";// when result returns and there is no positive vallue

		//update initiator status if there is an error..............................
		$success_update = $data_handler_Obj->update_balance_status($correlation_id, $codes, $balance_status, $descriptions);
		// var_dump($success_update);
	}else{
		echo "\nWith values";//  when result returns and there is positive vallue
		//update initiator status if there is an error..............................
		$success_update = $data_handler_Obj->update_balance_status($correlationID, $code, $status, $description);
		// var_dump($success_update);
	}


	//save_transaction_balance_response        
	$success = 0;
	$success = $data_handler_Obj->save_balance_response($transactionID, $correlationID, $status, $code, $description, $transactionId, $walletName, $walletBalance);

	//check if saving was successful............
	// var_dump($success);
	if($success > 0) 
	{
		echo "New initiation saved!";
	}else
	{
		echo "Sorry couldn't save details!";
	}


	$createdTime = date("Y-m-d");
    $file        = fopen("log/balance_endpoint_data-$createdTime.log", 'a');
    $request_log = "[ transaction_id : $transactionID, correlationID: $correlationID , msisdn: $msisdn, amount : $walletBalance, walletName : $walletName, status: '".$status."', ResponseCode: '".$code."', description: '".$description."', transactionId: '".$transactionId."', initiate_date: '".$createdTime."' ], \n";
    fwrite($file, "$request_log");
    fclose($file);

    session_unset();
    session_destroy();
}