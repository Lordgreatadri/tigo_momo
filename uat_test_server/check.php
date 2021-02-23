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
				$tigoNumber       = "233273206468"; //233277505736(mcc)  233273206468 (sammy)
				// $correlationID    = "2334".substr(time(), 0, 15).rand(25, 9999999);
				// $transactionID    = substr(md5(time()), 0, 32);
				// $paymentReference = rand(1000000000,100);

				$unique_value = date("YmdHis");

				

				// $url = "https://accessgw.tigo.com.gh:8443/live/PurchaseInitiate";
				$url = "http://178.79.172.242:8071/debit_tigoCash_callBack.php";//https://41.215.168.138:4443/osb/services/PurchaseInitiate_2_0

				$data = '<BILLPAYRESPONSE>
					<id>MP191125.1417.C00005</id>
					<status>SUCCESS</status>
					<code>00</code>
					</BILLPAYRESPONSE> 
					';

					// <BILLPAYREQUEST>
					// <transactionID>MP191114.1539.C00004<transactionID>
					// <correlationID>96894530x5b3c</correlationID>
					// <status>ERROR</status>
					// <code>purchase-3008-3001-E</code>
					// <description>Invalid MSISDN length</description>
					// </BILLPAYREQUEST>


					// <BILLPAYREQUEST>
					// <transactionID>MP191114.1539.C00004</transactionID>
					// <correlationID>96894530x5b3c</correlationID>
					// <status>OK</status>
					// <code>purchase-3008-0000-S</code>
					// <description>The request has been processed successfully</description>
					// </BILLPAYREQUEST>


					// <BILLPAYRESPONSE>
					// <id>MP191125.1417.C00005</id>
					// <status>SUCCESS</status>
					// <code>00</code>
					// </BILLPAYRESPONSE> 

						// $createdTime = date("Y-m-d");
						// file_put_contents("logs/transactions/InitiateSampRequest_-$createdTime.xml", print_r($data, true));


				$curl = curl_init();
		  		curl_setopt($curl, CURLOPT_URL, $url);
				curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: text/xml', 'SOAPaction: http://178.79.172.242:8071/debit_tigoCash_callBack.php','Content-Length: ' . strlen($data),'Cache-Control: no-cache','Pragma: no-cache'));

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

				// file_put_contents("initiatecash.xml",$response);

				var_dump($response );