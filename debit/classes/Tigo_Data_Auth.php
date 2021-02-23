<?php 

/**------------------------------------------------------------------------------------------------------------------------------------------------
 * @@Name:              Tigo_Data_Auth file
 
 * @@Author: 			Lordgreat - Adri Emmanuel <'rexmerlo@gmail.com'>
 
 * @Date:   			2018-09-27 11:00:30
 * @Last Modified by:   Lordgreat -  Adri Emmanuel
 * @Last Modified time: 2018-09-27 02:35:30

 * @Copyright: 			MobileContent.Com Ltd <'owner'>
 
 * @Website: 			https://mobilecontent.com.gh
 *---------------------------------------------------------------------------------------------------------------------------------------------------
 */

include_once 'includes/autoloader.inc.php';
	
	class Tigo_Data_Auth extends MCC_CI_Database
	{
		
		//fetch users from the database..............
		public function fetch_user_details()
		{
			try 
			{	
				$query =  $this->db_conn->query("SELECT * FROM users");
				$query->execute();

				// set the resulting array to associative
				$result = $query->setFetchMode(PDO::FETCH_ASSOC);
				return $query->fetchAll();
			} catch (Exception $exc) 
			{
				echo __LINE__ . $exc->getMessage();
			}
		}

		
		
		//fetch customer from the database..............
		public function fetch_customer_details()
		{
			try 
			{	
				$query =  $this->db_conn->query("SELECT * FROM mcc_user_details LIMIT 0,1");
				$query->execute();

				// set the resulting array to associative
				$result = $query->setFetchMode(PDO::FETCH_ASSOC);
				return $query->fetchAll();
			} catch (Exception $exc) 
			{
				echo __LINE__ . $exc->getMessage();
			}
		}



		// get payment url .............
		public function get_payment_endpoint_url($purchase_initiate_url='')
		{
			try 
			{	
				$query =  $this->db_conn->query("SELECT purchase_initiate_url FROM mcc_user_details LIMIT 0,1");
				$query->execute();

				// set the resulting array to associative
				$result = $query->setFetchMode(PDO::FETCH_ASSOC);
				$data = $query->fetchAll();

				$endpoint  = "";
				foreach ($data as $key) 
				{
					$endpoint  = $key['purchase_initiate_url'];
				}

				return $endpoint;
			} catch (Exception $exc) 
			{
				echo __LINE__ . $exc->getMessage();
			}
		}







		// get payment url .............
		public function get_authorization_code($service_purchase='')
		{
			try 
			{	
				$query =  $this->db_conn->query("SELECT accessKey FROM mcc_user_details LIMIT 0,1");
				$query->execute();

				// set the resulting array to associative
				$result = $query->setFetchMode(PDO::FETCH_ASSOC);
				$data = $query->fetchAll();

				$authorization_code  = "";
				foreach ($data as $key) 
				{
					$authorization_code  = $key['accessKey'];
				}

				return $authorization_code;
			} catch (Exception $exc) 
			{
				echo __LINE__ . $exc->getMessage();
			}
		}



		#save record to the trasanction request table.................................
		public function save_transaction_initiate_response($transaction_id, $external_transaction_id, $correlation_id, $payment_reference, $msisdn, $item, $amount, $initiate_status, $initiate_code, $initiate_description, $payment_id)
		{
			try 
			{
				$stmnt = "INSERT INTO general_transactions(transaction_id, external_transaction_id, correlation_id, payment_reference, msisdn, item, amount, initiate_status, initiate_code, initiate_description, payment_id) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
				$values = array($transaction_id, $external_transaction_id, $correlation_id, $payment_reference, $msisdn, $item, $amount, $initiate_status, $initiate_code, $initiate_description, $payment_id);
				$query = $this->db_conn->prepare($stmnt);
				$query->execute($values);
			    $rowcount = $query->rowCount();
			    return $rowcount;
			} catch (Exception $exc) 
			{
				echo __LINE__ . $exc->getMessage();
			}
		}







		//update payment's response if there is error or not ...............
		public function update_PaymentRequest_status($utibaTransactionId, $response_code, $payment_description, $payment_status, $correlation_id)
		{
			try 
			{
				$stmnt = "UPDATE general_transactions SET utibaTransactionId=:utibaTransactionId, response_code=:response_code, payment_description=:payment_description, payment_status=:payment_status WHERE correlation_id=:correlation_id OR payment_reference=:payment_reference";
				$query = $this->db_conn->prepare($stmnt);
				$value = array('utibaTransactionId'=>$utibaTransactionId, 'response_code'=>$response_code, 'payment_description'=>$payment_description, 'payment_status'=> $payment_status, 'correlation_id'=>$correlation_id, 'payment_reference'=>$correlation_id);
				
				$query->execute($value);

				//count number of row affected
				$count_row = $query->rowCount();
				return $count_row;
			} catch (Exception $e) 
			{
				echo __LINE__ . $e->getMessage();
			}
		}







		// logging the callback response..........general_tansactions_callback
		public function logPaymentResponse($transaction_id, $external_transaction_id, $correlation_id, $msisdn, $utibaTransactionId, $payment_status, $response_code, $payment_description, $amount)
		{
			try 
			{
				$stmnt = "INSERT INTO general_tansactions_callback(transaction_id, external_transaction_id, correlation_id, msisdn, utibaTransactionId, payment_status, response_code, payment_description, amount) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?)";
				$values = array($transaction_id, $external_transaction_id, $correlation_id, $msisdn, $utibaTransactionId, $payment_status, $response_code, $payment_description, $amount);
				$query = $this->db_conn->prepare($stmnt);
				$query->execute($values);
			    $rowcount=$query->rowCount();
			    return $rowcount;
			} catch (Exception $exc) 
			{
				echo __LINE__ . $exc->getMessage();
			}
		}









		//fetch customer from the database..............
		public function fetch_transaction_detail_for_customer($correlation_id)
		{
			try 
			{	
				$query =  $this->db_conn->query("SELECT * FROM general_transactions WHERE correlation_id = '$correlation_id' OR payment_reference = '$correlation_id' ORDER BY id DESC LIMIT 0,1");
				$query->execute();

				// set the resulting array to associative
				$result = $query->setFetchMode(PDO::FETCH_ASSOC);
				return $query->fetchAll();
			} catch (Exception $exc) 
			{
				echo __LINE__ . $exc->getMessage();
			}
		}

		 

		//update initiator's tranasaction if there is error...............
		public function update_initiator_status($correlator_value, $error_code, $error_status, $error_description)
		{
			try 
			{
				$stmnt = "UPDATE general_transactions SET initiate_code=:initiate_code, initiate_status=:initiate_status, initiate_description=:initiate_description WHERE correlation_id=:correlation_id";
				$query = $this->db_conn->prepare($stmnt);
				$value = array('initiate_code'=>$error_code,'initiate_status'=>$error_status,'initiate_description'=>$error_description, 'correlation_id'=>$correlator_value);
				
				$query->execute($value);

				//count number of row affected
				$count_row = $query->rowCount();
				return $count_row;
			} catch (Exception $e) 
			{
				echo __LINE__ . $e->getMessage();
			}
		}


		public function get_PrimaryCallbackUrl($value='')
		{
			try 
			{	
				$query =  $this->db_conn->query("SELECT primary_callback_url FROM mcc_user_details LIMIT 0,1");
				$query->execute();

				// set the resulting array to associative
				$result = $query->setFetchMode(PDO::FETCH_ASSOC);
				$data = $query->fetchAll();

				$primary_callback_url  = "";
				foreach ($data as $key) 
				{
					$primary_callback_url  = $key['primary_callback_url'];
				}

				return $primary_callback_url;
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



        //process final callback data...........
        public function airteltigo_cash_callback_response($utibaTransactionId, $correlationID, $status, $response_code, $payment_description)
        {

        	if(trim($utibaTransactionId) && trim($correlationID)) 
        	{
        		$CustomerMsisdn = "";
    			$Amount         = "";
    			$Narration      = "";
    			$transaction_id = "";
    			$external_transaction_id  = "";
    			$payment_reference = "";

        		foreach($this->fetch_transaction_detail_for_customer($correlationID) as $key) 
        		{
        			$CustomerMsisdn = $key['msisdn'];
        			$Amount         = $key['amount'];
        			$Narration      = $key['item'];
        			$transaction_id = $key['transaction_id'];
        			$external_transaction_id  = $key['external_transaction_id'];
        			$payment_reference = $key['payment_reference'];
        		}


        		// update with callback response.........
        		$this->update_PaymentRequest_status($utibaTransactionId, $response_code, $payment_description, $status, $correlationID);

        		// log the callback response separately ........
        		$this->logPaymentResponse($transaction_id, $external_transaction_id, $correlationID, $CustomerMsisdn, $utibaTransactionId, $status, $response_code, $payment_description, $Amount);

        		// sms user for testing purpose...........
        		$message = "Your payment was successful on MCC platform.";
        		$this->sendUserResponse($CustomerMsisdn, $message);
/*
        		$PrimaryCallbackUrl = $this->get_PrimaryCallbackUrl();

        		$data = array(
        			"UtibaTransactionId" => $utibaTransactionId,
        			"CorrelationId" => $correlationID,
        			"Description"   => $payment_description,
        			"Status"        => $status,
        			"ExternalTransactionId" => $external_transaction_id,
        			"Amount"        => $Amount,
        			"ClientReference" => $payment_reference,
        			"Product"       => $Narration,
        			"CustomerMsisdn"=> $CustomerMsisdn
        		);

        		$response = array();

        		$response['ResponseCode'] = $response_code;
        		$response['Data'] = $data;

        		$data = json_encode($params); //Please kindly submit raw array data (params) here...


				$curl = curl_init($PrimaryCallbackUrl);
				// curl_setopt($curl, CURLOPT_URL, $url);

				// curl_setopt( $curl, CURLOPT_POST, true );
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
				curl_setopt($curl, CURLOPT_POSTFIELDS, $data);//Just submit the raw array....
				curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
		            'Cache-Control: no-cache',
		            'Content-Type: application/json',
		        ));

		        $result = curl_exec($curl);
		        $err    = curl_error($curl);
		        curl_close($curl);


		        file_put_contents("logs/clientCallback.log",$data);
*/
        	}       	
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




	    // format the user msisdn to the required format...........
	    public function _formart_number_233($voter_number)
	    {
	        $myNew_value = null;
	        $result = "";
	        if(strlen($voter_number) == 12)
            {   
                //convert your string into array
                $array_num = str_split($voter_number);

                $result = mb_substr($voter_number, 0, 3);	
            }else
            {
                $result = ""; 
            }

	        return $result;
	    }


		#select and cross check if receiving customer detail exist and is account holder
		public function verify_receiver_detail($customer_account)
		{
			try 
			{
				$query =  $this->db_conn->query("SELECT * FROM consumer_details WHERE customer_account = '$customer_account' LIMIT 0,1");
				$query->execute();

				// set the resulting array to associative
				$result = $query->setFetchMode(PDO::FETCH_ASSOC);
				return $query->fetchAll();

			} catch (Exception $exc) 
			{
				echo __LINE__ . $exc->getMessage();
			}
		}



		
	}
 ?>