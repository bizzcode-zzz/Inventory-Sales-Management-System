<?php

include "db.php";

$sql = "

SELECT

p.product_name,

SUM(s.quantity) AS total_sold

FROM sales s

INNER JOIN products p

ON s.product_id = p.id

GROUP BY s.product_id

ORDER BY total_sold DESC

LIMIT 1

";

$result = mysqli_query($conn, $sql);

$row = mysqli_fetch_assoc($result);

header("Content-Type: application/json");

echo json_encode($row);