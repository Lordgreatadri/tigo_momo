<?php 

include_once 'includes/autoloader.inc.php';

// include_once 'MCC_CI_Database.php';
	/**
	 * 
	 */
	class Reverse_Transaction_Data_Auths extends MCC_CI_Database
	{	
		
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
		public function get_reverse_endpoint_url($reverse_transaction_url='')
		{
			try 
			{	
				$query =  $this->db_conn->query("SELECT reverse_transaction_url FROM mcc_user_details LIMIT 0,1");
				$query->execute();

				// set the resulting array to associative
				$result = $query->setFetchMode(PDO::FETCH_ASSOC);
				$data = $query->fetchAll();

				$endpoint  = "";
				foreach ($data as $key) 
				{
					$endpoint  = $key['reverse_transaction_url'];
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


// transaction_id
// external_transaction_id
// request_correlator_id
// request_transaction_id
// transaction_type
// payment_reference
// api_type
// status
// response_code
// description



		// save save_reverse_transaction request............
		public function save_reverse_transaction($transaction_id, $external_transaction_id, $request_correlator_id, $request_transaction_id, $transaction_type, $payment_reference, $api_type, $status, $response_code, $description, $utiba_transaction_id)
		{
			try 
			{	
				$stmnt = "INSERT INTO reverse_transaction(transaction_id, external_transaction_id, request_correlator_id, request_transaction_id, transaction_type, payment_reference, api_type, status, response_code, description, utiba_transaction_id) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
				$values = array($transaction_id, $external_transaction_id, $request_correlator_id, $request_transaction_id, $transaction_type, $payment_reference, $api_type, $status, $response_code, $description, $utiba_transaction_id);

				$query = $this->db_conn->prepare($stmnt);
				$query->execute($values);
				$counts = $query->rowCount();

				return $counts;	
			} catch (Exception $exc) 
			{
				echo __LINE__ . $exc->getMessage();
			}
		}




		// get payment url .............
		public function get_current_transaction_details($transaction_id)
		{
			try 
			{	
				$query =  $this->db_conn->query("SELECT * FROM reverse_transaction WHERE transaction_id = '$transaction_id'  ORDER BY rev_id DESC LIMIT 0,1");
				$query->execute();

				// set the resulting array to associative
				$result = $query->setFetchMode(PDO::FETCH_ASSOC);

				return $query->fetchAll();
			} catch (Exception $exc) 
			{
				echo __LINE__ . $exc->getMessage();
			}
		}



		//update revers's tranasaction if there is error...............
		public function update_reversed_transactions_status($external_transaction_id, $correlator_value, $response_code, $status, $description)
		{
			try 
			{
				$stmnt = "UPDATE reversed_transactions SET external_transaction_id=:external_transaction_id, response_code=:response_code, status=:status, description=:description WHERE correlation_id=:correlation_id";
				$query = $this->db_conn->prepare($stmnt);
				$value = array('external_transaction_id'=>$external_transaction_id, 'response_code'=>$response_code, 'status'=>$status, 'description'=>$description, 'correlation_id'=>$correlator_value);
				
				$query->execute($value);

				//count number of row affected
				$count_row = $query->rowCount();
				return $count_row;
			} catch (Exception $e) 
			{
				echo __LINE__ . $e->getMessage();
			}
		}

		 


		//update service payment status.............
		 public function reversePaymentFund($request_correlator_id, $utibaTransactionId)
		 {
		 	try 
			{
				$reversed_on = date("Y-m-d H:i:s");
				$stmnt = "UPDATE service_payments SET is_reversed=:is_reversed, reversed_on=:reversed_on WHERE correlation_id=:correlation_id OR result_transaction_id=:result_transaction_id";
				$query = $this->db_conn->prepare($stmnt);
				$value = array('is_reversed'=>"True", 'reversed_on'=>$reversed_on, 'correlation_id'=>$request_correlator_id, 'result_transaction_id'=>$utibaTransactionId);
				
				$query->execute($value);

				//count number of row affected
				$count_row = $query->rowCount();
				return $count_row;
			} catch (Exception $e) 
			{
				echo __LINE__ . $e->getMessage();
			}
		}





		//update item purchase status.............
		public function reversePurchaseFund($request_correlator_id, $utibaTransactionId)
		{
		 	try 
			{
				$reversed_on = date("Y-m-d H:i:s");
				$stmnt = "UPDATE general_transactions SET is_reversed=:is_reversed, reversed_on=:reversed_on WHERE correlation_id=:correlation_id OR utibaTransactionId=:utibaTransactionId";
				$query = $this->db_conn->prepare($stmnt);
				$value = array('is_reversed'=>"True", 'reversed_on'=>$reversed_on, 'correlation_id'=>$request_correlator_id, 'utibaTransactionId'=>$utibaTransactionId);
				
				$query->execute($value);

				//count number of row affected
				$count_row = $query->rowCount();
				return $count_row;
			} catch (Exception $e) 
			{
				echo __LINE__ . $e->getMessage();
			}
		}



		//update callback purchase status.............
		public function reversecallbackStatus($request_correlator_id, $utibaTransactionId)
		{
		 	try 
			{
				$reversed_on = date("Y-m-d H:i:s");
				$stmnt = "UPDATE general_tansactions_callback SET is_reversed=:is_reversed, reversed_on=:reversed_on WHERE correlation_id=:correlation_id OR utibaTransactionId=:utibaTransactionId";
				$query = $this->db_conn->prepare($stmnt);
				$value = array('is_reversed'=>"True", 'reversed_on'=>$reversed_on, 'correlation_id'=>$request_correlator_id, 'utibaTransactionId'=>$utibaTransactionId);
				
				$query->execute($value);

				//count number of row affected
				$count_row = $query->rowCount();
				return $count_row;
			} catch (Exception $e) 
			{
				echo __LINE__ . $e->getMessage();
			}
		}
 
 
	}