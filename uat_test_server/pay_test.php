<?php 

				$Username         = "live_mw_mcontent";
				$Password         = "P@ssw0rd@12345";//THYyT783!#Mcon
				$consumerID       = "MContent";//"mcontent";
				$merchantName     = "Mobile Content";
				$country1         = "GHA";
				$webUser          = "MCONTENT_report";//MOBILE_CONTENT
				$webPassword      = "Cashme@21";//"Dealer@1357";
				$merchantNumber   ="233277231999"; //233277231999
				$AuthorizationCode = "bGl2ZV9td19tY29udGVudFBAc3N3MHJkQDEyMzQ1";
				$payer_pin		  = "0852"; //MCC PIN  FROM KELVIN....Mon 8th Feb 2021



				
				// $correlationID    = "2334".substr(time(), 0, 15).rand(25, 9999999);
				// $transactionID    = substr(md5(time()), 0, 32);
				// $paymentReference = rand(1000000000,100);

				$unique_value = date("YmdHis");
				$targetNumber = "233273593525";

				$amount       = '0.5';

				$url = "https://41.215.168.138:4443/osb/services/Payments_3_0";
				$data = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:v1="http://xmlns.tigo.com/MFS/PaymentsRequest/V1" xmlns:v3="http://xmlns.tigo.com/RequestHeader/V3" xmlns:v2="http://xmlns.tigo.com/ParameterType/V2" xmlns:cor1="http://soa.mic.co.af/coredata_1">
				   <soapenv:Header xmlns:wsse="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd">
				      <cor1:debugFlag>true</cor1:debugFlag>
				      <wsse:Security>
				         <wsse:UsernameToken>
				            <wsse:Username>'.trim($Username).'</wsse:Username>
				            <wsse:Password>'.trim($Password).'</wsse:Password>
				         </wsse:UsernameToken>
				      </wsse:Security>
				   </soapenv:Header>
				   <soapenv:Body>
				      <v1:payBillRequest xmlns:v1="http://xmlns.tigo.com/MFS/PaymentsRequest/V1">
				         <v3:RequestHeader>
				            <v3:GeneralConsumerInformation>
				               <v3:consumerID>'.trim($consumerID).'</v3:consumerID>
				                
				               <v3:transactionID>'.trim($unique_value).'</v3:transactionID>
				               <v3:country>GHA</v3:country>
				               <v3:correlationID>'.trim($unique_value).'</v3:correlationID>
				            </v3:GeneralConsumerInformation>
				         </v3:RequestHeader>
				         <v1:requestBody>
				            <v1:paymentreference>'.trim($unique_value).'</v1:paymentreference>
				            <v1:sourceWallet>
				               
				               <v1:msisdn>'.trim($merchantNumber).'</v1:msisdn>
				            </v1:sourceWallet>
				            <v1:targetWallet>
				               
				               <v1:msisdn>'.trim($targetNumber).'</v1:msisdn>
				            </v1:targetWallet>
				             
				            <v1:pin>'.$payer_pin.'</v1:pin>
				             
				            <v1:companyId></v1:companyId>
				            <v1:authorizationcode>'.trim($AuthorizationCode).'</v1:authorizationcode>
				            <v1:amount>'.trim($amount).'</v1:amount>
				         </v1:requestBody>
				      </v1:payBillRequest>
				   </soapenv:Body>
				</soapenv:Envelope>';


				$createdTime = date("Y-m-d");
				file_put_contents("PaymentrequestTest-$createdTime.xml", print_r($data, true));


				$curl = curl_init();
		  		curl_setopt($curl, CURLOPT_URL, $url);
				curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: text/xml', 'SOAPaction: https://41.215.168.138:4443/osb/services/Payments_3_0','Content-Length: ' . strlen($data),'Cache-Control: no-cache','Pragma: no-cache'));

				curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
				curl_setopt($curl, CURLOPT_HEADER, 0);
		    	curl_setopt($curl, CURLOPT_POST, 1);
		    	curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
		    	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		    	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
				curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
				curl_setopt($curl, CURLOPT_SSLCERT, getcwd().'/ag_partner/ag_partner.pem');
		    	curl_setopt($curl, CURLOPT_SSLCERTPASSWD, 'tigo123!');


		    	$response = curl_exec($curl);
		    	$error    = curl_error($curl);
				curl_close($curl);

				file_put_contents("PaymentResultTest-$createdTime.xml", print_r($response, true));
				var_dump($response);
?>