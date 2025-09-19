<?php 
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $driverNumber = isset($_POST['driver_number']) ? $_POST['driver_number'] : '';
    
    // ใช้วันที่และเวลาอัตโนมัติ
    $dateTime = date("Y-m-d H:i:s");  // ตัวอย่างรูปแบบ: 2025-07-17 14:30:00
    
    $image1 = isset($_POST['image1']) ? $_POST['image1'] : '';
    $image2 = isset($_POST['image2']) ? $_POST['image2'] : '';
    $image3 = isset($_POST['image3']) ? $_POST['image3'] : '';
    
    // สร้างโฟลเดอร์เก็บภาพ
    $imageDir = 'images';
    if (!file_exists($imageDir)) {
        mkdir($imageDir, 0777, true);
    }

    // ฟังก์ชันแปลง base64 เป็นไฟล์ภาพ
    function saveBase64Image($base64Data, $dir) {
        if (empty($base64Data)) {
            return false;  // ตรวจสอบว่า base64Data ไม่เป็นค่าว่าง
        }
        list(, $data) = explode(',', $base64Data);
        $imageData = base64_decode($data);

        // ตรวจสอบว่า base64_decode() คืนค่าเป็นข้อมูลที่ถูกต้องหรือไม่
        if ($imageData === false) {
            return false;  // ถ้าแปลงไม่ได้ให้คืนค่า false
        }

        // สร้างชื่อไฟล์ที่ไม่ซ้ำ
        $filename = $dir . '/' . uniqid() . '.png';
        if (file_put_contents($filename, $imageData) === false) {
            return false;  // ถ้าบันทึกไฟล์ไม่ได้ ให้คืนค่า false
        }
        return $filename;
    }

    // บันทึกภาพ
    $path1 = saveBase64Image($image1, $imageDir);
    $path2 = saveBase64Image($image2, $imageDir);
    $path3 = saveBase64Image($image3, $imageDir);

    if (!$path1 || !$path2 || !$path3) {
        echo "เกิดข้อผิดพลาดในการบันทึกภาพบางตัว";
        exit;  // หยุดการดำเนินการถ้าภาพไม่สามารถบันทึกได้
    }

    // บันทึกลงฐานข้อมูล driver_info
    $stmt = $conn->prepare("INSERT INTO driver_info (driver_number, date_time, image_path, image_path2, image_path3) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $driverNumber, $dateTime, $path1, $path2, $path3);

    if ($stmt->execute()) {
        echo "บันทึกข้อมูลสำเร็จ";
    } else {
        echo "เกิดข้อผิดพลาดในการบันทึกข้อมูล driver_info: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    echo "[Invalid Request]";
}
?>
