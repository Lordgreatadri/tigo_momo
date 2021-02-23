<?php
require_once 'db_functions.php';
// var_dump($_GLOBAL['request']);
// ob_start();
// $data = ob_get_clean();
// file_put_contents("callback.log", print_r($data, true));
//Make sure that this is a POST request.
// if(strcasecmp($_SERVER['REQUEST_METHOD'], 'POST') != 0){
//     //If it isn't, send back a 405 Method Not Allowed header.
//     header($_SERVER["SERVER_PROTOCOL"]." 405 Method Not Allowed", true, 405);
//     exit;
// }

// $_POST['Authorization'] = 'GqeaUSbau2pMNWnNLiSYarcJt097zYZo';
// $_POST['api_key'] = "mcc@tigoc@sh";
// $_POST['PrimaryCallbackUrl'] = "http://178.79.172.242/adri/uat/tigo/category/payment_callback.php";
// $_POST['CustomerMsisdn'] = "233273593525";
// $_POST['Product'] = 'Voting';
// $_POST['Amount'] = 1.0;
// $_POST['Network'] = "tigo-gh";
// $_POST['ClientReference'] = "1223345465";
// $_POST['ServiceDescription'] = "Tigo momo testing on web portal";
// $_POST['Narration'] = "Debit Tigo customer";
// $_POST['CustomerName'] = "MCC-TIGO-TEST";
// $_POST['Network'] = "tigo-gh";

if($_SERVER['REQUEST_METHOD'] == 'POST') 
{
	$response    = array();
	$dataArray   = array();
	$statusArray = array();
			
	$data_Obj = new data_auth();
	$user_response = 0;


// $callback_Obj    = file_get_contents("php://input");
// 	    $json            = json_decode($callback_Obj, true);

// 	    file_put_contents("callback.log", print_r($data, true));


	if(!empty($_POST['api_key']) && $_POST['api_key'] == "mcc@tigoc@sh")
	{

		


	    $message         = "";

	    // $ResponseCode    = $json['ResponseCode'];
	    // //$AmountAfterCharges = $json['Data']['AmountAfterCharges'];
	    // $TransactionId   = $json['Data']['TransactionId'];
	    // $ClientReference = $json['Data']['ClientReference'];
	    // $CorrelationId   = $json['Data']['CorrelationId'];
	    // $Description     = $json['Data']['Description'];
	    // $Status  		 = $json['Data']['Status'];
	    // $ExternalTransactionId = $json['Data']['ExternalTransactionId'];
	    // $Amount  		 = $json['Data']['Amount'];
	    // $Charges 		 = $json['Data']['Charges'];


		if(trim(!empty($_POST['Authorization']))) 
		{
			
			$PrimaryCallbackUrl = $_POST['PrimaryCallbackUrl'];
			$Authorization      = $_POST['Authorization'];//GqeaUSbau2pMNWnNLiSYarcJt097zYZo
			$user_response      = $data_Obj->getClientAuthorization($Authorization);//get client authorization code from db...

			$prefix = $data_Obj->_formart_number_233($_POST['CustomerMsisdn']);//check if the momo number if in accepted format...


			// check if the customer is authorized in our system to access our API...........
			if (trim($user_response) != null && trim($user_response) > 0) 
			{
				// all these fields must be provided to continue cash process............
				if(!empty($_POST['CustomerMsisdn']) && !empty($_POST['Product']) && !empty($_POST['Amount']) && !empty($_POST['Network']) && !empty($_POST['Narration']) && !empty($_POST['ServiceDescription']) && !empty($_POST['PrimaryCallbackUrl']))
				{
					if(trim(strlen($_POST['CustomerMsisdn'])) == 12 && trim($prefix) == "233") //is the momo number correct....?
					{
						if(is_numeric($_POST['Amount'])) //&& trim(is_double($_POST['Amount']))
						{
							$params = array(
								"RefNo"    => "MCC-PAY-238963114",
								"ClientId" => "1625",
								"CustomerName" => $_POST['CustomerName'],								
								"CustomerMsisdn" => $_POST['CustomerMsisdn'],
								"Network" => $_POST['Network'],
								"Product" => $_POST['Product'],
								"Amount" => $_POST['Amount'],
								"Narration" => $_POST['Narration'],
								"ServiceDescription" => $_POST['ServiceDescription']
							);

							
							$data = json_encode($params);
							//0240974010  233247954362
							$url  = "http://178.79.172.242/adri/payments/tigo/process_initiate_test.php";
							$curl = curl_init($url);
							// curl_setopt($curl, CURLOPT_URL, $url);
							curl_setopt( $curl, CURLOPT_POST, true );
							curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
							curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
					        // curl_setopt($curl, CURLOPT_HTTPHEADER, array(
					        //     'Cache-Control: no-cache',
					        //     'Content-Type: application/json',
					        // ));

					        $result = curl_exec($curl);
					        $err    = curl_error($curl);
					        curl_close($curl);


					        // $response['Data'] = $result;
					     //    header('Content-Type: application/json');
						    // echo json_encode($result);

					        // var_dump($result) ;

					        $json = json_decode($result, true);

					        file_put_contents("momoAPI.log", print_r($json, true));
					        file_put_contents("momovalue.log", print_r($data, true));
					        $CorrelationId = $json['Data']['CorrelationId'];

					        file_put_contents("CorrelationId.log", print_r($CorrelationId, true));

					        $data_Obj->logUserPaymentRequest('1625', $_POST['CustomerName'], $_POST['CustomerMsisdn'], $_POST['Network'], $_POST['Product'], $_POST['Amount'], $_POST['Narration'], $_POST['ServiceDescription'], $_POST['PrimaryCallbackUrl'], $CorrelationId);
					        echo $result;
					        
						} else {
							$response['Success'] = 'Failed';
							$statusArray['Message'] = 'Amount value not valid.';
							$statusArray['ResponseCode'] = '2006';
							$statusArray['Description'] = 'The amount should be in double.';
							// array_push($dataArray, $statusArray);
							$response['Data'] = $statusArray;				

				            header('Content-Type: application/json');
						    echo json_encode($response);
						}						
					} else 
					{
						$response['Success'] = 'Failed';
						$statusArray['Message'] = 'Wrong CustomerMsisdn Format.';
						$statusArray['ResponseCode'] = '2005';
						$statusArray['Description'] = 'Wrong CustomerMsisdn format provided. It should be a valid momo number in 233 format.';
						// array_push($dataArray, $statusArray);
						$response['Data'] = $statusArray;				

			            header('Content-Type: application/json');
					    echo json_encode($response);
					}					
				} else 
				{
					$response['Success'] = 'Failed';
					$statusArray['Message'] = 'Invalid Request.';
					$statusArray['ResponseCode'] = '2003';
					$statusArray['Description'] = 'Invalid request submitted. Check all parameters and try again.';
					// array_push($dataArray, $statusArray);
					$response['Data'] = $statusArray;				

		            header('Content-Type: application/json');
				    echo json_encode($response);
				}				
			} else 
			{
				$response['Success'] = 'Failed';
				$statusArray['Message'] = 'Request Failed. Non-authorization client.';
				$statusArray['ResponseCode'] = '2002';
				$statusArray['Description'] = 'Wrong client authorization key provided. See MCC Support Team to be enrolled.';
				// array_push($dataArray, $statusArray);
				$response['Data'] = $statusArray;				

	            header('Content-Type: application/json');
			    echo json_encode($response);
			}
			
		} else {
			$response['Success'] = 'Failed';
			$statusArray['Message'] = 'Request Failed. Client authorization failed';
			$statusArray['ResponseCode'] = '2001';
			$statusArray['Description'] = 'Provide Client Authorization. Or see MCC Support Team to be enrolled.';
			// array_push($dataArray, $statusArray);
			$response['Data'] = $statusArray;
			
            header('Content-Type: application/json');
		    echo json_encode($response);
		}
		
	}else
	{
		$response['Success'] = 'Failed';
		$statusArray['Message'] = 'Access Denied.';
		$statusArray['ResponseCode'] = '2000';
		$statusArray['Description'] = 'You cannot access this service.';
		// array_push($dataArray, $statusArray);
		$response['Data'] = $statusArray;
		
        header('Content-Type: application/json');
	    echo json_encode($response);
	}
} else 
{
	$response['Success'] = 'Failed';
	$statusArray['Message'] = 'Wrong Request.';
	$statusArray['ResponseCode'] = '2004';
	$statusArray['Description'] = 'Request should be a POST request, check and try again.';
	// array_push($statusArray, $dataArray);
	$response['Data'] = $statusArray;
	
    header('Content-Type: application/json');
    echo json_encode($response);
}




// $params = array(
// 	"RefNo" => "MCC-PAY-238963114",
// 	"ClientId" => "1625",
// 	"CustomerName" => $_POST['CustomerName'],								
// 	"CustomerMsisdn" => $_POST['CustomerMsisdn'], //"233240974010",
// 	"Network" => $_POST['Network'],
// 	"Product" => $_POST['Product'],
// 	"Amount" => $_POST['Amount'],
// 	"Narration" => "Debit Tigo customer",
// 	"ServiceDescription" => $_POST['ServiceDescription']
// );





