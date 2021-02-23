<?php

	require_once 'tigo_data_functions.php';
	$data_Obj        = new tigo_data_functions();


	$callback_Obj    = file_get_contents("php://input");
    $json            = json_decode($callback_Obj, true);

    file_put_contents("logs/callback.log", print_r($json, true));


    $message         = "";

    $ResponseCode    = $json['ResponseCode'];
    //$AmountAfterCharges = $json['Data']['AmountAfterCharges'];
    $TransactionId   = $json['Data']['TransactionId'];
    $ClientReference = $json['Data']['ClientReference'];
    $CorrelationId   = $json['Data']['CorrelationId'];
    $Description     = $json['Data']['Description'];
    $Status  		 = $json['Data']['Status'];
    $ExternalTransactionId = $json['Data']['ExternalTransactionId'];
    $Amount  		 = $json['Data']['Amount'];
    $Charges 		 = $json['Data']['Charges'];


    $VoteCount       = 0;
    $ContestantId    = "";
    $ContestantName  = "";

    // fetch current Transaction details..........getClientTransactions($payment_reference = '', $transaction_id = '')
    foreach ($data_Obj->getTransactionDetails($ClientReference, $TransactionId) as $key) 
    {
    	$customerAccount = $key['momo_number'];
    	$Amount          = $key['amount'];
    	$ContestantName  = $key['ContestantName'];
    	$Cabinet         = $key['cabinet'];
    	$VoteCount       = $key['amount'];
    	$ContestantNum   = $key['contestant_num'];
    }



    // check if payment was successful..............
    if (trim($ResponseCode) == "0000" && trim($Status) == "OK") 
    {
    	$currentVotes = 0;
    	// get the chosen contestant data for new vote update...........
    	foreach ($data_Obj->getContestantData(trim($ContestantName), trim($Cabinet), trim($ContestantNum)) as $contestant) 
    	{
    		$currentVotes = $contestant['number_of_votes'];
    	}

    	$newVotes = $currentVotes + $VoteCount;

    	// update the vote with current vote plan..............
    	$data_Obj->updateContestantVotes($ContestantNum, $newVotes);

    	// notify user for successful payment.............
        $message = "You voted successfully for ".$ContestantName." \n~~ PCabinet Voting.";
    	$data_Obj->sendUserResponse($customerAccount, $message);
    	// $ptn = "/^0/";  // Regex
	     //$str = $number; //Your input, perhaps $_POST['msisdn']
	     //$rpltxt = "233";  // Replacement string
	     //$sender = preg_replace($ptn, $rpltxt, $str);
	     //$sendSms->send_response($sender, $Description."-BEHIND THE VOICE");
    } else {
    	$message = "Transaction Failed:\n".$Description." \n~~ PCabinet Voting.";

    	// notify user for failed Transaction................
    	$data_Obj->sendUserResponse($customerAccount, $message);
    }
    


