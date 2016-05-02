<?php

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
</head>
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
    table, th, td {
        text-align: center;
    }
    .w {
        width: 100px;
    }
    .td {
        text-align: center;
    }    

</style>

</head>
<body>


<h1>新增工作記錄
<input class="right btn btn-default" value="管理員列表" type="button" onclick="location='helper.php'" />
<input class="right btn btn-default" value="師傅列表" type="button" onclick="location='masseur.php'" />
<input class="right btn btn-default" value="上傳頁面" type="button" onclick="location='upload.php'" />
<input class="right btn btn-default" value="回首頁" type="button" onclick="location='/'" />

<!-- <form method="post" name="frm">
    <Button class="right btn btn-danger" onClick="delete_record();">刪除</Button>
</form> -->
</h1> 

<!-- Insert registration info -->
<form method='post' action='create_view.php'>
<table class='table table-bordered table-striped'>
    <tr><th>小站</th><th class="w">日期</th><th>師傅姓氏</th><th>師傅名字</th>
        <th class="w">指定節數</th><th class="w">未指定節數</th><th class="w">來客數</th>
        <th>管理員姓氏</th><th>管理員名字</th></tr>
</table>
    <div id=showBlock></div>
    <?php 
      $tx = 0;
      for ($i=0; $i<2; $i++) { ?>
          <tr>
        <td>
        <?php
                $sql = "SELECT * FROM shop";
                $st_shop = $conn->query($sql);
                $registrants = $st_shop->fetchAll();
                if(count($registrants) > 0) {
                    echo "<select  class='form-control' name='shopid'>";
                    foreach($registrants as $registrant) {
                        echo "<option name=sname". $tx . " value=" . $registrant['sid'] . ">" . $registrant['sname'] . "</option>";
                    }
                    echo "</select>";
                } else echo "0 shop result!"; 
        ?></td>
        <td><input class='d form-control' type="text" name='log_date<?php echo $tx; ?>' placeholder='2016-01-01' id='log_date<?php echo $tx; ?>' /></td>
        <td>
            <!-- <input class='form-control' type='text' name='mid' placeholder='mid' /> -->
            <?php
                $sql = "SELECT * FROM masseur";
                $st_shop = $conn->query($sql);
                $registrants = $st_shop->fetchAll();
                if(count($registrants) > 0) {
                    echo "<select class='form-control' name='masseurid'>";
                    foreach($registrants as $registrant) {
                        echo "<option name=mlast". $tx . " value=" . $registrant['mid'] . ">" . $registrant['mname'] . "</option>";
                    }
                    echo "</select>";
                } else echo "0 shop result!"; 
            ?>
        </td>
        <td>
            <!-- <input class='form-control' type='text' name='mid' placeholder='mname' /> -->
            <?php
                $sql = "SELECT * FROM masseur";
                $st_shop = $conn->query($sql);
                $registrants = $st_shop->fetchAll();
                if(count($registrants) > 0) {
                    echo "<select class='form-control' name='masseurid'>";
                    foreach($registrants as $registrant) {
                        echo "<option name=mname". $tx . " value=" . $registrant['mid'] . ">" . $registrant['mname'] . "</option>";
                    }
                    echo "</select>";
                } else echo "0 shop result!"; 
            ?>
        </td>
        <td><input class='w form-control' type='text' name='assigned<?php echo $tx; ?>' /></td>
        <td><input class='w form-control' type='text' name='not_assigned<?php echo $tx; ?>' /></td>
        <td><input class='w form-control' type='text' name='guest_num<?php echo $tx; ?>' /></td>
        <td>
            <!-- <input class='form-control' type='text' name='hid' placeholder='hid' /> -->
            <?php
                $sql = "SELECT * FROM helper";
                $st_shop = $conn->query($sql);
                $registrants = $st_shop->fetchAll();
                if(count($registrants) > 0) {
                    echo "<select class='form-control' name='helpid'>";
                    foreach($registrants as $registrant) {
                        echo "<option name=hlast". $tx . " value=" . $registrant['hid'] . ">" . $registrant['hname'] . "</option>";
                    }
                    echo "</select>";
                } else echo "0 shop result!"; 
            ?>
        </td>
        <td>
            <!-- <input class='form-control' type='text' name='hid' placeholder='hname' /> -->
            <?php
                $sql = "SELECT * FROM helper";
                $st_shop = $conn->query($sql);
                $registrants = $st_shop->fetchAll();
                if(count($registrants) > 0) {
                    echo "<select class='form-control' name='helpid'>";
                    foreach($registrants as $registrant) {
                        echo "<option name=hname". $tx . " value=" . $registrant['hid'] . ">" . $registrant['hname'] . "</option>";
                    }
                    echo "</select>";
                } else echo "0 shop result!"; 
            ?>
        </td>
    <?php
        $tx++;  
        }

    ?>
<!-- </table> -->

<input class='right btn btn-primary' type='submit' name='submit' value='預覽工作表' />
</form>


<script>
$(function() {
  var tx = 0;
  for (var i=0; i<10 ; i++) {
    $( "#log_date"+tx ).datepicker();
    $( "#log_date"+tx ).datepicker( "option", "dateFormat", "yy-mm-dd");
    tx++;
  }
});

</script>
</body>
</html>
