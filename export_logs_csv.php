<?php

include "db.php";

header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="activity_logs.csv"');

$output = fopen("php://output", "w");

fputcsv($output, [
    "Activity",
    "Date"
]);

$result = $conn->query("
    SELECT activity, created_at
    FROM activity_logs
    ORDER BY id DESC
");

while ($row = $result->fetch_assoc()) {

    fputcsv($output, [

        $row['activity'],

        $row['created_at']

    ]);
}

fclose($output);

$conn->close();

?>