<?php

include "db.php";

$sql = "

SELECT

p.product_name,

SUM(s.quantity)
AS total_sold

FROM sales s

INNER JOIN products p

ON s.product_id = p.id

GROUP BY s.product_id

ORDER BY total_sold DESC

LIMIT 5

";

$result =
$conn->query($sql);

while (
    $row =
    $result->fetch_assoc()
) {

    echo "

    <div
    style='padding:8px;
    border-bottom:1px solid #444;'>

    <strong>

    {$row['product_name']}

    </strong>

    <br>

    Sold:

    {$row['total_sold']}

    </div>

    ";

}

$conn->close();