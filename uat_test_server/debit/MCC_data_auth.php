<?php

require_once 'MCC_dbCon.php';
/**
 * 
 */
	class MCC_data_auth extends MCC_dbCon
	{
		
		public function getClientAuthorization($Authorization = '')
		{
			try 
			{
				if($Authorization) 
				{
					$query =  $this->db_conn->query("SELECT * FROM client_details WHERE Authorization = '$Authorization' LIMIT 0,1");
					$query->execute();

					// set the resulting array to associative
					$result = $query->setFetchMode(PDO::FETCH_ASSOC);
					return  $query->rowCount();
				} else {
					return null;
				}
				
				
			} catch (Exception $ex) {
				return $ex->getMessage();
			}
		}







		public function logUserPaymentRequest($ClientId, $CustomerName, $CustomerMsisdn, $Network, $Product, $Amount, $Narration, $ServiceDescription, $PrimaryCallbackUrl, $correlationID)
		{
			try 
			{
				$stmnt = "INSERT INTO transactions(ClientId, CustomerName, CustomerMsisdn, Network, Product, Amount, Narration,  ServiceDescription,PrimaryCallbackUrl, correlationID) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
				$values = array($ClientId, $CustomerName, $CustomerMsisdn, $Network, $Product, $Amount, $Narration, $ServiceDescription, $PrimaryCallbackUrl, $correlationID);
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





		public function update_payment_response($correlation_id, $ResponseCode, $description)
		{
			try 
			{
				$stmnt = "UPDATE transactions SET ResponseCode=:ResponseCode, Description=:Description WHERE correlation_id=:correlation_id";
				$query = $this->db_conn->prepare($stmnt);
				$value = array('ResponseCode'=>$ResponseCode, 'Description'=>$description, 'correlationID'=>$correlation_id);
				
				$query->execute($value);

				//count number of row affected
				$count_row = $query->rowCount();
				return $count_row;
			} catch (Exception $exc) 
			{
				echo __LINE__ . $exc->getMessage();
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



	    public function get_prefix_type($user_mesage)
        {
            try 
            {
                $result = mb_substr($user_mesage, 0, 2);

                if(strtoupper(trim($result))   == 'ds'  || strtoupper(trim($result)) == 'DS')
                {
                    $prefix = 'DS';                       
                }

                return $prefix;
            } catch (Exception $exc) 
            {
                echo LINE . $exc->getMessage();
            }
        }




        // fetchAll data for callback.............
        public function get_client_transactions($correlationID)
        {
        	try 
			{
				$query =  $this->db_conn->query("SELECT * FROM transactions WHERE correlationID = '$correlationID' LIMIT 0,1");
				$query->execute();

				// set the resulting array to associative
				$result = $query->setFetchMode(PDO::FETCH_ASSOC);
				return  $query->fetchAll();

			} catch (Exception $ex) {
				return $ex->getMessage();
			}
        }




        //process final callback data...........
        public function airteltigo_cash_callback_response($transaction_id, $correlationID, $status, $code, $description)
        {
        	if(trim($transaction_id) && trim($correlationID)) 
        	{
        		foreach($this->get_client_transactions($correlationID) as $key) 
        		{
        			$CustomerMsisdn = $key['CustomerMsisdn'];
        			$Amount         = $key['Amount'];
        			$Narration      = $key['Narration'];
        			$PrimaryCallbackUrl  = $key['PrimaryCallbackUrl'];
        			// $correlationID  = $key['correlationID'];
        		}



        		$data = array(
        			"TransactionId" => $correlationID,
        			"CorrelationId" => $correlationID,
        			"Description"   => $description,
        			"Status"        => $status,
        			"ExternalTransactionId" => $transaction_id,
        			"Amount"        => $Amount,
        			"ClientReference" => $correlationID,
        			"Narration"     => $Narration,
        			"CustomerMsisdn"=> $CustomerMsisdn,
        		);

        		$response = array();

        		$response['ResponseCode'] = $code;
        		$response['Data'] = $data;

        		$data = json_encode($params); //Please kindly submit raw array data (params) is here...

				$url  = $PrimaryCallbackUrl;
				$curl = curl_init($url);
				// curl_setopt($curl, CURLOPT_URL, $url);
				curl_setopt( $curl, CURLOPT_POST, true );
				curl_setopt($curl, CURLOPT_POSTFIELDS, $data);//Just submit the raw array....
				curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
		            'Cache-Control: no-cache',
		            'Content-Type: application/json',
		        ));

		        $result = curl_exec($curl);
		        $err    = curl_error($curl);
		        curl_close($curl);

        	}       	
        }
	}


	




