<?php

session_start();

if (
    !isset($_SESSION["role"])
    ||
    $_SESSION["role"] !== "admin"
) {

    die("Access Denied");

}

include "db.php";

$id =
(int) $_POST["id"];

$password =
trim($_POST["password"]);

$hashedPassword =
password_hash(
    $password,
    PASSWORD_DEFAULT
);

$stmt =
$conn->prepare(
"
UPDATE users

SET password = ?

WHERE id = ?
"
);

$stmt->bind_param(
    "si",
    $hashedPassword,
    $id
);

$stmt->execute();

echo "Password Reset Successful";