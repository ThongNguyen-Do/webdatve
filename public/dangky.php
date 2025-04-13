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
    // Lấy thông tin từ form
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Mã hóa mật khẩu
    $email = $_POST['email'];

    // Sử dụng prepared statements để tránh SQL Injection
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "Tên đăng nhập hoặc email đã tồn tại!";
    } else {
        // Chèn dữ liệu vào cơ sở dữ liệu
        $stmt = $conn->prepare("INSERT INTO users (username, password, email) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $password, $email);
        if ($stmt->execute()) {
            echo "Đăng ký thành công! <a href='dangnhap.php'>Đăng nhập</a>";
        } else {
            echo "Lỗi: " . $stmt->error;
        }
    }

    $stmt->close(); // Đóng prepared statement
}

$conn->close(); // Đóng kết nối MySQL
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Ký Tài Khoản</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(to right, #141E30, #243B55);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .form-container {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            padding: 40px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.2);
            width: 350px;
            text-align: center;
        }

        h2 {
            color: white;
            margin-bottom: 30px;
        }

        label {
            color: white;
            text-align: left;
            font-size: 14px;
            display: block;
            margin-bottom: 5px;
        }

        input[type="text"], input[type="password"], input[type="email"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #fff;
            color: #333;
            font-size: 16px;
        }

        button {
            background-color: #FF6347;
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
            transition: background 0.3s ease;
        }

        button:hover {
            background-color: #FF4500;
        }

        .link {
            color: #FF6347;
            text-decoration: none;
            font-size: 14px;
        }

        .link:hover {
            text-decoration: underline;
        }

        footer {
            font-size: 14px;
            color: white;
            margin-top: 20px;
        }

    </style>
</head>
<body>

    <div class="form-container">
        <h2>Đăng Ký Tài Khoản</h2>
        <form action="dangky.php" method="POST">
            <label for="username">Tên đăng nhập:</label>
            <input type="text" name="username" id="username" required>

            <label for="password">Mật khẩu:</label>
            <input type="password" name="password" id="password" required>

            <label for="email">Email:</label>
            <input type="email" name="email" id="email" required>

            <button type="submit">Đăng ký</button>
        </form>

        <p>Đã có tài khoản? <a href="dangnhap.php" class="link">Đăng nhập ngay</a></p>
    </div>

    

</body>
</html>
