<?php 
	include_once 'includes/autoloader.inc.php';
	/**------------------------------------------------------------------------------------------------------------------------------------------------
 * @@Name:              Payment_Data_Auths file
 
 * @@Author: 			Lordgreat - Adri Emmanuel <'rexmerlo@gmail.com'>
 
 * @Date:   			2018-09-27 11:00:30
 * @Last Modified by:   Lordgreat -  Adri Emmanuel
 * @Last Modified time: 2018-09-27 02:35:30

 * @Copyright: 			MobileContent.Com Ltd <'owner'>
 
 * @Website: 			https://mobilecontent.com.gh
 *---------------------------------------------------------------------------------------------------------------------------------------------------
 */

	class Payment_Data_Auths extends MCC_CI_Database
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
		public function get_payment_endpoint_url($service_payment='')
		{
			try 
			{	
				$query =  $this->db_conn->query("SELECT service_payment_url FROM mcc_user_details LIMIT 0,1");
				$query->execute();

				// set the resulting array to associative
				$result = $query->setFetchMode(PDO::FETCH_ASSOC);
				$data = $query->fetchAll();

				$endpoint  = "";
				foreach ($data as $key) 
				{
					$endpoint  = $key['service_payment_url'];
				}

				return $endpoint;
			} catch (Exception $exc) 
			{
				echo __LINE__ . $exc->getMessage();
			}
		}







		// get payment url .............
		public function get_authorization_code($service_payment='')
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






		//saving the get purchase initiator's detail......................
		public function save_purcase_details_request($ext_transaction_id, $transaction_id, $correlation_id, $result_transaction_id, $user_reference,  $payeeWallet, $amount, $responseCode, $status, $code_type, $description, $additional_info, $ItemName)
		{
			try 
			{	
				$stmnt = "INSERT INTO service_payments(ext_transaction_id, transaction_id, correlation_id, result_transaction_id, user_reference, target_number, amount, response_code, status, code_type, description, additional_info, item_name) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
				
				$values = array($ext_transaction_id, $transaction_id, $correlation_id, $result_transaction_id, $user_reference,  $payeeWallet, $amount, $responseCode, $status, $code_type, $description, $additional_info, $ItemName);

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
		public function get_current_transaction_details($correlation_id)
		{
			try 
			{	
				$query =  $this->db_conn->query("SELECT * FROM service_payments WHERE correlation_id = '$correlation_id' ORDER BY payment_id DESC LIMIT 0,1");
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