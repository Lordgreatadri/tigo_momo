<?php
ini_set('display errors', false);
/**------------------------------------------------------------------------------------------------------------------------------------------------
 * @@Name: RequestEndPoint
 
 * @@Author: Kyei Amos Mensah <'buyitgh@gmail.com'>
 
 * @Date:   			2019-03-22 11:52:15
 * @Last Modified by:   Kyei Amos Mensah
 * @Last Modified time: 2019-05-15 09:46:21

 * @Copyright: 			waDev Inc. <'owner'>
 
 * @Website: 			https://wadev.com
 *---------------------------------------------------------------------------------------------------------------------------------------------------
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class RequestEndPoint extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->model('Mcc_MTN_payment_Library_model');
	}

	/**
	 * Initialize payment requests (DEBIT)
	 */

	public function initialize_debit_customer_request()
	{

		$randomize = rand(1000000000,100);
		// receive values
		$phone_number = $this->input->get('phone_number');
		$amount       = $this->input->get('amount');

		// var_dump($phone_number);
		// var_dump($amount);
		// code here
		$refNo = "MCC-PAY-".$randomize." ";
		$msisdn = "".$phone_number.""; // "233240974010";
		$debit_amount = "".$amount."";  // "1.00";

		// $msisdn = "233240974010"; // "233240974010";
		// $debit_amount = "1.00";  // "1.00";

		$network = "MTN";
		$voucher = "";
		$narration = "Debit MTN customer";
		// initiallize the Debit call
		$this->Mcc_MTN_payment_Library_model->mcc_debit_customer($refNo, $msisdn, $debit_amount, $network, $voucher, $narration);

		//send response
		$this->Mcc_MTN_payment_Library_model->mcc_debit_customer_response();
	}


	/**
	 *  Callback URL (endpoint)
	 */

	public function process_transaction_result()
	{
		// echo "Endpoint";
		//
		$transaction_response = json_decode(@file_get_contents('php://input')); 

		// log the enpoint response (temp)
		file_put_contents('application/logs/aaaendpoint_results.log', print_r($transaction_response, true));
		$createdTime = date("Y-m-d");
        $file        = fopen("application/logs/aaaendpoint_results-$createdTime.log", 'a');
        $current     = "uniwalletTransactionId: ".$transaction_response->uniwalletTransactionId.", networkTransactionId: ".$transaction_response->networkTransactionId.",  refNo: ".$transaction_response->refNo.", merchantId: ".$transaction_response->merchantId.", productId: ".$transaction_response->productId.", msisdn: ".$transaction_response->msisdn.", amount: ".$transaction_response->amount.", balance: ".$transaction_response->balance.", responseCode: ".$transaction_response->responseCode.", responseMessage: ".$transaction_response->responseMessage." \n";
        fwrite($file, "$current");
        fclose($file);


        #sending response to verify outcome and notify user after payment..................
		$params = array(
			"uniwalletTransactionId" => $transaction_response->uniwalletTransactionId,
	        "refNo" =>  $transaction_response->refNo,   //"MCC-PAY-238963114",
	        "msisdn" => $transaction_response->msisdn, //$get_num,//$get_num, 233558719210 //"233240974010",
	        "responseCode" => $transaction_response->responseCode,  //"1.00",
	        "responseMessage" => $transaction_response->responseMessage,
	        "networkTransactionId" => $transaction_response->networkTransactionId
		);
		$data = json_encode($params);
		//0240974010  233247954362
		$ch =  curl_init('http://mysmsinbox.com/mtn_hitz/ussd/mtn_momotest_callback.php');
		curl_setopt( $ch, CURLOPT_POST, true );
		curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	    // curl_setopt($ch, CURLOPT_HTTPHEADER, array(
	    //     'Cache-Control: no-cache',
	    //     'Content-Type: application/json',
	    // ));

	    $result = curl_exec($ch);
	    $err    = curl_error($ch);
	    curl_close($ch);


        // $dat = fopen("application/logs/aaae-$createdTime.log", 'a');
        // fwrite($dat, "$transaction_response, \n");
        // fclose($fidatle);


        // $current     = "uniwalletTransactionId: ".$transaction_response['uniwalletTransactionId'].", networkTransactionId: ".$transaction_response['networkTransactionId'].",  refNo: ".$transaction_response['refNo'].", merchantId: ".$transaction_response['merchantId'].", productId: ".$transaction_response['productId'].", msisdn: ".$transaction_response['msisdn'].", amount: ".$transaction_response['amount'].", balance: ".$transaction_response['balance'].", responseCode: ".$transaction_response['responseCode'].", responseMessage: ".$transaction_response['responseMessage']." \n";
        // fwrite($file, "$current");
        // fclose($file);

		// send callback response
		$this->Mcc_MTN_payment_Library_model->callback_response();

	}


	/**
	 * 	Initialize payment request (CREDIT)
	 */

	public function initialize_credit_customer_request()
	{
		// params here 
		$randomize = rand(1000000000,100);
		$refNo = "MCC-PAY-".$randomize." ";
		// $refNo = "MCC-PAY-37270733";
		$msisdn = "233240974010"; // "233240974010";
		$credit_amount = "1.00";  // "1.00";
		$network = "MTN";
		$voucher = "";
		$narration = "Credit MTN customer (Refund policy)";

		// Inintialize Credit call 
		$initialze_request = $this->Mcc_MTN_payment_Library_model->mcc_credit_customer( $refNo, $msisdn, $credit_amount, $network, $narration);

		// send credit response
		$this->Mcc_MTN_payment_Library_model->mcc_credit_customer_response();
		
	}
	


} 
/**
 *  END
 */