<?php
session_start();

if (!isset($_SESSION['UserID'])) {
    // ถ้าไม่มี session ของผู้ใช้ แสดงหน้าล็อกอิน
    header("Location: login.php");
    exit();
}
?>
