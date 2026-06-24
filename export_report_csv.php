<?php

include "db.php";

$startDate =
$_GET["startDate"];

$endDate =
$_GET["endDate"];

header(
'Content-Type: text/csv'
);

header(
'Content-Disposition: attachment; filename="report.csv"'
);

$output =
fopen(
"php://output",
"w"
);

fputcsv(
$output,
[
"Metric",
"Value"
]
);

$revenueQuery =
$conn->query("

SELECT

SUM(quantity * sale_price)
AS total_revenue

FROM sales

WHERE DATE(sale_date)

BETWEEN

'$startDate'

AND

'$endDate'

");

$revenue =
$revenueQuery
->fetch_assoc();

$totalItemsQuery =
$conn->query("

SELECT

SUM(quantity)
AS total_items

FROM sales

WHERE DATE(sale_date)

BETWEEN

'$startDate'

AND

'$endDate'

");

$totalItems =
$totalItemsQuery
->fetch_assoc();

$topProductQuery =
$conn->query("

SELECT

p.product_name,

SUM(s.quantity)
AS total_sold

FROM sales s

INNER JOIN products p

ON s.product_id = p.id

WHERE DATE(s.sale_date)

BETWEEN

'$startDate'

AND

'$endDate'

GROUP BY s.product_id

ORDER BY total_sold DESC

LIMIT 1

");

$topProduct =
$topProductQuery
->fetch_assoc();

fputcsv(
$output,
[
"Total Revenue",
$revenue["total_revenue"]
?? 0
]
);

fputcsv(
$output,
[
"Top Product",
$topProduct["product_name"]
?? "No Data"
]
);

fputcsv(
$output,
[
"Total Items Sold",
$totalItems["total_items"]
?? 0
]
);

fclose($output);

$conn->close();