<html>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>
<head>
<Title>Registration Form</Title>
<style type="text/css">
    body { background-color: #fff; border-top: solid 10px #000;
        color: #333; font-size: .85em; margin: 20; padding: 20;
        font-family: "Segoe UI", Verdana, Helvetica, Sans-Serif;
    }
    h1, h2, h3,{ color: #000; margin-bottom: 0; padding-bottom: 0; }
    h1 { font-size: 2em; }
    h2 { font-size: 1.75em; }
    h3 { font-size: 1.2em; }
    table { margin-top: 0.75em; border: 0 none; }
    th { font-size: 1.2em; text-align: left; border: solid 2px #000; padding-left: 0; }
    td { padding: 0.25em 2em 0.25em 0em; border: solid 2px #000; }
</style>
</head>
<body>
<h1>All Cases List!</h1>
<p>Fill in all cases everyday, then click <strong>Submit</strong> to record.</p>
<form method="post" action="index.php" enctype="multipart/form-data" >
      店名  <input type="text" name="sid" id="sid"/></br>
      日期  <input type="text" name="log_date" id="log_date"/></br>
      師傅編號 <input type="text" name="mid" id="mid"/></br>
      接待員編號 <input type="text" name="hid" id="hid"/></br>
      指定節數 <input type="text" name="assigned" id="assigned"/></br>
      未指定節數 <input type="text" name="not_assigned" id="not_assigned"/></br>
      來客數 <input type="text" name="guest_num" id="guest_num"/></br>
      <input type="submit" name="submit" value="Submit" />
</form>
<?php
    // DB connection info
    //TODO: Update the values for $host, $user, $pwd, and $db
    //using the values you retrieved earlier from the Azure Portal.
    $host = "ap-cdbr-azure-east-c.cloudapp.net";
    $user = "b4aa79b2c77ddc";
    $pwd = "23d314ad";
    $db = "D4SG_VIM";
    // Connect to database.
    try {
        $conn = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pwd);
        $conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
    }
    catch(Exception $e){
        die(var_dump($e));
    }
    // Insert registration info
    if(!empty($_POST)) {
    try {
    	$sid = $_POST['sid'];
        $log_date = $_POST['log_date'];
        $mid = $_POST['mid'];
        $assigned = $_POST['assigned'];
        $not_assigned = $_POST['not_assigned'];
        $hid = $_POST['hid'];
        $guest_num = $_POST['guest_num'];
        // $date = date("Y-m-d");
        // Insert data
        $sql_insert = "INSERT INTO worklog (log_date, mid, assigned, not_assigned, hid, guest_num, sid)
                   VALUES (?,?,?,?,?,?,?)";
        $stmt = $conn->prepare($sql_insert);
        $stmt->bindValue(1, $log_date);
        $stmt->bindValue(2, $mid);
        $stmt->bindValue(3, $assigned);
        $stmt->bindValue(4, $not_assigned);
        $stmt->bindValue(5, $hid);
        $stmt->bindValue(6, $guest_num);
        $stmt->bindValue(7, $sid);
        // $stmt->bindValue(3, $date);
        $stmt->execute();
    }
    catch(Exception $e) {
        die(var_dump($e));
    }
    echo "<h3>This case already creates!</h3>";
    }
    // Retrieve data
    $sql_select = "SELECT w.*, h.hname, m.mname, s.sname
    				FROM worklog as w
    					LEFT JOIN helper as h ON w.hid = h.hid
    						LEFT JOIN masseur as m ON w.mid = m.mid
    							LEFT JOIN shop as s ON w.sid = s.sid";
    $stmt = $conn->query($sql_select);
    $registrants = $stmt->fetchAll();
    if(count($registrants) > 0) {
        echo "<h2>Masseur Work List:</h2>";
        echo "<table>";
        echo "<tr><th>編號</th>";
        echo "<th>店名</th>";
        echo "<th>日期</th>";
        echo "<th>師傅</th>";
        echo "<th>指定節數</th>";
        echo "<th>未指定節數</th>";
        echo "<th>來客數</th>";
        echo "<th>師傅薪水</th>";
        echo "<th>接待員</th>";
        echo "<th>接待員薪水</th></tr>";
        foreach($registrants as $registrant) {
            echo "<tr><td>".$registrant['wid']."</td>";
            echo "<td>".$registrant['sname']."</td>";
            echo "<td>".$registrant['log_date']."</td>";
            echo "<td>".$registrant['mname']."(".$registrant['mid'].")"."</td>";
            echo "<td>".$registrant['assigned']."</td>";
            echo "<td>".$registrant['not_assigned']."</td>";
            echo "<td>".$registrant['guest_num']."</td>";
            echo "<td>".($registrant['assigned']*100 + $registrant['not_assigned']*100)."</td>";
            echo "<td>".$registrant['hname']."(".$registrant['hid'].")"."</td>";
            echo "<td>".(($registrant['assigned']*100 + $registrant['not_assigned']*100)*1)."</td></tr>";
        }
        echo "</table>";
    } else {
        echo "<h3>No case is created.</h3>";
    }
?>
</body>
</html>
