<?php
include('login_check.php');
include('db_connect.php');

// ตั้งค่า timezone
date_default_timezone_set("Asia/Bangkok");

// รับค่าวันที่จากฟอร์ม ถ้าไม่กรอกให้ใช้วันที่ปัจจุบัน
$search_date = isset($_GET['date']) ? $_GET['date'] : date("Y-m-d");

// Query ข้อมูลตามวันที่
$sql = "SELECT * FROM driver_info WHERE DATE(date_time) = '$search_date' ORDER BY date_time ASC";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta name="description" content="รายงานการบันทึกข้อมูล"> 
  <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
  <link rel="stylesheet" href="datas.css" />
  <link rel="stylesheet" href="style.css" />
  <title>รายงานการบันทึกข้อมูล</title>
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
      <h2 class="mb-4">📑 รายงานการบันทึกข้อมูล (แยกรายวัน)</h2>

      <!-- ฟอร์มค้นหาวันที่ + ปุ่ม export -->
      <form method="get" class="mb-4 d-flex flex-wrap align-items-center gap-2">
        <input type="date" name="date" value="<?= $search_date ?>" class="form-control w-auto">
        <button type="submit" class="btn btn-primary">ค้นหา</button>
        <a href="export_excel.php?date=<?= $search_date ?>" class="btn btn-success">
          <i class="bx bx-file"></i> Export Excel
        </a>
        <a href="export_pdf.php?date=<?= $search_date ?>" class="btn btn-danger">
          <i class="bx bx-file-pdf"></i> Export PDF
        </a>
      </form>

      <!-- ตารางรายงาน -->
      <table class="table table-bordered table-hover text-center align-middle">
        <thead class="table-dark">
          <tr>
            <th>#</th>
            <th>วันที่</th>
            <th style="width: 180px;">License ID</th>
            <th>กล้อง 1</th>
            <th>กล้อง 2</th>
            <th>กล้อง 3</th>
          </tr>
        </thead>
        <tbody>
          <?php if(mysqli_num_rows($result) > 0): ?>
            <?php $i=1; while($row = mysqli_fetch_assoc($result)) { ?>
              <tr>
                <td><?= $i++ ?></td>
                <td class="date-column"><?= $row['date_time'] ?></td>
                <td><?= !empty($row['driver_number']) ? htmlspecialchars($row['driver_number']) : "<span class='text-muted'>ไม่มีข้อมูล</span>" ?></td>
                <td><?= !empty($row['image_path']) ? "<img src='{$row['image_path']}' class='img-thumbnail preview-img' style='max-width:100px; max-height:80px;' alt='cam1'>" : "<span class='text-muted'>ไม่มีภาพ</span>" ?></td>
                <td><?= !empty($row['image_path2']) ? "<img src='{$row['image_path2']}' class='img-thumbnail preview-img' style='max-width:100px; max-height:80px;' alt='cam2'>" : "<span class='text-muted'>ไม่มีภาพ</span>" ?></td>
                <td><?= !empty($row['image_path3']) ? "<img src='{$row['image_path3']}' class='img-thumbnail preview-img' style='max-width:100px; max-height:80px;' alt='cam3'>" : "<span class='text-muted'>ไม่มีภาพ</span>" ?></td>
              </tr>
            <?php } ?>
          <?php else: ?>
            <tr><td colspan="6" class="text-center">ไม่พบข้อมูลสำหรับวันที่นี้</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>

  <script src="script.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>

  <script>
  document.addEventListener("DOMContentLoaded", () => {
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

    document.querySelectorAll('.date-column').forEach(cell => {
      if(cell.textContent.trim()){
        cell.textContent = thai_date(cell.textContent.trim());
      }
    });

    const modal = new bootstrap.Modal(document.getElementById('imagePreviewModal'));
    const previewImg = document.getElementById('previewImage');
    document.querySelectorAll('.preview-img').forEach(img => {
      img.addEventListener('click', () => {
        previewImg.src = img.src;
        modal.show();
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
