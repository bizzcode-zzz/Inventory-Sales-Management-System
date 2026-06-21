<?php
/* =========================================
   CHART
========================================= */

include "db.php";

$sql = "
SELECT category, COUNT(*) as total
FROM products
GROUP BY category
";

$result = $conn->query($sql);

$data = [];

while ($row = $result->fetch_assoc()) {

    $data[] = $row;
}

echo json_encode($data);

$conn->close();