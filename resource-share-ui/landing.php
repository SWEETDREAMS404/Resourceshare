<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>ResourceShare - Empowering Communities</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
      background: #fff;
      color: #000;
    }
    .navbar-custom {
      background: #000;
      border-radius: 30px;
      margin: 20px auto;
      width: 90%;
      padding: 10px 30px;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .navbar-custom .navbar-nav {
      width: 100%;
      display: flex;
      justify-content: space-around;
      align-items: center;
    }
    .navbar-custom .nav-link {
      color: #fff !important;
      font-weight: bold;
      font-size: 1rem;
    }
    .navbar-custom .nav-link:hover {
      color: #9fef00 !important;
    }
    .navbar-custom .sun-icon {
      width: 24px;
      height: 24px;
      margin-left: 5px;
    }
    .hero {
      text-align: center;
      margin-top: 60px;
      position: relative;
    }
    .hero h1 {
      font-size: 2.5rem;
      font-weight: bold;
      display: inline-block;
      position: relative;
    }
    .hero .splash-img {
      position: absolute;
      top: -40px;
      left: 100px;
      width: 70px;
    }
    .hero .side-splash {
      position: absolute;
      bottom: -40px;
      left: 0;
      width: 40px;
    }
    .hero p {
      max-width: 400px;
      margin: 20px auto;
      font-size: 0.95rem;
      line-height: 1.6;
      text-align: left;
    }
  </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-custom">
  <ul class="navbar-nav">
    <li class="nav-item"><a class="nav-link" href="about.php">About us</a></li>
    <li class="nav-item"><a class="nav-link" href="contact.php">Contacts</a></li>
    <li class="nav-item d-flex align-items-center">
      <img src="assets/sun.png" alt="Login Icon" class="sun-icon">
      <a class="nav-link" href="login.php">Login</a>
    </li>
    <li class="nav-item"><a class="nav-link" href="register.php">Register</a></li>
  </ul>
</nav>

<!-- Hero Section -->
<div class="hero">
  <img src="assets/splash.png" alt="Splash" class="splash-img">
  <h1>Empowering Communities Through<br>Smarter Resource Sharing</h1>
  <img src="assets/sidesplash.png" alt="Side Splash" class="assets/side-splash">

  <p>
    Resource Share is more than just a platform — it’s a space where collaboration thrives and resources 
    are made accessible for everyone. By simplifying the way people connect, upload, and access materials, 
    our system ensures that communities can work smarter, faster, and more efficiently.
  </p>

  
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
