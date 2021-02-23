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

				$minutesToExpire  = "20";
				$tigoNumber       = "233273593525"; //233277505736(mcc)  233273206468 (sammy)
				// $correlationID    = "2334".substr(time(), 0, 15).rand(25, 9999999);
				// $transactionID    = substr(md5(time()), 0, 32);
				// $paymentReference = rand(1000000000,100);

				$unique_value = date("YmdHis");

				

				// $url = "https://accessgw.tigo.com.gh:8443/live/PurchaseInitiate";
				$url = "https://41.215.168.138:4443/osb/services/GetPurchaseTransDetails_2_0";

				$data = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:v1="http://xmlns.tigo.com/GetPurchaseTransDetailsRequest/V1" xmlns:v3="http://xmlns.tigo.com/RequestHeader/V3" xmlns:v2="http://xmlns.tigo.com/ParameterType/V2" xmlns:cor="http://soa.mic.co.af/coredata_1">
				   <soapenv:Header xmlns:wsse="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd">
				      <cor:debugFlag>true</cor:debugFlag>
				      <wsse:Security>
				         <wsse:UsernameToken>
				            <wsse:Username>'.trim($Username).'</wsse:Username>
				            <wsse:Password>'.trim($Password).'</wsse:Password>
				         </wsse:UsernameToken>
				      </wsse:Security>
				   </soapenv:Header>
				   <soapenv:Body>
				      <v1:GetPurchaseTransDetailsRequest>
				         <v3:RequestHeader>
				            <v3:GeneralConsumerInformation>
				               <v3:consumerID>'.trim($consumerID).'</v3:consumerID>
 
				               <v3:transactionID>'.trim($unique_value).'</v3:transactionID>
				               <v3:country>GHA</v3:country>
				               <v3:correlationID>'.trim($unique_value).'</v3:correlationID>
				            </v3:GeneralConsumerInformation>
				         </v3:RequestHeader>
				         <v1:requestBody>
				            <v1:customerAccount>
				                
				               <v1:msisdn>'.trim($tigoNumber).'</v1:msisdn>
				            </v1:customerAccount>
				            <v1:paymentReference>20210127213203</v1:paymentReference>
				            <v1:apiType>purchase</v1:apiType>
				            <v1:AuthorizationCode>bGl2ZV9td19tY29udGVudFBAc3N3MHJkQDEyMzQ1</v1:AuthorizationCode>
				         </v1:requestBody>
				      </v1:GetPurchaseTransDetailsRequest>
				   </soapenv:Body>
				</soapenv:Envelope>';

						$createdTime = date("Y-m-d");
						file_put_contents("getPurchaseRequest_-$createdTime.xml", print_r($data, true));


				$curl = curl_init();
		  		curl_setopt($curl, CURLOPT_URL, $url);
				curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: text/xml', 'SOAPaction: https://41.215.168.138:4443/osb/services/GetPurchaseTransDetails_2_0','Content-Length: ' . strlen($data),'Cache-Control: no-cache','Pragma: no-cache'));

				curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
				curl_setopt($curl, CURLOPT_HEADER, 0);
		    	curl_setopt($curl, CURLOPT_POST, 1);
		    	curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
		    	// curl_setopt($curl, "https://mycloud.com.gh/payment/airtel_tigo/tigo_request_endpoint.php");

		    	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		    	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
				curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);

				curl_setopt($curl, CURLOPT_SSLCERT, getcwd().'/ag_partner/ag_partner.pem');
		    	curl_setopt($curl, CURLOPT_SSLCERTPASSWD, 'tigo123!');

		    	$response = "";
				if (curl_exec($curl)) 
				{
					$response = curl_exec($curl);
				} 
				else 
				{
					$response = curl_error($curl);
		    	}
				
				curl_close($curl);

				file_put_contents("getPurchaseResponse.xml",$response);

				var_dump($response );