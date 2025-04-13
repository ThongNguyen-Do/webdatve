<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: dangnhap.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thêm Phim</title>
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
            background-color: #218838;
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

<h2> Thêm Phim Mới</h2>

<form method="post" action="movies_manage.php">
    <input type="hidden" name="add_movie" value="1">
    <label for="title">Tên phim:</label>
    <input type="text" name="title" id="title" required>

    <label for="description">Mô tả:</label>
    <textarea name="description" id="description" required></textarea>

    

    <button type="submit">Thêm Phim</button>
</form>

<div class="back">
    <a href="movies_manage.php">← Quay lại danh sách phim</a>
</div>

</body>
</html>
