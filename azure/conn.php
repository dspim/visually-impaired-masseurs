<?php
    // DB connection info
    //TODO: Update the values for $host, $user, $pwd, and $db
    //using the values you retrieved earlier from the Azure Portal.
    // $host = "ap-cdbr-azure-east-c.cloudapp.net";
    // $user = "b4aa79b2c77ddc";
    // $pwd = "23d314ad";
    // $db = "D4SG_VIM";

    // $host = "dream.cs.nccu.edu.tw:32769";
    // $user = "root";
    // $pwd = "d4sg";
    // $db = "d4sg";
    $host = "localhost:3306";
    $user = "d4sg";
    $pwd = "d4sg";
    $db = "d4sg_vim";
    // Connect to database.
    try {
        $conn = new PDO("mysql:host=$host;dbname=$db;charset=utf8;port=3306", $user, $pwd);
        $conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
        // echo "connect";
    }
    catch(Exception $e){
        die(var_dump($e));
    }
    ?>
