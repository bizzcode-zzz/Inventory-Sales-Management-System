<?php

include "db.php";

mysqli_query(
$conn,
"TRUNCATE TABLE sales"
);

mysqli_query(
$conn,
"TRUNCATE TABLE products"
);

mysqli_query(
$conn,
"TRUNCATE TABLE activity_logs"
);

$files = glob("uploads/*");

foreach ($files as $file) {

    if (is_file($file)) {

        unlink($file);

    }

}

echo "success";