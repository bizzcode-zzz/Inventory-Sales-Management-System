<?php

include "db.php";

$startDate =
$_GET['startDate'] ?? '';

$endDate =
$_GET['endDate'] ?? '';

$sql = "

SELECT

DATE(sale_date) AS sale_day,

SUM(quantity * sale_price) AS revenue

FROM sales

";

if (
    !empty($startDate)
    &&
    !empty($endDate)
) {

    $sql .= "

    WHERE DATE(sale_date)

    BETWEEN

    '$startDate'

    AND

    '$endDate'

    ";

}

$sql .= "

GROUP BY DATE(sale_date)

ORDER BY sale_day ASC

";

$result = mysqli_query($conn, $sql);

$data = [];

while ($row = mysqli_fetch_assoc($result)) {

    $data[] = $row;

}

header("Content-Type: application/json");

echo json_encode($data);