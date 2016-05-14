<?php  

header("Content-Type:text/html; charset=utf-8");

if(isset($_POST['azure']))
{  
    $string= $_POST['string'];

    // echo $string;

    $replace_example = explode(".",$string);

    $num = substr_count($string,".");
    for ($i=0; $i<$num;$i++) {
        $parse_string = $replace_example[$i];
        $data = explode(",",$parse_string);
            $sid = $data[0];
            $log_date = $data[1];
            $hid = $data[2];
            $mid = $data[3];
            $assigned = $data[4];
            $not_assigned = $data[5];
            $guest_num = $data[6];

            // echo $sid . " ";
            // echo $mid . " ";
            // echo $hid . " ";
            // echo $log_date . " ";
            // echo $guest_num . " ";
            // echo $assigned . " ";
            // echo $not_assigned . " ";

            $host = "dream.cs.nccu.edu.tw:32781"; 
            $user = "root";
            $pwd = "d4sg";
            $db = "d4sg";
            // Connect to database.
            try {
                $conn = new PDO("mysql:host=$host;dbname=$db;charset=utf8;port=32781", $user, $pwd);
                $conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
                // echo "connect";
            }
            catch(Exception $e){
                die(var_dump($e));
            }

            try {

            // Insert data
                $sql_save = "INSERT INTO worklog (log_date, mid, assigned, not_assigned, hid, guest_num, sid) VALUES (?,?,?,?,?,?,?) ON DUPLICATE KEY UPDATE sid=?, hid=?, guest_num=?, assigned=?, not_assigned=?";
                $stmt = $conn->prepare($sql_save);
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
            echo "<script>alert('記錄中有欄位格式錯誤喔，請重新確認。'); window.history.back();</script>";
        }

    }
    echo "<script>alert('新增成功!');location='/';</script>";
    
    
}