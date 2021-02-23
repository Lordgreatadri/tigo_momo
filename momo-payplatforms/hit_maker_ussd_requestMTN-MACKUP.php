<?php
/**------------------------------------------------------------------------------------------------------------------------------------------------
* @@Name:              hitz_ussd_request

* @@Author:            Lordgreat -  Adri Emmanuel <'rexmerlo@gmail.com'>
* @@Tell:              +233543645688/+233273593525

* @Date:               2020-06-26 13:40:30
* @Last Modified by:   Lordgreat - Adri Emmanuel
* @Last Modified time: 2020-06-26 14:48:17

* @Copyright:          MobileContent.Com Ltd <'owner'>

* @Website:            https://mobilecontent.com.gh
*-------------------------------------------------------------------------------------------------------------------------------------------------
*/


$ussdRequest     = json_decode(@file_get_contents('php://input')); 

// Check if no errors occured. 
if($ussdRequest != NULL) 
{
	$conn      = new mysqli('localhost','root', '#4kLxMzGurQ7Z~', 'h_maker');

	//Create a response object. 
	$ussdResponse = new stdClass; 

	if($ussdRequest->Type == "Initiation") 
	{
		// if($ussdRequest->Mobile != '233543645688' && $ussdRequest->Mobile != '233244632692' && $ussdRequest->Mobile != '233544336599') {
		// 	$ussdResponse->Message = "Please, system update is ongoing now, try in few moment. Thank you";
		// 	$ussdResponse->Type = "Release";
		// 	// $ussdResponse->ClientState = 'Sequence1';
		// 	header('Content-type: application/json; charset=utf-8');
		// 	echo json_encode($ussdResponse);
		// 	die();
		// }else{
		$ussdResponse->Message = "Welcome to Hitmaker Season 9.\n\nChoose Option\n1. Singer P\n2. Derrick\n3. Sinam\n4. Jeremy\n5. Mimi\n0. More info\n# Next Page";
		$ussdResponse->Type = "Response";
		$ussdResponse->ClientState = 'Sequence1';
		header('Content-type: application/json; charset=utf-8');
		echo json_encode($ussdResponse);
		die();	
		// }
	}









	// ***************** SEQUENCE 2   *****************************
	if($ussdRequest->Sequence == "2")
	{
		if(trim($ussdRequest->Message) == '0') 
		{
			$ussdResponse->Message = "Visit:\nhttp://bit.ly/mtnhitmaker\nTo vote online for your favourite nominee.";
			$ussdResponse->Type = "Release";
			header('Content-type: application/json; charset=utf-8');
			echo json_encode($ussdResponse);
			die();
		}

			#user select next page
		if(trim($ussdRequest->Message) == "#") 
		{
			$ussdResponse->Message = "Choose Option\n\n6. Mawuly\n7. Kojo Karl\n8. Qwesi Ishe\n9. Kofi Pages\n10. Nessa\n11. Ali Baba\n12. Lasmid\n0. More info";
			$ussdResponse->Type = "Response";
			$ussdResponse->ClientState = 'contestant2';
			header('Content-type: application/json; charset=utf-8');
			echo json_encode($ussdResponse);
			die();
		}

		#user selected the nominee to vote for
		if($ussdRequest->ClientState == 'Sequence1')
		{
			$contestant_id   = "";
	        $contestant_name = "";
	        $voteidentifier  = "";

			$query_track_pay = mysqli_query($conn, "SELECT * FROM contestants WHERE (name = '".trim(strtoupper($ussdRequest->Message))."' OR contestant_id = '".trim($ussdRequest->Message)."' OR voteidentifier = '".trim(strtoupper($ussdRequest->Message))."') LIMIT 1");

			while($row = mysqli_fetch_assoc($query_track_pay))
			{
				$contestant_id   = $row['contestant_id'];
	            $contestant_name = $row['name'];
	            $voteidentifier  = $row['voteidentifier'];
			}

			// file_put_contents('log/con_query.log', print_r($contestant_name, true));

			#selected nominee exist...................
			if(trim($ussdRequest->Message) == $contestant_id || trim(strtoupper($ussdRequest->Message)) == trim(strtoupper($contestant_name))  || trim(strtoupper($ussdRequest->Message)) == trim($voteidentifier)) 
	        {
				$query_test = "INSERT INTO track_process(user_entry, initiator, contestant_id, voteidentifier, nominee_name) VALUES('".$ussdRequest->Message."','".$ussdRequest->Mobile."', '".$contestant_id."', '".$voteidentifier."', '".$contestant_name."')";
				$save = $conn->query($query_test);

	          	$ussdResponse->Message = "Choose bulk votes for ".$contestant_name."\n\n1. 2 votes=ghc1.20\n2. 50 votes=ghc30\n3. 100 votes=ghc60\n4. 200 votes=ghc120\n5. 500 votes=ghc300\n6. 1000 votes=ghc600";
			    $ussdResponse->ClientState = 'PAYMENT1';
			    $ussdResponse->Type = "Response";
			    header('Content-type: application/json; charset=utf-8');
				echo json_encode($ussdResponse);

				die();
	        }else
	        {
	        	$ussdResponse->Message = "Sorry, wrong entry or your nominee is already evicted.\nTry again";
	        	$ussdResponse->ClientState = 'contestant2';
			    $ussdResponse->Type = "Response";
			    header('Content-type: application/json; charset=utf-8');
				echo json_encode($ussdResponse);
				die();
	        }

			header('Content-type: application/json; charset=utf-8');
			echo json_encode($ussdResponse);
			die();
		}else
		{
			$ussdResponse->Message = "Sorry, your selection was wrong. Try again";
			$ussdResponse->Type = "Release";
			header('Content-type: application/json; charset=utf-8');
			echo json_encode($ussdResponse);
			die();
		}
	}





	// ***************** SEQUENCE 3   *****************************
	if($ussdRequest->Sequence == "3")
	{
		#user was on the second page.................
		if($ussdRequest->ClientState == 'contestant2') 
		{
			#user want to know the onlikne portal link.............
			if(trim($ussdRequest->Message) == '0') 
			{
				$ussdResponse->Message = "Visit:\nhttp://bit.ly/mtnhitmaker\nTo vote online for your favourite nominee.";
			    $ussdResponse->Type = "Release";
			    header('Content-type: application/json; charset=utf-8');
				echo json_encode($ussdResponse);
				die();
			}


			$contestant_id   = "";
	        $contestant_name = "";
	        $voteidentifier  = "";

			$query_track_pay = mysqli_query($conn, "SELECT * FROM contestants WHERE (name = '".trim(strtoupper($ussdRequest->Message))."' OR contestant_id = '".trim($ussdRequest->Message)."' OR voteidentifier = '".trim(strtoupper($ussdRequest->Message))."') LIMIT 1");

			while($row = mysqli_fetch_assoc($query_track_pay))
			{
				$contestant_id   = $row['contestant_id'];
	            $contestant_name = $row['name'];
	            $voteidentifier  = $row['voteidentifier'];
			}

			// file_put_contents('log/con_query.log', print_r($contestant_name, true));

			#check if the selected nominee exist...................
			if(trim($ussdRequest->Message) == $contestant_id || trim(strtoupper($ussdRequest->Message)) == trim(strtoupper($contestant_name)) || trim(strtoupper($ussdRequest->Message)) == trim($voteidentifier)) 
	        {
				$query_test = "INSERT INTO track_process(user_entry, initiator, contestant_id, voteidentifier, nominee_name) VALUES('".$ussdRequest->Message."', '".$ussdRequest->Mobile."', '".$contestant_id."', '".$voteidentifier."', '".$contestant_name."')";
				$save = $conn->query($query_test);

	          	$ussdResponse->Message = "Choose bulk votes for ".$contestant_name."\n\n1. 2 votes=ghc1.20\n2. 50 votes=ghc30\n3. 100 votes=ghc60\n4. 200 votes=ghc120\n5. 500 votes=ghc300\n6. 1000 votes=ghc600";
			    $ussdResponse->ClientState = 'PAYMENT2';
			    $ussdResponse->Type = "Response";
			    header('Content-type: application/json; charset=utf-8');
				echo json_encode($ussdResponse);

				die();
	        }else
	        {
	        	$ussdResponse->Message = "Sorry, wrong entry or your nominee is already evicted.";
			    $ussdResponse->Type = "Release";
			    header('Content-type: application/json; charset=utf-8');
				echo json_encode($ussdResponse);
				die();
	        }
		}//user selected the payment plan................
		elseif($ussdRequest->ClientState == 'PAYMENT1') 
		{
			$contestant_ids  = "";
	        $contestant_nam  = "";
	        $voteidentifier  = "";
	        $user_entry      = "";
			$query_track_pay = mysqli_query($conn, "SELECT * FROM track_process WHERE initiator = '".$ussdRequest->Mobile."' ORDER BY id DESC LIMIT 1");
			while($row = mysqli_fetch_assoc($query_track_pay))
			{
				$contestant_ids = $row['contestant_id'];
	            $contestant_nam = $row['nominee_name'];
	            $voteidentifier = $row['voteidentifier'];
	            $user_entry     = $row['user_entry'];
			}


			if(trim($ussdRequest->Message) == '1') 
			{
				#update user session and pass for vote processing..........
				$mtn_update = "UPDATE `track_process` SET `amount`='1.20', contestant_id = '".$contestant_ids."', nominee_name = '".$contestant_nam."', `voteidentifier` = '$voteidentifier', `user_entry` = '$user_entry' WHERE (`initiator`='".$ussdRequest->Mobile."') ";
	    		$test_run = $conn->query($mtn_update);


				#alert user to authorize payment then pass session data to payment API.............
				$ussdResponse->Message = "Please authorize payment of GH1.20 for '".$contestant_nam."' on ".$ussdRequest->Mobile.". Thank you. Keep voting.";
			 	$ussdResponse->Type    = "Release";
			 	exec("php /var/www/mtn_hitz/ussd/hit_maker_payment_process.php  > /tmp/hitmaker_ussd.log 2>&1 &");
				header('Content-type: application/json; charset=utf-8');
				echo json_encode($ussdResponse);

				mysqli_close($conn);
				die();
			}elseif(trim($ussdRequest->Message) == '2')
			{
			    #update user session and pass for vote processing..........
				$mtn_update = "UPDATE `track_process` SET `amount`='30', contestant_id = '".$contestant_ids."', nominee_name = '".$contestant_nam."', `voteidentifier` = '$voteidentifier', `user_entry` = '$user_entry' WHERE (`initiator`='".$ussdRequest->Mobile."') ";
	    		$test_run = $conn->query($mtn_update);


				#alert user to authorize payment then pass session data to payment API.............
				$ussdResponse->Message = "Please authorize payment of GH30 for '".$contestant_nam."' on ".$ussdRequest->Mobile.". Thank you. Keep voting.";
			 	$ussdResponse->Type    = "Release";
			 	exec("php /var/www/mtn_hitz/ussd/hit_maker_payment_process.php  > /tmp/hitmaker_ussd.log 2>&1 &");
				header('Content-type: application/json; charset=utf-8');
				echo json_encode($ussdResponse);

				mysqli_close($conn);
				die();
			}elseif(trim($ussdRequest->Message) == '3')
			{
			    #update user session and pass for vote processing..........
				$mtn_update = "UPDATE `track_process` SET `amount`='60', contestant_id = '".$contestant_ids."', nominee_name = '".$contestant_nam."', `voteidentifier` = '$voteidentifier', `user_entry` = '$user_entry' WHERE (`initiator`='".$ussdRequest->Mobile."') ";
	    		$test_run = $conn->query($mtn_update);


				#alert user to authorize payment then pass session data to payment API.............
				$ussdResponse->Message = "Please authorize payment of GH60 for '".$contestant_nam."' on ".$ussdRequest->Mobile.". Thank you. Keep voting.";
			 	$ussdResponse->Type    = "Release";
			 	exec("php /var/www/mtn_hitz/ussd/hit_maker_payment_process.php  > /tmp/hitmaker_ussd.log 2>&1 &");
				header('Content-type: application/json; charset=utf-8');
				echo json_encode($ussdResponse);

				mysqli_close($conn);
				die();
			}elseif(trim($ussdRequest->Message) == '4')
			{
			    #update user session and pass for vote processing..........
				$mtn_update = "UPDATE `track_process` SET `amount`='120', contestant_id = '".$contestant_ids."', nominee_name = '".$contestant_nam."', `voteidentifier` = '$voteidentifier', `user_entry` = '$user_entry' WHERE (`initiator`='".$ussdRequest->Mobile."') ";
	    		$test_run = $conn->query($mtn_update);


				#alert user to authorize payment then pass session data to payment API.............
				$ussdResponse->Message = "Please authorize payment of GH120 for '".$contestant_nam."' on ".$ussdRequest->Mobile.". Thank you. Keep voting.";
			 	$ussdResponse->Type    = "Release";
			 	exec("php /var/www/mtn_hitz/ussd/hit_maker_payment_process.php  > /tmp/hitmaker_ussd.log 2>&1 &");
				header('Content-type: application/json; charset=utf-8');
				echo json_encode($ussdResponse);

				mysqli_close($conn);
				die();
			}elseif(trim($ussdRequest->Message) == '5')
			{
			    #update user session and pass for vote processing..........
				$mtn_update = "UPDATE `track_process` SET `amount`='300', contestant_id = '".$contestant_ids."', nominee_name = '".$contestant_nam."', `voteidentifier` = '$voteidentifier', `user_entry` = '$user_entry' WHERE (`initiator`='".$ussdRequest->Mobile."') ";
	    		$test_run = $conn->query($mtn_update);


				#alert user to authorize payment then pass session data to payment API.............
				$ussdResponse->Message = "Please authorize payment of GH300 for '".$contestant_nam."' on ".$ussdRequest->Mobile.". Thank you. Keep voting.";
			 	$ussdResponse->Type    = "Release";
			 	exec("php /var/www/mtn_hitz/ussd/hit_maker_payment_process.php  > /tmp/hitmaker_ussd.log 2>&1 &");
				header('Content-type: application/json; charset=utf-8');
				echo json_encode($ussdResponse);

				mysqli_close($conn);
				die();
			}elseif(trim($ussdRequest->Message) == '6')
			{
			    #update user session and pass for vote processing..........
				$mtn_update = "UPDATE `track_process` SET `amount`='600', contestant_id = '".$contestant_ids."', nominee_name = '".$contestant_nam."', `voteidentifier` = '$voteidentifier', `user_entry` = '$user_entry' WHERE (`initiator`='".$ussdRequest->Mobile."') ";
	    		$test_run = $conn->query($mtn_update);


				#alert user to authorize payment then pass session data to payment API.............
				$ussdResponse->Message = "Please authorize payment of GH600 for '".$contestant_nam."' on ".$ussdRequest->Mobile.". Thank you. Keep voting.";
			 	$ussdResponse->Type    = "Release";
			 	exec("php /var/www/mtn_hitz/ussd/hit_maker_payment_process.php  > /tmp/hitmaker_ussd.log 2>&1 &");
				header('Content-type: application/json; charset=utf-8');
				echo json_encode($ussdResponse);

				mysqli_close($conn);
				die();
			}
			else
			{
				$ussdResponse->Message = "Sorry your selection was wrong\nTry again.";
			    $ussdResponse->Type    = "Release";
			    header('Content-type: application/json; charset=utf-8');
				echo json_encode($ussdResponse);
				die();
			}
		}else
		{
			$ussdResponse->Message = 'Sorry your selection was wrong';
			$ussdResponse->Type    = "Release";
			header('Content-type: application/json; charset=utf-8');
			echo json_encode($ussdResponse);
			die();
		}
	}













	// ***************** SEQUENCE 4   *****************************
	if($ussdRequest->Sequence == "4")
	{
		#user is doing payment from the second page...........
		if($ussdRequest->ClientState == 'PAYMENT2') 
		{
			$contestant_ids  = "";
	        $contestant_nam  = "";
	        $voteidentifier  = "";
	        $user_entry      = "";
			$query_track_pay = mysqli_query($conn, "SELECT * FROM track_process WHERE initiator = '".$ussdRequest->Mobile."' ORDER BY id DESC LIMIT 1");
			while($row = mysqli_fetch_assoc($query_track_pay))
			{
				$contestant_ids = $row['contestant_id'];
	            $contestant_nam = $row['nominee_name'];
	            $voteidentifier = $row['voteidentifier'];
	            $user_entry     = $row['user_entry'];
			}


			if(trim($ussdRequest->Message) == '1') 
			{
				#update user session and pass for vote processing..........
				$mtn_update = "UPDATE `track_process` SET `amount`='1.20', contestant_id = '".$contestant_ids."', nominee_name = '".$contestant_nam."', `voteidentifier` = '$voteidentifier', `user_entry` = '$user_entry' WHERE (`initiator`='".$ussdRequest->Mobile."') ";
	    		$test_run = $conn->query($mtn_update);


				#alert user to authorize payment then pass session data to payment API.............
				$ussdResponse->Message = "Please authorize payment of GH1.20 for '".$contestant_nam."' on ".$ussdRequest->Mobile.". Thank you. Keep voting.";
			 	$ussdResponse->Type    = "Release";
			 	exec("php /var/www/mtn_hitz/ussd/hit_maker_payment_process.php  > /tmp/hitmaker_ussd.log 2>&1 &");
				header('Content-type: application/json; charset=utf-8');
				echo json_encode($ussdResponse);

				mysqli_close($conn);
				die();

			}elseif(trim($ussdRequest->Message) == '2')
			{
				#update user session and pass for vote processing..........
				$mtn_update = "UPDATE `track_process` SET `amount`='30', contestant_id = '".$contestant_ids."', nominee_name = '".$contestant_nam."', `voteidentifier` = '$voteidentifier', `user_entry` = '$user_entry' WHERE (`initiator`='".$ussdRequest->Mobile."') ";
	    		$test_run = $conn->query($mtn_update);


				#alert user to authorize payment then pass session data to payment API.............
				$ussdResponse->Message = "Please authorize payment of GH30 for '".$contestant_nam."' on ".$ussdRequest->Mobile.". Thank you. Keep voting.";
			 	$ussdResponse->Type    = "Release";
			 	exec("php /var/www/mtn_hitz/ussd/hit_maker_payment_process.php  > /tmp/hitmaker_ussd.log 2>&1 &");
				header('Content-type: application/json; charset=utf-8');
				echo json_encode($ussdResponse);

				mysqli_close($conn);
				die();
			}elseif(trim($ussdRequest->Message) == '3')
			{
				#update user session and pass for vote processing..........
				$mtn_update = "UPDATE `track_process` SET `amount`='60', contestant_id = '".$contestant_ids."', nominee_name = '".$contestant_nam."', `voteidentifier` = '$voteidentifier', `user_entry` = '$user_entry' WHERE (`initiator`='".$ussdRequest->Mobile."') ";
	    		$test_run = $conn->query($mtn_update);


				#alert user to authorize payment then pass session data to payment API.............
				$ussdResponse->Message = "Please authorize payment of GH60 for '".$contestant_nam."' on ".$ussdRequest->Mobile.". Thank you. Keep voting.";
			 	$ussdResponse->Type    = "Release";
			 	exec("php /var/www/mtn_hitz/ussd/hit_maker_payment_process.php  > /tmp/hitmaker_ussd.log 2>&1 &");
				header('Content-type: application/json; charset=utf-8');
				echo json_encode($ussdResponse);

				mysqli_close($conn);
				die();

			}elseif(trim($ussdRequest->Message) == '4')
			{#update user session and pass for vote processing..........
				$mtn_update = "UPDATE `track_process` SET `amount`='120', contestant_id = '".$contestant_ids."', nominee_name = '".$contestant_nam."', `voteidentifier` = '$voteidentifier', `user_entry` = '$user_entry' WHERE (`initiator`='".$ussdRequest->Mobile."') ";
	    		$test_run = $conn->query($mtn_update);


				#alert user to authorize payment then pass session data to payment API.............
				$ussdResponse->Message = "Please authorize payment of GH120 for '".$contestant_nam."' on ".$ussdRequest->Mobile.". Thank you. Keep voting.";
			 	$ussdResponse->Type    = "Release";
			 	exec("php /var/www/mtn_hitz/ussd/hit_maker_payment_process.php  > /tmp/hitmaker_ussd.log 2>&1 &");
				header('Content-type: application/json; charset=utf-8');
				echo json_encode($ussdResponse);

				mysqli_close($conn);
				die();

			}elseif(trim($ussdRequest->Message) == '5')
			{
				#update user session and pass for vote processing..........
				$mtn_update = "UPDATE `track_process` SET `amount`='300', contestant_id = '".$contestant_ids."', nominee_name = '".$contestant_nam."', `voteidentifier` = '$voteidentifier', `user_entry` = '$user_entry' WHERE (`initiator`='".$ussdRequest->Mobile."') ";
	    		$test_run = $conn->query($mtn_update);


				#alert user to authorize payment then pass session data to payment API.............
				$ussdResponse->Message = "Please authorize payment of GH300 for '".$contestant_nam."' on ".$ussdRequest->Mobile.". Thank you. Keep voting.";
			 	$ussdResponse->Type    = "Release";
			 	exec("php /var/www/mtn_hitz/ussd/hit_maker_payment_process.php  > /tmp/hitmaker_ussd.log 2>&1 &");
				header('Content-type: application/json; charset=utf-8');
				echo json_encode($ussdResponse);

				mysqli_close($conn);
				die();

			}elseif(trim($ussdRequest->Message) == '6')
			{
				#update user session and pass for vote processing..........
				$mtn_update = "UPDATE `track_process` SET `amount`='600', contestant_id = '".$contestant_ids."', nominee_name = '".$contestant_nam."', `voteidentifier` = '$voteidentifier', `user_entry` = '$user_entry' WHERE (`initiator`='".$ussdRequest->Mobile."') ";
	    		$test_run = $conn->query($mtn_update);


				#alert user to authorize payment then pass session data to payment API.............
				$ussdResponse->Message = "Please authorize payment of GH600 for '".$contestant_nam."' on ".$ussdRequest->Mobile.". Thank you. Keep voting.";
			 	$ussdResponse->Type    = "Release";
			 	exec("php /var/www/mtn_hitz/ussd/hit_maker_payment_process.php  > /tmp/hitmaker_ussd.log 2>&1 &");
				header('Content-type: application/json; charset=utf-8');
				echo json_encode($ussdResponse);

				mysqli_close($conn);
				die();

			}
			else
			{
				$ussdResponse->Message = "Sorry your entry was wrong.";
			    $ussdResponse->Type = "Release";
			    header('Content-type: application/json; charset=utf-8');
				echo json_encode($ussdResponse);
				die();
			}
		}
		
	}












	// ***************** SEQUENCE 5   *****************************
	if($ussdRequest->Sequence == "5")
	{
		#check if user enter valid momo number format...........
		if(is_numeric($ussdRequest->Message)) //trim(strlen($ussdRequest->Message)) == 12 && 
		{
			$contestant_id   = "";
	        $contestant_name = "";
	        $voteidentifier  = "";
	        $user_entry      = "";
			$query_track_pay = mysqli_query($conn, "SELECT * FROM track_process WHERE initiator = '".$ussdRequest->Mobile."' ORDER BY id DESC LIMIT 1");
			while($row = mysqli_fetch_assoc($query_track_pay))
			{
				$contestant_id   = $row['contestant_id'];
	            $contestant_name = $row['nominee_name'];
	            $voteidentifier  = $row['voteidentifier'];
	            $user_entry      = $row['user_entry'];
			}

			$mtn_update = "UPDATE `track_process` SET `amount`='".$ussdRequest->ClientState."', `payer_phone` = '".$ussdRequest->Message."', contestant_id = '".$contestant_id."', nominee_name = '".$contestant_name."', `voteidentifier` = '$voteidentifier', `user_entry` = '$user_entry' WHERE (`initiator`='".$ussdRequest->Mobile."') ";
    		$test_run = $conn->query($mtn_update);
			
			$ussdResponse->Message = "Please authorize payment for '".$contestant_name."' on ".$ussdRequest->Message.". Thank you. Keep voting.";
		 	$ussdResponse->Type    = "Release";
		 	exec("php /var/www/mtn_hitz/ussd/hit_maker_payment_process.php > /tmp/hitmaker_ussd.log 2>&1 &");
			header('Content-type: application/json; charset=utf-8');
			echo json_encode($ussdResponse);

			mysqli_close($conn);
			die();
		}else 
		{
			$contestant_id   = "";
	        $contestant_name = "";
	        $voteidentifier  = "";
	        $user_entry      = "";
			$query_track_pay = mysqli_query($conn, "SELECT * FROM track_process WHERE initiator = '".$ussdRequest->Mobile."' ORDER BY id DESC LIMIT 1");
			while($row = mysqli_fetch_assoc($query_track_pay))
			{
				$contestant_id   = $row['contestant_id'];
	            $contestant_name = $row['nominee_name'];
	            $voteidentifier  = $row['voteidentifier'];
	            $user_entry      = $row['user_entry'];
			}

			$mtn_update = "UPDATE `track_process` SET `amount`='".$ussdRequest->ClientState."', contestant_id = '".$contestant_id."', nominee_name = '".$contestant_name."', `voteidentifier` = '$voteidentifier', `user_entry` = '$user_entry' WHERE (`initiator`='".$ussdRequest->Mobile."') ";
    		$test_run = $conn->query($mtn_update);

    		//keep trying till you get the valid momo  number...........
			$ussdResponse->Message = "Sorry your entry was wrong.\nEnter valid mobile money number.";
			$ussdResponse->ClientState = $ussdRequest->ClientState;
			$ussdResponse->Type    = "Response";
			header('Content-type: application/json; charset=utf-8');
			echo json_encode($ussdResponse);
			die();
		}	
	}













	// ***************** SEQUENCE 6   *****************************
	if($ussdRequest->Sequence == "6")
	{
		if(is_numeric($ussdRequest->Message)) //trim(strlen($ussdRequest->Message)) == 10 && 
		{
			$contestant_id   = "";
	        $contestant_name = "";
	        $voteidentifier  = "";
	        $user_entry      = "";

			$query_track_pay = mysqli_query($conn, "SELECT * FROM track_process WHERE initiator = '".$ussdRequest->Mobile."' ORDER BY id DESC LIMIT 1");
			while($row = mysqli_fetch_assoc($query_track_pay))
			{
				$contestant_id   = $row['contestant_id'];
	            $contestant_name = $row['nominee_name'];
	            $voteidentifier  = $row['voteidentifier'];
	            $user_entry      = $row['user_entry'];
			}

			$mtn_update = "UPDATE `track_process` SET amount = '".$ussdRequest->ClientState."', `payer_phone` = '".$ussdRequest->Message."', contestant_id = '".$contestant_id."', nominee_name = '".$contestant_name."', `voteidentifier` = '$voteidentifier', `user_entry` = '$user_entry' WHERE (`initiator`='".$ussdRequest->Mobile."') ";
    		$test_run = $conn->query($mtn_update);
			
			$ussdResponse->Message = "Please authorize payment for '".$contestant_name."' on ".$ussdRequest->Message.". Thank you. Keep voting.";
		 	$ussdResponse->Type    = "Release";
		 	exec("php /var/www/mtn_hitz/ussd/hit_maker_payment_process.php > /tmp/hitmaker_ussd.log 2>&1 &");
			header('Content-type: application/json; charset=utf-8');
			echo json_encode($ussdResponse);

			mysqli_close($conn);
			die();
		}else 
		{	//keep trying till you get the valid momo  number...........
			$ussdResponse->Message = "Sorry your entry was wrong.\nEnter valid mobile money number.";
			$ussdResponse->ClientState = $ussdRequest->ClientState;
			$ussdResponse->Type    = "Response";
			header('Content-type: application/json; charset=utf-8');
			echo json_encode($ussdResponse);
			die();
		}	
	}








	// ***************** SEQUENCE 7   *****************************
	if($ussdRequest->Sequence == "7")
	{
		if(trim(strlen($ussdRequest->Message)) == 10 && is_numeric($ussdRequest->Message)) 
		{
			$contestant_id   = "";
	        $contestant_name = "";
	        $voteidentifier  = "";
	        $user_entry      = "";

			$query_track_pay = mysqli_query($conn, "SELECT * FROM track_process WHERE initiator = '".$ussdRequest->Mobile."' ORDER BY id DESC LIMIT 1");
			while($row = mysqli_fetch_assoc($query_track_pay))
			{
				$contestant_id   = $row['contestant_id'];
	            $contestant_name = $row['nominee_name'];
	            $voteidentifier  = $row['voteidentifier'];
	            $user_entry      = $row['user_entry'];
			}

			$mtn_update = "UPDATE `track_process` SET amount = '".$ussdRequest->ClientState."',`payer_phone` = '".$ussdRequest->Message."', contestant_id = '".$contestant_id."', nominee_name = '".$contestant_name."', `user_entry` = '$user_entry' WHERE (`initiator`='".$ussdRequest->Mobile."') ";
    		$test_run = $conn->query($mtn_update);
			
			$ussdResponse->Message = "Please authorize payment for '".$contestant_name."' on ".$ussdRequest->Message.". Thank you. Keep voting.";
		 	$ussdResponse->Type    = "Release";
		 	exec("php /var/www/mtn_hitz/ussd/hit_maker_payment_process.php > /tmp/hitmaker_ussd.log 2>&1 &");
			header('Content-type: application/json; charset=utf-8');
			echo json_encode($ussdResponse);

			mysqli_close($conn);
			die();
		}else 
		{
			$ussdResponse->Message = "Sorry your entry was wrong.\nInvalid mobile money number.";
			$ussdResponse->Type    = "Release";
			header('Content-type: application/json; charset=utf-8');
			echo json_encode($ussdResponse);
			die();
		}	
	}
}