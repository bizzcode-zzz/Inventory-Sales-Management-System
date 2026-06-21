<?php

include "db.php";

$startDate =
$_GET['startDate'] ?? '';

$endDate =
$_GET['endDate'] ?? '';

$sql = "

SELECT

p.product_name,

SUM(s.quantity) AS total_sold

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

GROUP BY s.product_id

ORDER BY total_sold DESC

LIMIT 5

";

$result = mysqli_query($conn, $sql);

$data = [];

while ($row = mysqli_fetch_assoc($result)) {

    $data[] = $row;

}

header("Content-Type: application/json");

echo json_encode($data);