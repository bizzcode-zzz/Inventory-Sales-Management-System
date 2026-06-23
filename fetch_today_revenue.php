<?php

include "db.php";

$result = mysqli_query(
    $conn,
    "
    SELECT
    COALESCE(
        SUM(quantity * sale_price),
        0
    ) AS today_revenue
    FROM sales
    WHERE DATE(sale_date) = CURDATE()
    "
);

$row = mysqli_fetch_assoc($result);

header("Content-Type: application/json");

echo json_encode($row);