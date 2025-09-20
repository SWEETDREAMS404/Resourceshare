<?php
session_start();
require 'db.php';

$logged = isset($_SESSION['user_id']);
$isAdmin = $logged && isset($_SESSION['role']) && $_SESSION['role'] === 'admin';

// Handle search
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$selectedCategory = isset($_GET['category']) ? intval($_GET['category']) : 0;
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Resources — ResourceShare</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: #fff;
      font-family: 'Segoe UI', sans-serif;
    }
    /* Top header */
    .topbar {
      background: #000;
      border-radius: 40px;
      padding: 10px 30px;
      margin: 20px auto;
      max-width: 1100px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      color: #fff;
    }
    .topbar .brand {
      font-weight: 700;
      font-size: 20px;
      text-transform: uppercase;
    }
    .topbar .user-info {
      display: flex;
      align-items: center;
      gap: 10px;
      position: relative;
    }
    .topbar .user-info img {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      object-fit: cover;
      border: 2px solid #fff;
    }
    .search-bar {
      max-width: 1100px;
      margin: 10px auto 20px;
      display: flex;
      gap: 10px;
      align-items: center;
      padding: 0 10px;
    }
    .search-bar input, .search-bar select {
      height: 45px;
      border-radius: 8px;
    }
    .search-bar button {
      height: 45px;
      border-radius: 8px;
      background: #000;
      color: #fff;
      padding: 0 20px;
      border: none;
    }
    .content-area {
      max-width: 1100px;
      margin: auto;
      display: flex;
      gap: 20px;
    }
    .left-col {
      flex: 2;
      display: flex;
      flex-direction: column;
      gap: 15px;
    }
    .right-col {
      flex: 1;
      display: flex;
      flex-direction: column;
      gap: 20px;
    }
    .card-box {
      background: #f7f7f7;
      border-radius: 5px;
      padding: 20px;
      box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }
    .resource-card {
      background: #f7f7f7;
      border-radius: 5px;
      padding: 15px;
      box-shadow: 0 1px 2px rgba(0,0,0,0.1);
    }
    .btn-green {
      background-color: #32cd32;
      border: none;
      color: #fff;
      padding: 8px 16px;
      margin: 2px;
      border-radius: 5px;
      font-weight: 500;
    }
    .btn-green:hover {
      background-color: #28a428;
      color: #fff;
    }
  </style>
</head>
<body>

<!-- Top black rounded header -->
<div class="topbar">
  <div class="brand">RESOURCE SHARE</div>
  <div class="user-info dropdown">
    <span class="dropdown-toggle" id="userMenu" data-bs-toggle="dropdown" aria-expanded="false" style="cursor:pointer;">
      <?php echo $logged ? htmlspecialchars($_SESSION['username']) : 'Guest'; ?>
    </span>
    <img 
      src="<?= ($logged && isset($_SESSION['profile_pic']) && $_SESSION['profile_pic']) 
                ? 'uploads/' . htmlspecialchars($_SESSION['profile_pic']) 
                : 'assets/profile.png' ?>" 
      alt="Profile" 
      class="dropdown-toggle" 
      id="userMenuImg" 
      data-bs-toggle="dropdown" 
      aria-expanded="false" 
      style="cursor:pointer;">
    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userMenu">
      <?php if ($logged): ?>
        <li><a class="dropdown-item" href="manage_account.php">Manage Account</a></li>
        <li><hr class="dropdown-divider"></li>
        <li><a class="dropdown-item text-danger" href="logout.php">Logout</a></li>
      <?php else: ?>
        <li><a class="dropdown-item" href="login.php">Login</a></li>
      <?php endif; ?>
    </ul>
  </div>
</div>

<!-- Search bar -->
<form class="search-bar" method="get" action="">
  <div class="input-group" style="flex:2;">
    <span class="input-group-text bg-white border-end-0"><i class="bi bi-search"></i></span>
    <input type="text" class="form-control border-start-0" name="search" placeholder="Search title or description" value="<?= htmlspecialchars($search) ?>">
  </div>
  <select class="form-select" name="category" style="flex:1;">
    <option value="0">All categories</option>
    <?php
    $cats = $mysqli->query("SELECT * FROM categories");
    while($c = $cats->fetch_assoc()):
      $selected = ($selectedCategory == $c['category_id']) ? 'selected' : '';
    ?>
      <option value="<?= htmlspecialchars($c['category_id']) ?>" <?= $selected ?>>
        <?= htmlspecialchars($c['name']) ?>
      </option>
    <?php endwhile; ?>
  </select>
  <button type="submit">Search</button>
</form>

<!-- Content area -->
<div class="content-area">
  <!-- Left -->
  <div class="left-col">
    <?php
    $sql = "SELECT r.resource_id, r.title, r.description, r.uploaded_at, u.username, c.name AS category
            FROM resources r
            JOIN users u ON r.user_id=u.user_id
            LEFT JOIN categories c ON r.category_id=c.category_id
            WHERE 1=1";
    if ($selectedCategory > 0) {
      $sql .= " AND r.category_id = $selectedCategory";
    }
    if (!empty($search)) {
      $s = $mysqli->real_escape_string($search);
      $sql .= " AND (r.title LIKE '%$s%' OR r.description LIKE '%$s%')";
    }
    $sql .= " ORDER BY r.uploaded_at DESC";

    $q = $mysqli->query($sql);
    if ($q->num_rows > 0):
      while($row = $q->fetch_assoc()):
    ?>
    <div class="resource-card">
      <a href="resource_view.php?id=<?= $row['resource_id'] ?>" style="text-decoration:none; color:inherit;">
        <div class="d-flex justify-content-between">
          <strong><?= htmlspecialchars($row['title']) ?></strong>
          <small class="text-muted"><?= $row['uploaded_at'] ?></small>
        </div>
        <p class="small mb-1"><?= htmlspecialchars(substr($row['description'],0,120)) ?>...</p>
        <small class="text-muted">By <?= htmlspecialchars($row['username']) ?> • <?= htmlspecialchars($row['category'] ?: 'Uncategorized') ?></small>
      </a>
    </div>
    <?php endwhile; else: ?>
      <p class="text-muted">No resources found.</p>
    <?php endif; ?>
  </div>

  <!-- Right -->
  <div class="right-col">
    <div class="d-flex gap-2 mb-3">
      <a href="index.php" class="btn btn-green">Home</a>
      <?php if($logged): ?>
        <a href="upload.php" class="btn btn-green">Upload</a>
      <?php endif; ?>
    </div>

    <div class="card-box">
      <h6>Categories</h6>
      <ul class="list-group list-group-flush">
        <?php
        $cats = $mysqli->query("SELECT * FROM categories");
        while($c = $cats->fetch_assoc()):
        ?>
          <li class="list-group-item">
            <a href="?category=<?= $c['category_id'] ?>" style="text-decoration:none;">
              <?= htmlspecialchars($c['name']) ?>
            </a>
          </li>
        <?php endwhile; ?>
      </ul>
    </div>

    <div class="card-box">
      <h6>Tips</h6>
      <p class="small">✔ Upload only high-quality resources.<br>✔ Use clear titles and descriptions.<br>✔ Be respectful when sharing content.</p>
    </div>
  </div>
</div>

</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
<script>
// Enable hover-to-open dropdowns
document.querySelectorAll('.user-info').forEach(function(el) {
  let dropdownToggle = el.querySelector('[data-bs-toggle="dropdown"]');
  let dropdown = new bootstrap.Dropdown(dropdownToggle);

  el.addEventListener('mouseenter', function() {
    dropdown.show();
  });
  el.addEventListener('mouseleave', function() {
    dropdown.hide();
  });
});
</script>
</html>
