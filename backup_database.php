<?php

session_start();

if (
    !isset($_SESSION["role"])
    || $_SESSION["role"] !== "admin"
) {
    die("Access Denied");
}

$dbHost = "localhost";
$dbUser = "root";
$dbPass = "";
$dbName = "mydb";

$backupFile =
    "backup_" .
    date("Y-m-d_H-i-s") .
    ".sql";

header("Content-Type: application/octet-stream");
header("Content-Disposition: attachment; filename=\"$backupFile\"");

$command =
    "C:\\xampp\\mysql\\bin\\mysqldump.exe " .
    "--host=$dbHost " .
    "--user=$dbUser " .
    "$dbName";

passthru($command);
exit;