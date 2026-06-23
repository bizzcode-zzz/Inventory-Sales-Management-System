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


$stmt = $conn->prepare(
"
SELECT username
FROM users
WHERE id = ?
"
);

$stmt->bind_param(
    "i",
    $id
);

$stmt->execute();

$result =
$stmt->get_result();

$user =
$result->fetch_assoc();

if (!$user) {

    die("User not found.");

}

$usernameToReset =
$user["username"];



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

$log = "Reset Password: " . $usernameToReset;

$logStmt = $conn->prepare(
    "INSERT INTO activity_logs (activity)
     VALUES (?)"
);

$logStmt->bind_param(
    "s",
    $log
);

$logStmt->execute();
$logStmt->close();

echo "Password Reset Successful";