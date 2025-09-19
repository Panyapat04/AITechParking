<?php
// กำหนดข้อมูลการเชื่อมต่อ
include('../db_connect.php');

// ฟังก์ชัน toggle
function toggleDirection() {
    global $servername, $username, $password, $dbname;

    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // อ่านค่าปัจจุบัน
    $sql = "SELECT id, direction FROM control ORDER BY id DESC LIMIT 1";
    $result = $conn->query($sql);

    if ($row = $result->fetch_assoc()) {
        $id = $row['id'];
        $current = $row['direction'];
        $newValue = ($current == 1) ? 0 : 1;

        // อัปเดตค่าเดิม
        $update = "UPDATE control SET direction = $newValue WHERE id = $id";
        $conn->query($update);
        $conn->close();
        return $newValue;
    } else {
        // ถ้ายังไม่มีข้อมูลเลย
        $insert = "INSERT INTO control (direction) VALUES (0)";
        $conn->query($insert);
        $conn->close();
        return 0;
    }
}

// ถ้ามีการกดปุ่ม
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $toggled = toggleDirection();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Toggle Direction</title>
</head>
<body style="font-family:sans-serif; text-align:center; margin-top:100px;">
    <h1>สลับทิศทาง Servo (0/1)</h1>
    <form method="post">
        <button type="submit" style="font-size:24px; padding:10px 20px;">Toggle</button>
    </form>
    <?php if (isset($toggled)): ?>
        <p style="font-size:20px;">Direction ถูกเปลี่ยนเป็น: <strong><?php echo $toggled; ?></strong></p>
    <?php endif; ?>
</body>
</html>
