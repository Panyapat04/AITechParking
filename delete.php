<?php
include('db_connect.php');  // เชื่อมต่อฐานข้อมูล

if (isset($_POST['id'])) {
    $id = intval($_POST['id']); // แปลงเป็น int กัน SQL Injection เบื้องต้น

    // ลบข้อมูล
    $query = "DELETE FROM driver_info WHERE person_id = $id";
    if (mysqli_query($conn, $query)) {
        echo "success";
    } else {
        echo "error";
    }
} else {
    echo "no_id";
}
?>
