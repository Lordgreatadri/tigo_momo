<?php 
	
	require_once 'tigo_data_functions.php';
	
	if($_SERVER['REQUEST_METHOD'] == 'POST') 
	{
		$response    = array();
		$dataArray   = array();
		$statusArray = array();
		$data_Obj    = new tigo_data_functions();
		

		// check if user is allowed.............
		if(trim($_POST['api_key']) != "mcc@tigoc@sh") 
		{
			$response['Success'] = false;
			$statusArray['Description'] = 'Access Denied. Unknown request...';
			$response['Data'] = $statusArray;				

            header('Content-Type: application/json');
		    echo json_encode($response);
		    exit();
		} else 
		{
			// formart number to 233..........
			$voter_number = $data_Obj->_formart_number($_POST['number']);

		 	// check if correct network and right number formart is presented............
			$value = $data_Obj->get_channel_type($voter_number);

			if(trim($value) == null) 
			{
				$response['Success'] = false;
				$statusArray['Description'] = 'Please provide valid mobile money number only.';
				$response['Data'] = $statusArray;				

	            header('Content-Type: application/json');
			    echo json_encode($response);
			    exit();
			} elseif(trim($value) != "tigo-gh")
			{			
				$response['Success'] = false;
				$statusArray['Description'] = 'Wrong momo number provided. It should be airtel-tigo only in 233 format.';
				$response['Data'] = $statusArray;				

	            header('Content-Type: application/json');
			    echo json_encode($response);
			    exit();
			}
			
			$response['Success'] = true;
			$statusArray['Description'] = 'Vote is being processed. Check to confirm payment on your phone.';
			$response['Data'] = $statusArray;				

            header('Content-Type: application/json');
		    echo json_encode($response);



		    if(isset($_POST['amount']) && isset($_POST['contestant_name']) && isset($_POST['api_key'])) 
		    {
		    	$data_Obj->send_momo_request($voter_number, $_POST['amount'], $_POST['contestant_name'], $_POST['contestant_code'], $_POST['cabinet']);
		    }
		    
		}
		


		

	}
?>




