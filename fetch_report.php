<?php

include "db.php";

$startDate =
$_GET["startDate"];

$endDate =
$_GET["endDate"];

$sql = "

SELECT

SUM(quantity * sale_price)
AS total_revenue

FROM sales

WHERE DATE(sale_date)

BETWEEN

'$startDate'

AND

'$endDate'

";

$result =
$conn->query($sql);

$row =
$result->fetch_assoc();

$totalItemsQuery = $conn->query("

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



echo "

<h3>

💰 Total Revenue

</h3>

<p>

₱

" .

number_format(
    $row["total_revenue"] ?? 0,
    2
)

.

"</p>

";

echo "

<h3>

🏆 Top Product

</h3>

<p>

" .

($topProduct["product_name"]
?? "No Data")

.

"</p>

";

echo "

<h3>

📦 Total Items Sold

</h3>

<p>

" .

($totalItems["total_items"]
?? 0)

.

"</p>

";

$conn->close();



?>