<?php

session_start();


if (
    $_SESSION["role"] !== "admin"
) {

    die("Access Denied");

}

include "db.php";

$username =
trim($_POST["username"]);

$password =
trim($_POST["password"]);

$role =
trim($_POST["role"]);

$hashedPassword =
password_hash(
    $password,
    PASSWORD_DEFAULT
);

$stmt =
$conn->prepare(
"
INSERT INTO users
(
username,
password,
role
)
VALUES
(
?,
?,
?
)
"
);

$stmt->bind_param(
    "sss",
    $username,
    $hashedPassword,
    $role
);

$stmt->execute();

echo "success";