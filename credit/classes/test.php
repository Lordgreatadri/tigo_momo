<?php 

include_once 'Payment_Data_Auths.php';

$data_handler_Obj   = new Payment_Data_Auths();
	$authorization_code = $data_handler_Obj->get_authorization_code();
var_dump($authorization_code);