<?php
    $serverName = "127.0.0.1";
    $databaseName = "airtel_tigo"; 
    $databaseUser = "root";
    $databasePassword = 'Mccg8(3P^tJVnBDsF'; //FAg8(3P^tJVnBDsF%F  #4kLxMzGurQ7Z~

    $database = mysqli_connect($serverName, $databaseUser, $databasePassword, $databaseName);

    if(!$database) {
        die("unable to connect to database");
    }else
    {
    	//echo "conneted to the database";
    }
    // echo $database;