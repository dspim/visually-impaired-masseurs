<html>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
<!-- jQuery library -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>

<!-- Latest compiled JavaScript -->
<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
</head>
<head>
<Title>小站列表</Title>
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
        margin-left: 10px;
    }
    table, th {
        text-align: center;
    }
    th {
        font-size: 20px;
    }
</style>
</head>
<body>
<!-- filter page -->
<?php
$conn = @mysql_connect('localhost:3306','d4sg','d4sg') or trigger_error("SQL", E_USER_ERROR);
$db = mysql_select_db('d4sg_vim',$conn) or trigger_error("SQL", E_USER_ERROR);

//預設每頁筆數
$pageRow_records = 30;
$num_pages = 1;

if (isset($_GET['page'])) {
$num_pages = $_GET['page'];
}
$startRow_records = ($num_pages -1) * $pageRow_records;
$sql_query = "SELECT * FROM shop";

// filter pages
$rows=mysql_query($sql_query);
$total=mysql_num_rows($rows);
$show=ceil($total/30);

$sql_query_limit = $sql_query." LIMIT ".$startRow_records.", ".$pageRow_records;
$result = mysql_query($sql_query_limit);
$all_result = mysql_query($sql_query);
$total_records = mysql_num_rows($all_result);
$total_pages = ceil($total_records/$pageRow_records);
?>

<h1>小站列表
<input class="right btn btn-default" value="按摩師傅列表" type="button" onclick="location='masseur.php'" />
<input class="right btn btn-default" value="管理員列表" type="button" onclick="location='helper.php'" />
<input class="right btn btn-default" value="新增工作記錄" type="button" onclick="location='create_view.php'" />
<input class="right btn btn-default" value="上傳頁面" type="button" onclick="location='upload.php'" />
<input class="right btn btn-default" type="button" onclick="location='/'" value="回首頁" /><br>

<!-- <input class="right btn btn-default" value="刪除" type="button" onclick="location='delete.php'" /> -->
</h1>

<?php
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
    // create a new helper
    if(!empty($_POST)) {
    	try {
    		$sid = $_POST['sid'];
    		$sname = $_POST['sname'];

    		$sql_create = "INSERT INTO shop (sid, sname) VALUES (?,?) ON DUPLICATE KEY UPDATE sid=?, sname=?";
			$stmt = $conn->prepare($sql_create);
		        $stmt->bindValue(1, $sid);
		        $stmt->bindValue(2, $sname);
		        $stmt->bindValue(3, $sid);
		        $stmt->bindValue(4, $sname);
		        $stmt->execute();

		    echo "<script> alert('新增小站：[".$sid."] ".$sname." 成功'); window.location.href = 'shop.php'; </script>";
    	} catch(Exception $e) {
    		// die(var_dump($e));
    		echo "<script>alert('有欄位是空白 或是 編號不為數字'); window.location.href = 'shop.php';</script>";
    	}

    }

    // Retrieve data
    $sql_select = "SELECT * FROM shop
    		            LIMIT ".$startRow_records.", ".$pageRow_records;
    $stmt = $conn->query($sql_select);
    $registrants = $stmt->fetchAll();

    	echo "<table class='table'>";
        // create
        echo "<form method='post' action='shop.php'>";
        echo "<tr><td><input class='form-control' type='text' name='sid' id='sid' placeholder='小站編號[限數字]' /></td>";
        echo "<td><input class='form-control' type='text' name='sname' id='sname' placeholder='小站名字' /></td>";
        echo "<td><input class='btn btn-primary' type='submit' name='submit' value='新增' /></td></tr>";
        echo "<tr><td></td><td></td><td></td></tr>";
        echo "</form></table><br>";
        // read
    if(count($registrants) > 0) {
        echo "<form method='post' name='frm'>";
        echo "<table class='table table-striped'>";

        echo "<tr><th><Button class='btn btn-danger' onClick='delete_record();'>刪除</Button></th><th>編號</th>";
        echo "<th>小站名</th>";
        echo "</tr>";
        foreach($registrants as $registrant) {
            echo "<tr><td><input class='chkbox' type='checkbox' value='".$registrant['sid']."' name='chk_s[]'></td><td>".$registrant['sid']."</td>";
            echo "<td>".$registrant['sname']."</td>";
            echo "</tr>";
        }
        echo "</table>";
        echo "</form>";
    } else {
        echo "<h3>No case is created.</h3>";
    }

?>

<!-- show page -->
<table border="0" align="center">
<tr>
<td>共 <?php echo $total ?> 筆資料</td>
<td>
<?php
$range = $total_pages;
if ($num_pages > 1) {
    echo " <a href={$_SERVER['PHP_SELF']}?page=1><<</a> ";
    $prevpage = $num_pages - 1;
    echo " <a href={$_SERVER['PHP_SELF']}?page=".$prevpage."><</a> ";
}
// 顯示當前分頁鄰近的分頁頁數
for ($x = (($num_pages - $range) - 1); $x < (($num_pages + $range) + 1); $x++) {
    if (($x > 0) && ($x <= $total_pages)) {
        if ($x == $num_pages) {
            echo " [<b>".$x."</b>] ";
        } else {
            echo " <a href=index.php?page=".$x.">".$x."</a> ";
        }
    }
}
// 如果不是最後一頁, 顯示跳往下一頁及最後一頁的連結
if ($num_pages != $total_pages) {
    $nextpage = $num_pages + 1;
    echo " <a href={$_SERVER['PHP_SELF']}?page=".$nextpage.">></a> ";
    echo " <a href={$_SERVER['PHP_SELF']}?page=".$total_pages.">>></a> ";
}
?>
</td>
</tr>
</table>
<script type="text/javascript">
$('document').ready(function() {
    $('.chkbox').attr('checked', this.checked)
});

function delete_record()
{
document.frm.action = "delete.php?ch=4";
document.frm.submit();
}

</script>
</body>
</html>
