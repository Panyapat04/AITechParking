<?php
// เชื่อมต่อกับฐานข้อมูล
include('db_connect.php');

// กำหนดรหัสผ่านใหม่ที่ต้องการแฮช
$new_password = '99999999'; // รหัสผ่านของแอดมินที่ต้องการแฮช

// ใช้ password_hash เพื่อสร้าง hashed password
$hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

// สร้างคำสั่ง SQL สำหรับอัพเดตข้อมูลรหัสผ่านของแอดมิน
$query = "UPDATE users SET Password = ? WHERE Username = 'dewdew'";

// เตรียมคำสั่ง SQL และบันทึก hashed password
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $hashed_password);
$stmt->execute();

// ตรวจสอบว่าอัพเดตสำเร็จหรือไม่
if ($stmt->affected_rows > 0) {
    echo "อัพเดตรหัสผ่านแอดมินเรียบร้อยแล้ว!";
} else {
    echo "เกิดข้อผิดพลาดในการอัพเดตข้อมูล";
}
?>
