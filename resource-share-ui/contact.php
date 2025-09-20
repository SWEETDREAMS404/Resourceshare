<?php session_start(); ?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Contact Us — ResourceShare</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
  <div class="container">
    <a class="navbar-brand" href="index.php">ResourceShare</a>
    <div class="d-flex">
      <a class="btn btn-outline-light me-2" href="landing.php">Back</a>
      <a class="btn btn-outline-light me-2" href="about.php">About</a>
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
    <h2>Contact Us</h2>
    <p>
      Have questions, feedback, or suggestions? We’d love to hear from you. 
      Get in touch with our team using the information below:
    </p>

    <ul class="list-group mb-3">
      <li class="list-group-item"><strong>Email:</strong> support@resourceshare.com</li>
      <li class="list-group-item"><strong>Phone:</strong> +63 912 345 6789</li>
      <li class="list-group-item"><strong>Address:</strong> ResourceShare HQ, Manila, Philippines</li>
    </ul>

    <h5>Send us a message:</h5>
    <form>
      <div class="mb-3">
        <label class="form-label">Your Name</label>
        <input type="text" class="form-control" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Your Email</label>
        <input type="email" class="form-control" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Message</label>
        <textarea class="form-control" rows="4" required></textarea>
      </div>
      <button type="submit" class="btn btn-primary">Send</button>
    </form>
  </div>
</div>
</body>
</html>
