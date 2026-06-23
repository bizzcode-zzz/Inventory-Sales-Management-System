<?php

include "db.php";

$result = mysqli_query(
    $conn,
    "
    SELECT
    COALESCE(
        SUM(quantity * sale_price),
        0
    ) AS week_revenue
    FROM sales
    WHERE YEARWEEK(
        sale_date,
        1
    ) = YEARWEEK(
        CURDATE(),
        1
    )
    "
);

$row = mysqli_fetch_assoc($result);

header("Content-Type: application/json");

echo json_encode($row);