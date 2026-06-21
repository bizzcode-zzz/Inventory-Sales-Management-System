<?php

include "db.php";

header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="products.csv"');

$output = fopen("php://output", "w");

fputcsv($output, [
    "ID",
    "Product Name",
    "Category",
    "Price",
    "Stock"
]);

$result = $conn->query("
    SELECT id, product_name, category, price, stock
    FROM products
    ORDER BY id DESC
");

while ($row = $result->fetch_assoc()) {

    fputcsv($output, [
        $row['id'],
        $row['product_name'],
        $row['category'],
        $row['price'],
        $row['stock']
    ]);
}

fclose($output);

$conn->close();

?>