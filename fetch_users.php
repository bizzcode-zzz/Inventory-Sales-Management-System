<?php

session_start();

if (
    $_SESSION["role"] !== "admin"
) {

    die("Access Denied");

}

include "db.php";

$result =
mysqli_query(
    $conn,
    "
    SELECT
    id,
    username,
    role
    FROM users
    ORDER BY id DESC
    "
);

echo "<table border='1'>";

echo "
<tr>
<th>ID</th>
<th>Username</th>
<th>Role</th>
<th>Action</th>
</tr>
";

while (
    $row =
    mysqli_fetch_assoc($result)
) {

    echo "<tr>";

    echo "<td>{$row['id']}</td>";

    echo "<td>{$row['username']}</td>";

    echo "<td>{$row['role']}</td>";

    echo "<td>";

    if (
        $row["id"]
        ==
        $_SESSION["user_id"]
    ) {

        echo "Protected";

    } else {

        echo "
        <button
        onclick='deleteUser({$row["id"]})'>
        Delete
        </button>
        ";

    }

    echo "</td>";

    echo "</tr>";

}

echo "</table>";