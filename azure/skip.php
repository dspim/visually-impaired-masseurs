<!-- Insert registration info -->
<!-- <p>Fill in all cases everyday, then click <strong>Submit</strong> to record.</p>
<form method="post" action="create_view.php" enctype="multipart/form-data" >
      小站  <input type="text" name="sid" id="sid"/></br>
      日期  <input type="text" name="log_date" id="log_date"/></br>
      師傅編號 <input type="text" name="mid" id="mid"/></br>
      接待員編號 <input type="text" name="hid" id="hid"/></br>
      指定節數 <input type="text" name="assigned" id="assigned"/></br>
      未指定節數 <input type="text" name="not_assigned" id="not_assigned"/></br>
      來客數 <input type="text" name="guest_num" id="guest_num"/></br>
      <input type="submit" name="submit" value="Submit" />
</form> -->

<!--     // if(!empty($_POST)) {
    //     try {
    //      $sid = $_POST['shopid'];
    //         $log_date = $_POST['log_date'];
    //         $mid = $_POST['masseurid'];
    //         $assigned = $_POST['assigned'];
    //         $not_assigned = $_POST['not_assigned'];
    //         $hid = $_POST['helpid'];
    //         $guest_num = $_POST['guest_num'];

    //         if($sid == 0 || $mid == 0 || $hid == 0 ) {
    //             echo "<script>alert('有欄位是空白'); window.location.href = '/';</script>";
    //             exit;
    //         }

    //     // Insert data
    //         $sql_check = "INSERT INTO worklog (log_date, mid, assigned, not_assigned, hid, guest_num, sid) VALUES (?,?,?,?,?,?,?) ON DUPLICATE KEY UPDATE sid=?, hid=?, guest_num=?, assigned=?, not_assigned=?";
    //         $stmt = $conn->prepare($sql_check);
    //             $stmt->bindValue(1, $log_date);
    //             $stmt->bindValue(2, $mid);
    //             $stmt->bindValue(3, $assigned);
    //             $stmt->bindValue(4, $not_assigned);
    //             $stmt->bindValue(5, $hid);
    //             $stmt->bindValue(6, $guest_num);
    //             $stmt->bindValue(7, $sid);
    //             $stmt->bindValue(8, $sid);
    //             $stmt->bindValue(9, $hid);
    //             $stmt->bindValue(10, $guest_num);
    //             $stmt->bindValue(11, $assigned);
    //             $stmt->bindValue(12, $not_assigned);
    //             $stmt->execute();
    //          // echo "<script>alert('no execute'); window.location.href = '/';</script>";

    //     }
    //     catch(Exception $e) {
    //         // die(var_dump($e));
    //         echo "<script>alert('記錄中有欄位格式錯誤喔'); window.location.href = '/';</script>";
    //     }

    //         $sql_index = "SELECT * FROM worklog";
    //         $q = $conn->query($sql_index);
    //         $rows = $q->fetchAll();
    //         $show = count($rows);
    //         $new = ceil($show/30);
    //         echo "<script>location='index.php?page=".$new."';alert('新增成功!');</script>";
    // } -->

    
<?php
    // Insert registration info
    if(!empty($_POST)) {
        try {
            $sid = $_POST['shopid'];
            $log_date = $_POST['log_date'];
            $mid = $_POST['masseurid'];
            $assigned = $_POST['assigned'];
            $not_assigned = $_POST['not_assigned'];
            $hid = $_POST['helpid'];
            $guest_num = $_POST['guest_num'];

            if($sid == 0 || $mid == 0 || $hid == 0 ) {
                echo "<script>alert('有欄位是空白'); window.location.href = '/';</script>";
                exit;
            }

        // Insert data
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
             // echo "<script>alert('no execute'); window.location.href = '/';</script>";

        }
        catch(Exception $e) {
            // die(var_dump($e));
            echo "<script>alert('記錄中有欄位格式錯誤喔'); window.location.href = '/';</script>";
        }

            $sql_index = "SELECT * FROM worklog";
            $q = $conn->query($sql_index);
            $rows = $q->fetchAll();
            $show = count($rows);
            $new = ceil($show/30);
            echo "<script>location='index.php?page=".$new."';alert('新增成功!');</script>";
    }
?>

        <form method="post" action="index.php">
        <table class='table table-bordered table-striped'>
            <tr><th class="ww">小站</th><th class="w">日期</th><th class="ww">按摩師傅</th>
                <th class="w">指定節數</th><th class="w">未指定節數</th><th class="w">來客數</th>
                <th class="ww">管理員</th><th>新增</th></tr>
            
            <tr>
                <td>
                <?php
                        $sql = "SELECT * FROM shop";
                        $st_shop = $conn->query($sql);
                        $registrants_s = $st_shop->fetchAll();
                        if(count($registrants_s) > 0) {
                            echo "<select  class='form-control' name='shopid'>";
                            echo "<option value=0>小站名</option>";
                            foreach($registrants_s as $registrant_s) {
                                echo "<option value=" . $registrant_s['sid'] . ">" . $registrant_s['sname'] . "</option>";
                            }
                            echo "</select>";
                        } else echo "0 shop result!"; 
                ?></td>
                <td><input class='d form-control' type="text" name='log_date' placeholder='2016-01-01' id='log_date' /></td>
                <td>
                    <!-- <input class='form-control' type='text' name='mid' placeholder='mid' /> -->
                    <?php
                        $sql = "SELECT * FROM masseur";
                        $st_shop = $conn->query($sql);
                        $registrants_m = $st_shop->fetchAll();
                        if(count($registrants_m) > 0) {
                            echo "<select class='form-control' name='masseurid'>";
                            foreach($registrants_m as $registrant_m) {
                                echo "<option value=" . $registrant_m['mid'] . ">" . $registrant_m['mname'] . "</option>";
                            }
                            echo "</select>";
                        } else echo "0 shop result!"; 
                    ?>
                </td>
                <td><input class='w form-control' type='text' placeholder='限數字輸入 ex: 1' name='assigned' /></td>
                <td><input class='w form-control' type='text' placeholder='限數字輸入 ，ex: 1' name='not_assigned' /></td>
                <td><input class='w form-control' type='text' placeholder='限數字輸入 ，ex: 1' name='guest_num' /></td>
                <td>
                    <!-- <input class='form-control' type='text' name='hid' placeholder='hname' /> -->
                    <?php
                        $sql = "SELECT * FROM helper";
                        $st_shop = $conn->query($sql);
                        $registrants_h = $st_shop->fetchAll();
                        if(count($registrants_h) > 0) {
                            echo "<select class='form-control' name='helpid'>";
                            foreach($registrants_h as $registrant_h) {
                                echo "<option value=" . $registrant_h['hid'] . ">" . $registrant_h['hname'] . "</option>";
                            }
                            echo "</select>";
                        } else echo "0 shop result!"; 
                    ?>
                </td>
                <td><input class='btn btn-primary' type='submit' name='submit' value='+' /></td>
            </tr>
    
        </table>
        </form>