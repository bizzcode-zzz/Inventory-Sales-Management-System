<?php

include "db.php";

$result = $conn->query(
    "SELECT activity, created_at
     FROM activity_logs
     ORDER BY id DESC
     LIMIT 10"
);

while ($row = $result->fetch_assoc()) {

    echo "<div style='padding:8px;border-bottom:1px solid #444;'>";

    echo "<strong>" .
         htmlspecialchars($row['activity']) .
         "</strong>";

    echo "<br>";

    echo "<small>" .
         $row['created_at'] .
         "</small>";

    echo "</div>";
}

$conn->close();
?>