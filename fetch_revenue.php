<?php

include "db.php";

$startDate =
$_GET['startDate'] ?? '';

$endDate =
$_GET['endDate'] ?? '';

$sql = "

SELECT

SUM(quantity * sale_price)

AS revenue

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

$result = mysqli_query($conn, $sql);

$row = mysqli_fetch_assoc($result);

header("Content-Type: application/json");

echo json_encode($row);