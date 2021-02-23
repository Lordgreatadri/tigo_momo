<?php 
	require_once 'db_class.php';
	/**
	 * 
	 */
	class payment_data_auth extends db_links
	{		
			//fetch customer from the database..............
		public function fetch_customer_details()
		{
			try 
			{	
				$query =  $this->db_conn->query("SELECT * FROM consumer_details LIMIT 0,1");
				$query->execute();

				// set the resulting array to associative
				$result = $query->setFetchMode(PDO::FETCH_ASSOC);
				return $query->fetchAll();
			} catch (Exception $exc) 
			{
				echo __LINE__ . $exc->getMessage();
			}
		}




		//saving the get purchase initiator's detail......................
		public function save_purcase_details_request($ext_transaction_id, $transaction_id, $correlation_id, $result_transaction_id, $user_reference, $payerWallet, $payeeWallet, $amount, $responseCode, $status, $code_type, $description, $additional_info)
		{
			try 
			{	
				$stmnt = "INSERT INTO service_payments(ext_transaction_id, transaction_id, correlation_id, result_transaction_id, user_reference, source_number, target_number, amount, response_code, status, code_type, description, additional_info) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
				
				$values = array($ext_transaction_id, $transaction_id, $correlation_id, $result_transaction_id, $user_reference, $payerWallet, $payeeWallet, $amount, $responseCode, $status, $code_type, $description, $additional_info);

				$query = $this->db_conn->prepare($stmnt);
				$query->execute($values);
				$counts = $query->rowCount();

				return $counts;	
			} catch (Exception $exc) 
			{
				echo __LINE__ . $exc->getMessage();
			}
		}


		//saving the get purchase initiator's detail......................
		public function save_purcase_details_error($transactionID, $correlation_id, $descriptions, $error_codes, $error_status, $additionalInfo)
		{
			try 
			{	
				$stmnt = "INSERT INTO get_purchase_details(extTransactionId, correlationID, description, responseCode, status, additionalInfo) VALUES(?, ?, ?, ?, ?, ?)";
				$values = array($transactionID, $correlation_id, $descriptions, $error_codes, $error_status, $additionalInfo);

				$query = $this->db_conn->prepare($stmnt);
				$query->execute($values);
				$counts = $query->rowCount();

				return $counts;	
			} catch (Exception $exc) 
			{
				echo __LINE__ . $exc->getMessage();
			}
		}
		


		//update initiator's tranasaction if there is error...............
		public function update_purcase_details_status($correlator_value, $error_code, $error_status, $error_description)
		{
			try 
			{
				$stmnt = "UPDATE get_purchase_details SET responseCode=:responseCode, status=:status, description=:description WHERE correlationID=:correlation_id";
				$query = $this->db_conn->prepare($stmnt);
				$value = array('responseCode'=>$error_code, 'status'=>$error_status, 'description'=>$description, 'correlation_id'=>$correlator_value);
				
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
?>