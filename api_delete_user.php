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



$stmt =
$conn->prepare(
"
SELECT role
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



if (
    $user["role"]
    ===
    "admin"
) {

    $result =
    mysqli_query(
        $conn,
        "
        SELECT COUNT(*)
        AS total_admins

        FROM users

        WHERE role = 'admin'
        "
    );

    $row =
    mysqli_fetch_assoc(
        $result
    );

    if (
        $row["total_admins"]
        <= 1
    ) {

        die(
            "Cannot delete last admin."
        );

    }

}

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