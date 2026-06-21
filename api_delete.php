<?php

/* =========================================
   DELETE PRODUCT API
========================================= */

include "db.php";

/* =========================================
   RECEIVE PRODUCT ID
========================================= */

$id = $_POST['id'] ?? 0;

if ($id) {

    /* =========================================
   GET PRODUCT INFO
========================================= */

$nameStmt = $conn->prepare(
    "SELECT product_name, image
FROM products
WHERE id=?"
);

$nameStmt->bind_param("i", $id);
$nameStmt->execute();

$nameResult = $nameStmt->get_result();
$nameRow = $nameResult->fetch_assoc();

$productName = $nameRow['product_name'] ?? '';
$productImage = $nameRow['image'] ?? '';

$nameStmt->close();


/* =========================================
   DELETE PRODUCT RECORD
========================================= */

$stmt = $conn->prepare("DELETE FROM products WHERE id=?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {

/* =========================================
   ACTIVITY LOG
========================================= */

    $log = "Deleted Product: " . $productName;

    $logStmt = $conn->prepare(
        "INSERT INTO activity_logs (activity)
         VALUES (?)"
    );

    $logStmt->bind_param("s", $log);
    $logStmt->execute();
    $logStmt->close();


     /* =========================================
        DELETE IMAGE FILE
        ========================================= */

    if (!empty($productImage)) {

    $file = "uploads/" . $productImage;

    if (file_exists($file)) {
        unlink($file);
    }
}

    echo "success";

} else {

    echo "error";

}

$stmt->close();

   
}

$conn->close();
?>