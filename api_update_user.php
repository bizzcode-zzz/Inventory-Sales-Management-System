<?php

session_start();

if (
    $_SESSION["role"]
    !==
    "admin"
) {

    die("Access Denied");

}

include "db.php";

$id =
(int) $_POST["id"];

$username =
trim($_POST["username"]);

$role =
trim($_POST["role"]);

$stmt =
$conn->prepare(
"
UPDATE users

SET
username = ?,
role = ?

WHERE id = ?
"
);

$stmt->bind_param(
    "ssi",
    $username,
    $role,
    $id
);

$stmt->execute();

echo "success";

$log = "Edited User: " . $username;

$logStmt = $conn->prepare(
    "INSERT INTO activity_logs (activity)
     VALUES (?)"
);

$logStmt->bind_param("s", $log);
$logStmt->execute();
$logStmt->close();