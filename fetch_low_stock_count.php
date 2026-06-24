<?php

include "db.php";

$result =
mysqli_query(
    $conn,
    "
    SELECT COUNT(*)
    AS total

    FROM products

    WHERE stock <= 5
    AND stock > 0
    "
);

$row =
mysqli_fetch_assoc(
    $result
);

echo $row["total"];

$conn->close();