<?php

$startDate =
$_GET['startDate'] ?? '';

$endDate =
$_GET['endDate'] ?? '';

include "db.php";

$sql = "

SELECT

s.id,
p.product_name,
s.quantity,
s.sale_price,
s.sale_date

FROM sales s

INNER JOIN products p

ON s.product_id = p.id

";

if (
    !empty($startDate)
    &&
    !empty($endDate)
) {

    $sql .= "

    WHERE DATE(s.sale_date)

    BETWEEN

    '$startDate'

    AND

    '$endDate'

    ";

}
$sql .= "

ORDER BY s.sale_date DESC

";

$result = mysqli_query($conn, $sql);

$data = [];

while ($row = mysqli_fetch_assoc($result)) {

    $data[] = $row;

}

header("Content-Type: application/json");

echo json_encode($data);