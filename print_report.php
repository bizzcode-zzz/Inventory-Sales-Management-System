<?php

include "db.php";

$startDate =
$_GET['startDate'] ?? '';

$endDate =
$_GET['endDate'] ?? '';

$sql = "

SELECT

p.product_name,

s.quantity,

s.sale_price,

s.sale_date

FROM sales s

INNER JOIN products p

ON s.product_id = p.id

";

if (
    !empty($startDate)
    &&
    !empty($endDate)
) {

    $sql .= "

    WHERE DATE(s.sale_date)

    BETWEEN

    '$startDate'

    AND

    '$endDate'

    ";

}

$sql .= "

ORDER BY s.sale_date DESC

";




$result = mysqli_query($conn, $sql);
$totalRevenue = 0;

?>

<!DOCTYPE html>

<html>

<head>

<title>Sales Report</title>

</head>
<style>

body {
    font-family: Arial, sans-serif;
    margin: 30px;
}

table {
    width: 100%;
    border-collapse: collapse;
}

th,
td {
    padding: 10px;
    border: 1px solid black;
}

h1,
h2,
h3 {
    text-align: center;
}

</style>

<body>

<h1>Inventory Sales Report</h1>
<h3>

Date Range:

<?php echo $startDate; ?>

to

<?php echo $endDate; ?>

</h3>

<table border="1" cellpadding="10">

<tr>

<th>Date</th>

<th>Product</th>

<th>Qty</th>

<th>Amount</th>

</tr>

<?php 
while ($row = mysqli_fetch_assoc($result)) {

    $amount =
    $row['quantity']
    *
    $row['sale_price'];

    $totalRevenue += $amount;


   echo "<tr>";

    echo "<td>{$row['sale_date']}</td>";

    echo "<td>{$row['product_name']}</td>";

    echo "<td>{$row['quantity']}</td>";

    echo "<td>₱"
         .
         number_format(
         $amount,
         2
         )
         .
         "</td>";

    echo "</tr>";
    }
?>


</table>


 <h2>

Total Revenue:

₱<?php
echo number_format(
$totalRevenue,
2
);
?>

</h2>

<script>

window.print();

</script>

</body>

</html>