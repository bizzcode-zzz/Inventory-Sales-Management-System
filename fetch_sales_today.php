<?php

include "db.php";

$result = mysqli_query(
    $conn,
    "
    SELECT
    COUNT(*) AS sales_today
    FROM sales
    WHERE DATE(sale_date) = CURDATE()
    "
);

$row = mysqli_fetch_assoc($result);

header("Content-Type: application/json");

echo json_encode($row);