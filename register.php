<?php
$conn = new mysqli('localhost', 'root', '', 'blog');
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $role = 'editor'; // default role

    if (strlen($username) < 3 || strlen($password) < 5) {
        die("Username must be at least 3 chars and password 5 chars.");
    }

    $passwordHash = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $passwordHash, $role);
    if ($stmt->execute()) {
        echo "Registration successful. <a href='login.php'>Login</a>";
    } else {
        echo "Error: Username might already exist.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Register</title>
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
<h2>Register</h2>
<form method="post" class="w-50 mx-auto needs-validation" novalidate>
    <div class="mb-3">
        <label>Username</label>
        <input name="username" class="form-control" required minlength="3">
        <div class="invalid-feedback">At least 3 characters.</div>
    </div>
    <div class="mb-3">
        <label>Password</label>
        <input type="password" name="password" class="form-control" required minlength="5">
        <div class="invalid-feedback">At least 5 characters.</div>
    </div>
    <button class="btn btn-primary" type="submit">Register</button>
</form>
<script>
(() => {
  'use strict';
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
