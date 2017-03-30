<?php

	// $host = "dream.cs.nccu.edu.tw:32769";
  //   $user = "root";
  //   $pwd = "d4sg";
  //   $db = "d4sg";
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
header("Content-Type:text/html; charset=utf-8");

$ch = $_GET['ch'];
// echo $ch." ";
switch ($ch) {
	case 2:
		{
			// masseur.php delete
			$chk_m = $_POST['chk_m'];
			$chk_count_m = count($chk_m);

			if($chk_count_m < 1) {
				echo "<script>alert('刪除至少選一個喔！！'); window.location.href = 'masseur.php'; </script>";
			} else {
				for($ii=0; $ii<$chk_count_m; $ii++) {
					$del_m = $chk_m[$ii];
					// echo $del;
					$sql_m = "DELETE FROM masseur WHERE mid=$del_m";
					$stmt_m = $conn->query($sql_m);
				}
				if($stmt_m) {
					echo "<script> alert('$chk_count_m 筆資料被刪除！！'); window.location.href = 'masseur.php'; </script>";
				} else {
					echo "<script> alert('噢噢刪除操作錯誤請重新選擇！！');window.history.back(); </script>";
				}
			}
		}
		break;
  case 3:
  {
      // helper.php delete
      $chk = $_POST['chk_h'];
      $chk_count = count($chk);
      // echo $chk_count;

      if($chk_count < 1) {
        echo "<script>alert('刪除至少選一個喔！！'); window.location.href = 'helper.php'; </script>";
      } else {
        for($i=0; $i<$chk_count; $i++) {
          $del = $chk[$i];
          // echo $del;
          $sql = "DELETE FROM helper WHERE hid=$del";
          $stmt = $conn->query($sql);
        }
        if($stmt) {
          echo "<script> alert('$chk_count 筆資料被刪除！！'); window.location.href = 'helper.php'; </script>";
        } else {
          echo "<script> alert('刪除操作錯誤請重新選擇！！');window.history.back(); </script>";
        }
      }
    }
    break;
  case 4:
  {
      // shop.php delete
      $chk = $_POST['chk_s'];
      $chk_count = count($chk);
      // echo $chk_count;

      if($chk_count < 1) {
        echo "<script>alert('刪除至少選一個喔！！'); window.location.href = 'shop.php'; </script>";
      } else {
        for($i=0; $i<$chk_count; $i++) {
          $del = $chk[$i];
          // echo $del;
          $sql = "DELETE FROM shop WHERE sid=$del";
          $stmt = $conn->query($sql);
        }
        if($stmt) {
          echo "<script> alert('$chk_count 筆資料被刪除！！'); window.location.href = 'shop.php'; </script>";
        } else {
          echo "<script> alert('刪除操作錯誤請重新選擇！！');window.history.back(); </script>";
        }
      }

    }
		break;
	default:
		{
			// index.php delete
			$chk = $_POST['chk'];
			$chk_count = count($chk);

			if($chk_count < 1) {
				echo "<script>alert('刪除至少選一個喔！！'); window.location.href = '/'; </script>";
			} else {
				for($i=0; $i<$chk_count; $i++) {
					$del = $chk[$i];
					// echo $del;
					$sql = "DELETE FROM worklog WHERE wid=".$del;
					$stmt = $conn->query($sql);
				}
				if($stmt) {
					echo "<script> alert('$chk_count 筆資料被刪除！！'); window.location.href = '/'; </script>";
				} else {
					echo "<script> alert('刪除操作錯誤請重新選擇！！');window.history.back(); </script>";
				}
			}
		}
		break;
}

?>
