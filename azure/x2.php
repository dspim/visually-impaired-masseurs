    <tr>
        <td>
        <?php
                $sql = "SELECT * FROM shop";
                $st_shop = $conn->query($sql);
                $registrants = $st_shop->fetchAll();
                if(count($registrants) > 0) {
                    echo "<select  class='form-control' name='shopid'>";
                    foreach($registrants as $registrant) {
                        echo "<option value=" . $registrant['sid'] . ">" . $registrant['sname'] . "</option>";
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
                $registrants = $st_shop->fetchAll();
                if(count($registrants) > 0) {
                    echo "<select class='form-control' name='masseurid'>";
                    foreach($registrants as $registrant) {
                        echo "<option value=" . $registrant['mid'] . ">" . $registrant['mname'] . "</option>";
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
                        echo "<option value=" . $registrant['mid'] . ">" . $registrant['mname'] . "</option>";
                    }
                    echo "</select>";
                } else echo "0 shop result!"; 
            ?>
        </td>
        <td><input class='w form-control' type='text' name='assigned' /></td>
        <td><input class='w form-control' type='text' name='not_assigned' /></td>
        <td><input class='w form-control' type='text' name='guest_num' /></td>
        <td>
            <!-- <input class='form-control' type='text' name='hid' placeholder='hid' /> -->
            <?php
                $sql = "SELECT * FROM helper";
                $st_shop = $conn->query($sql);
                $registrants = $st_shop->fetchAll();
                if(count($registrants) > 0) {
                    echo "<select class='form-control' name='helpid'>";
                    foreach($registrants as $registrant) {
                        echo "<option value=" . $registrant['hid'] . ">" . $registrant['hname'] . "</option>";
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
                        echo "<option value=" . $registrant['hid'] . ">" . $registrant['hname'] . "</option>";
                    }
                    echo "</select>";
                } else echo "0 shop result!"; 
            ?>
        </td>
        <td><Button class='btn btn-danger' id="del" onClick='delete_record();'>x</Button></td>
    </tr>
    