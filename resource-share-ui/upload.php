<?php
ini_set('display_errors', 0);   
ini_set('log_errors', 1);       
error_reporting(E_ALL & ~E_WARNING);
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) { 
    header('Location: login.php'); 
    exit; 
}

$err = ''; 
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = isset($_POST['title']) ? trim($_POST['title']) : '';
    $description = isset($_POST['description']) ? trim($_POST['description']) : '';
    $category_id = isset($_POST['category_id']) && $_POST['category_id'] !== '' ? (int)$_POST['category_id'] : null;

    if (!isset($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
        $err = 'Please select a valid file to upload.';
    } else {
        $f = $_FILES['file'];
        $mime = mime_content_type($f['tmp_name']);

        $allowed_mimes = [
            'application/pdf',
            'image/png',
            'image/jpeg',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
        ];
        $allowed_ext = ['pdf','docx','doc','jpg','jpeg','png'];
        $ext = strtolower(pathinfo($f['name'], PATHINFO_EXTENSION));

        if (!in_array($mime, $allowed_mimes) || !in_array($ext, $allowed_ext)) {
            $err = 'Invalid file type. Only PDF, DOCX, JPG, and PNG files are allowed.';
        } elseif ($f['size'] > 15*1024*1024) {
            $err = 'File too large (max 15MB).';
        } else {
            $target_dir = "uploads/";
            if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);

            $safeName = preg_replace('/[^A-Za-z0-9_.-]/','_', basename($f['name']));
            $target_file = $target_dir . time() . "_" . $safeName;

            if (move_uploaded_file($f['tmp_name'], $target_file)) {
                $uid = $_SESSION['user_id'];
                $size = $f['size'];

                if ($category_id === null) {
                    $stmt = $mysqli->prepare(
                        'INSERT INTO resources (user_id, title, description, file_path, file_mime, file_size) 
                         VALUES (?, ?, ?, ?, ?, ?)'
                    );
                    $stmt->bind_param('issssi', $uid, $title, $description, $target_file, $mime, $size);
                } else {
                    $stmt = $mysqli->prepare(
                        'INSERT INTO resources (user_id, category_id, title, description, file_path, file_mime, file_size) 
                         VALUES (?, ?, ?, ?, ?, ?, ?)'
                    );
                    $stmt->bind_param('iissssi', $uid, $category_id, $title, $description, $target_file, $mime, $size);
                }

                if ($stmt->execute()) {
                    $success = 'Upload successful.';
                } else {
                    $err = 'DB error: '.$stmt->error;
                }
            } else {
                $err = 'Failed to move uploaded file.';
            }
        }
    }
}

$cats = $mysqli->query('SELECT * FROM categories ORDER BY name');
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Upload Resource — ResourceShare</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
  body { background:#fff; font-family:'Segoe UI', sans-serif; }
  .topbar {
    background:#000; border-radius:40px; padding:10px 30px; 
    margin:20px auto; max-width:900px; 
    display:flex; align-items:center; justify-content:space-between; 
    color:#fff;
  }
  .topbar .brand { font-weight:700; font-size:20px; text-transform:uppercase; }
  .topbar .user-info { display:flex; align-items:center; gap:10px; }
  .topbar .user-info img { width:40px; height:40px; border-radius:50%; border:2px solid #fff; }
  .upload-box {
    max-width:700px; margin:20px auto; padding:20px; 
    background:#f7f7f7; border-radius:8px; 
    box-shadow:0 1px 3px rgba(0,0,0,0.1);
  }
  .upload-box label { font-weight:500; margin-bottom:4px; }
  .form-control, .form-select { background:#f3f3f3; border:none; border-radius:4px; }
  textarea.form-control { resize:none; }
  .btn-upload {
    background:#32cd32; color:#fff; font-weight:600; 
    padding:8px 20px; border:none; border-radius:4px;
  }
  .btn-upload:hover { background:#28a428; }
  .btn-back {
    background:#6c757d; color:#fff; font-weight:600; 
    padding:8px 20px; border:none; border-radius:4px;
    margin-left:10px;
  }
  .btn-back:hover { background:#5a6268; }
</style>
</head>
<body>

<!-- Top black rounded header -->
<div class="topbar">
  <div class="brand">RESOURCE SHARE</div>
  <div class="user-info">
    <span><?= htmlspecialchars($_SESSION['username']) ?></span>
    <img src="<?= isset($_SESSION['profile_pic']) && $_SESSION['profile_pic'] 
                ? 'uploads/'.htmlspecialchars($_SESSION['profile_pic']) 
                : 'assets/profile.png' ?>" alt="Profile">
  </div>
</div>

<!-- Upload Form -->
<div class="upload-box">
  <?php if($err): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($err) ?></div>
  <?php endif; ?>
  <?php if($success): ?>
    <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
  <?php endif; ?>

  <form method="post" enctype="multipart/form-data">
    <div class="mb-3">
      <label>Title</label>
      <input class="form-control" name="title" required>
    </div>
    <div class="mb-3">
      <label>Description</label>
      <textarea class="form-control" name="description" rows="2"></textarea>
    </div>
    <div class="mb-3">
      <label>Category</label>
      <select name="category_id" class="form-select" required>
        <option value="">-- Select category --</option>
        <?php while($c=$cats->fetch_assoc()): ?>
          <option value="<?=$c['category_id']?>"><?=htmlspecialchars($c['name'])?></option>
        <?php endwhile; ?>
      </select>
    </div>
    <div class="mb-3">
      <label>File</label>
      <input class="form-control" type="file" name="file" 
       accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" required>
    </div>
    <div class="d-flex">
      <button class="btn-upload" type="submit">UPLOAD</button>
      <a href="index.php" class="btn-back">BACK</a>
    </div>
  </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
