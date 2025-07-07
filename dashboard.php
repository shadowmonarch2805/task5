<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
$conn = new mysqli('localhost', 'root', '', 'blog');

$search = $_GET['search'] ?? '';
$page = $_GET['page'] ?? 1;
$limit = 5;
$offset = ($page - 1) * $limit;

$total_result = $conn->query("SELECT COUNT(*) AS total FROM posts WHERE title LIKE '%$search%' OR content LIKE '%$search%'");
$total_rows = $total_result->fetch_assoc()['total'];
$total_pages = ceil($total_rows / $limit);

$stmt = $conn->prepare("SELECT * FROM posts WHERE title LIKE CONCAT('%', ?, '%') OR content LIKE CONCAT('%', ?, '%') ORDER BY created_at DESC LIMIT ? OFFSET ?");
$stmt->bind_param("ssii", $search, $search, $limit, $offset);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
   
body.light-mode {
    background: #f8f9fa;
    color: #212529;
}
body.dark-mode {
    background: #121212;
    color: #ffffff;
}
.dark-mode .card {
    background-color: #1e1e1e;
    color: #ffffff;
    border-color: #444;
}
.dark-mode .btn {
    color: #ffffff;
    background-color: #333;
    border-color: #555;
}
.dark-mode .form-control {
    background-color: #2c2c2c;
    color: #ffffff;
    border-color: #555;
}
</style>

</head>
<body class="light-mode">
<div class="container my-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Welcome, <?= $_SESSION['role']; ?></h2>
        <div>
            <a href="create.php" class="btn btn-success me-2">+ New Post</a>
            <a href="logout.php" class="btn btn-danger">Logout</a>
        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <form method="get" class="d-flex w-100 me-3">
            <input name="search" class="form-control me-2" placeholder="Search..." value="<?= htmlspecialchars($search) ?>">
            <button class="btn btn-primary">Search</button>
        </form>
        <button onclick="toggleMode()" class="btn btn-secondary">Toggle Mode</button>
    </div>

    <?php while($row = $result->fetch_assoc()): ?>
    <div class="card mb-3">
        <div class="card-body">
            <h5 class="card-title"><?= htmlspecialchars($row['title']) ?></h5>
            <p class="card-text"><?= nl2br(htmlspecialchars(substr($row['content'], 0, 200))) ?>...</p>
            <small class="text-muted">Posted on <?= $row['created_at'] ?></small>
            <div class="mt-2">
                <?php if ($_SESSION['role'] === 'admin'): ?>
                    <a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                    <a href="delete.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this post?')">Delete</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php endwhile; ?>

    <nav>
        <ul class="pagination justify-content-center">
            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <li class="page-item <?= ($i == $page ? 'active' : '') ?>">
                <a class="page-link" href="?search=<?= urlencode($search) ?>&page=<?= $i ?>"><?= $i ?></a>
            </li>
            <?php endfor; ?>
        </ul>
    </nav>
</div>

<script>
function toggleMode() {
    const body = document.body;
    body.classList.toggle('dark-mode');
    body.classList.toggle('light-mode');
    localStorage.setItem('theme', body.classList.contains('dark-mode') ? 'dark-mode' : 'light-mode');
}
document.addEventListener("DOMContentLoaded", () => {
    const savedMode = localStorage.getItem("theme");
    if (savedMode) {
        document.body.classList.remove("light-mode", "dark-mode");
        document.body.classList.add(savedMode);
    }
});
</script>
</body>
</html>
