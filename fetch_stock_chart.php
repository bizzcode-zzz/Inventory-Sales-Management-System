<?php

include "db.php";

$inStock = 0;
$lowStock = 0;
$outStock = 0;

$result = $conn->query(
    "SELECT stock FROM products"
);

while ($row = $result->fetch_assoc()) {

    $stock = (int)$row['stock'];

    if ($stock <= 0) {

        $outStock++;

    } elseif ($stock <= 5) {

        $lowStock++;

    } else {

        $inStock++;

    }
}

echo json_encode([
    "inStock" => $inStock,
    "lowStock" => $lowStock,
    "outStock" => $outStock
]);

$conn->close();
?>