<?php session_start(); ?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>About Us — ResourceShare</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
  <div class="container">
    <a class="navbar-brand" href="index.php">ResourceShare</a>
    <div class="d-flex">
      <a class="btn btn-outline-light me-2" href="landing.php">Back</a>
      <a class="btn btn-outline-light me-2" href="contact.php">Contact</a>
      <?php if(isset($_SESSION['user_id'])): ?>
        <a class="btn btn-outline-success" href="resources.php">Resources</a>
      <?php else: ?>
        <a class="btn btn-outline-primary me-2" href="login.php">Login</a>
        <a class="btn btn-success" href="register.php">Register</a>
      <?php endif; ?>
    </div>
  </div>
</nav>

<div class="container" style="margin-top:100px;">
  <div class="card p-4 shadow">
    <h2>About ResourceShare</h2>
    <p>
      ResourceShare is a platform built to empower communities through smarter sharing of knowledge and materials. 
      Our goal is to create a space where collaboration thrives, and educational, technical, and professional resources 
      are accessible to everyone.
    </p>
    <p>
      By simplifying the way people connect, upload, and access files, ResourceShare ensures that students, educators, 
      professionals, and communities can work smarter, faster, and more efficiently. 
    </p>
    <h4>Our Mission</h4>
    <p>
      To provide an inclusive platform that promotes learning, innovation, and collaboration by making resources freely 
      accessible and easy to share.
    </p>
    <h4>Our Vision</h4>
    <p>
      A connected world where communities empower each other through shared resources, knowledge, and opportunities.
    </p>
  </div>
</div>
</body>
</html>
