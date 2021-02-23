<?php 
// require_once 'dbconfig.php';
//http://178.79.172.242/adri/payments/tigo/debit/debit_tigoCash_callBack.php
$response =  file_get_contents("php://input");

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

// $id            = $xml->id;


// $xml = simplexml_load_string($response);
$transactionID   = $_GET['transactionID'];
$correlationID = $_GET['correlationID'];
$status     = $_GET['status'];
$code  		 = $_GET['code'];
$description  		 = $_GET['description'];


echo "transactionID => ".$transactionID;
echo "<br>";
echo "<br>";
// echo $json;


$createdTime = date("Y-m-d");
    $file        = fopen("_cashrespone_data-$createdTime.log", 'a');
    $request_log = "transaction_id :=> $transactionID, correlationID:=> $correlationID , status:=> '".$status."', description:=> '".$description."', initiate_date:=> '".$createdTime."' \n";
    fwrite($file, "$request_log");
    fclose($file);


$serverName = "127.0.0.1";
    $databaseName = "airtel_tigo"; 
    $databaseUser = "root";
    $databasePassword = 'Mccg8(3P^tJVnBDsF'; //FAg8(3P^tJVnBDsF%F  #4kLxMzGurQ7Z~

    $database = mysqli_connect($serverName, $databaseUser, $databasePassword, $databaseName);


if($correlationID && trim(!empty($correlationID))) 
{
	// echo "Payment callback Successful...";echo "<br>";	

	echo "<BILLPAYRESPONSE>
			<id>'".$transactionID."'</id>
			<status>SUCCESS</status>
			<code>00</code>
		</BILLPAYRESPONSE> ";

	$query_track_pay = "INSERT INTO test_response(`id`, `transactionID`, `correlationID`, `status`, `code`, `description`) VALUES(null, '".$transactionID."', '".$correlationID."','".$status."', '".$code."', '".$description."')";
			 
    mysqli_query($database, $query_track_pay);
} elseif(trim($code) == "00" ) 
{
	echo $id;echo "<br>";
	echo "Final payment response received. Payment Succeeded...";

	$query_track_pay1 = "INSERT INTO test_response(`id`, `transactionID`, `status`, `code`) VALUES(null, '".$id."', '".$status."', '".$code."')";
			 
    mysqli_query($database, $query_track_pay1);
}
else {
	echo "Still no data received.";
}











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
