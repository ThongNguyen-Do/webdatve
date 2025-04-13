<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: dangnhap.php");
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "datve";
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

if (!isset($_GET['id'])) {
    echo "Thiếu ID phim!";
    exit();
}

$id = $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM movies WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if (!$result || $result->num_rows == 0) {
    echo "Phim không tồn tại!";
    exit();
}

$movie = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Sửa Phim</title>
    <style>
        body {
            background: linear-gradient(to right, #141E30, #243B55);
            font-family: sans-serif;
            padding: 40px;
        }

        form {
            max-width: 600px;
            margin: auto;
            background: #fff;
            padding: 30px;
            border-radius: 10px;
        }

        h2 {
            text-align: center;
            color: white;
        }

        input[type="text"],
        textarea {
            width: 100%;
            padding: 10px;
            margin-top: 12px;
            border-radius: 6px;
            border: 1px solid #ccc;
        }

        textarea {
            height: 100px;
        }

        button {
            margin-top: 20px;
            background-color: #1e3c72;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
        }

        button:hover {
            background-color: #0056b3;
        }

        .back {
            text-align: center;
            margin-top: 20px;
        }

        .back a {
            color: #007bff;
            text-decoration: none;
        }

        .back a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<h2> Chỉnh sửa phim</h2>

<form method="post" action="movies_manage.php">
    <input type="hidden" name="edit_movie" value="1">
    <input type="hidden" name="id" value="<?= $movie['id'] ?>">

    <label for="title">Tên phim:</label>
    <input type="text" name="title" id="title" value="<?= htmlspecialchars($movie['title']) ?>" required>

    <label for="description">Mô tả:</label>
    <textarea name="description" id="description" required><?= htmlspecialchars($movie['description']) ?></textarea>

   

    <button type="submit">Lưu Thay Đổi</button>
</form>

<div class="back">
    <a href="movies_manage.php">← Quay lại danh sách phim</a>
</div>

</body>
</html>

<?php $conn->close(); ?>
