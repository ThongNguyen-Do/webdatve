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

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Xử lý thêm phim
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_movie'])) {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $image_url = $_POST['image_url'];

    $sql = "INSERT INTO movies (title, description, image_url) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $title, $description, $image_url);
    if ($stmt->execute()) {
        echo "<p style='color: green;'>Phim đã được thêm thành công!</p>";
    } else {
        echo "<p style='color: red;'>Lỗi khi thêm phim!</p>";
    }
    $stmt->close();
}

// Xử lý xóa phim
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $delete_sql = "DELETE FROM movies WHERE id = ?";
    $stmt = $conn->prepare($delete_sql);
    $stmt->bind_param("i", $delete_id);
    if ($stmt->execute()) {
        echo "<p style='color: green;'>Phim đã được xóa thành công!</p>";
    } else {
        echo "<p style='color: red;'>Lỗi khi xóa phim!</p>";
    }
    $stmt->close();
}

// Xử lý sửa phim
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit_movie'])) {
    $id = $_POST['id'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $image_url = $_POST['image_url'];

    $sql = "UPDATE movies SET title = ?, description = ?, image_url = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $title, $description, $image_url, $id);
    if ($stmt->execute()) {
        echo "<p style='color: green;'>Phim đã được cập nhật!</p>";
    } else {
        echo "<p style='color: red;'>Lỗi khi cập nhật phim!</p>";
    }
    $stmt->close();
}

// Lấy danh sách phim
$sql = "SELECT * FROM movies";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Phim</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background: rgba(255, 255, 255, 0.1);
            color: #333;
        }

        h1 {
            text-align: center;
            margin-top: 30px;
            font-size: 36px;
            color: #444;
        }

        .movie-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
            margin-top: 40px;
        }

        .movie {
            background: white;
            border-radius: 10px;
            width: 280px;
            padding: 20px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            text-align: center;
            transition: transform 0.3s ease-in-out;
        }

        .movie:hover {
            transform: scale(1.05);
        }

        .movie img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 10px;
            margin-bottom: 15px;
        }

        .movie h3 {
            font-size: 20px;
            font-weight: bold;
            color: #333;
            margin-bottom: 10px;
        }

        .movie p {
            font-size: 14px;
            color: #666;
            margin-bottom: 15px;
        }

        .btn {
            background-color: #333;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 8px;
            display: inline-block;
            transition: background 0.3s ease;
            font-size: 14px;
        }

        .btn:hover {
            background-color: #555;
        }

        .form-container {
            background: white;
            width: 400px;
            margin: 0 auto;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            margin-top: 40px;
        }

        input[type="text"], textarea {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }

        button {
            background-color: #333;
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
            background-color: #555;
        }

        .add-movie-btn {
            background-color: #FF6347;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 8px;
            display: inline-block;
            font-size: 16px;
            margin-bottom: 30px;
            transition: background 0.3s ease;
        }

        .add-movie-btn:hover {
            background-color: #FF4500;
        }
    </style>
</head>
<body>

<h1>Quản lý Phim</h1>

<!-- Nút Thêm Phim -->
<a href="them_phim.php" class="add-movie-btn">Thêm Phim</a>

<!-- Danh Sách Phim -->
<div class="movie-container">
    <?php
    if ($result && $result->num_rows > 0):
        while ($row = $result->fetch_assoc()):
    ?>
        <div class="movie">
            <img src="<?= htmlspecialchars($row['image_url']) ?>" alt="Poster của <?= htmlspecialchars($row['title']) ?>">
            <h3><?= htmlspecialchars($row['title']) ?></h3>
            <p><?= nl2br(htmlspecialchars($row['description'])) ?></p>
            <!-- Sửa Phim -->
            <a href="sua_phim.php?id=<?= $row['id'] ?>" class="btn">Sửa</a>
            <!-- Xóa Phim -->
            <a href="movies_manage.php?delete_id=<?= $row['id'] ?>" class="btn" onclick="return confirm('Bạn chắc chắn muốn xóa phim này?')">Xóa</a>
        </div>
    <?php
        endwhile;
    else:
        echo "<p>Không có phim nào!</p>";
    endif;
    ?>
</div>

</body>
</html>

<?php $conn->close(); ?>
