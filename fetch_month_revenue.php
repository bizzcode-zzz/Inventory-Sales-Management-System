<?php

include "db.php";

$result = mysqli_query(
    $conn,
    "
    SELECT
    COALESCE(
        SUM(quantity * sale_price),
        0
    ) AS month_revenue
    FROM sales
    WHERE MONTH(sale_date) = MONTH(CURDATE())
    AND YEAR(sale_date) = YEAR(CURDATE())
    "
);

$row = mysqli_fetch_assoc($result);

header("Content-Type: application/json");

echo json_encode($row);