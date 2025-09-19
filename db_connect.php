<?php
$servername = "localhost";
$username   = "root";
$password   = ""; // ว่าง ถ้าไม่ได้ตั้งรหัสผ่าน
$dbname     = "date_db";

// สร้างการเชื่อมต่อ
$conn = new mysqli($servername, $username, $password, $dbname);

// ตรวจสอบการเชื่อมต่อ
// if ($conn->connect_error) {
//     die("เชื่อมต่อฐานข้อมูลล้มเหลว: " . $conn->connect_error);
// } else {
//     echo "เชื่อมต่อสำเร็จ";
// }
?>