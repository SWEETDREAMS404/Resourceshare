<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Help & Support — ResourceShare</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background:#fff;
      font-family:'Segoe UI', sans-serif;
    }
    .topbar {
      background:#000; border-radius:40px; padding:12px 30px;
      margin:20px auto 10px; max-width:600px;
      display:flex; align-items:center; justify-content:center;
      color:#fff;
    }
    .topbar h2 { font-size:20px; font-weight:600; margin:0; }
    .action-buttons {
      display:flex; justify-content:flex-start;
      margin:10px auto 20px; max-width:600px;
    }
    .btn-green {
      background:#32cd32; color:#fff; font-weight:600;
      border:none; border-radius:5px; padding:8px 20px; margin-right:10px;
    }
    .btn-green:hover { background:#28a428; }
    .btn-red {
      background:#dc3545; color:#fff; font-weight:600;
      border:none; border-radius:5px; padding:8px 20px;
    }
    .btn-red:hover { background:#b52a36; }
    .content-box {
      max-width:600px; margin:20px auto; background:#f9f9f9;
      border-radius:8px; box-shadow:0 2px 4px rgba(0,0,0,0.1);
      padding:20px;
    }
    .faq h5 { font-weight:600; margin-top:15px; }
    .faq p { margin-bottom:10px; }
  </style>
</head>
<body>

<!-- Header -->
<div class="topbar">
  <h2>Help & Support</h2>
</div>

<!-- Buttons -->
<div class="action-buttons">
  <a href="index.php" class="btn-green">⬅ Back to Home</a>
  <a href="logout.php" class="btn-red">Log out</a>
</div>

<!-- Help Content -->
<div class="content-box">
  <h4 class="mb-3 text-center">Frequently Asked Questions</h4>
  
  <div class="faq">
    <h5>🔑 How do I reset my password?</h5>
    <p>Go to <b>Manage Account</b> → Enter a new password and confirm it → Click <b>Update Password</b>.</p>

    <h5>📧 How do I change my email address?</h5>
    <p>Navigate to <b>Manage Account</b> → Enter your new email → Click <b>Update Email</b>.</p>

    <h5>🖼 How do I update my profile picture?</h5>
    <p>Go to <b>Manage Account</b> → Upload a new picture → Click <b>Update Picture</b>.</p>

    <h5>❓ What if I forgot my login details?</h5>
    <p>Please contact support for account recovery assistance.</p>
  </div>

  <hr>
  <h4 class="mt-3 text-center">Contact Support</h4>
  <p class="text-center">
    📩 Email us at <a href="mailto:support@resourceshare.com">support@resourceshare.com</a><br>
    📞 Call: +1 (234) 567-890
  </p>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
