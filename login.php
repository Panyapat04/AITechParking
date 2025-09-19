<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include('db_connect.php'); 
    
    $username = $_POST['username'];
    $password = $_POST['password'];

    $query = "SELECT * FROM users WHERE Username = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

   if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['Password']) || $password === $user['Password']) {
            $_SESSION['UserID'] = $user['UserID'];
            $_SESSION['Username'] = $user['Username'];
            $_SESSION['Status'] = $user['Status'];
            header("Location: index.php");
            exit;
        } else {
            $error_message = "รหัสผ่านไม่ถูกต้อง";
        }
    } else {
        $error_message = "ไม่พบชื่อผู้ใช้นี้";
    }
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>เข้าสู่ระบบ</title>
<link rel="icon" type="image/png" href="logo/logo.png">
<link href="https://fonts.googleapis.com/css2?family=Mitr:wght@300;400;500;600&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
    body {
        font-family: 'Mitr', sans-serif;
        background: linear-gradient(135deg, #e3f2fd, #bbdefb);
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .login-card {
        width: 100%;
        max-width: 400px;
        padding: 2rem;
        border-radius: 8px;
        background-color: #ffffff;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    .btn-primary {
        background-color: #1976d2;
        border-color: #1976d2;
    }
    .btn-primary:hover {
        background-color: #1565c0;
        border-color: #1565c0;
    }
</style>
</head>
<body>

<div class="login-card">
    <h4 class="text-center mb-4">เข้าสู่ระบบ</h4>
    <?php if (!empty($error_message)) { ?>
        <div class="alert alert-danger text-center"><?= $error_message; ?></div>
    <?php } ?>
    <form method="POST">
        <div class="mb-3">
            <label for="username" class="form-label">ชื่อผู้ใช้</label>
            <input type="text" class="form-control" id="username" name="username" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">รหัสผ่าน</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">เข้าสู่ระบบ</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
