<?php

	$database = new mysqli('localhost', 'root', '#4kLxMzGurQ7Z~', 'h_maker');


    $responseCode = $_POST['responseCode'];
    $responseMessage  = $_POST['responseMessage']; 
    $uniwalletTransactionId  = $_POST['uniwalletTransactionId'];
    $refNo = $_POST['refNo'];
    $msisdn = $_POST['msisdn'];
    $networkTransactionId = $_POST['networkTransactionId']; 


    $contestant_name = "";
    $voteidentifier   = "";
    $momo_number   = "";
    $paying_amount   = "";  
    $uniwallet_TransactionId = "";
    $notification_status  = "";



    $query_track_pay = mysqli_query($database, "SELECT * FROM mtn_response WHERE `uniwalletTransactionId` = '$uniwalletTransactionId' AND  `refNo` = '$refNo' AND `momo_number` = '$msisdn' ORDER BY id DESC LIMIT 1");
    while($row = mysqli_fetch_assoc($query_track_pay))
    {
        // $voteidentifier  = $row['voteidentifier'];
        $contestant_name = $row['contestant_name'];
        $uniwallet_TransactionId   = $row['uniwalletTransactionId'];
        $momo_number     = $row['momo_number'];
        $notification_status  = $row['notification_status'];
    }


    $query_weekly_vote_update = "UPDATE mtn_response SET `response_code` = '".$responseCode."', `responseMessage` = '".$responseMessage."', `networkTransactionId` = '".$networkTransactionId."' WHERE `uniwalletTransactionId` = '".$uniwalletTransactionId."'  AND `momo_number` = '".$momo_number."' ";

    mysqli_query($database, $query_weekly_vote_update);

    if (trim($notification_status) == "") 
    {
        if (trim($responseCode) == '01' || trim($responseMessage) == "Successfully processed transaction.|Debit MTN customer") 
        {
            $message = "You have successfully voted for '$contestant_name' on MTN Hitmaker 2020. Keep Voting. For help call 0552557227.";
            $message = urlencode($message);//200.2.168.175:2199
            // $url = "http://54.163.215.114:2788/Receiver?User=mycloudhttp&Pass=M1C2T3&From=1400&To=$msisdn&Text=$message";
            // $url = "http://54.163.215.114:2799/Receiver?User=test&Pass=test&From=1413&To=$msisdn&Text=$message";
            $url = "http://34.230.90.80:2788/Receiver?User=mycloudhttp&Pass=M1C2T3&From=1413&To=$msisdn&Text=$message";
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_URL => $url
            ));
           
            $result = curl_exec($curl);
            $error = curl_error($curl);

            curl_close($curl);

            $status_update = "UPDATE mtn_response SET `notification_status` = 'Sent' WHERE `uniwalletTransactionId` = '".$uniwalletTransactionId."'  AND `momo_number` = '".$momo_number."' ";

            mysqli_query($database, $status_update);

        }else
        {
            // $msisdn = $transaction_response->msisdn;//var_dump($msisdn);
            $message = "Voting Failed for '$contestant_name' on Hitmaker 2020. Check and try again at another time. For help Call 0552557227";
            $message = urlencode($message);//200.2.168.175:2199
            // $url = "http://54.163.215.114:2788/Receiver?User=mycloudhttp&Pass=M1C2T3&From=1400&To=$msisdn&Text=$message";
            // $url = "http://54.163.215.114:2799/Receiver?User=test&Pass=test&From=1413&To=$msisdn&Text=$message";
            $url = "http://34.230.90.80:2788/Receiver?User=mycloudhttp&Pass=M1C2T3&From=1413&To=$msisdn&Text=$message";
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_URL => $url
            ));
           
            $result = curl_exec($curl);
            $error = curl_error($curl);

            curl_close($curl);

            $status_update = "UPDATE mtn_response SET `notification_status` = 'Sent' WHERE `uniwalletTransactionId` = '".$uniwalletTransactionId."'  AND `momo_number` = '".$momo_number."' ";

            mysqli_query($database, $status_update);
        }
    } else {
       
    }
    

        