<?php

include "db.php";

$result = mysqli_query(
    $conn,
    "
    SELECT
    COALESCE(
        SUM(quantity),
        0
    ) AS total_items_sold
    FROM sales
    "
);

$row = mysqli_fetch_assoc($result);

header("Content-Type: application/json");

echo json_encode($row);