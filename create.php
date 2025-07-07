<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
$conn = new mysqli('localhost', 'root', '', 'blog');
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $imagePath = '';
    if ($_FILES['image']['name']) {
        $imagePath = 'uploads/' . basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], $imagePath);
    }
    $stmt = $conn->prepare("INSERT INTO posts (title, content, image_path) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $title, $content, $imagePath);
    $stmt->execute();
    header("Location: dashboard.php");
}
?>
<!DOCTYPE html>
<html><head><title>Create Post</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"></head>
<body class="container my-5">
<h2>Create Post</h2>
<form method="post" enctype="multipart/form-data" class="w-75 mx-auto">
    <div class="mb-3"><label>Title</label><input name="title" class="form-control" required></div>
    <div class="mb-3"><label>Content</label><textarea name="content" class="form-control" required></textarea></div>
    <div class="mb-3"><label>Image</label><input type="file" name="image" class="form-control"></div>
    <button class="btn btn-success" type="submit">Create</button>
</form>
</body></html>
