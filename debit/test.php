<?php 
	
	include_once 'includes/autoloader.inc.php';

	// require_once 'classes/TigoTransactionInitiateProcessor.php';
	// require_once 'classes/Tigo_Data_Auth.php';

		$data_handler_Obj = new Tigo_Data_Auth();
	$authorization_code = $data_handler_Obj->get_authorization_code();

	var_dump($authorization_code);