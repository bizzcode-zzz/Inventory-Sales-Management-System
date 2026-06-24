<?php

include "db.php";

$search =
trim(
    $_GET["search"]
    ?? ""
);

$startDate =
$_GET["startDate"]
?? "";

$endDate =
$_GET["endDate"]
?? "";

$sql =
"
SELECT activity, created_at
FROM activity_logs
WHERE 1=1
";

if (!empty($search)) {

    $sql .=
    "
    AND activity
    LIKE '%$search%'
    ";

}

if (
    !empty($startDate)
    &&
    !empty($endDate)
) {

    $sql .=
    "
    AND DATE(created_at)
    BETWEEN
    '$startDate'
    AND
    '$endDate'
    ";

}

$sql .=
"
ORDER BY id DESC
LIMIT 10
";

$result =
$conn->query($sql);

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