<?php

$conn = new mysqli("localhost", "root", "", "mydb");

$stats = $conn->query("
    SELECT
        COUNT(*) AS total_products,
        COALESCE(AVG(price),0) AS avg_price,
        COALESCE(MAX(price),0) AS max_price
    FROM products
");

$row = $stats->fetch_assoc();

echo json_encode([
    "total" => $row['total_products'],
    "avg" => number_format($row['avg_price'], 2),
    "max" => number_format($row['max_price'], 2)
]);