<?php
include('../db_connect.php');

// สร้างการเชื่อมต่อ
$conn = new mysqli($servername, $username, $password, $dbname);

// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT direction FROM control ORDER BY id DESC LIMIT 1";
$result = $conn->query($sql);

if ($row = $result->fetch_assoc()) {
    echo $row["direction"];
} else {
    echo "0"; // default
}
$conn->close();
?>
