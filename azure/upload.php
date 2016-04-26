<?php

include "conn.php";
//上傳檔案類型清單
$uptypes=array(
'text/csv'
);
$max_file_size=2000000; //上傳檔案大小限制, 單位BYTE
$destination_folder="~/Downloads/"; //上傳檔路徑
?>
<html>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
<!-- jQuery library -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>

<!-- Latest compiled JavaScript -->
<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
<head>
<title>檔案上傳程式</title>
<style type="text/css">
	.warning {
		color: red;
	}
	.uploadfile {
		margin-left: 20px;
	}
	.margintop {
		margin-top: 20px;
	}
	table, th {
		text-align: center;
	}

</style>
</head>


<body>

<div class="margintop uploadfile">
<form class="form-inline" enctype="multipart/form-data" method="post" name="upform">
上傳檔案:
<input class="form-control" id="focusedInput" name="upfile" type="file">
<input class="btn btn-default" type="submit" name="submit" value="上傳"><br>
允許上傳的檔案類型為:<?php echo implode(',',$uptypes)?>
</form>

</div>

<?php
$gl = "";
if(isset($_POST['submit']))
// if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
	if (!is_uploaded_file($_FILES["upfile"]['tmp_name']))
	//是否存在檔案
	{
	echo "<p class='uploadfile'>您還沒有選擇檔!</p>";
	exit;
	}


	$file = $_FILES["upfile"];
	if($max_file_size < $file["size"])
	//檢查檔案大小
	{
	echo "<p class='uploadfile'>您選擇的檔太大了!</p>";
	exit;
	}

	if(!in_array($file["type"], $uptypes))
	//檢查檔案類型
	{
	echo "<p class='uploadfile'>檔案類型不符!</p>";
	exit;
	}


	$fileName = $_FILES["upfile"]['tmp_name'];
	$csvData = file_get_contents($fileName);
	$gl = $csvData;
	echo $gl;
	$lines = explode(PHP_EOL, $csvData);
	
	$array = array();
	foreach ($lines as $line) {
	    $array[] = str_getcsv($line);
	    $m = count(str_getcsv($line));
	    $n = sizeof($lines)-1;
	}

	$ch = $array[0][0];	
		if ($ch === "店名") {
			// echo "ok";

			print "<div class='warning uploadfile'>請資訊確認如下：\n" . 
				"如有錯誤欄位請重新上傳，再點選「存入資料庫」。</div><br>" . 
				"<table class='table table-striped'>\n";
			echo "<tr>";
			echo "<th>店名</th>";
			echo "<th>日期</th>";
			echo "<th>師傅</th>";
			echo "<th>指定節數</th>";
			echo "<th>未指定節數</th>";
			echo "<th>來客數</th>";
			echo "<th>接待員</th></tr>";
			for($i=1;$i<=$n;$i++){            
			    echo "<tr>";
			    for($j=0;$j<$m;$j++){
			        echo "<td>".$array[$i][$j]."</td>";   
			    }
			    echo "</tr>\n"; 
			}
			print "</table>";


			
			echo "<form method='post'>
					<input name='gl' value='".$gl."' type='hidden'>
					<input class='uploadfile btn btn-default' name='fn' type='submit' value='存入資料庫' />
					</form>";
		} else {
			echo "<div class='uploadfile'> CSV 錯誤格式</div>";
			exit;
		}
	
} if(isset($_POST['fn'])) {
	// echo 'isset '.$_POST['gl'];
	$gl = $_POST['gl'];
	try {	
		$lines = explode(PHP_EOL, $gl);
		$array = array();
		foreach ($lines as $line) {
		    $array[] = str_getcsv($line);
		    $n = sizeof($lines)-1;

		}


			for($i=1;$i<=$n;$i++){

			$sidd = $array[$i][0];
			$sid = substr($sidd, 0, 1);
			$log_date = $array[$i][1];
			$midd = $array[$i][2];
			$mid = substr($midd, 0, 1);
			$assigned = $array[$i][3];
			$not_assigned = $array[$i][4];
			$guest_num = $array[$i][5];
			$hidd = $array[$i][6];
			$hid = substr($hidd, 0, 1);

			
			$sql_check = "INSERT INTO worklog (log_date, mid, assigned, not_assigned, hid, guest_num, sid) VALUES (?,?,?,?,?,?,?) ON DUPLICATE KEY UPDATE sid=?, hid=?, guest_num=?, assigned=?, not_assigned=?";
			$stmt = $conn->prepare($sql_check);
		        $stmt->bindValue(1, $log_date);
		        $stmt->bindValue(2, $mid);
		        $stmt->bindValue(3, $assigned);
		        $stmt->bindValue(4, $not_assigned);
		        $stmt->bindValue(5, $hid);
		        $stmt->bindValue(6, $guest_num);
		        $stmt->bindValue(7, $sid);
		        $stmt->bindValue(8, $sid);
		        $stmt->bindValue(9, $hid);
		        $stmt->bindValue(10, $guest_num);
		        $stmt->bindValue(11, $assigned);
		        $stmt->bindValue(12, $not_assigned);
		        $stmt->execute();

			// $sql_insert = "INSERT INTO worklog (log_date, mid, assigned, not_assigned, hid, guest_num, sid)
   //                 VALUES (?,?,?,?,?,?,?)";
  		
			}
			echo "<script>location='index.php';alert('新增成功!');</script>";
	}
    catch(Exception $e) {
        die(var_dump($e));
    }
		


}

?>



</body>
</html>