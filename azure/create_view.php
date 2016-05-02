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
<script type="text/javascript">
    
    $(function() {
    $( "#log_date" ).datepicker();
    $( "#log_date" ).datepicker( "option", "dateFormat", "yy-mm-dd");
});
</script>
</head>
<body>


<h1>新增工作記錄
<input class="right btn btn-default" value="管理員列表" type="button" onclick="location='helper.php'" />
<input class="right btn btn-default" value="師傅列表" type="button" onclick="location='masseur.php'" />
<input class="right btn btn-default" value="上傳頁面" type="button" onclick="location='upload.php'" />
<input class="right btn btn-default" value="回首頁" type="button" onclick="location='/'" />
<input class='right btn btn-primary' type='button' name='add' id='add' value='新增一筆' />
</h1>

<!-- Insert registration info -->
<form method='post' action='create.php'>
<table class='table table-striped'>
    <tr><th>小站</th><th class="w">日期</th><th>師傅姓氏</th><th>師傅名字</th>
        <th class="w">指定節數</th><th class="w">未指定節數</th><th class="w">來客數</th>
        <th>管理員姓氏</th><th>管理員名字</th><th>刪除</th></tr>
</table>    

<div id="showBlock"><input class="form-control" type="text" name="log_date" placeholder="2016-01-01" id="log_date"></div>
<input class='right btn btn-primary' type='submit' name='submit' value='預覽工作表' />
</form>


<script>

  //set the default value
  var txtId = 1;
  var php = '<table><tr><td><input class="form-control" type="text" name="log_date" placeholder="2016-01-01" id="log_date"></td>';
  //add input block in showBlock
  $("#add").click(function () {
      $("#showBlock").append('<div id="div' + txtId + '">'+php+' <td><input class="btn btn-danger" type="button" value="x" onclick="deltxt('+txtId+')"></td></tr></table></div>');
      txtId++;
  });
  //remove div
  function deltxt(id) {
      $("#div"+id).remove();
  }

</script>
</body>
</html>
