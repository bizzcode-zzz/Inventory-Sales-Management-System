<?php

session_start();

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    die("Invalid Request");
}

if (
    !isset($_SESSION["role"]) ||
    $_SESSION["role"] !== "admin"
) {
    die("Access Denied");
}

include "db.php";

$sql = "TRUNCATE TABLE sales";

if (mysqli_query($conn, $sql)) {

    echo "success";

} else {

    echo "error";

}