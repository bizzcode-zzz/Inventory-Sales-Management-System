<?php

include "db.php";

$result = mysqli_query(
    $conn,
    "
    SELECT
        p.category,
        SUM(s.quantity) AS total_sold

    FROM sales s

    INNER JOIN products p
        ON s.product_id = p.id

    GROUP BY p.category

    ORDER BY total_sold DESC

    LIMIT 1
    "
);

$row = mysqli_fetch_assoc($result);

header("Content-Type: application/json");

echo json_encode($row);