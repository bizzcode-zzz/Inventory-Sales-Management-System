<?php

require 'vendor/autoload.php';

use Dompdf\Dompdf;

include "db.php";

$startDate =
$_GET["startDate"];

$endDate =
$_GET["endDate"];


// Revenue

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


// Total Items

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


// Top Product

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


// HTML

$html = "

<h1>

Sales Report

</h1>

<p>

Date Range:

$startDate

to

$endDate

</p>

<hr>

<h3>

Total Revenue

</h3>

<p>

PHP "
.

number_format(
$revenue["total_revenue"]
?? 0,
2
)

.

"</p>

<h3>

Top Product

</h3>

<p>"

.

(
$topProduct["product_name"]
?? "No Data"
)

.

"</p>

<h3>

Total Items Sold

</h3>

<p>"

.

(
$totalItems["total_items"]
?? 0
)

.

"</p>

";


// Generate PDF

$dompdf =
new Dompdf();

$dompdf->loadHtml(
$html
);

$dompdf->setPaper(
'A4',
'portrait'
);

$dompdf->render();

$dompdf->stream(
"sales_report.pdf",
[
"Attachment" => true
]
);