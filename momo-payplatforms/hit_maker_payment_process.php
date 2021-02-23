<?php 
	error_reporting();
echo "hello";
// die();
	// include_once('../../hit_maker_api/Hubtel_Pay.php');//../../hit_maker_api/Hubtel_Pay.php 3224
	// $obj = new hubtelPay;
	$database = new mysqli('localhost', 'root', '#4kLxMzGurQ7Z~', 'h_maker');

	function get_channel_type($voter_number) 
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
				
			}elseif($result == '026' || $result == '056') 
			{
				$network = 'airtel-gh';
			}elseif($result == '027' || $result == '057') 
			{
				$network = 'tigo-gh';
			}elseif($result == '020' || $result == '050') 
			{
				$network = 'vodafone-gh-ussd';
			}

			return $network;
			var_dump($network);
		} catch (Exception $exc) 
		{
			echo __LINE__ . $exc->getMessage();
		}
	}

$contestant_name = "";
$voteidentifier   = "";
$paying_number   = "";
$paying_amount   = "";	
$user_number = "";

#fetch and curent session data for payment
$query_track_pay = mysqli_query($database, "SELECT * FROM track_process ORDER BY id DESC LIMIT 1");
var_dump($query_track_pay);
while($row = mysqli_fetch_assoc($query_track_pay))
{
	$voteidentifier   = $row['voteidentifier'];
	$contestant_name = $row['nominee_name'];
	$paying_number   = $row['payer_phone'];
	$paying_amount   = $row['amount'];
	$user_number     = $row['initiator'];
	echo $paying_number;
}


#check channel type ........
$channel_type = get_channel_type($paying_number);

var_dump($channel_type);

// http://mysmsinbox.com/hit_maker_api/execute_pay.php
//$push_request = $payment_Obj->process_momo_request($channel_type, $paying_number, $paying_amount, $contestant_name, $voteidentifier);

// $send_request = $obj->receive_momo_request($channel_type, $paying_number, $paying_amount, $contestant_name, $voteidentifier, "ussd" );//$voteidentifier

#push request to payment API
$ch =  curl_init('http://mysmsinbox.com/hit_maker_api/execute_pay.php');  
				curl_setopt( $ch, CURLOPT_POST, true );  
				curl_setopt( $ch, CURLOPT_POSTFIELDS, array('channel' => $channel_type,'number' => $paying_number,'amount' => $paying_amount,'nominee_name' => $contestant_name, 'voteidentifier' => $voteidentifier, 'api_key' => '33ffc38bcaff137103b94abb0480f966','device' => 'ussd'));  
				curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );

		$result = curl_exec($ch); 
		$err = curl_error($ch);
		curl_close($ch);

		var_dump($result);
        $createdTime = date("Y-m-d");
        $file1 = fopen("logs/mass_Result-$createdTime.log",'a');
        fwrite($file1, json_encode($result)."\n");
        fclose($file1);


        if ($err) {
        	$createdTime = date("Y-m-d");
            $file2 = fopen("logs/mass_Error-$createdTime.log",'a');
            fwrite($file2, json_encode($err)."\n");
            fclose($file2);
        }

// die();

#clear track_process after user	
$conte_query = "DELETE FROM track_process WHERE payer_phone = '".$paying_number."' ";
$get_values  = $database->query($conte_query);


$conte_query1 = "DELETE FROM track_process WHERE initiator = '$user_number' ";
$get_values   = $database->query($conte_query1);


$contestant_name = "";
$voteidentifier   = "";
$paying_number   = "";
$paying_amount   = "";

mysqli_close($database);


?>