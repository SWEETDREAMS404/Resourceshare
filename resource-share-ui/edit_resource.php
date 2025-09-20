<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$stmt = $mysqli->prepare("SELECT * FROM resources WHERE resource_id=? AND user_id=?");
$stmt->bind_param("ii", $id, $_SESSION['user_id']);
$stmt->execute();
$res = $stmt->get_result();

if (!$row = $res->fetch_assoc()) {
    die("Resource not found or you don't have permission to edit it.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $category_id = !empty($_POST['category_id']) ? (int)$_POST['category_id'] : null;

    $stmt = $mysqli->prepare("UPDATE resources SET title=?, description=?, category_id=? WHERE resource_id=? AND user_id=?");
    $stmt->bind_param("ssiii", $title, $description, $category_id, $id, $_SESSION['user_id']);
    if ($stmt->execute()) {
        header("Location: resource_view.php?id=$id&updated=1");
        exit;
    } else {
        $error = "Failed to update resource.";
    }
}
?>

<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Edit Resource - ResourceShare</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
  <h3>Edit Resource</h3>
  <?php if (!empty($error)): ?><div class="alert alert-danger"><?=$error?></div><?php endif; ?>
  <form method="post">
    <div class="mb-3">
      <label class="form-label">Title</label>
      <input class="form-control" type="text" name="title" value="<?=htmlspecialchars($row['title'])?>" required>
    </div>
    <div class="mb-3">
      <label class="form-label">Description</label>
      <textarea class="form-control" name="description" required><?=htmlspecialchars($row['description'])?></textarea>
    </div>
    <div class="mb-3">
      <label class="form-label">Category</label>
      <select class="form-select" name="category_id">
        <option value="">Uncategorized</option>
        <?php
        $cats = $mysqli->query("SELECT * FROM categories ORDER BY name");
        while ($cat = $cats->fetch_assoc()):
        ?>
          <option value="<?=$cat['category_id']?>" <?=($row['category_id']==$cat['category_id'])?'selected':''?>><?=htmlspecialchars($cat['name'])?></option>
        <?php endwhile; ?>
      </select>
    </div>
    <button class="btn btn-primary" type="submit">Save Changes</button>
    <a class="btn btn-secondary" href="resource_view.php?id=<?=$id?>">Cancel</a>
  </form>
</div>
</body>
</html>
