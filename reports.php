<?php
session_start();

if (!isset($_SESSION["user"])) {

    header("Location: login.php");
    exit();

}
?>

<!DOCTYPE html>

<html>

<head>

<title>Sales Reports</title>

<link
rel="stylesheet"
href="css/style.css">

</head>

<body>

<h2>📊 Sales Reports</h2>

<input
type="date"
id="startDate">

<input
type="date"
id="endDate">

<button
onclick="loadReport()">

Generate Report

</button>
<button
onclick="exportReport()">

Export Report CSV

</button>

<hr>

<div id="reportResult">

Select a date range.

</div>

<script src="js/reports.js"></script>

</body>

</html>