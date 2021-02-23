<?php 



	$serviceId     = $_REQUEST['id'];
	$serviceStatus = $_REQUEST['status'];
	$serviceCode   = $_REQUEST['code'];
	$description   = $_REQUEST['description'];

	variant_abs($serviceId.", status:".$serviceStatus.", code:"$serviceCode.", decript:".$description);

	$tigoResponse = json_decode(@file_get_contents('php://input'));
	var_dump($tigoResponse);
    // Receive incoming post request
    $requestId   = $tigoResponse->id;
    $status		 = $tigoResponse->status;
    $starCode	 = $tigoResponse->code;
    $keyWord 	 = $tigoResponse->description;

 	$createdTime = date("Y-m-d");
    $date_stamp  = date("Y-m-d H:i:s");
    $file        = fopen("logs/am_receive_ooooo-$createdTime.log", 'a');
    $request_log = "Response Details: id: $serviceId, status: $serviceStatus, code: serviceCode, desc: $description{<=>} reqid: $requestId, reqstatus: $status, starCode: $starCode, requeDesc: $keyWord \n";
    fwrite($file, "$request_log");
    fclose($file);
	
/*	$xmlData = trim(file_get_contents('php://input'));
    $returnDoc = new DOMDocument();
    $returnDoc->loadXML($xmlData);
    $_file = $returnDoc->loadXML($xmlData);

    file_put_contents('dump_log.log', $returnDoc->saveXML(), true);


	file_put_contents('dump_log11.log', print_r($_file, true));


	// $xml = simplexml_load_file(put_the_api_here);
	
	// header('Content-Type: text/xml');
	$callback_obj = trim(file_get_contents("php://input"));
	//$json = json_decode($callback_obj, true);


	file_put_contents("endpoint.xml",$callback_obj);

	file_put_contents("endpoint.log",$callback_obj);
*/



	// $endpointXML = simplexml_load_file($callback_obj);

	// $xmlData = simplexml_load_string($callback_obj);

	/*
$_REQUEST['id'];
$_REQUEST['code'];
$_REQUEST['status'];
$_REQUEST['description'];
*/

	$transactionID = "";
	$correlationID = "";
	$status		   = "";
	$code 		   = "";
	$description   = "";

	// //pass values to variable.........
	// $transactionID = $json['transactionID'];
	// $correlationID = $json['correlationID'];
	// $status		  = $json['status'];
	// $code		  = $json['code'];
	// $description   = $json['description'];
	
	
	//$xml = simplexml_load_file('endpoint.xml');


	// $list = $xml->BILLPAYREQUEST;

	// for ($i = 0; $i < count($list); $i++) {
	// 	$transactionID = $list[$i]->transactionID;
	// 	$correlationID = $list[$i]->correlationID;
	// 	$status		   = $list[$i]->status;
	// 	$code 		   = $list[$i]->code;
	// 	$description   = $list[$i]->description;

	// }



/* $log_file = fopen("log-callback.txt", "a") or die("Unable to open file!");
// fwrite($log_file, $callback_obj); 
// fclose($log_file);

// $json = json_decode($callback_obj, true);
// $log_file = fopen("ljson-callback.txt", "a") or die("Unable to open file!");
// fwrite($log_file, $json); 
// fclose($log_file);

// $xmlNode = simplexml_load_file('tigo_response.xml');
//$arrayData = xmlToArray($xmlNode);
//echo json_encode($arrayData);
// echo $json = json_encode($xmlNode);
/*$array = json_decode($json,TRUE);
*/
?>