<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
$conn = new mysqli('localhost', 'root', '', 'blog');
$id = $_GET['id'];
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $stmt = $conn->prepare("UPDATE posts SET title=?, content=? WHERE id=?");
    $stmt->bind_param("ssi", $title, $content, $id);
    $stmt->execute();
    header("Location: dashboard.php");
}
$result = $conn->query("SELECT * FROM posts WHERE id=$id");
$post = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html><head><title>Edit Post</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"></head>
<body class="container my-5">
<h2>Edit Post</h2>
<form method="post" class="w-75 mx-auto">
    <div class="mb-3"><label>Title</label><input name="title" class="form-control" value="<?= htmlspecialchars($post['title']) ?>" required></div>
    <div class="mb-3"><label>Content</label><textarea name="content" class="form-control" required><?= htmlspecialchars($post['content']) ?></textarea></div>
    <button class="btn btn-primary" type="submit">Update</button>
</form>
</body></html>
