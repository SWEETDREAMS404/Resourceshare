<?php
session_start();
require 'db.php';

$err = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (strlen($username) < 3 || strlen($password) < 6) {
        $err = 'Username must be at least 3 characters and password at least 6 characters.';
    } else {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $mysqli->prepare('INSERT INTO users (username,password,email,role) VALUES (?,?,?,?)');
        $role = 'user';
        $stmt->bind_param('ssss', $username, $hash, $email, $role);

        try {
            if ($stmt->execute()) {
                header('Location: login.php?registered=1');
                exit;
            }
        } catch (mysqli_sql_exception $e) {
            if ($e->getCode() === 1062) {
                $err = 'Username or email already exists. Please choose another.';
            } else {
                $err = 'Registration failed. Please try again later.';
            }
        }
    }
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Create an Account — ResourceShare</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      min-height:100vh;
      display:flex;
      align-items:center;
      justify-content:center;
      font-family: Arial, sans-serif;
      background:#fff;
      margin:0;
    }
    .register-box {
      width:360px;
      text-align:center;
      padding: 32px 28px;
    }
      .logo {
    width: 96px;
    height: 96px;
    border-radius: 50%;
    overflow: hidden; /* makes sure the image stays inside circle */
    margin: 0 auto 18px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #000; /* fallback if image not loaded */
  }
  .logo img {
    width: 100%;
    height: 100%;
    object-fit: cover; /* keeps proportions inside circle */
  }
    h4 { font-weight:700; margin-bottom:18px; }
    .form-control {
      background:#f2f2f2; border:none; padding:12px;
      margin-bottom:14px;
    }
    .form-control:focus { box-shadow:none; border:2px solid #9fef00; }
    .btn-register {
      background:#28a745; color:#fff; border:none; width:100%;
      font-weight:700; padding:10px;
    }
    .btn-register:hover { background:#218838; }
    .password-toggle { position:absolute; right:14px; top:50%; transform:translateY(-50%); cursor:pointer; font-size:18px; }
    .form-group { position:relative; }

    /* Left middle back pill */
    .side-back {
      position:fixed;
      left:-23px;
      top:200px;
      width:66px;
      height:350px;
      border-radius:100px;
      background:#000;
      color:#fff;
      display:flex;
      align-items:center;
      justify-content:center;
      text-decoration:none;
      box-shadow:0 6px 18px rgba(0,0,0,0.15);
    }
    .side-back svg { left: 9px; width:40px; height:400px;}
    @media (max-width:420px) { .register-box{ width:92%; padding:20px;} .side-back{display:none;} }
  </style>
</head>
<body>

<!-- Left middle back button -->
<a class="side-back" href="landing.php" aria-label="Back to home" title="Back to home">
  <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="#fff" stroke-width="3" viewBox="0 0 24 24">
    <path d="M15 18L9 12L15 6" stroke-linecap="round" stroke-linejoin="round"/>
  </svg>
</a>

<div class="register-box card shadow-sm">
  <div class="logo">
  <img src="assets/logo.png" alt="ResourceShare Logo">
</div>
  <h4>Create an Account</h4>

  <?php if ($err): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
      <?= htmlspecialchars($err) ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  <?php endif; ?>

  <form method="post" novalidate>
    <input type="text" name="username" class="form-control" placeholder="Username" required value="<?= isset($_POST['username']) ? htmlspecialchars($_POST['username']) : '' ?>">
    <input type="email" name="email" class="form-control" placeholder="Email" required value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>">
    <div class="form-group">
      <input type="password" name="password" id="password" class="form-control" placeholder="Password" required>
      <span class="password-toggle" onclick="togglePassword()" id="toggleIcon">👁️</span>
    </div>
    <button type="submit" class="btn btn-register mt-2">REGISTER</button>
  </form>

  <div class="mt-3 small">
    Already have an account? <a href="login.php">Login</a>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
function togglePassword(){
  const p = document.getElementById('password');
  const icon = document.getElementById('toggleIcon');
  if(p.type === 'password'){
    p.type = 'text';
    icon.textContent = '🙈';
  } else {
    p.type = 'password';
    icon.textContent = '👁️';
  }
}
</script>
</body>
</html>
