<?php

include "db.php";

header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="sales_report.csv"');

$output = fopen("php://output", "w");

fputcsv($output, [
    "Product Name",
    "Quantity",
    "Sale Price",
    "Sale Date"
]);

$result = $conn->query("

    SELECT

    p.product_name,

    s.quantity,

    s.sale_price,

    s.sale_date

    FROM sales s

    INNER JOIN products p

    ON s.product_id = p.id

    ORDER BY s.sale_date DESC

");

while ($row = $result->fetch_assoc()) {

    fputcsv($output, [

        $row['product_name'],

        $row['quantity'],

        $row['sale_price'],

        $row['sale_date']

    ]);

}

fclose($output);

$conn->close();

?>