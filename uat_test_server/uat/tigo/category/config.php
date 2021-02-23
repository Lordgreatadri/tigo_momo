<?php
    $serverName = "127.0.0.1";
    $databaseName = "pcabinet"; #"gmb";
    $databaseUser = "root";
    $databasePassword = 'Mccg8(3P^tJVnBDsF'; #"#4kLxMzGurQ7Z~";

    $database = mysqli_connect($serverName, $databaseUser, $databasePassword, $databaseName);
    //$connect = new PDO("mysql:host=localhost;dbname=pcabinet;charset=utf8","root","Mccg8(3P^tJVnBDsF");//#4kLxMzGurQ7Z~
    if (!$database) {
        die("unable to connect to database");
    }else
    {
    	//echo "connected";
    }
