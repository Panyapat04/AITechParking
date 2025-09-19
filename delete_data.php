<?php
include('db_connect.php');

if (isset($_GET['id'])) {
    // ลบข้อมูลทีละรายการ
    $personId = $_GET['id'];
    $query = "DELETE FROM driver_info WHERE person_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $personId);
    $stmt->execute();
    $stmt->close();
    header("Location: data_resource.php");
} elseif (isset($_GET['ids'])) {
    // ลบข้อมูลหลายรายการ
    $ids = explode(',', $_GET['ids']);
    $query = "DELETE FROM driver_info WHERE person_id IN (" . implode(',', array_fill(0, count($ids), '?')) . ")";
    $stmt = $conn->prepare($query);
    $stmt->bind_param(str_repeat('i', count($ids)), ...$ids);
    $stmt->execute();
    $stmt->close();
    header("Location: data_resource.php");
}
?>
