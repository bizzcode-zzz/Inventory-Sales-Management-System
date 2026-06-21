<?php

/* =========================================
   SAVE PRODUCT API
========================================= */

include "db.php";

/* =========================================
   RECEIVE FORM DATA
========================================= */

$name = $_POST['product_name'] ?? '';
$price = $_POST['price'] ?? '';
$stock = $_POST['stock'] ?? 0;
$category = trim($_POST['category'] ?? '');

$imageName = '';

/* =========================================
   UPLOAD IMAGE
========================================= */

if (
    isset($_FILES['image']) &&
    $_FILES['image']['error'] === 0
) {

    $imageName =
        time() . "_" . basename($_FILES['image']['name']);

    move_uploaded_file(
        $_FILES['image']['tmp_name'],
        "uploads/" . $imageName
    );
}

if (!empty($name) && !empty($price)) {

/* =========================================
   INSERT PRODUCT
========================================= */

    $stmt = $conn->prepare(
        "INSERT INTO products
        (product_name, price, image, stock, category)
        VALUES (?, ?, ?, ?, ?)"
    );

    $stmt->bind_param(
        "sdsis",
        $name,
        $price,
        $imageName,
        $stock,
        $category
    );

    if ($stmt->execute()) {

    /* =========================================
       ACTIVITY LOG
       ========================================= */

    $log = "Added Product: " . $name;

    $logStmt = $conn->prepare(
        "INSERT INTO activity_logs (activity)
         VALUES (?)"
    );

    $logStmt->bind_param("s", $log);
    $logStmt->execute();
    $logStmt->close();

    echo "success";

} else {

    echo "error";

}

    $stmt->close();

} else {

    echo "error";

}

$conn->close();

?>