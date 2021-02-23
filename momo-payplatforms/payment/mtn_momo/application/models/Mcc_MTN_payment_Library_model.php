<?php

/**------------------------------------------------------------------------------------------------------------------------------------------------
 * @@Name: Mcc_MTN_payment_Library_model
 
 * @@Author: Kyei Amos Mensah <'buyitgh@gmail.com'>
 
 * @Date:   			2019-03-22 13:33:22
 * @Last Modified by:   Kyei Amos Mensah
 * @Last Modified time: 2019-04-08 16:04:53

 * @Copyright: 			waDev Inc. <'owner'>
 
 * @Website: 			https://wadev.com
 *---------------------------------------------------------------------------------------------------------------------------------------------------
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Mcc_MTN_payment_Library_model extends CI_Controller
{
	

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		// $this->load->model('Master_model');
	}

/**
 *  Debit Customer Api 
 */

	public function mcc_debit_customer( $refNo, $msisdn, $amount, $network, $voucher, $narration )
	{
 
		// prepare params here and pass to below.
		$post_debit_request = "http://68.169.63.40:6565/uniwallet/debit/customer";

		$merchantId = "1625";
		$productId = "150";
		$apiKey = "GqeaUSbau2pMNWnNLiSYarcJt097zYZo";

        $data        = array(
            "merchantId" => "" . $merchantId . "",
            "productId" => "" . $productId . "",
            "refNo" => "" . $refNo . "",
            "msisdn" => "" . $msisdn . "",
            "amount" => "" . $amount . "",
            "network" => "" . $network . "",
            "voucher" => "" . $voucher . "",
            "narration" => "" . $narration . "",

            "apiKey" => "" . $apiKey . ""
        );
        $data_string = json_encode($data);
        
        print_r($data_string);
        
        $ch = curl_init("" . $post_debit_request . "");
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // curl_setopt($ch, CURLOPT_URL, $client_endpoint);

        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json'
        ));
        
        // var_dump($ch);
        // echo json_decode($ch);
        $result = curl_exec($ch);
        $error  = curl_error($ch); //Life is hard!, trap some errors if availabe plsss
        
        // echo json_decode($result);
        if ($error) {
            echo "cURL Error #:" . $error; // show me wai.
        } else {
            
            var_dump($result);
        }
        
        var_dump($ch); // Debug
        
        curl_close($ch);


        return $result;
	}

    /**
     *  Debit customer response
     */

    public function mcc_debit_customer_response()
    {
        $response =  array(
            "responseCode" => "03",
            "responseMessage" => "Processing payment."
        );
        $encode_response = json_encode($response);

        echo $encode_response;
    }


    /**
     *  Credit Customer API
     */

    public function mcc_credit_customer( $refNo, $msisdn, $amount, $network, $narration)
    {
        //code here
        $post_debit_request = "http://68.169.63.40:6565/uniwallet/v2/credit/customer";

        $merchantId = "1625";
        $productId = "150";
        $apiKey = "GqeaUSbau2pMNWnNLiSYarcJt097zYZo";

        $data        = array(
            "merchantId" => "" . $merchantId . "",
            "productId" => "" . $productId . "",
            "refNo" => "" . $refNo . "",
            "msisdn" => "" . $msisdn . "",
            "amount" => "" . $amount . "",
            "network" => "" . $network . "",
            "narration" => "" . $narration . "",

            "apiKey" => "" . $apiKey . ""
        );
        $data_string = json_encode($data);
        
        print_r($data_string);
        
        $ch = curl_init("" . $post_debit_request . "");
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // curl_setopt($ch, CURLOPT_URL, $client_endpoint);

        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json'
        ));
        
        // var_dump($ch);
        // echo json_decode($ch);
        $result = curl_exec($ch);
        $error  = curl_error($ch); //Life is hard!, trap some errors if availabe plsss
        
        // echo json_decode($result);
        if ($error) {
            echo "cURL Error #:" . $error; // show me wai.
        } else {
            
            var_dump($result);
        }
        
        var_dump($ch); // Debug
        
        curl_close($ch);

        return $result;
    }


    /**
     *  Credit customer response
     */

    public function mcc_credit_customer_response()
    {
        $response =  array(
            "responseCode" => "03",
            "responseMessage" => "Processing payment."
        );
        $encode_response = json_encode($response);

        echo $encode_response;
    }


    
    /**
     *  Prepare Callback response for Endpoint  Callback
     */

    public function callback_response()
    {
        $response =  array(
            "responseCode" => "01",
            "responseMessage" => "Callback Successful."
        );
        $encode_response = json_encode($response);

        echo $encode_response;

    }






} 
/**
 *  END
 */