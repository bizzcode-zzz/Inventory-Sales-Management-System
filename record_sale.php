<?php

include "db.php";

$productId = $_POST["product_id"];
$quantity  = $_POST["quantity"];

/* =========================================
   GET PRODUCT INFO
========================================= */

$sql = "SELECT *
        FROM products
        WHERE ID = '$productId'";

$result = mysqli_query($conn, $sql);

$product = mysqli_fetch_assoc($result);



if (!$product) {

    die("Product not found");

}

$currentStock = $product["stock"];
$productName = $product["product_name"];
$productPrice = $product["price"];

/* =========================================
   CHECK STOCK
========================================= */

if ($quantity > $currentStock) {

    die("Not enough stock");

}

/* =========================================
   INSERT SALE
========================================= */

$saleSql = "INSERT INTO sales
            (product_id,
             quantity,
             sale_price,
             sale_date)

            VALUES

            ('$productId',
             '$quantity',
             '$productPrice',
             NOW())";

mysqli_query($conn, $saleSql);

/* =========================================
   UPDATE STOCK
========================================= */

$newStock = $currentStock - $quantity;

$updateSql = "UPDATE products

              SET stock = '$newStock'

              WHERE ID = '$productId'";

mysqli_query($conn, $updateSql);

/* =========================================
   ACTIVITY LOG
========================================= */

$activity = "Sale Recorded: "
          . $productName
          . " (Qty: "
          . $quantity
          . ")";

$logSql = "INSERT INTO activity_logs
           (activity, created_at)

           VALUES

           ('$activity', NOW())";

mysqli_query($conn, $logSql);

echo "Sale Recorded Successfully";