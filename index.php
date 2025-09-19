<?php
include('db_connect.php');

// ตั้งค่าโซนเวลา
date_default_timezone_set("Asia/Bangkok");
$today     = date("Y-m-d");
$thisMonth = date("Y-m");

// 🚗 จำนวนรถวันนี้
$sql_today = "
    SELECT COUNT(DISTINCT person_id) AS total_cars_today
    FROM driver_info
    WHERE DATE(date_time) = '$today'
";
$result_today   = $conn->query($sql_today);
$row_today      = $result_today->fetch_assoc();
$totalCarsToday = $row_today ? $row_today['total_cars_today'] : 0;

// 🚙 จำนวนรถเดือนนี้
$sql_month = "
    SELECT COUNT(DISTINCT person_id) AS total_cars_month
    FROM driver_info
    WHERE DATE_FORMAT(date_time, '%Y-%m') = '$thisMonth'
";
$result_month   = $conn->query($sql_month);
$row_month      = $result_month->fetch_assoc();
$totalCarsMonth = $row_month ? $row_month['total_cars_month'] : 0;

// 👤 จำนวนผู้ใช้งานทั้งหมด
$sql_users = "
    SELECT COUNT(DISTINCT person_id) AS total_users
    FROM driver_info
";
$result_users = $conn->query($sql_users);
$row_users    = $result_users->fetch_assoc();
$totalUsers   = $row_users ? $row_users['total_users'] : 0;

// 📊 ดึงจำนวนรถรายวันของเดือนนี้ (สำหรับกราฟ)
$sql_chart = "
    SELECT DATE(date_time) AS day, COUNT(DISTINCT person_id) AS total
    FROM driver_info
    WHERE DATE_FORMAT(date_time, '%Y-%m') = '$thisMonth'
    GROUP BY DATE(date_time)
    ORDER BY day ASC
";
$result_chart = $conn->query($sql_chart);

$chartLabels = [];
$chartData   = [];
while ($row_chart = $result_chart->fetch_assoc()) {
    $chartLabels[] = $row_chart['day'];
    $chartData[]   = $row_chart['total'];
}

$conn->close();

// สมมติว่ามี session เก็บชื่อผู้ใช้งาน
session_start();
$username = isset($_SESSION['username']) ? $_SESSION['username'] : "Guest";
?>


<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>แดชบอร์ดการเข้า-ออกรถ</title>
  <link rel="icon" type="image/png" href="logo/logo.png">

  <!-- CSS -->
  <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="style.css" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet" />

  <style>
    body {
      background-color: #f5f7fb;
      font-family: "Poppins", sans-serif;
    }
    .counter {
      font-size: 68px;
      font-weight: 700;
      color: #0d6efd;
    }
    .datetime {
      font-size: 16px;
      margin-top: 8px;
      color: #666;
    }
    .card {
      border-radius: 22px;
      text-align: center;
      transition: 0.3s;
      border: none;
      min-height: 260px;
      padding: 30px 20px;
    }
    .icon-box {
      font-size: 48px;
      margin-bottom: 15px;
      color: #0d6efd;
    }
    .card h5 {
      font-size: 20px;
      font-weight: 600;
      color: #003366;
      margin-bottom: 20px;
    }
    h2 {
      margin-bottom: 30px;
      font-weight: 700;
      color: #003366;
    }
    /* กล่องชื่อผู้ใช้งาน */
    .user-box {
      position: absolute;
      top: 15px;
      right: 30px;
      background: #ffffff;
      padding: 10px 15px;
      border-radius: 12px;
      box-shadow: 0px 3px 8px rgba(0,0,0,0.1);
      font-weight: 600;
      color: #003366;
    }
  </style>

  <script>
    setInterval(() => { window.location.reload(); }, 5000);

    function updateDateTime() {
      const now = new Date();
      document.getElementById("datetime").innerText = now.toLocaleString("th-TH");
    }
    setInterval(updateDateTime, 1000);
  </script>
</head>
<body>
  <!-- Navbar -->
  <?php include('navbar.php'); ?>

  <!-- Sidebar -->
  <?php include('sidebar.php'); ?>

  <!-- กล่องชื่อผู้ใช้งาน -->
  <div class="user-box">
    👤 <?php echo $username; ?>
  </div>

  <!-- Content -->
  <div class="content" style="margin-left:250px; padding:30px;">
    <div class="container-fluid">
      <h2 class="text-center">แดชบอร์ดสรุปผลการเข้า-ออกรถ</h2>
      
      <!-- การ์ดสรุป -->
      <div class="row g-4 mb-4">
        <div class="col-md-4">
          <div class="card shadow p-4">
            <div class="icon-box"><i class="bx bxs-car"></i></div>
            <h5>รถที่เข้าใช้งานวันนี้</h5>
            <div class="counter"><?php echo $totalCarsToday; ?></div>
            <div id="datetime" class="datetime"></div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card shadow p-4">
            <div class="icon-box"><i class="bx bxs-calendar"></i></div>
            <h5>รถที่เข้าใช้งานเดือนนี้</h5>
            <div class="counter text-primary"><?php echo $totalCarsMonth; ?></div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card shadow p-4">
            <div class="icon-box"><i class="bx bxs-user"></i></div>
            <h5>ผู้ใช้งานทั้งหมด</h5>
            <div class="counter text-info"><?php echo $totalUsers; ?></div>
          </div>
        </div>
      </div>

      <!-- กราฟ -->
      <div class="card shadow p-4 mt-4">
        <h5 class="text-center mb-4">แนวโน้มการเข้าใช้งานรายวัน (เดือนนี้)</h5>
        <canvas id="carsChart" height="100"></canvas>
      </div>
    </div>
  </div>

  <!-- JavaScript -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script>
    updateDateTime();
    const labels = <?php echo json_encode($chartLabels, JSON_UNESCAPED_UNICODE); ?>;
    const data   = <?php echo json_encode($chartData, JSON_UNESCAPED_UNICODE); ?>;

    const ctx = document.getElementById('carsChart').getContext('2d');
    new Chart(ctx, {
      type: 'bar',
      data: {
        labels: labels,
        datasets: [{
          label: 'จำนวนรถเข้าใช้งาน',
          data: data,
          backgroundColor: 'rgba(13,110,253,0.7)',
          borderColor: '#003366',
          borderWidth: 1,
          borderRadius: 6
        }]
      },
      options: {
        responsive: true,
        plugins: { 
          legend: { display: true, position: 'top' } 
        },
        scales: {
          x: { title: { display: true, text: 'วันที่' } },
          y: { 
            title: { display: true, text: 'จำนวนรถ (คัน)' }, 
            beginAtZero: true 
          }
        }
      }
    });
  </script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
