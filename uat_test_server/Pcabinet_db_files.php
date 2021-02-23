<?php
require_once "Pcabinet_dbCon.php";

    class Pcabinet_functions extends db_conn{


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
    }