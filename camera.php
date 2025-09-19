<?php
// Include the login check script for security.
include('login_check.php');
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="description" content="ระบบถ่ายภาพบันทึกฐานข้อมูลสำหรับการจัดการกล้อง CCTV หรืออื่นๆ">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
    <title>หน้าแรก - Security college</title>
    <link rel="icon" type="image/png" href="logo/logo.png">
    <link rel="stylesheet" href="style.css" />
    <style>
        /* General element styling */
        video, img {
            width: 100%;
            border-radius: 1px;
            border: 1px solid red;
        }
        .preview-box { margin-top: 10px; }
        .edit-icon { cursor: pointer; margin-left: 10px; }
        .card button {
            border: none; outline: 0; padding: 8px; color: white;
            background-color: #000; text-align: center; cursor: pointer;
            width: 100%; font-size: 22px;
        }
        .card button:hover { opacity: 0.8; }
        
        /* Loading spinner */
        #loading {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background-color: rgba(0, 0, 0, 0.5); display: flex;
            justify-content: center; align-items: center; z-index: 9999;
            display: none;
        }
        .spinner-border { width: 3rem; height: 3rem; border-width: 0.3em; }

        /* Modern button style */
        .btn-modern {
            padding: 14px 40px; font-size: 18px; font-weight: 600;
            border-radius: 50px; border: none; cursor: pointer;
            background: linear-gradient(135deg, #28a745, #2b743bff);
            box-shadow: 0 6px 20px rgba(40, 167, 69, 0.4); letter-spacing: 1px;
            transition: all 0.3s ease-in-out; position: relative; overflow: hidden;
        }
        .btn-modern::before {
            content: ""; position: absolute; top: -50%; left: -50%;
            width: 200%; height: 200%; transform: rotate(25deg);
            background: linear-gradient(120deg, rgba(255, 255, 255, 0.2), transparent 60%);
            transition: all 0.6s ease;
        }
        .btn-modern:hover::before { left: 100%; }
        .btn-modern:hover {
            transform: translateY(-3px) scale(1.02);
            box-shadow: 0 10px 30px rgba(0, 114, 255, 0.6);
            background: linear-gradient(135deg, #186ac7ff, #27b9f3af);
        }

        /* Responsive and spacing adjustments */
        .content .container-fluid { padding: 0 5px; } /* Reduced padding */
        h2.text-center { margin-top: 10px !important; margin-bottom: 10px !important; }
        .row.g-2 { margin-top: 5px; } /* Reduced spacing between rows */
        .camera-card { min-height: 100%; }
    </style>
</head>
<body class="bg-light">

<?php include('navbar.php'); ?>

<?php include('sidebar.php'); ?>

<div class="content">
    <div class="container-fluid mt-2">
        <h2 class="text-center mb-4">การจัดเก็บข้อมูล กำลังทำงาน</h2>

        <?php
        include 'db_connect.php';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $driverNumber = isset($_POST['driver_number']) ? trim($_POST['driver_number']) : '';
            date_default_timezone_set("Asia/Bangkok");
            $dateTime = date("Y-m-d H:i:s");
            $image1 = isset($_POST['image1']) ? $_POST['image1'] : '';
            $image2 = isset($_POST['image2']) ? $_POST['image2'] : '';
            $image3 = isset($_POST['image3']) ? $_POST['image3'] : '';

            $imageDir = 'uploads';
            if (!file_exists($imageDir)) {
                mkdir($imageDir, 0777, true);
            }

            function saveBase64Image($base64Data, $dir) {
                if (!$base64Data) return '';
                list(, $data) = explode(',', $base64Data);
                $imageData = base64_decode($data);
                $filename = $dir . '/' . uniqid() . '.png';
                file_put_contents($filename, $imageData);
                return $filename;
            }

            $path1 = saveBase64Image($image1, $imageDir);
            $path2 = saveBase64Image($image2, $imageDir);
            $path3 = saveBase64Image($image3, $imageDir);

            $check = $conn->prepare("SELECT person_id FROM driver_info WHERE driver_number=? AND date_time > (NOW() - INTERVAL 5 SECOND)");
            $check->bind_param("s", $driverNumber);
            $check->execute();
            $check->store_result();

            if ($check->num_rows == 0) {
                $stmt = $conn->prepare("INSERT INTO driver_info (driver_number, date_time, image_path, image_path2, image_path3) VALUES (?, ?, ?, ?, ?)");
                $stmt->bind_param("sssss", $driverNumber, $dateTime, $path1, $path2, $path3);
                if ($stmt->execute()) {
                    $updateStmt = $conn->prepare("UPDATE control SET direction = 1");
                    $updateStmt->execute();
                    $updateStmt->close();
                } else {
                    echo "เกิดข้อผิดพลาดในการบันทึกข้อมูล driver_info: " . $stmt->error;
                }
                $stmt->close();
            }
            $check->close();
            $conn->close();
        }
        ?>

        <div class="row g-1">
            <div class="col-md-4">
                <div class="card shadow-sm camera-card">
                    <div class="card-body text-center">
                        <h5 contenteditable="true" id="cameraTitle1" class="card-title d-inline">กล้อง 1</h5>
                        <i class='bx bx-pencil edit-icon ms-2' onclick="editCameraTitle('cameraTitle1')"></i>
                        <video id="video1" autoplay class="w-100 rounded mt-2"></video>
                        <select id="cameraSelect1" class="form-select mt-2 mb-2"></select>
                        <div class="preview-box">
                            <canvas id="canvas1" style="display:none;"></canvas>
                            <img id="photo1" src="" class="img-fluid rounded">
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card shadow-sm camera-card">
                    <div class="card-body text-center">
                        <h5 contenteditable="true" id="cameraTitle2" class="card-title d-inline">กล้อง 2</h5>
                        <i class='bx bx-pencil edit-icon ms-2' onclick="editCameraTitle('cameraTitle2')"></i>
                        <video id="video2" autoplay class="w-100 rounded mt-2"></video>
                        <select id="cameraSelect2" class="form-select mt-2 mb-2"></select>
                        <div class="preview-box">
                            <canvas id="canvas2" style="display:none;"></canvas>
                            <img id="photo2" src="" class="img-fluid rounded">
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card shadow-sm camera-card">
                    <div class="card-body text-center">
                        <h5 contenteditable="true" id="cameraTitle3" class="card-title d-inline">กล้อง 3</h5>
                        <i class='bx bx-pencil edit-icon ms-2' onclick="editCameraTitle('cameraTitle3')"></i>
                        <video id="video3" autoplay class="w-100 rounded mt-2"></video>
                        <select id="cameraSelect3" class="form-select mt-2 mb-2"></select>
                        <div class="preview-box">
                            <canvas id="canvas3" style="display:none;"></canvas>
                            <img id="photo3" src="" class="img-fluid rounded">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <form action="#" method="post" enctype="multipart/form-data" class="mt-4" id="uploadForm">
            <input type="hidden" name="image1" id="image1_data">
            <input type="hidden" name="image2" id="image2_data">
            <input type="hidden" name="image3" id="image3_data">

            <div class="mb-3">
                <label for="driver_number" class="form-label fw-bold">รหัสใบขับขี่</label>
                <input type="text" class="form-control form-control-lg" id="driver_number" name="driver_number" required>
            </div>
            
            <div class="text-center">
                <button type="submit" class="btn-modern">บันทึกข้อมูล</button>
            </div>
        </form>
    </div>
</div>

<div id="loading">
    <div class="spinner-border text-success" role="status"></div>
</div>

<script>
    async function setupCamera(videoElement, cameraSelectElement, storageKey) {
        try {
            const cameras = await navigator.mediaDevices.enumerateDevices();
            const videoDevices = cameras.filter(device => device.kind === 'videoinput');
            cameraSelectElement.innerHTML = "";
            videoDevices.forEach((device, index) => {
                const option = document.createElement('option');
                option.value = device.deviceId;
                option.text = device.label || `กล้อง ${index + 1}`;
                cameraSelectElement.appendChild(option);
            });
            const savedCamera = localStorage.getItem(storageKey);
            let constraints;
            if (savedCamera) {
                cameraSelectElement.value = savedCamera;
                constraints = { video: { deviceId: { exact: savedCamera } } };
            } else {
                if (videoDevices.length > 0) {
                    const firstCamera = videoDevices[0].deviceId;
                    localStorage.setItem(storageKey, firstCamera);
                    cameraSelectElement.value = firstCamera;
                    constraints = { video: { deviceId: { exact: firstCamera } } };
                } else {
                    constraints = { video: true };
                }
            }
            const stream = await navigator.mediaDevices.getUserMedia(constraints);
            videoElement.srcObject = stream;
            cameraSelectElement.addEventListener('change', async () => {
                localStorage.setItem(storageKey, cameraSelectElement.value);
                const newConstraints = { video: { deviceId: { exact: cameraSelectElement.value } } };
                const newStream = await navigator.mediaDevices.getUserMedia(newConstraints);
                videoElement.srcObject = newStream;
            });
        } catch (err) {
            console.error('กล้องนี้ไม่พร้อมใช้งาน:', err);
        }
    }
    function captureImagesBeforeSubmit() {
        const videos = ['video1', 'video2', 'video3'];
        const canvases = ['canvas1', 'canvas2', 'canvas3'];
        const imageInputs = ['image1_data', 'image2_data', 'image3_data'];
        const photoPreviews = ['photo1', 'photo2', 'photo3'];
        for (let i = 0; i < videos.length; i++) {
            const video = document.getElementById(videos[i]);
            const canvas = document.getElementById(canvases[i]);
            const ctx = canvas.getContext('2d');
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
            const imageData = canvas.toDataURL('image/png');
            document.getElementById(imageInputs[i]).value = imageData;
            document.getElementById(photoPreviews[i]).src = imageData;
        }
    }
    document.addEventListener('DOMContentLoaded', () => {
        setupCamera(document.getElementById('video1'), document.getElementById('cameraSelect1'), 'camera1');
        setupCamera(document.getElementById('video2'), document.getElementById('cameraSelect2'), 'camera2');
        setupCamera(document.getElementById('video3'), document.getElementById('cameraSelect3'), 'camera3');
        document.getElementById('driver_number').focus();
        const driverNumberInput = document.getElementById('driver_number');
        driverNumberInput.addEventListener('change', function () {
            if (driverNumberInput.value) {
                document.getElementById('uploadForm').submit();
            }
        });
        document.getElementById('cameraTitle1').innerText = localStorage.getItem('cameraTitle1') || 'กล้อง 1';
        document.getElementById('cameraTitle2').innerText = localStorage.getItem('cameraTitle2') || 'กล้อง 2';
        document.getElementById('cameraTitle3').innerText = localStorage.getItem('cameraTitle3') || 'กล้อง 3';
    });
    function editCameraTitle(cameraId) {
        const cameraElement = document.getElementById(cameraId);
        cameraElement.focus();
        cameraElement.addEventListener('blur', function () {
            localStorage.setItem(cameraId, cameraElement.innerText);
        });
    }
    document.getElementById('uploadForm').addEventListener('submit', function (e) {
        captureImagesBeforeSubmit();
        document.getElementById('loading').style.display = 'flex';
    });
</script>
</body>
</html>