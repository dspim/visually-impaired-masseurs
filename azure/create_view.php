<?php
error_reporting(0);
include "conn.php";
?>

<html>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
<!-- <link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel="stylesheet" type="text/css"/> -->
<!-- jQuery library -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
<!-- Latest compiled JavaScript -->
<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<script src="//code.jquery.com/jquery-1.10.2.js"></script>
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<!-- <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script> -->

<head>
<Title>新增頁面</Title>
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
    .warning {
        color: red;
    }
    table, th, td {
        text-align: center;
    }
    .w {
        width: 150px;
    }
    .ww {
        width: 200px;
    }
    .td {
        text-align: center;
    }
    .hidden {
        visibility: hidden;
    }

</style>

</head>
<body>


<h1>新增工作記錄
<input class="right btn btn-default" value="管理員列表" type="button" onclick="location='helper.php'" />
<input class="right btn btn-default" value="小站列表" type="button" onclick="location='shop.php'" />
<input class="right btn btn-default" value="按摩師傅列表" type="button" onclick="location='masseur.php'" />
<input class="right btn btn-default" value="上傳頁面" type="button" onclick="location='upload.php'" />
<input class="right btn btn-default" value="回首頁" type="button" onclick="location='/'" />
<!-- <input class='right btn btn-primary' type='button' name='add' id='add' value='新增一筆' /> -->
</h1>

<!-- Insert registration info -->
記錄欄位格式參考<br><br>
<table class='table table-bordered table-striped'>
    <tr><th>小站</th><th>日期</th><th>按摩師傅</th>
        <th>指定節數</th><th>未指定節數</th><th>來客數</th>
        <th>管理員</th></tr>

    <tr>
        <td>雙連</td><td>2015-04-01</td><td>王依</td><td>3</td><td>6</td><td>6</td><td>王武</td>
    </tr>
</table>

<span class="warning">操作：請先按左上“新增筆數”資料，完成所有該欄位填寫再按出“預覽工作表”。</span><br>
<div id="wrongsign"></div>
<br><br>

<form method='post' action='create_view.php'>
<table class="table table-striped">
    <tr>
        <td><input class="form-control" type="text" name="no_of_rec" maxlength="2" pattern="[0-9]+" placeholder="請輸入增加筆數（數字，最多二位數）" /></td>
        <td><button class='btn btn-primary' type="submit" name="btn-gen-form">筆數</button> </td>
    </tr>
</table>
</form>


<!-- view record -->
<?php
include "conn.php";
if(isset($_POST['save_mul']))
{
 $total = $_POST['total'];

?>
<table class="table table-striped">


    <tr><th>##</th><th>小站</th><th>日期</th><th>師傅</th>
        <th>指定節數</th><th>未指定節數</th><th>來客數</th>
        <th>管理員</th></tr>
<?php

    $output = array();
    $string = "";
     for($i=1; $i<=$total; $i++)
     {
        // $string = "";
      echo '<form method="post" action="multi_create.php">';
      $sid = $_POST["shopid$i"];
      $sname = "";
      $log_date = $_POST["log_date$i"];
      $mid = $_POST["masseurid$i"];
      $mname = "";
      $guest_num = $_POST["guest_num$i"];
      $assigned = $_POST["assigned$i"];
      $not_assigned = $_POST["not_assigned$i"];
      $hid = $_POST["helpid$i"];
      $hname = "";


      if($sid == 0 || $mid == 0 || $hid == 0 || $guest_num==null || $assigned==null|| $not_assigned==null || $log_date == null) {
            echo "<script>alert('記錄中有欄位是空白喔！'); window.history.back();</script>";
            // echo "<script> alert('欄位有空白喔！); window.history.go(-1);</script>";
            exit;
      }
      if(!is_numeric ($assigned) || !is_numeric ($not_assigned) || !is_numeric ($guest_num)) {
            echo "<script>alert('記錄中有欄位是格式錯誤喔！'); window.history.back();</script>";
            exit;
      }
      if(substr_count($log_date,"-") != 2) {
            echo "<script>alert('記錄中有欄位是日期格式錯誤喔！'); window.history.back();</script>";
            exit;
      }
        $sql = "SELECT * FROM shop, helper, masseur";
        $st_shop = $conn->query($sql);
        $registrants = $st_shop->fetchAll();
        if(count($registrants) > 0) {
            foreach($registrants as $registrant) {
                if($registrant['sid'] == $sid) {
                    $sname = $registrant['sname'];
                } else if ($registrant['mid'] == $mid) {
                    $mname = $registrant['mname'];
                } else if ($registrant['hid'] == $hid) {
                    $hname = $registrant['hname'];
                }
            }
        }

        echo '<tr><td>'.$i.'</td><td>'.$sname.'</td><td>'.$log_date.'</td><td>'.$mname .
                '</td><td>'.$assigned.'</td><td>'.$not_assigned.'</td><td>'.$guest_num.'</td><td>'.$hname.'</tr>';

        $string .= $sid . "," . $log_date .",".$hid.",".$mid. ",".$assigned .",".$not_assigned."," . $guest_num . ".";



            // $obj = array('num' => intval($i), 'sid' => intval($sid), 'log_date' => $log_date, 'mid' => intval($mid), 'hid' => intval($hid), 'assigned' => intval($assigned), 'not_assigned' => intval($not_assigned), 'guest_num' => intval($guest_num), 'hid' => intval($hid));
            // array_push($output, $obj);


    }

    // $json = json_encode($output, JSON_UNESCAPED_UNICODE);
    // echo $json;
    // echo '<input type="hidden" name="json" value"' . $json . '"/>';
    echo '<input type="hidden" name="string" value="' . $string.'"/>';
    echo '</table>';

    echo '<button class="btn btn-primary" type="submit" name="azure">存入資料庫</button></form>';
    echo '<button class="right btn btn-primary" onclick="goBack()">回上一頁修改</button>';
}


?>

<!-- get number and show table to fill out -->
<!-- <div class="container"> -->

<?php
if(isset($_POST['btn-gen-form']))
{
 ?>
    <form method="post" action="create_view.php">
    <input type="hidden" name="total" value="<?php echo $_POST["no_of_rec"]; ?>" />
 <table class="table table-striped">


    <tr><th>##</th><th>小站</th><th>日期</th><th>師傅姓氏</th><th>師傅名字</th>
        <th>指定節數</th><th>未指定節數</th><th>來客數</th>
        <th>管理員</th></tr>
 <?php
 include "conn.php";

 for($i=1; $i<=$_POST["no_of_rec"]; $i++)
 {
  ?>
        <tr>
        <td><?php echo $i; ?></td>
        <!-- <td><input type="text" name="fname<?php echo $i; ?>" placeholder="first name" /></td>
        <td><input type="text" name="lname<?php echo $i; ?>" placeholder="last name" /></td>
        </tr> -->

        <td>
        <?php
                $sql = "SELECT * FROM shop";
                $st_shop = $conn->query($sql);
                $registrants = $st_shop->fetchAll();
                if(count($registrants) > 0) {
                    echo "<select  class='form-control' name='shopid".$i."'>";
                    echo "<option value=0>請選擇小站名</option>";
                    foreach($registrants as $registrant) {
                        echo "<option value=" . $registrant['sid'] . ">" . $registrant['sname'] . "</option>";
                    }
                    echo "</select>";
                } else echo "0 shop result!";
        ?></td>
        <td><input class='form-control' type="text" name='log_date<?php echo $i; ?>' placeholder='2016-01-01' id='log_date<?php echo $i; ?>' /></td>
        <td>
            <?php
                $sql = "SELECT DISTINCT SUBSTRING(mname, 1, 1) AS mla FROM masseur";
                $st_shop = $conn->query($sql);
                $registrants = $st_shop->fetchAll();
                if(count($registrants) > 0) {
                    echo "<select class='form-control' id='masseurla$i' name='masseurla$i'>";
                    echo "<option value=0>選擇師傅姓氏</option>";
                    // echo "<option value=1>林</option>";
                      foreach($registrants as $registrant) {

                        echo "<option value='" . $registrant['mla'] . "'>" . $registrant['mla'] . "</option>";

                      }

                    echo "</select>";
                } else echo "0 shop result!";
            ?>
        </td>


        <td>
            <!-- <input class='form-control' type='text' name='mid' placeholder='mname' /> -->
            <?php
                // $lastname=$_POST["masseurla$i"];
                echo "<select class='form-control' id='masseurid".$i."' name='masseurid".$i."'>";
                echo "<option>請選擇名字</option>";
                echo "</select>"

                // $sql = "SELECT * FROM masseur WHERE SUBSTRING(mname, 1, 1)='$lastname'";
                // $st_shop = $conn->query($sql);
                // $registrants = $st_shop->fetchAll();
                // if(count($registrants) > 0) {
                //     echo "<select class='form-control' name='masseurid".$i."'>";
                //     echo "<option value=0>選擇師傅名字</option>";
                //     foreach($registrants as $registrant) {
                //         echo "<option value=" . $registrant['mid'] . ">" . $registrant['mname'] . "</option>";
                //     }
                //     echo "</select>";
                // } else echo "0 shop result!";
            ?>
        </td>
        <td><input class='form-control' type='text' name='assigned<?php echo $i; ?>' /></td>
        <td><input class='form-control' type='text' name='not_assigned<?php echo $i; ?>' /></td>
        <td><input class='form-control' type='text' name='guest_num<?php echo $i; ?>' /></td>
        <td>
            <!-- <input class='form-control' type='text' name='hid' placeholder='hid' /> -->
            <?php
                $sql = "SELECT * FROM helper";
                $st_shop = $conn->query($sql);
                $registrants = $st_shop->fetchAll();
                if(count($registrants) > 0) {
                    echo "<select class='form-control' name='helpid".$i."'>";
                    echo "<option value=0>請選擇管理員</option>";
                    foreach($registrants as $registrant) {
                        echo "<option value=" . $registrant['hid'] . ">" . $registrant['hname'] . "</option>";
                    }
                    echo "</select>";
                } else echo "0 shop result!";
            ?>
        </td>
        </tr>

        <?php
 }
 ?>

    </table>
    <button class='btn btn-primary' type="submit" name="save_mul">預覽工作表資料</button>
    </form>
 <?php
}
?>
<!-- </div> -->






<script>
var num = 1;
<?php
    $n = $_POST["no_of_rec"];
    for($i=1; $i<=$_POST["no_of_rec"]; $i++) {
?>
$(function() {

    $("#log_date"+<?=$i?>).datepicker();
    $("#log_date"+<?=$i?>).datepicker( "option", "dateFormat", "yy-mm-dd");
    num++;
});


$(document).ready(function(){
   $("#masseurla"+<?=$i?>).change(function(){
         var masseurla=$("#masseurla"+<?=$i?>).val();
         $.ajax({
            type:"post",
            url:'create_view_lastname.php?la='+masseurla,
            data:"masseurla="+masseurla,
            success:function(data){
                  $("#masseurid"+<?=$i?>).html(data);
            }
         });
   });
});
<?php
}
?>

function goBack() {
    window.history.go(-1);
}
</script>
</body>
</html>
