<?php
session_start();
require 'db.php';

$logged = isset($_SESSION['user_id']);
$isAdmin = $logged && isset($_SESSION['role']) && $_SESSION['role'] === 'admin';

// Handle selected category
$selectedCategory = isset($_GET['category']) ? intval($_GET['category']) : 0;
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Dashboard — ResourceShare</title>
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
    .dropdown-menu {
      min-width: 180px;
      font-size: 14px;
    }
    .dropdown-menu a {
      cursor: pointer;
    }
    /* Buttons */
    .action-buttons {
      text-align: center;
      margin: 30px 0;
    }
    .btn-green {
      background-color: #32cd32;
      border: none;
      color: #fff;
      padding: 10px 20px;
      margin: 0 5px;
      border-radius: 5px;
      font-weight: 500;
    }
    .btn-green:hover {
      background-color: #28a428;
      color: #fff;
    }
    /* Layout */
    .content-area {
      max-width: 1100px;
      margin: auto;
      display: flex;
      gap: 20px;
    }
    .left-col {
      flex: 2;
    }
    .right-col {
      flex: 1;
      display: flex;
      flex-direction: column;
      gap: 20px;
    }
    /* Cards */
    .card-box {
      background: #f7f7f7;
      border-radius: 5px;
      padding: 20px;
      box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }
    .list-group-item {
      border: none;
      margin-bottom: 10px;
      border-radius: 5px;
      background: #fff;
      box-shadow: 0 1px 2px rgba(0,0,0,0.1);
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

<!-- Buttons -->
<div class="action-buttons">
  <a href="resources.php" class="btn btn-green">Browse Resources</a>
  <?php if($logged): ?>
    <a href="upload.php" class="btn btn-green">Upload a Resource</a>
  <?php endif; ?>
  <?php if($isAdmin): ?>
    <a href="admin.php" class="btn btn-green">Admin Panel</a>
  <?php endif; ?>
</div>

<!-- Content area -->
<div class="content-area">
  <!-- Left -->
  <div class="left-col">
    <div class="card-box mb-3">
      <h5>Recent Uploads</h5>
      <div class="list-group">
        <?php
        $sql = "SELECT r.resource_id, r.title, r.description, r.uploaded_at, u.username, c.name AS category
                FROM resources r
                JOIN users u ON r.user_id=u.user_id
                LEFT JOIN categories c ON r.category_id=c.category_id";
        if ($selectedCategory > 0) {
          $sql .= " WHERE r.category_id = $selectedCategory";
        }
        $sql .= " ORDER BY r.uploaded_at DESC LIMIT 6";

        $q = $mysqli->query($sql);
        while($row = $q->fetch_assoc()):
        ?>
        <a class="list-group-item list-group-item-action" href="resource_view.php?id=<?= $row['resource_id'] ?>">
          <div class="d-flex justify-content-between">
            <strong><?= htmlspecialchars($row['title']) ?></strong>
            <small class="text-muted"><?= $row['uploaded_at'] ?></small>
          </div>
          <p class="small mb-1"><?= htmlspecialchars(substr($row['description'],0,120)) ?>...</p>
          <small class="text-muted">By <?= htmlspecialchars($row['username']) ?> • <?= htmlspecialchars($row['category'] ?: 'Uncategorized') ?></small>
        </a>
        <?php endwhile; ?>
      </div>
    </div>
  </div>

  <!-- Right -->
  <div class="right-col">
    <div class="card-box">
      <h6>Categories</h6>
      <form method="get" action="">
        <select class="form-select" name="category" onchange="this.form.submit()">
          <option value="0">-- All Categories --</option>
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
      </form>
    </div>

    <div class="card-box">
      <h6>Tips</h6>
      <p class="small">✔ Upload only high-quality resources.<br>✔ Use clear titles and descriptions.<br>✔ Be respectful when sharing content.</p>
    </div>
  </div>
</div>

</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
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
