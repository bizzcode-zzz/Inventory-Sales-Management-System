<?php

$conn = new mysqli("localhost", "root", "", "mydb");

if (!isset($_GET['id'])) {
    die("Receipt ID missing.");
}

$id = (int) $_GET['id'];

$stmt = $conn->prepare("
    SELECT
        s.id,
        p.product_name,
        s.quantity,
        s.sale_price,
        s.sale_date
    FROM sales s
    INNER JOIN products p
        ON s.product_id = p.id
    WHERE s.id = ?
");

$stmt->bind_param("i", $id);

$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Receipt not found.");
}

$row = $result->fetch_assoc();

$total =
    $row['quantity'] *
    $row['sale_price'];

?>

<!DOCTYPE html>
<html>

<head>

<title>Sales Receipt</title>

<style>

@media print {

    #printBtn {
    display:none;
    }

}

</style>

<style>

body {

    font-family: Arial, sans-serif;
    max-width: 400px;
    margin: 30px auto;
    padding: 20px;
    border: 1px solid #ccc;

}

h2 {

    text-align: center;

}

.line {

    margin: 10px 0;

}

.total {

    font-size: 20px;
    font-weight: bold;

}

button {

    margin-top: 20px;
    padding: 10px 20px;
    cursor: pointer;

}

</style>

</head>

<body>

<h2>SALES RECEIPT</h2>

<hr>

<div class="line">
Receipt ID:
<strong>
<?= $row['id'] ?>
</strong>
</div>

<div class="line">
Product:
<strong>
<?= htmlspecialchars($row['product_name']) ?>
</strong>
</div>

<div class="line">
Quantity:
<strong>
<?= $row['quantity'] ?>
</strong>
</div>

<div class="line">
Price:
<strong>
₱<?= number_format($row['sale_price'], 2) ?>
</strong>
</div>

<div class="line total">
Total:
₱<?= number_format($total, 2) ?>
</div>

<div class="line">
Date:
<strong>
<?= $row['sale_date'] ?>
</strong>
</div>

<hr>

<p style="text-align:center;">
Thank You!
</p>

<button
id="printBtn"
onclick="window.print()"
style="padding:10px 20px;">
🖨 Print Receipt
</button>

</body>

</html>