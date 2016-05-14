
<?php
        include "conn.php";
        

        $la = $_GET['la'];
        $sql = "SELECT * FROM masseur WHERE SUBSTRING(mname, 1, 1)='$la'";
        $st_shop = $conn->query($sql);
        $registrants = $st_shop->fetchAll();
        if(count($registrants) > 0) {
            foreach($registrants as $registrant) {
                echo "<option value=" . $registrant['mid'] . ">" . $registrant['mname'] . "</option>";
            }
            // echo "</select>";
        } else echo "0 shop result!";

        
?>
