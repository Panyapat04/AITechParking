<!-- sidebar.php -->
<nav class="sidebar" id="sidebar"> 
  <div class="menu_content">
    <ul class="menu_items"> 
      <div class="menu_title menu_dahsboard"></div> 
      <li class="item"> 
        <a href="index.php" class="nav_link"> 
          <span class="navlink_icon"><i class="bx bx-home-alt"></i></span>
          <span class="navlink">หน้าแรก</span> 
        </a> 
      </li> 
    </ul> 

    <ul class="menu_items">
      <div class="menu_title menu_editor"></div> 
      <li class="item"> 
        <a href="camera.php" class="nav_link"> 
          <span class="navlink_icon"><i class="bx bx-loader-circle"></i></span> 
          <span class="navlink">เริ่มใช้งาน</span>
        </a>
      </li>
      <li class="item"> 
        <a href="data_resource.php" class="nav_link">
          <span class="navlink_icon"><i class="bx bx-cloud-upload"></i></span>
          <span class="navlink">ข้อมูลการเข้าใช้งาน</span>
        </a> 
      </li>
      <li class="item"> 
        <a href="report.php" class="nav_link">
          <span class="navlink_icon"><i class="bx bx-file"></i></span>
          <span class="navlink">รายงานการบันทึกข้อมูล</span>
        </a> 
      </li> 
    </ul> 

    <ul class="menu_items settings-menu"> 
      <div class="menu_title menu_setting"></div>
      <li class="item"> 
        <ul class="menu_items"> 
          <li class="item"> 
            <a href="login.php" class="nav_link login"> 
              <span class="navlink_icon"><i class="bx bx-log-in"></i></span> 
              <span class="navlink">เข้าสู่ระบบ</span> 
            </a> 
          </li> 
          <li class="item"> 
            <a href="logout.php" class="nav_link logout"> 
              <span class="navlink_icon"><i class="bx bx-log-out"></i></span>
              <span class="navlink">ออกจากระบบ</span>
            </a>
          </li> 
        </ul>
      </li>
    </ul>


    <!-- ปุ่มสลับไว้ล่างสุด -->
    <div class="bottom_content">
      <div class="bottom collapse_sidebar" id="toggleBtn">
        <i class='bx bx-chevron-left' id="toggleIcon"></i>
        <span id="toggleText">ย่อหน้าต่าง</span>
      </div>
    </div>
  </div>
</nav>

<style>
  /* ---------- Sidebar ---------- */
  nav.sidebar {
    width: 265px;
    background: #3b82cdef;
    border-right: 1px solid #ddd;
    height: 100vh;
    padding: 15px 10px;
    transition: width 0.3s ease;
    overflow: hidden;
    display: flex;
    flex-direction: column;
  }

  .menu_items {
    list-style: none;
    padding: 0;
    margin: 15px 0;
  }

  .menu_items .item {
    margin-bottom: 8px;
  }

  .menu_items .item .nav_link {
    display: flex;
    align-items: center;
    padding: 12px 18px;
    border-radius: 8px;
    font-size: 17px;
    font-weight: 500;
    color: #fff;
    text-decoration: none;
    gap: 12px;
    transition: all 0.3s ease;
    white-space: nowrap;
  }

  .menu_items .item .nav_link:hover {
    background: #f0f0f0;
    color: #1976d2;
  }

  .menu_items .item .nav_link .navlink_icon {
    font-size: 20px;
    flex-shrink: 0;
  }

  /* ---------- Highlight ---------- */
  .nav_link.highlight {
    background: #e3f2fd;
    color: #1976d2;
    font-weight: bold;
  }

  /* ---------- Login / Logout color ---------- */
  .nav_link.login {
    color: green;
  }

  .nav_link.logout {
    color: red;
  }

  /* ---------- Collapse ---------- */
  .sidebar.collapsed {
    width: 60px;
  }

  /* ซ่อนข้อความเมื่อย่อ */
  .sidebar.collapsed .navlink {
    display: none;
  }

  .sidebar.collapsed .navlink_icon {
    font-size: 22px;
    margin: auto;
  }

  /* ---------- ปุ่ม toggle ---------- */
  .bottom_content {
    margin-top: auto; /* ดันลงล่าง */
    padding: 10px 0;
  }

  .bottom {
    display: flex;
    align-items: center;
    justify-content: flex-start;
    gap: 8px;
    padding: 10px 12px;
    border-radius: 6px;
    font-size: 14px;
    cursor: pointer;
    transition: background 0.3s;
  }

  .bottom:hover {
    background: #1d61ff59;
  }

  /* หมุน icon เมื่อ sidebar ย่อ */
  .sidebar.collapsed #toggleIcon {
    transform: rotate(180deg);
    transition: transform 0.3s ease;
  }

  /* โหมดย่อ */
.sidebar.collapsed {
  width: 70px; /* กว้างขึ้นหน่อย (จาก 60px → 70px) */
}

/* ซ่อนข้อความเมื่อย่อ */
.sidebar.collapsed .navlink {
  display: none;
}

/* ไอคอนในโหมดย่อ */
.sidebar.collapsed .navlink_icon {
  font-size: 28px;       /* ขยายให้ใหญ่ขึ้น */
  margin: 0 auto;        /* จัดให้อยู่ตรงกลาง */
  display: flex;
  align-items: center;
  justify-content: center;
  width: 100%;           /* ใช้พื้นที่เต็ม */
}

</style>

<script>
  const sidebar = document.getElementById("sidebar");
  const toggleBtn = document.getElementById("toggleBtn");
  const toggleText = document.getElementById("toggleText");

  toggleBtn.addEventListener("click", () => {
    sidebar.classList.toggle("collapsed");
    if (sidebar.classList.contains("collapsed")) {
      toggleText.textContent = "ขยายหน้าต่าง";
    } else {
      toggleText.textContent = "ย่อหน้าต่าง";
    }
  });
</script>
