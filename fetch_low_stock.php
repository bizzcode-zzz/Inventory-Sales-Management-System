<?php

include "db.php";

$result =
mysqli_query(
    $conn,
    "
    SELECT
    product_name,
    stock

    FROM products

    WHERE stock <= 5
    AND stock > 0

    ORDER BY stock ASC
    "
);

while (
    $row =
    mysqli_fetch_assoc($result)
) {

    echo "
    <div class='low-stock-item'>

        <strong>
        {$row['product_name']}
        </strong>

        <span>
        Stock:
        {$row['stock']}
        </span>

    </div>
    ";

}