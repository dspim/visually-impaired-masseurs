<?php
	header("Access-Control-Allow-Origin: *");
	header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
	header('Access-Control-Allow-Headers: X-Requested-With, Content-Type, Accept');
	header("Content-type: text/json; charset=utf-8");
	$host = "104.41.179.64:3306";
	$user = "d4sg";
	$pwd = "d4sg";
	$db = "d4sg_vim";
    // Connect to database.
    try {
        $conn = new PDO( "mysql:host=$host;dbname=$db;charset=utf8;port=3306", $user, $pwd);
        $conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
    }
    catch(Exception $e){
        die(var_dump($e));
    }

    $method = $_SERVER['REQUEST_METHOD'];

    switch ($method) {
        case 'GET':
            $sql_select = "SELECT * FROM masseur";

            $stmt = $conn->query($sql_select);
            $registrants = $stmt->fetchAll();


            $output = array();
            // array_push($output, $_GET);

            if(count($registrants) > 0) {
                foreach($registrants as $registrant) {
                    $obj = array('mname' => $registrant['mname']);
                    array_push($output, $obj);
                }
            }
            echo json_encode($output, JSON_UNESCAPED_UNICODE);
            break;

        case 'POST':
            if(!empty($_POST)) {
                try {
                    $mid = $_POST['mid'];
                    $mname = $_POST['mname'];
                    $sql_insert = "INSERT INTO masseur (mid, mname) VALUES (?,?)";
                    $stmt = $conn->prepare($sql_insert);
                    $stmt->bindValue(1, $mid);
                    $stmt->bindValue(2, $mname);
                    // $stmt->bindValue(3, $date);
                    $stmt->execute();
                }
                catch(Exception $e) {
                    die(var_dump($e));
                }

                echo json_encode($_POST, JSON_UNESCAPED_UNICODE);
            }
            break;

        default:
            echo "{\"Result\":\"ERROR\"}";
            break;
    }

 ?>
