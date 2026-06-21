<?php

require 'vendor/autoload.php';
include 'db.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheet = new Spreadsheet();

$sheet = $spreadsheet->getActiveSheet();

$sheet->setCellValue('A1', 'ID');
$sheet->setCellValue('B1', 'Product Name');
$sheet->setCellValue('C1', 'Category');
$sheet->setCellValue('D1', 'Price');
$sheet->setCellValue('E1', 'Stock');

$result = $conn->query("
    SELECT id, product_name, category, price, stock
    FROM products
    ORDER BY id DESC
");

$rowNumber = 2;

while ($row = $result->fetch_assoc()) {

    $sheet->setCellValue('A' . $rowNumber, $row['id']);
    $sheet->setCellValue('B' . $rowNumber, $row['product_name']);
    $sheet->setCellValue('C' . $rowNumber, $row['category']);
    $sheet->setCellValue('D' . $rowNumber, $row['price']);
    $sheet->setCellValue('E' . $rowNumber, $row['stock']);

    $rowNumber++;
}

header(
    'Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
);

header(
    'Content-Disposition: attachment;filename="products.xlsx"'
);

header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);

$writer->save('php://output');

exit;