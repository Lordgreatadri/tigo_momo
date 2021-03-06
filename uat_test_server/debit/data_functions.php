<?php

require_once 'Pcabinet_dbCon.php';
/**
 * 
 */
	class data_functions extends db_conn
	{
		// fetch contestants data to award votes................
		public function getContestantData($contestant_name, $cabinet, $contestant_num)
		{
			try 
			{
					$query =  $this->db_conn->query("SELECT * FROM contestants WHERE name = '$contestant_name' AND cabinet = '$cabinet' AND contestant_num = '$contestant_num' LIMIT 0,1");
					$query->execute();

					// set the resulting array to associative
					$result = $query->setFetchMode(PDO::FETCH_ASSOC);
					return  $query->rowCount();
				
			} catch (Exception $ex) {
				return $ex->getMessage();
			}
		}
		



		//update cotestant's vote if payment succeed ...............
		public function updateContestantVotes($contestant_num, $num_of_votes)
		{
			try 
			{
				$stmnt = "UPDATE contestants SET num_of_votes=:num_of_votes WHERE contestant_num=:contestant_num";
				$query = $this->db_conn->prepare($stmnt);
				$value = array('num_of_votes'=>$num_of_votes, 'contestant_num'=>$contestant_num);
				
				$query->execute($value);

				//count number of row affected
				$count_row = $query->rowCount();
				return $count_row;
			} catch (Exception $e) 
			{
				echo __LINE__ . $e->getMessage();
			}
		}
		


		// log the initial payment request data.................
		public function logUserPaymentRequest($transaction_id, $external_transaction_id, $correlation_id, $initiate_status, $initiate_code, $initiate_description, $payment_id, $payment_reference, $contestant_num, $contestant_name, $cabinet, $momo_number, $amount)
		{
			try 
			{
				$stmnt = "INSERT INTO momo_payments(transaction_id, external_transaction_id, correlation_id, initiate_status, initiate_code, initiate_description, payment_id, payment_reference, contestant_num, contestant_name, cabinet, momo_number, amount) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?)";
				$values = array($transaction_id, $external_transaction_id, $correlation_id, $initiate_status, $initiate_code, $initiate_description, $payment_id, $payment_reference, $contestant_num, $contestant_name, $cabinet, $momo_number, $amount);
				$query = $this->db_conn->prepare($stmnt);
				$query->execute($values);
			    $rowcount = $query->rowCount();
			    return $rowcount;
				//$this->db_conn = null;
			} catch (Exception $exc) 
			{
				echo __LINE__ . $exc->getMessage();
			}
		}




		// fetch transaction record for vote processing...............
		public function getTransactionDetails($correlation_id)
		{
			try 
			{
				// if($correlation_id) 
				// {
					$query =  $this->db_conn->query("SELECT * FROM momo_payments WHERE correlation_id = '$correlation_id' LIMIT 0,1");
					$query->execute();

					// set the resulting array to associative
					$result = $query->setFetchMode(PDO::FETCH_ASSOC);
					return $query->fetchAll();
				// } else {
				// 	return null;
				// }
				
				
			} catch (Exception $ex) {
				return $ex->getMessage();
			}
		}






		//update payment's response if there is error or not ...............
		public function update_PaymentRequest_status($utibaTransactionId, $response_code, $response_description, $correlation_id)
		{
			try 
			{
				$stmnt = "UPDATE momo_payments SET utibaTransactionId=:utibaTransactionId, response_code=:response_code, response_description=:response_description WHERE correlation_id=:correlation_id";
				$query = $this->db_conn->prepare($stmnt);
				$value = array('utibaTransactionId'=>$utibaTransactionId, 'response_code'=>$response_code, 'response_description'=>$response_description, 'correlation_id'=>$correlation_id);
				
				$query->execute($value);

				//count number of row affected
				$count_row = $query->rowCount();
				return $count_row;
			} catch (Exception $e) 
			{
				echo __LINE__ . $e->getMessage();
			}
		}



		// logging the callback response..........
		public function logPaymentResponse($transaction_id, $external_transaction_id, $correlation_id, $status, $response_code, $customer_msisdn, $description, $amount)
		{
			try 
			{
				$stmnt = "INSERT INTO completed_transactions(transaction_id, external_transaction_id, correlation_id, status, response_code, customer_msisdn, description, amount) VALUES(?, ?, ?, ?, ?, ?, ?, ?)";
				$values = array($transaction_id, $external_transaction_id, $correlation_id, $status, $response_code, $customer_msisdn, $description, $amount);
				$query = $this->db_conn->prepare($stmnt);
				$query->execute($values);
			    $rowcount=$query->rowCount();
			    return $rowcount;
				//$this->db_conn = null;
			} catch (Exception $exc) 
			{
				echo __LINE__ . $exc->getMessage();
			}
		}




		// send momo request with needed parameters.............
		public function send_momo_request($voter_number, $amount, $contestant_name, $contestant_code, $cabinet)
	    {
	    	$params = array(
	    		"api_key"            => "mcc@tigoc@sh",
				"RefNo"              => "MCC-PAY-238963114",
				"ClientId"           => "1625",
				"CustomerName"       => "MCC-TIGO-TEST",								
				"CustomerMsisdn"     => $voter_number, //"233240974010",
				"Network"            => "tigo-gh",
				"Product"            => "Vote",
				"Amount"             => $amount,
				"Narration"          => "Debit Tigo customer",
				"ServiceDescription" => "Tigo momo testing on web portal",
				"Authorization"      => "GqeaUSbau2pMNWnNLiSYarcJt097zYZo",
				"PrimaryCallbackUrl" => "http://178.79.172.242/adri/uat/tigo/category/payment_callback.php"
			);


			$data = json_encode($params);

			//0240974010  233247954362
			$url  = "tigo_pay/tigo_cash_request.php";
			$curl = curl_init();
			curl_setopt($curl, CURLOPT_URL, $url);
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

	        $json = json_decode($result, true);
	        file_put_contents("logs/momoApi.log", print_r($json, true));
	        file_put_contents("logs/data.log", print_r($data, true));

	        
	        $this->logUserPaymentRequest($json['Data']['TransactionId'], $json['Data']['ExternalTransactionId'], $json['Data']['CorrelationId'], $json['Data']['Status'], $json['Data']['ResponseCode'], $json['Data']['Description'], $json['Data']['PaymentId'], $json['Data']['PaymentReference'], $contestant_code, $contestant_name, $cabinet, $voter_number, $amount);
	        // var_export($result);
	    }




	    // format the user msisdn to the required format...........
	    public function _formart_number($voter_number)
	    {
	        $myNew_value = null;
	        $voting_number = '';
	        if(strlen($voter_number) == 10)
            {   
                //convert your string into array
                $array_num = str_split($voter_number);

                for($i = 1; $i <count($array_num) ; $i++)
                {        
                    $myNew_value .= $array_num[$i];
                }
                 
                $voting_number = '233'. $myNew_value;
            }else
            {
                $voting_number = $voter_number; 
            }

	        return $voting_number;
	    }



	    // process the channel type from msisdn.............
	    public function get_channel_type($voter_number) 
		{
			try 
			{
				//first check if the number recieved in 233 format........
				$myNew_value=null;
				$voting_number ='';//count($striped_num)
				if(strlen($voter_number) > 10)
				{	
					//convert your string into array
					$array_num = str_split($voter_number);

					for($i = 3; $i <count($array_num) ; $i++)
					{	     
					    $myNew_value .= $array_num[$i];
					}
					 
					$voting_number = '0'. $myNew_value;
				}else
				{
					$voting_number = $voter_number;	
				}


				//check for channell type.................................
				$result = mb_substr($voting_number, 0, 3);

				$network = '';
				if(trim($result)  == '054'  || trim($result) == '055' || trim($result) == '024' || trim($result) == '059')
				{
					$network = 'mtn-gh';
					
				}elseif($result == '027' || $result == '057' || $result == '026' || $result == '056') 
				{
					$network = 'tigo-gh';
				}elseif($result == '020' || $result == '050') 
				{
					$network = 'vodafone-gh-ussd';
				}else
				{
					$network = null;
				}

				return $network;
				// var_dump($network);
			} catch (Exception $exc) 
			{
				echo __LINE__ . $exc->getMessage();
			}
		}




		// function for sms notification to the user after callback.......
		public function sendUserResponse($user_number, $message)
		{
			try 
	        {
	            $myNew_value = null;
	            $raw_number  ='';
	            if(strlen($user_number) == 10)
	            {   //convert your string into array
	                $array_num = str_split($user_number);

	                for($i = 1; $i <count($array_num) ; $i++)
	                {        
	                    $myNew_value .= $array_num[$i];
	                }
	                 
	                $raw_number = '233'. $myNew_value;

	            }else
	            {
	                $raw_number = $user_number; 
	            }
	            
	            $msisdn = $raw_number;//var_dump($msisdn);
	            $message = urlencode($message);//200.2.168.175:2199 
	            $url = "http://34.230.90.80:2789/Receiver?User=tv3quiz&Pass=T3Q3v3&From=1470&To=$msisdn&Text=$message";
	            $curl = curl_init();
	            curl_setopt_array($curl, array(
	                CURLOPT_RETURNTRANSFER => 1,
	                CURLOPT_URL => $url
	            ));
	           
	            $result = curl_exec($curl);
	            $error = curl_error($curl);

	            if ($error) {
	                echo "There was an: ". $error;
	            } else{
	                //var_dump($result);
	            }
	            // echo json_encode($result);
	            curl_close($curl);
	            return $result;

	        }catch (Exception $exc) 
	        {
	            echo __LINE__ . $exc->getMessage();
	        }
		}
	}



