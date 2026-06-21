<?php
include "db.php";


$id = $_POST['product_id'] ?? 0;
$name = $_POST['new_product_name'] ?? '';
$price = $_POST['new_price'] ?? '';
$stock = $_POST['new_stock'] ?? 0;
$category = trim($_POST['new_category'] ?? '');

$oldImage = '';

$stmt = $conn->prepare(
    "SELECT image FROM products WHERE id=?"
);

$stmt->bind_param("i", $id);
$stmt->execute();

$result = $stmt->get_result();
$row = $result->fetch_assoc();

$oldImage = $row['image'] ?? '';

$stmt->close();

$newImageName = null;

if (
    isset($_FILES['new_image']) &&
    $_FILES['new_image']['error'] === 0
) {

    $newImageName =
        time() . "_" . basename($_FILES['new_image']['name']);

        if (!empty($oldImage)) {

    $oldFile = "uploads/" . $oldImage;

    if (is_file($oldFile)) {
    unlink($oldFile);
    }
}

    move_uploaded_file(
        $_FILES['new_image']['tmp_name'],
        "uploads/" . $newImageName
    );
}

if ($id && $name && $price) {


    if ($newImageName) {

    $stmt = $conn->prepare(
    "UPDATE products
    SET product_name=?, category=?, price=?, stock=?, image=?
    WHERE id=?"
    );

    $stmt->bind_param(
        "ssdisi",
        $name,
        $category,
        $price,
        $stock,
        $newImageName,
        $id
    );

} else {

    $stmt = $conn->prepare(
    "UPDATE products
    SET product_name=?, category=?, price=?, stock=?
    WHERE id=?"
    );

    $stmt->bind_param(
        "ssdii",
        $name,
        $category,
        $price,
        $stock,
        $id
    );
}

    if ($stmt->execute()) {

    $log = "Updated Product: " . $name;

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
}

$conn->close();
?>