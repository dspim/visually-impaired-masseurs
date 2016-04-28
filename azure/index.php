
<html>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
<!-- jQuery library -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>

<!-- Latest compiled JavaScript -->
<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
</head>
<head>
<Title>Registration Form</Title>
<style type="text/css">
    body { background-color: #fff; border-top: solid 10px #000;
        color: #333; margin: 20; padding: 20;
        font-family: "Segoe UI", Verdana, Helvetica, Sans-Serif;
    }
    h1 {
        margin-bottom: 30px; 
    }
    .right {
        float: right;
        margin-bottom: 30px; 
    }
    table, th {
        text-align: center;
    }
</style>
</head>
<body>
<?php  
    $conn = mysql_connect('ap-cdbr-azure-east-c.cloudapp.net','b4aa79b2c77ddc','23d314ad') or trigger_error("SQL", E_USER_ERROR);
    $db = mysql_select_db('D4SG_VIM',$conn) or trigger_error("SQL", E_USER_ERROR);


 //預設每頁筆數(依需求修改)
 $pageRow_records = 30;
 //預設頁數
 $num_pages = 1;
 //若已經有翻頁，將頁數更新
 if (isset($_GET['page'])) {
   $num_pages = $_GET['page'];
 }
 //本頁開始記錄筆數 = (頁數-1)*每頁記錄筆數
 $startRow_records = ($num_pages -1) * $pageRow_records;
 //未加限制顯示筆數的SQL敘述句
 $sql_query = "SELECT w.*, h.hname, m.mname, s.sname
                    FROM worklog as w
                        LEFT JOIN helper as h ON w.hid = h.hid
                            LEFT JOIN masseur as m ON w.mid = m.mid
                                LEFT JOIN shop as s ON w.sid = s.sid";

  // filter pages    
    $rows=mysql_query($sql_query);
    $total=mysql_num_rows($rows);
    $show=ceil($total/30);

 //加上限制顯示筆數的SQL敘述句，由本頁開始記錄筆數開始，每頁顯示預設筆數
 $sql_query_limit = $sql_query." LIMIT ".$startRow_records.", ".$pageRow_records;
 //以加上限制顯示筆數的SQL敘述句查詢資料到 $result 中
 $result = mysql_query($sql_query_limit);
 //以未加上限制顯示筆數的SQL敘述句查詢資料到 $all_result 中
 $all_result = mysql_query($sql_query);
 //計算總筆數
 $total_records = mysql_num_rows($all_result);
 //計算總頁數=(總筆數/每頁筆數)後無條件進位。
 $total_pages = ceil($total_records/$pageRow_records);
?>

<h1>工作列表

<input class="right btn btn-default" value="上傳頁面" type="button" onclick="location='upload.php'" />
</h1> 
<!-- Insert registration info -->
<!-- <p>Fill in all cases everyday, then click <strong>Submit</strong> to record.</p>
<form method="post" action="index.php" enctype="multipart/form-data" >
      店名  <input type="text" name="sid" id="sid"/></br>
      日期  <input type="text" name="log_date" id="log_date"/></br>
      師傅編號 <input type="text" name="mid" id="mid"/></br>
      接待員編號 <input type="text" name="hid" id="hid"/></br>
      指定節數 <input type="text" name="assigned" id="assigned"/></br>
      未指定節數 <input type="text" name="not_assigned" id="not_assigned"/></br>
      來客數 <input type="text" name="guest_num" id="guest_num"/></br>
      <input type="submit" name="submit" value="Submit" />
</form> -->
<?php
    $host = "ap-cdbr-azure-east-c.cloudapp.net"; 
    $user = "b4aa79b2c77ddc";
    $pwd = "23d314ad";
    $db = "D4SG_VIM";
    // Connect to database.
    try {
        $conn = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pwd);
        $conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
        // echo "connect";
    }
    catch(Exception $e){
        die(var_dump($e));
    }
    // Insert registration info
    // if(!empty($_POST)) {
    // try {
    // 	$sid = $_POST['sid'];
    //     $log_date = $_POST['log_date'];
    //     $mid = $_POST['mid'];
    //     $assigned = $_POST['assigned'];
    //     $not_assigned = $_POST['not_assigned'];
    //     $hid = $_POST['hid'];
    //     $guest_num = $_POST['guest_num'];
    //     // $date = date("Y-m-d");
    //     // Insert data
    //     $sql_insert = "INSERT INTO worklog (log_date, mid, assigned, not_assigned, hid, guest_num, sid)
    //                VALUES (?,?,?,?,?,?,?)";
    //     $stmt = $conn->prepare($sql_insert);
    //     $stmt->bindValue(1, $log_date);
    //     $stmt->bindValue(2, $mid);
    //     $stmt->bindValue(3, $assigned);
    //     $stmt->bindValue(4, $not_assigned);
    //     $stmt->bindValue(5, $hid);
    //     $stmt->bindValue(6, $guest_num);
    //     $stmt->bindValue(7, $sid);
    //     // $stmt->bindValue(3, $date);
    //     $stmt->execute();
    // }
    // catch(Exception $e) {
    //     die(var_dump($e));
    // }
    // echo "<h3>This case already creates!</h3>";
    // }

    // Retrieve data
    $sql_select = "SELECT w.*, h.hname, m.mname, s.sname
    				FROM worklog as w
    					LEFT JOIN helper as h ON w.hid = h.hid
    						LEFT JOIN masseur as m ON w.mid = m.mid
    							LEFT JOIN shop as s ON w.sid = s.sid
                                    LIMIT ".$startRow_records.", ".$pageRow_records;
    $stmt = $conn->query($sql_select);
    $registrants = $stmt->fetchAll();
    if(count($registrants) > 0) {
        echo "<table class='table table-striped'>";
        echo "<tr><th>編號</th>";
        echo "<th>店名</th>";
        echo "<th>日期</th>";
        echo "<th>師傅</th>";
        echo "<th>指定節數</th>";
        echo "<th>未指定節數</th>";
        echo "<th>來客數</th>";
        echo "<th>師傅薪水</th>";
        echo "<th>接待員</th></tr>";
        // echo "<th>接待員薪水</th></tr>";
        foreach($registrants as $registrant) {
            echo "<tr><td>".$registrant['wid']."</td>";
            echo "<td>".$registrant['sname']."</td>";
            echo "<td>".$registrant['log_date']."</td>";
            echo "<td>".$registrant['mname']."(".$registrant['mid'].")"."</td>";
            echo "<td>".$registrant['assigned']."</td>";
            echo "<td>".$registrant['not_assigned']."</td>";
            echo "<td>".$registrant['guest_num']."</td>";
            echo "<td>".($registrant['assigned']*100 + $registrant['not_assigned']*100)."</td>";
            echo "<td>".$registrant['hname']."(".$registrant['hid'].")"."</td></tr>";
            // echo "<td>".(($registrant['assigned']*100 + $registrant['not_assigned']*100)*1)."</td></tr>";
        }
        echo "</table>";
    } else {
        echo "<h3>No case is created.</h3>";
    }

?>


<table border="0" align="center">
<tr>
<td>共 <?php echo $total ?> 筆資料</td>
<td>
<?php
// 顯示的頁數範圍
$range = $total_pages;
 
// 若果正在顯示第一頁，無需顯示「前一頁」連結
if ($num_pages > 1) {
    // 使用 << 連結回到第一頁
    echo " <a href={$_SERVER['PHP_SELF']}?page=1><<</a> ";
    // 前一頁的頁數
    $prevpage = $num_pages - 1;
    // 使用 < 連結回到前一頁
    echo " <a href={$_SERVER['PHP_SELF']}?page=".$prevpage."><</a> ";
} // end if
 
// 顯示當前分頁鄰近的分頁頁數
for ($x = (($num_pages - $range) - 1); $x < (($num_pages + $range) + 1); $x++) {
    // 如果這是一個正確的頁數...
    if (($x > 0) && ($x <= $total_pages)) {
        // 如果這一頁等於當前頁數...
        if ($x == $num_pages) {
            // 不使用連結, 但用高亮度顯示
            echo " [<b>".$x."</b>] ";
            // 如果這一頁不是當前頁數...
        } else {
            // 顯示連結
            echo " <a href=index.php?page=".$x.">".$x."</a> ";
        } // end else
    } // end if
} // end for
 
// 如果不是最後一頁, 顯示跳往下一頁及最後一頁的連結
if ($num_pages != $total_pages) {
    // 下一頁的頁數
    $nextpage = $num_pages + 1;
    // 顯示跳往下一頁的連結
    echo " <a href={$_SERVER['PHP_SELF']}?page=".$nextpage.">></a> ";
    // 顯示跳往最後一頁的連結
    echo " <a href={$_SERVER['PHP_SELF']}?page=".$total_pages.">>></a> ";
} // end if
?>
</td>
</tr>
</table>

</body>
</html>
