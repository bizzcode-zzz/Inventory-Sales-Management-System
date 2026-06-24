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

echo "

<h3>

💰 Total Revenue

</h3>

<p>

₱

" .

number_format(
$row["total_revenue"],
2
)

.

"</p>

";

$conn->close();

?>