<!-- navbar.php -->
<nav class="navbar">
  <div class="logo_item">
    <i class="bx bx-menu" id="sidebarOpen"></i>
    <img src="logo/logo.png" alt="">
    <span class="navbar-title">ระบบบันทึกการเข้า-ออกลานจอดรถวิทยาลัยอาชีวศึกษาสุราษฎร์ธานี</span>
  </div>
  <div class="navbar_content">
    <i class="bi bi-grid"></i>
  </div>

  <style>
    /* CSS สำหรับ Navbar */
    .navbar {
      background-color: #001f3f; /* สีน้ำเงินเข้ม */
      color: white;             
      padding: 10px 20px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    /* โลโก้กับข้อความ */
    .logo_item {
      display: flex;
      align-items: center;
      font-weight: bold;
      font-size: 18px;
    }


    /* สีข้อความหัวเรื่อง */
    .navbar-title {
      color: #ffffffff; 
      font-size: 22px;/* สีเหลืองทอง (ชัดเจนบนพื้นน้ำเงินเข้ม) */
    }

    /* ไอคอนใน navbar */
    .navbar_content i {
      color: white;
      font-size: 22px;
      cursor: pointer;
    }
  </style>
</nav>
