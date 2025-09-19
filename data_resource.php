<?php
include('login_check.php');
include('db_connect.php');

// Query to fetch data from driver_info table
$query = "SELECT * FROM driver_info ORDER BY date_time DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta name="description" content="ระบบถ่ายภาพบันทึกฐานข้อมูลสำหรับการจัดการกล้อง CCTV หรืออื่นๆ"> 
  <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
  <link rel="stylesheet" href="datas.css" />
  <link rel="stylesheet" href="style.css" />
  <title>ข้อมูลการเข้าใช้งาน</title>
  <link rel="icon" type="image/png" href="logo/logo.png">
</head>

<body>
  <!-- Navbar -->
  <?php include('navbar.php'); ?>

  <!-- Sidebar -->
  <?php include('sidebar.php'); ?>

  <!-- Content -->
  <div class="content">
    <div class="container mt-5">
      <h2 class="mb-4">ข้อมูลผู้เข้าใช้งานลานจอดรถวิทยาลัยอาชีวศึกษาสุราษฎร์ธานี</h2>

      <table class="table table-bordered table-hover text-center align-middle">
        <thead class="table-dark">
          <tr>
            <th><input type="checkbox" id="checkAll"></th>
            <th>วันที่</th>
            <th style="width: 180px;">License ID</th>
            <th>กล้อง 1</th>
            <th>กล้อง 2</th>
            <th>กล้อง 3</th>
            <th>การจัดการ</th>
          </tr>
        </thead>

        <tbody>
        <?php while($row = mysqli_fetch_assoc($result)) { ?>
          <tr>
            <td><input type="checkbox" class="deleteCheck" data-id="<?= $row['person_id'] ?>"></td>
            <td class="date-column"><?= $row['date_time'] ?></td>

            <td>
              <?php if(!empty($row['driver_number'])): ?>
                <?= htmlspecialchars($row['driver_number']) ?>
              <?php else: ?>
                <span class="text-muted">ไม่มีข้อมูล</span>
              <?php endif; ?>
            </td>

            <!-- แสดงรูปจากทั้ง 3 มุม -->
            <td>
              <?php if(!empty($row['image_path'])): ?>
               <img src="<?= $row['image_path'] ?>" class="img-thumbnail preview-img" style="max-width:100px; max-height:80px;" alt="cam1">
              <?php else: ?>
               <span class="text-muted">ไม่มีภาพ</span>
             <?php endif; ?>
            </td>
            <td>
              <?php if(!empty($row['image_path2'])): ?>
                <img src="<?= $row['image_path2'] ?>" class="img-thumbnail preview-img" style="max-width:100px; max-height:80px;" alt="cam2">
              <?php else: ?>
                <span class="text-muted">ไม่มีภาพ</span>
              <?php endif; ?>
            </td>
            <td>
              <?php if(!empty($row['image_path3'])): ?>
                <img src="<?= $row['image_path3'] ?>" class="img-thumbnail preview-img" style="max-width:100px; max-height:80px;" alt="cam3">
              <?php else: ?>
                <span class="text-muted">ไม่มีภาพ</span>
              <?php endif; ?>
            </td>

            <td>
              <button class="btn btn-danger deleteBtn" data-id="<?= $row['person_id'] ?>">ลบ</button>
            </td>
          </tr>
        <?php } ?>
        </tbody>
      </table>
    </div>
  </div>

  <script src="datas_fun.js"></script>
  <script src="script.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>

 <script>
document.addEventListener("DOMContentLoaded", () => {

  // ✅ ฟังก์ชันแปลงวันที่เป็นภาษาไทย
  function thai_date(dateStr) {
    var date = new Date(dateStr);
    var thaiMonths = ["", "มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน",
                      "กรกฎาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม"];
    var day = date.getDate();
    var month = thaiMonths[date.getMonth() + 1];
    var year = date.getFullYear() + 543;
    var h = date.getHours().toString().padStart(2, "0");
    var m = date.getMinutes().toString().padStart(2, "0");
    return `${day} ${month} ${year} เวลา ${h}:${m} น.`;
  }

  // ✅ แปลงวันที่ในตาราง
  document.querySelectorAll('.date-column').forEach(cell => {
    if(cell.textContent.trim()){
      cell.textContent = thai_date(cell.textContent.trim());
    }
  });

  // ✅ เปิดพรีวิวรูปภาพ
  const modal = new bootstrap.Modal(document.getElementById('imagePreviewModal'));
  const previewImg = document.getElementById('previewImage');

  document.querySelectorAll('.preview-img').forEach(img => {
    img.addEventListener('click', () => {
      previewImg.src = img.src;
      modal.show();
    });
  });
  // ✅ ฟังก์ชันลบเฉพาะคอลัมน์ที่เลือก
document.querySelectorAll(".deleteBtn").forEach(btn => {
  btn.addEventListener("click", function() {
    if (confirm("คุณแน่ใจหรือไม่ที่จะลบคอลัมน์นี้?")) {
      const cell = this.closest("td");  // เลือก td ที่ปุ่มอยู่
      const personId = this.getAttribute("data-id");

      // ลบแค่คอลัมน์ออกจากตาราง
      if (cell) {
        cell.remove();
      }

      // ถ้าคุณต้องการลบในฐานข้อมูลด้วย
      fetch("delete.php?id=" + personId, { method: "GET" })
        .then(res => res.text())
        .then(data => console.log("ลบแล้ว:", data))
        .catch(err => console.error("ลบไม่สำเร็จ:", err));
    }
  });
});

});
</script>



  <!-- Image Preview Modal -->
  <div class="modal fade" id="imagePreviewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content bg-transparent border-0">
        <div class="modal-body text-center">
          <img id="previewImage" src="" class="img-fluid rounded shadow" alt="Preview Image">
        </div>
      </div>
    </div>
  </div>

</body>
</html>
