<?php
session_start();
require 'db.php';
$err='';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    // Fetch role AND profile_pic
    $stmt = $mysqli->prepare('SELECT user_id, password, role, profile_pic FROM users WHERE username=? LIMIT 1');
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($row = $res->fetch_assoc()) {
        if (password_verify($password, $row['password'])) {
            $_SESSION['user_id'] = $row['user_id'];
            $_SESSION['username'] = $username;
            $_SESSION['role'] = $row['role'];
            $_SESSION['profile_pic'] = $row['profile_pic']; // ✅ store profile picture in session

            // Redirect based on role
            if ($row['role'] === 'admin') {
                header('Location: admin.php'); 
                exit;
            } else {
                header('Location: index.php'); 
                exit;
            }
        } else {
            $err = 'Invalid credentials.';
        }
    } else {
        $err = 'Invalid credentials.';
    }
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Login — ResourceShare</title>
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
    .login-box {
      width:360px;
      text-align:center;
      padding: 32px 28px;
    }
    .logo {
      width: 96px;
      height: 96px;
      border-radius: 50%;
      overflow: hidden;
      margin: 0 auto 18px;
      display: flex;
      align-items: center;
      justify-content: center;
      background: #000;
    }
    .logo img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }
    h4 { font-weight:700; margin-bottom:18px; }
    .form-control {
      background:#f2f2f2; border:none; padding:12px;
      margin-bottom:14px;
    }
    .form-control:focus { box-shadow:none; border:2px solid #9fef00; }
    .btn-login {
      background:#28a745; color:#fff; border:none; width:100%;
      font-weight:700; padding:10px;
    }
    .btn-login:hover { background:#218838; }
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
    @media (max-width:420px) { .login-box{ width:92%; padding:20px;} .side-back{display:none;} }
  </style>
</head>
<body>

<!-- Left middle back button -->
<a class="side-back" href="landing.php" aria-label="Back to home" title="Back to home">
  <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="#fff" stroke-width="3" viewBox="0 0 24 24">
    <path d="M15 18L9 12L15 6" stroke-linecap="round" stroke-linejoin="round"/>
  </svg>
</a>

<div class="login-box card shadow-sm">
  <div class="logo">
    <img src="assets/logo.png" alt="ResourceShare Logo">
  </div>
  <h4>Login</h4>
<?php if (isset($_GET['registered']) && $_GET['registered'] == 1): ?>
  <div class="alert alert-success alert-dismissible fade show" role="alert">
    🎉 Registration successful! You can now log in.
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
<?php endif; ?>
  <?php if ($err): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
      <?= htmlspecialchars($err) ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  <?php endif; ?>

  <form method="post" novalidate>
    <input type="text" name="username" class="form-control" placeholder="Username" required value="<?= isset($_POST['username']) ? htmlspecialchars($_POST['username']) : '' ?>">
    <div class="form-group">
      <input type="password" name="password" id="password" class="form-control" placeholder="Password" required>
      <span class="password-toggle" onclick="togglePassword()" id="toggleIcon">👁️</span>
    </div>
    <button type="submit" class="btn btn-login mt-2">LOGIN</button>
  </form>

  <div class="mt-3 small">
    Don’t have an account? <a href="register.php">Register</a>
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
