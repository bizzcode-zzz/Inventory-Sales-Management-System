<?php

include "db.php";

$startDate =
$_GET["startDate"];

$endDate =
$_GET["endDate"];

$result =
$conn->query(

"

SELECT

DATE(sale_date)
AS sale_day,

SUM(
quantity * sale_price
)

AS revenue

FROM sales

WHERE DATE(sale_date)

BETWEEN

'$startDate'

AND

'$endDate'

GROUP BY DATE(sale_date)

ORDER BY sale_day

"

);

$data = [];

while (
$row =
$result->fetch_assoc()
) {

    $data[] =
    $row;

}

header(
'Content-Type: application/json'
);

echo json_encode(
$data
);

$conn->close();