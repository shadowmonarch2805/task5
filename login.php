<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'blog');
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $stmt = $conn->prepare("SELECT id, password, role FROM users WHERE username=?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->bind_result($user_id, $hashed_password, $role);
    if ($stmt->fetch() && password_verify($password, $hashed_password)) {
        $_SESSION['user_id'] = $user_id;
        $_SESSION['role'] = $role;
        header("Location: dashboard.php");
    } else {
        echo "Invalid login.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Login</title>
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
<body class="container my-5">
<h2>Login</h2>
<form method="post" class="w-50 mx-auto needs-validation" novalidate>
    <div class="mb-3"><label>Username</label><input name="username" class="form-control" required></div>
    <div class="mb-3"><label>Password</label><input type="password" name="password" class="form-control" required></div>
    <button class="btn btn-primary" type="submit">Login</button>
</form>
<script>
(() => {
  const forms = document.querySelectorAll('.needs-validation');
  Array.from(forms).forEach(form => {
    form.addEventListener('submit', e => {
      if (!form.checkValidity()) {
        e.preventDefault();
        e.stopPropagation();
      }
      form.classList.add('was-validated');
    }, false);
  });
})();
</script>
</body>
</html>
