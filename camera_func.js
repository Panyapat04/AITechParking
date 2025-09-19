let stream1, stream2, stream3;
let video1 = document.getElementById("video1");
let video2 = document.getElementById("video2");
let video3 = document.getElementById("video3");
let canvas1 = document.getElementById("canvas1");
let canvas2 = document.getElementById("canvas2");
let canvas3 = document.getElementById("canvas3");
let photo1 = document.getElementById("photo1");
let photo2 = document.getElementById("photo2");
let photo3 = document.getElementById("photo3");
let image1Data = document.getElementById("image1_data");
let image2Data = document.getElementById("image2_data");
let image3Data = document.getElementById("image3_data");

let select1 = document.getElementById("cameraSelect1");
let select2 = document.getElementById("cameraSelect2");
let select3 = document.getElementById("cameraSelect3");
// ดึงรายการกล้องทั้งหมด
navigator.mediaDevices.enumerateDevices().then((devices) => {
  let cameras = devices.filter((device) => device.kind === "videoinput");
  cameras.forEach((camera, index) => {
    let option1 = new Option(
      camera.label || `กล้อง ${index + 1}`,
      camera.deviceId
    );
    let option2 = new Option(
      camera.label || `กล้อง ${index + 1}`,
      camera.deviceId
    );
    let option3 = new Option(
      camera.label || `กล้อง ${index + 1}`,
      camera.deviceId
    );
    select1.add(option1);
    select2.add(option2);
    select3.add(option3);
  });
  if (cameras.length >= 3) {
    startCamera(select1.value, video1, 1);
    startCamera(select2.value, video2, 2);
    startCamera(select3.value, video3, 3);
  }
});

select1.onchange = () => startCamera(select1.value, video1, 1);
select2.onchange = () => startCamera(select2.value, video2, 2);
select3.onchange = () => startCamera(select3.value, video3, 3);

// เริ่มกล้อง
function startCamera(deviceId, videoElement, camNum) {
  let constraints = {
    video: { deviceId: { exact: deviceId } },
  };

  navigator.mediaDevices
    .getUserMedia(constraints)
    .then((stream) => {
      // ใช้ switch แทน if-else
      switch (camNum) {
        case 1:
          stream1 = stream;
          break;
        case 2:
          stream2 = stream;
          break;
        case 3:
          stream3 = stream;
          break;
        default:
          console.error("Invalid camNum");
          return;
      }
      videoElement.srcObject = stream;
    })
    .catch((err) => {
      console.error("Error accessing camera: ", err);
      alert("อุปกรณ์ไม่พร้อม ตรวจสอบการเชื่อมต่อกล้องของคุณ");
    });
}

// ถ่ายรูปจากกล้อง
// ถ่ายรูปจากกล้อง
function captureImage(video, canvas, photo, camNum) {
  let context = canvas.getContext("2d");
  canvas.width = video.videoWidth;
  canvas.height = video.videoHeight;

  // วาดภาพจาก video ลงใน canvas
  context.drawImage(video, 0, 0, canvas.width, canvas.height);

  // แปลง canvas เป็น Base64
  let dataURL = canvas.toDataURL("image/png");

  // แสดงภาพใน photo element
  photo.src = dataURL;

  // ส่งข้อมูล Base64 ไปยัง hidden input ที่ตรงกับ camNum
  document.getElementById(`image${camNum}_data`).value = dataURL;
}
