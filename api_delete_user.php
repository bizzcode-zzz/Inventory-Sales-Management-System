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

if (
    $id ==
    $_SESSION["user_id"]
) {

    die(
        "You cannot delete your own account."
    );

}

if (
    $id ==
    $_SESSION["user_id"]
) {

    die(
        "You cannot delete your own account."
    );

}

$stmt =
$conn->prepare(
"
DELETE FROM users
WHERE id = ?
"
);

$stmt->bind_param(
    "i",
    $id
);

$stmt->execute();

echo "User Deleted";