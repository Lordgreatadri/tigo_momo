<?php 
	include_once 'includes/autoloader.inc.php';
	/**
	 * 
	 */
	class Get_Purchase_Trans_Data_Auths extends MCC_CI_Database
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
		public function get_payment_endpoint_url($get_purchase_details_url='')
		{
			try 
			{	
				$query =  $this->db_conn->query("SELECT get_purchase_details_url FROM mcc_user_details LIMIT 0,1");
				$query->execute();

				// set the resulting array to associative
				$result = $query->setFetchMode(PDO::FETCH_ASSOC);
				$data = $query->fetchAll();

				$endpoint  = "";
				foreach ($data as $key) 
				{
					$endpoint  = $key['get_purchase_details_url'];
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


// tranasactionId
// extTransactionId
// utibaTransactionId
// soaTransactionId
// correlationId
// payerWallet
// payeeWallet
// amount
// status		
// itemName
// paymentReference
// responseCode
// description
// purchaseStatus
// transactionDate
// additionalInfo





		//saving the get purchase initiator's detail......................
		public function save_purcase_details_request($tranasactionId, $extTransactionId, $utibaTransactionId, $soaTransactionId, $correlationID, $payerWallet, $payeeWallet, $amount, $status, $paymentReference, $description, $responseCode, $purchaseStatus, $transactionDate, $itemName, $mwCode, $mwDescription)
		{
			try 
			{	
				$stmnt = "INSERT INTO get_transaction_details(tranasactionId, extTransactionId, utibaTransactionId, soaTransactionId, correlationId, payerWallet, payeeWallet, amount, status, paymentReference, description, responseCode, purchaseStatus, transactionDate, itemName, mwCode, mwDescription) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
				$values = array($tranasactionId, $extTransactionId, $utibaTransactionId, $soaTransactionId, $correlationID, $payerWallet, $payeeWallet, $amount, $status, $paymentReference, $description, $responseCode, $purchaseStatus, $transactionDate, $itemName, $mwCode, $mwDescription);

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
		public function get_current_transaction_details($correlationId, $tranasactionId)
		{
			try 
			{	
				$query =  $this->db_conn->query("SELECT * FROM get_transaction_details WHERE correlationId = '$correlationId' AND tranasactionId ='$tranasactionId' ORDER BY id DESC LIMIT 0,1");
				$query->execute();

				// set the resulting array to associative
				$result = $query->setFetchMode(PDO::FETCH_ASSOC);
				$data = $query->fetchAll();

				return $data;
			} catch (Exception $exc) 
			{
				echo __LINE__ . $exc->getMessage();
			}
		}


		//saving the get purchase initiator's detail......................
		public function save_purcase_details_error($tranasactionId, $exttransactionID, $correlation_id, $descriptions, $error_codes, $error_status, $additionalInfo)
		{
			try 
			{	
				$stmnt = "INSERT INTO get_transaction_details(tranasactionId, extTransactionId, correlationId, description, responseCode, status, additionalInfo) VALUES(?, ?, ?, ?, ?, ?)";
				$values = array($tranasactionId, $exttransactionID, $correlation_id, $descriptions, $error_codes, $error_status, $additionalInfo);

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
				$stmnt = "UPDATE get_transaction_details SET responseCode=:responseCode, status=:status, description=:description WHERE correlationID=:correlation_id";
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