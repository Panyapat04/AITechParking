<?php
include('db_connect.php');
require 'fpdf/fpdf.php'; // ใช้ FPDF library

$search_date = isset($_GET['date']) ? $_GET['date'] : date("Y-m-d");
$sql = "SELECT * FROM driver_info WHERE DATE(date_time) = '$search_date' ORDER BY date_time ASC";
$result = mysqli_query($conn, $sql);

$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial','B',14);
$pdf->Cell(0,10,iconv('UTF-8','cp874',"รายงานข้อมูลวันที่ $search_date"),0,1,'C');
$pdf->Ln(5);

$pdf->SetFont('Arial','B',10);
$pdf->Cell(10,10,'#',1);
$pdf->Cell(40,10,iconv('UTF-8','cp874','วันที่-เวลา'),1);
$pdf->Cell(40,10,iconv('UTF-8','cp874','License ID'),1);
$pdf->Cell(50,10,iconv('UTF-8','cp874','Path รูป 1'),1);
$pdf->Cell(50,10,iconv('UTF-8','cp874','Path รูป 2'),1);
$pdf->Ln();

$pdf->SetFont('Arial','',9);
$i=1;
while($row = mysqli_fetch_assoc($result)){
    $pdf->Cell(10,10,$i++,1);
    $pdf->Cell(40,10,$row['date_time'],1);
    $pdf->Cell(40,10,iconv('UTF-8','cp874',$row['driver_number']),1);
    $pdf->Cell(50,10,$row['image_path'],1);
    $pdf->Cell(50,10,$row['image_path2'],1);
    $pdf->Ln();
}

$pdf->Output("D","report_$search_date.pdf");
exit;
