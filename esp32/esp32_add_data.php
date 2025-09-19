<?php
include('../db_connect.php');

$sql = "UPDATE control SET direction = 0 WHERE id = 1";

if ($conn->query($sql) === TRUE) {
    echo "OK";
} else {
    echo "ERROR: " . $conn->error;
}

$conn->close();
?>