<?php
session_start();
require 'db.php';

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$success = $error = '';

// Fetch current user data
$stmt = $mysqli->prepare("SELECT username, email, profile_pic FROM users WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_email'])) {
        $email = trim($_POST['email']);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = "Invalid email address.";
        } else {
            $stmt = $mysqli->prepare("UPDATE users SET email=? WHERE user_id=?");
            $stmt->bind_param("si", $email, $user_id);
            if ($stmt->execute()) {
                $success = "Email updated successfully.";
                $user['email'] = $email;
            }
        }
    }

    if (isset($_POST['update_password'])) {
        $password = $_POST['password'];
        $confirm = $_POST['confirm_password'];
        if (strlen($password) < 6) {
            $error = "Password must be at least 6 characters.";
        } elseif ($password !== $confirm) {
            $error = "Passwords do not match.";
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $mysqli->prepare("UPDATE users SET password=? WHERE user_id=?");
            $stmt->bind_param("si", $hash, $user_id);
            if ($stmt->execute()) {
                $success = "Password updated successfully.";
            }
        }
    }

    if (isset($_POST['update_pic']) && isset($_FILES['profile_pic'])) {
        $file = $_FILES['profile_pic'];
        if ($file['error'] === 0) {
            $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
            $filename = "profile_" . $user_id . "." . $ext;
            $path = "uploads/" . $filename;

            // Create uploads folder if not exists
            if (!is_dir("uploads")) {
                mkdir("uploads", 0777, true);
            }

            if (move_uploaded_file($file['tmp_name'], $path)) {
                $stmt = $mysqli->prepare("UPDATE users SET profile_pic=? WHERE user_id=?");
                $stmt->bind_param("si", $filename, $user_id);
                if ($stmt->execute()) {
                    $success = "Profile picture updated successfully.";
                    $user['profile_pic'] = $filename;

                    // ✅ Update session so other pages can use it
                    $_SESSION['profile_pic'] = $filename;
                }
            } else {
                $error = "Failed to upload profile picture.";
            }
        }
    }
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Manage Account — ResourceShare</title>
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
      display:flex; align-items:flex-start; justify-content:left;
      color:#fff;
    }
    .topbar h2 { font-size:20px; font-weight:600; margin:0; }
    .action-buttons {
      display:flex; justify-content:flex-end;
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
    .profile-pic {
      width:100px; height:100px;
      border-radius:50%;
      object-fit:cover;
      margin-bottom:15px;
    }
  </style>
</head>
<body>

<!-- Header -->
<div class="topbar">
  <h2>Manage Account</h2>
</div>

<!-- Buttons -->
<div class="action-buttons">
  <a href="index.php" class="btn-green">⬅ Back to Home</a>
  <a href="logout.php" class="btn-red">Log out</a>
</div>

<!-- Account Box -->
<div class="content-box text-center">
  <img src="<?= $user['profile_pic'] ? 'uploads/' . htmlspecialchars($user['profile_pic']) : 'assets/profile.png' ?>" 
       alt="Profile Picture" class="profile-pic">

  <?php if ($success): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      <?= htmlspecialchars($success) ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  <?php endif; ?>
  <?php if ($error): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
      <?= htmlspecialchars($error) ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  <?php endif; ?>

  <!-- Update Email -->
  <form method="post" class="text-start">
    <label class="form-label">Email</label>
    <input type="email" name="email" class="form-control mb-2" value="<?= htmlspecialchars($user['email']) ?>" required>
    <button type="submit" name="update_email" class="btn btn-primary w-100 mb-3">Update Email</button>
  </form>

  <!-- Update Password -->
  <form method="post" class="text-start">
    <label class="form-label">New Password</label>
    <input type="password" name="password" class="form-control mb-2" required>
    <label class="form-label">Confirm Password</label>
    <input type="password" name="confirm_password" class="form-control mb-2" required>
    <button type="submit" name="update_password" class="btn btn-warning w-100 mb-3">Update Password</button>
  </form>

  <!-- Update Profile Picture -->
  <form method="post" enctype="multipart/form-data" class="text-start">
    <label class="form-label">Profile Picture</label>
    <input type="file" name="profile_pic" class="form-control mb-2" accept="image/*" required>
    <button type="submit" name="update_pic" class="btn btn-success w-100">Update Picture</button>
  </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
