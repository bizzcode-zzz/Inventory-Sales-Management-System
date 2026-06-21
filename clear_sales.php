<?php

include "db.php";

$sql = "TRUNCATE TABLE sales";

if (mysqli_query($conn, $sql)) {

    echo "success";

} else {

    echo "error";

}