<?php 

/**------------------------------------------------------------------------------------------------------------------------------------------------
 * @@Name:              database file
 
 * @@Author: 			Lordgreat - Adri Emmanuel <'rexmerlo@gmail.com'>
 
 * @Date:   			2018-09-27 11:00:30
 * @Last Modified by:   Lordgreat -  Adri Emmanuel
 * @Last Modified time: 2018-09-27 02:35:30

 * @Copyright: 			MobileContent.Com Ltd <'owner'>
 
 * @Website: 			https://mobilecontent.com.gh
 *---------------------------------------------------------------------------------------------------------------------------------------------------
 */
	require 'db_class.php';
	
	class data_auth extends db_links
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


	

		#save record to the trasanction request table.................................
		public function save_transaction_initiate_response($transaction_id, $external_transaction_id, $correlation_id, $initiate_status, $initiate_code, $initiate_description, $payment_id, $payment_reference)
		{
			try 
			{
				$stmnt = "INSERT INTO transaction_initiate_response(transaction_id, external_transaction_id, correlation_id, initiate_status, initiate_code, initiate_description, payment_id,  payment_reference) VALUES(?,?,?,?,?,?,?,?)";
				$values = array($transaction_id, $external_transaction_id, $correlation_id, $initiate_status, $initiate_code, $initiate_description, $payment_id, $payment_reference);
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


		//fetch customer from the database..............
		public function fetch_transaction_detail_for_customer()
		{
			try 
			{	
				$query =  $this->db_conn->query("SELECT * FROM transaction_initiate_response ORDER BY id DESC LIMIT 0,1");
				$query->execute();

				// set the resulting array to associative
				$result = $query->setFetchMode(PDO::FETCH_ASSOC);
				return $query->fetchAll();
			} catch (Exception $exc) 
			{
				echo __LINE__ . $exc->getMessage();
			}
		}

		//saving the initiator's detail......................
		public function save_new_initiator($correlation_id, $initiator_number, $amount, $item, $transaction_id, $userReference, $initiate_date)
		{
			try 
			{	
				$stmnt = "INSERT INTO transaction_initiate(correlation_id, initiator_number, amount, item, transaction_id, payment_reference, initiate_date) VALUES(?,?,?,?,?,?,?)";
				$values = array($correlation_id, $initiator_number, $amount, $item, $transaction_id, $userReference, $initiate_date);
				// var_dump($stmnt);
				// var_dump($values);//
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
		public function update_initiator_status($correlator_value, $error_code, $error_status, $error_description)
		{
			try 
			{
				$stmnt = "UPDATE transaction_initiate SET initiate_code=:initiate_code, initiate_status=:initiate_status, initiate_description=:initiate_description WHERE correlation_id=:correlation_id";
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




		// save save_reverse_transaction request............
		public function save_reverse_transaction( $transaction_id, $correlation_id, $request_transaction_id, $user_wallet, $transaction_type, $payment_reference, $api_type = '')
		{
			try 
			{	
				$stmnt = "INSERT INTO reversed_transactions(transaction_id, correlation_id, request_transaction_id, user_wallet, transaction_type, payment_reference, api_type) VALUES(?, ?, ?, ?, ?, ?, ?)";
				$values = array($transaction_id, $correlation_id, $request_transaction_id, $user_wallet, $transaction_type, $payment_reference, $api_type);
				$query = $this->db_conn->prepare($stmnt);
				$query->execute($values);
				$counts = $query->rowCount();

				return $counts;	
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

		//saving the balance request details......................
		public function save_balance_request($correlation_id, $user_number, $wallet, $transaction_id)
		{
			try 
			{	
				$stmnt = "INSERT INTO balance_request(correlation_id, user_number, wallet_type, transaction_id) VALUES(?,?,?,?)";
				$values = array($correlation_id, $user_number, $wallet, $transaction_id);
				// var_dump($stmnt);
				// var_dump($values);//
				$query = $this->db_conn->prepare($stmnt);
				$query->execute($values);
				$counts = $query->rowCount();

				return $counts;	
			} catch (Exception $exc) 
			{
				echo __LINE__ . $exc->getMessage();
			}
		}


		#save record balance response.................................
		public function save_balance_response($transactionID, $correlationID, $status, $code, $description, $transactionId, $walletName, $walletBalance)
		{
			try 
			{
				$stmnt = "INSERT INTO get_balance_response(transaction_id, correlation_id, balance_status, response_code, description, balanceTI_id,  walletName, walletBalance) VALUES(?,?,?,?,?,?,?,?)";
				$values = array($transactionID, $correlationID, $status, $code, $description, $transactionId, $walletName, $walletBalance);
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



		//update balance's response if there is error...............
		public function update_balance_status($correlator_value, $error_code, $error_status, $error_description)
		{
			try 
			{
				$stmnt = "UPDATE balance_request SET initiate_code=:initiate_code, initiate_status=:initiate_status, initiate_description=:initiate_description WHERE correlation_id=:correlation_id";
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
		
	}
 ?>