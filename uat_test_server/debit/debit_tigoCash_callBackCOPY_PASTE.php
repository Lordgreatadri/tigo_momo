<?php 
require_once "data_functions.php";
require_once 'dbconfig.php';

$data_Obj = new data_functions();

//https://stackoverflow.com/questions/26742078/retrieve-xml-from-http-post-request-using-php

//http://178.79.172.242/adri/payments/tigo/debit/debit_tigoCash_callBack.php
$response =  file_get_contents("php://input");


$transactionID = $_GET['transactionID'];
$correlationID = $_GET['correlationID'];
$status        = $_GET['status'];
$code  		   = $_GET['code'];
$description   = $_GET['description'];



$createdTime = date("Y-m-d");
    $file        = fopen("_cashrespone_data-$createdTime.log", 'a');
    $request_log = "transaction_id :=> $transactionID, correlationID:=> $correlationID , status:=> '".$status."', description:=> '".$description."', initiate_date:=> '".$createdTime."' \n";
    fwrite($file, "$request_log");
    fclose($file);


// $serverName = "127.0.0.1";
//     $databaseName = "airtel_tigo"; 
//     $databaseUser = "root";
//     $databasePassword = 'Mccg8(3P^tJVnBDsF'; //FAg8(3P^tJVnBDsF%F  #4kLxMzGurQ7Z~

//     $database = mysqli_connect($serverName, $databaseUser, $databasePassword, $databaseName);
	


$contestant_name = '';
$cabinet = '';
$contestant_num = "";
$amount = "";
$momo_number = "";

$transaction_id = "";
$external_transaction_id = "";
// load transaction details.............
foreach($data_Obj->getTransactionDetails($correlationID) as $value) 
{

	
	$amount = $value['amount'];
	$momo_number = $value['momo_number'];
	$contestant_name = $value['contestant_name'];
	$cabinet = $value['cabinet'];
	$contestant_num = $value["contestant_num"];
	$external_transaction_id = $value['external_transaction_id'];
	$transaction_id = $value["transaction_id"];

	// var_dump($contestant_name);
}


if($correlationID && trim(!empty($correlationID))) 
{
	$data = "<BILLPAYRESPONSE><id>$transactionID</id><status>SUCCESS</status><code>00</code><description>The request has been processed successfully</description></BILLPAYRESPONSE>";		
	echo $data;		

	$query_track_pay = "INSERT INTO test_response(`id`, `transactionID`, `correlationID`, `status`, `code`, `description`) VALUES(null, '".$transactionID."', '".$correlationID."','".$status."', '".$code."', '".$description."')";
			 
    mysqli_query($database, $query_track_pay);


    $update = $data_Obj->update_PaymentRequest_status($code, $description, $correlationID);
// var_dump($update);
   $log = $data_Obj->logPaymentResponse($transaction_id, $external_transaction_id, $correlationID, $status, $code, $momo_number, $description, $amount);
   // var_dump($log);


    // return $data;
}











// $status = "OK";/
// process votes here if payment succeed................
if($status && trim($status) == "OK") 
{
    // $data_Obj = new data_functions();
    
    $old_votes = 0;
    $new_votes = 0;

    // // load transaction details.............
    // foreach($data_Obj->getTransactionDetails($correlationID) as $value) 
    // {
    //     $amount = $value['amount'];
    //     $momo_number = $value['momo_number'];
    //     $contestant_name = $value['contestant_name'];
    //     $cabinet = $value['cabinet'];
    //     $contestant_num = $value["contestant_num"];
    // }

    foreach($data_Obj->getContestantData($contestant_name, $cabinet, $contestant_num) as $key) 
    {
        $old_votes = $key['num_of_votes'];
    }
    $new_votes = $amount + $old_votes;

    // var_dump($new_votes);

    // update the new votes here............
    $send = $data_Obj->updateContestantVotes($contestant_num, $new_votes);
// var_dump($send);
    // notify user for successful payment.............
    $message = "You voted successfully for ".$contestant_name." \n~~ PCabinet Voting.";
    $data_Obj->sendUserResponse($momo_number, $message);
} else 
{
    $message = "Transaction Failed:\n".$description." \n~~ PCabinet Voting.";

    // notify user for failed Transaction................
    $data_Obj->sendUserResponse($momo_number, $message);
}


// $file = fopen("request.xml",'a');
// $current = $response;
// fwrite($file, "$current");
// fclose($file);


// file_put_contents("cashrequest.xml", print_r($response, true));

// $xml  = simplexml_load_file("cashrequest.xml");

// var_dump($xml);


// echo "<br>";
// echo "<br>";
// // $xml = simplexml_load_string($response);
// //$json = json_encode($xml, true);


// $json = json_encode($response, true);

// $callback_Obj  = json_decode($json, true);
// file_put_contents("cashjson.php", print_r($json, true));


// // $xml = simplexml_load_string($response);
// $transactionID = $xml->transactionID;
// $correlationID = $xml->correlationID;
// $status        = $xml->status;
// $code          = $xml->code;
// $description   = $xml->description;



// $xml = simplexml_load_string($response);





#Sample Request: (XML/HTTP) for Successful Transaction
// <BILLPAYREQUEST>
// <transactionID>MP191114.1539.C00004<transactionID>
// <correlationID>96894530x5b3c</correlationID>
// <status>OK</status>
// <code>purchase-3008-0000-S</code>
// <description>The request has been processed successfully</description>
// <BILLPAYREQUEST>
// > 


#Sample Request: (XML/HTTP) for Failed Transaction
// <BILLPAYREQUEST>
// <transactionID>MP191114.1539.C00004<transactionID>
// <correlationID>96894530x5b3c</correlationID>
// <status>ERROR</status>
// <code>purchase-3008-3001-E</code>
// <description>Invalid MSISDN length</description>
// <BILLPAYREQUEST>


#Sample Response: (XML/HTTP)
// <BILLPAYRESPONSE>
// <id>MP191125.1417.C00005</id>
// <status>SUCCESS</status>
// <code>00</code>
// </BILLPAYRESPONSE> 
