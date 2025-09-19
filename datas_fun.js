document.addEventListener("DOMContentLoaded", function () {
  // Search functionality
  const searchBar = document.getElementById("searchBar");
  if (searchBar) {
    searchBar.addEventListener("input", function () {
      let filter = this.value.toLowerCase();
      let rows = document.querySelectorAll("#driverTable tbody tr");

      rows.forEach((row) => {
        let text = row.textContent.toLowerCase();
        row.style.display = text.includes(filter) ? "" : "none";
      });
    });
  }

  // Select all checkboxes functionality
  const checkAll = document.getElementById("checkAll");
  if (checkAll) {
    checkAll.addEventListener("change", function () {
      let checkboxes = document.querySelectorAll(".deleteCheck");
      checkboxes.forEach((checkbox) => {
        checkbox.checked = this.checked;
      });
    });
  }

  // Delete single record
  const deleteBtns = document.querySelectorAll(".deleteBtn");
  deleteBtns.forEach((button) => {
    button.addEventListener("click", function () {
      let personId = this.getAttribute("data-id");
      if (confirm(`คุณต้องการลบข้อมูลของผู้ขับขี่ ID: ${personId} หรือไม่?`)) {
        window.location.href = `delete_data.php?id=${personId}`;
      }
    });
  });

  // Delete multiple records
  const deleteSelected = document.getElementById("deleteSelected");
  if (deleteSelected) {
    deleteSelected.addEventListener("click", function () {
      let selectedIds = [];
      document.querySelectorAll(".deleteCheck:checked").forEach((checkbox) => {
        selectedIds.push(checkbox.getAttribute("data-id"));
      });

      if (selectedIds.length > 0) {
        if (
          confirm(`คุณต้องการลบข้อมูล ${selectedIds.length} รายการหรือไม่?`)
        ) {
          window.location.href = `delete_data.php?ids=${selectedIds.join(",")}`;
        }
      } else {
        alert("กรุณาเลือกข้อมูลที่ต้องการลบ");
      }
    });
  }

  // Sort by column functionality (ascending/descending)
  const headers = document.querySelectorAll("th");
  headers.forEach((header, index) => {
    header.addEventListener("click", () => {
      let rows = Array.from(document.querySelectorAll("#driverTable tbody tr"));
      let isAscending = header.classList.contains("asc");

      rows.sort((rowA, rowB) => {
        let cellA = rowA.children[index].textContent.trim();
        let cellB = rowB.children[index].textContent.trim();

        if (isNaN(cellA)) {
          return isAscending
            ? cellA.localeCompare(cellB)
            : cellB.localeCompare(cellA);
        } else {
          return isAscending ? cellA - cellB : cellB - cellA;
        }
      });

      rows.forEach((row) =>
        document.querySelector("#driverTable tbody").appendChild(row)
      );

      header.classList.toggle("asc", !isAscending);
      header.classList.toggle("desc", isAscending);
    });
  });

  // Toggle License ID visibility
  document.querySelectorAll(".toggle-license").forEach((button) => {
    button.addEventListener("click", function () {
      let personId = this.getAttribute("data-id");

      // หา span ที่ซ่อน License ID โดยใช้ personId
      let licenseId = document.getElementById(`license${personId}`);

      // ถ้า License ID ซ่อนอยู่ให้แสดง และเปลี่ยนข้อความปุ่มเป็น 'ซ่อน'
      if (licenseId.style.display === "none") {
        licenseId.style.display = "inline"; // แสดงข้อมูล License ID
        this.innerText = "ซ่อน"; // เปลี่ยนข้อความปุ่มเป็น 'ซ่อน'
      } else {
        licenseId.style.display = "none"; // ซ่อนข้อมูล License ID
        this.innerText = "แสดง"; // เปลี่ยนข้อความปุ่มเป็น 'แสดง'
      }
    });
  });
});
