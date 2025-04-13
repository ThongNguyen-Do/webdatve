<?php
// Kết nối MySQL
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "datve";

$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id']; // Giả sử đã đăng nhập, lưu user_id trong session
    $movie_id = $_POST['movie_id'];
    $quantity = $_POST['quantity'];
    $date = $_POST['date'];

    // Thực hiện chèn dữ liệu đặt vé vào bảng bookings
    $stmt = $conn->prepare("INSERT INTO bookings (user_id, movie_id, quantity, date) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiis", $user_id, $movie_id, $quantity, $date);

    if ($stmt->execute()) {
        echo "<p style='color: green;'>Đặt vé thành công!</p>";
    } else {
        echo "<p style='color: red;'>Lỗi: " . $stmt->error . "</p>";
    }

    $stmt->close();
}

$conn->close();
?>
