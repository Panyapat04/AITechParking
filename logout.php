<?php
session_start(); // เริ่มต้น session

// ลบข้อมูล session ทั้งหมด
session_unset();

// ทำลาย session
session_destroy();

// หลังจากออกจากระบบแล้ว, เปลี่ยนเส้นทางไปที่หน้า login.php
header("Location: login.php");
exit();
?>
