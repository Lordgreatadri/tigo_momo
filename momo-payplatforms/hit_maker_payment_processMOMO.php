<?php 
	error_reporting();
	echo "hello";


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
			// var_dump($network);
		} catch (Exception $exc) 
		{
			echo __LINE__ . $exc->getMessage();
		}
	}

	function _formart_number($voter_number)
    {
        $myNew_value=null;
            $voting_number ='';
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
	// echo $paying_number;
}


#check channel type ........
$channel_type = get_channel_type($paying_number);
$get_num = _formart_number($paying_number);
// var_dump($channel_type);
	$randomize = rand(1000000000,100);
	$refNo = "MCC-PAY-".$randomize;
	$params = array(
		"merchantId" => "1625",
        "productId" => "150",
        "refNo" =>  $refNo,   //"MCC-PAY-238963114",
        "msisdn" => $user_number, //$get_num,//$get_num, 233558719210 //"233240974010",
        "amount" => $paying_amount,  //"1.00",
        "network" => "MTN",
        "voucher" => "",
        "narration" => "Debit MTN customer",
        "apiKey" => "GqeaUSbau2pMNWnNLiSYarcJt097zYZo"
	);
	$data = json_encode($params);
	//233240974010  233247954362
	$ch =  curl_init('http://68.169.63.40:6565/uniwallet/debit/customer');
	curl_setopt( $ch, CURLOPT_POST, true );
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Cache-Control: no-cache',
        'Content-Type: application/json',
    ));

    $result = curl_exec($ch);
    $err    = curl_error($ch);
    curl_close($ch);
	
	var_dump($result);

    $createdTime = date("Y-m-d");
    $file1 = fopen("logs/mass_Result-$createdTime.log",'a');
    fwrite($file1, json_encode($result));
    fclose($file1);


    $file2 = fopen("logs/mass_data-$createdTime.log",'a');
    fwrite($file2, json_encode($data));
    fclose($file2);



    $json = json_decode($result, true);


    $received_date = date("Y-m-d H:i:s");
    $query_test = "INSERT INTO mtn_response(contestant_name, voteidentifier, momo_number, amount, response_code, responseMessage, refNo, uniwalletTransactionId, date_stamp) VALUES('".$contestant_name."','".$voteidentifier."', '".$user_number."', '".$paying_amount."', '".$json['responseCode']."', '".$json['responseMessage']."', '".$refNo."', '".$json['uniwalletTransactionId']."', '".$received_date."')";
	$save = $database->query($query_test);

    if ($err) {
    	$createdTime = date("Y-m-d");
        $file2 = fopen("logs/mass_Error-$createdTime.log",'a');
        fwrite($file2, json_encode($err)."\n");
        fclose($file2);
    }



    $contestant_name = "";
	$voteidentifier  = "";
	$paying_number   = "";
	$paying_amount   = "";

	mysqli_close($database);


?>