<?php
include('db_connect.php');
require 'vendor/autoload.php'; // ถ้าใช้ PhpSpreadsheet

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$search_date = isset($_GET['date']) ? $_GET['date'] : date("Y-m-d");
$sql = "SELECT * FROM driver_info WHERE DATE(date_time) = '$search_date' ORDER BY date_time ASC";
$result = mysqli_query($conn, $sql);

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

$sheet->setCellValue('A1', '#');
$sheet->setCellValue('B1', 'วันที่-เวลา');
$sheet->setCellValue('C1', 'License ID');
$sheet->setCellValue('D1', 'ภาพที่ 1');
$sheet->setCellValue('E1', 'ภาพที่ 2');
$sheet->setCellValue('F1', 'ภาพที่ 3');

$rowNum = 2; $i=1;
while($row = mysqli_fetch_assoc($result)){
    $sheet->setCellValue("A$rowNum", $i++);
    $sheet->setCellValue("B$rowNum", $row['date_time']);
    $sheet->setCellValue("C$rowNum", $row['driver_number']);
    $sheet->setCellValue("D$rowNum", $row['image_path']);
    $sheet->setCellValue("E$rowNum", $row['image_path2']);
    $sheet->setCellValue("F$rowNum", $row['image_path3']);
    $rowNum++;
}

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header("Content-Disposition: attachment; filename=report_$search_date.xlsx");
$writer = new Xlsx($spreadsheet);
$writer->save("php://output");
exit;
