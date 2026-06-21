<?php

include "db.php";

$outOfStock = $conn->query(
    "SELECT COUNT(*) AS total
     FROM products
     WHERE stock = 0"
)->fetch_assoc()['total'];

$lowStock = $conn->query(
    "SELECT COUNT(*) AS total
     FROM products
     WHERE stock BETWEEN 1 AND 5"
)->fetch_assoc()['total'];

$inStock = $conn->query(
    "SELECT COUNT(*) AS total
     FROM products
     WHERE stock > 5"
)->fetch_assoc()['total'];

echo json_encode([
    "out" => $outOfStock,
    "low" => $lowStock,
    "in" => $inStock
]);