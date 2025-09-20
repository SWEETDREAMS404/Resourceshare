<?php
session_start(); 
require 'db.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$stmt = $mysqli->prepare('SELECT r.*, u.username, c.name AS category 
                          FROM resources r 
                          JOIN users u ON r.user_id=u.user_id 
                          LEFT JOIN categories c ON r.category_id=c.category_id 
                          WHERE r.resource_id=?');
$stmt->bind_param('i',$id); 
$stmt->execute(); 
$res = $stmt->get_result();
if (!$row = $res->fetch_assoc()) { die('Resource not found'); }

$stmt2 = $mysqli->prepare('SELECT rt.*, u.username 
                           FROM ratings rt 
                           JOIN users u ON rt.user_id=u.user_id 
                           WHERE rt.resource_id=? 
                           ORDER BY rt.created_at DESC');
$stmt2->bind_param('i',$id); 
$stmt2->execute(); 
$ratings = $stmt2->get_result();

// helper to detect previewable types
function is_previewable($mime) {
    return strpos($mime, 'image/') === 0 || $mime === 'application/pdf';
}
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<title><?=htmlspecialchars($row['title'])?> — ResourceShare</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
    body { background:#fff; font-family:'Segoe UI', sans-serif; padding-top:20px; }
    .topbar {
        background:#000; border-radius:40px; padding:12px 30px;
        margin:20px auto 10px; max-width:1000px;
        display:flex; align-items:flex-start; justify-content:left;
        color:#fff;
    }
    .topbar h2 { font-size:20px; font-weight:600; margin:0; }
    .action-buttons {
        display:flex; justify-content:flex-end;
        margin:10px auto 20px; max-width:1000px;
    }
    .btn-green {
        background:#32cd32; color:#fff; font-weight:600;
        border:none; border-radius:5px; padding:8px 20px; margin-right:10px;
    }
    .btn-green:hover { background:#28a428; }
    .btn-blue {
        background:#007bff; color:#fff; font-weight:600;
        border:none; border-radius:5px; padding:8px 20px;
    }
    .btn-blue:hover { background:#0069d9; }
    .btn-red {
        background:#dc3545; color:#fff; font-weight:600;
        border:none; border-radius:5px; padding:8px 20px;
    }
    .btn-red:hover { background:#b52a36; }
    .content-box {
        max-width:1000px; margin:20px auto; background:#f9f9f9;
        border-radius:8px; box-shadow:0 2px 4px rgba(0,0,0,0.1);
        padding:20px;
    }
    .file-meta { font-size:14px; color:#555; margin:5px 0 0; }
    .dropdown-toggle::after { display:none; } /* remove default arrow */
</style>
</head>
<body>

<!-- Header -->
<div class="topbar">
    <h2>Resource Details</h2>
</div>

<!-- Buttons under header -->
<div class="action-buttons">
    <a href="resources.php" class="btn-green">⬅ Back</a>
    <?php if(isset($_SESSION['user_id'])): ?>
        <a href="upload.php" class="btn-blue">Upload</a>
    <?php endif; ?>
</div>

<!-- Resource card -->
<div class="content-box">
  <?php if (isset($_GET['reported']) && $_GET['reported'] == 1): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      ✅ Resource has been successfully reported.
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  <?php endif; ?>

  <div class="d-flex justify-content-between">
      <div>
        <h3><?=htmlspecialchars($row['title'])?></h3>
        <p class="file-meta">By <?=htmlspecialchars($row['username'])?> • <?=htmlspecialchars($row['category']?:'Uncategorized')?> • <?= $row['uploaded_at'] ?></p>
      </div>
      <div class="text-end">
        <a class="btn btn-success" href="<?=htmlspecialchars($row['file_path'])?>" download>Download</a>
        <?php if(isset($_SESSION['user_id']) && $_SESSION['user_id']==$row['user_id']): ?>
          <a class="btn btn-warning" href="edit_resource.php?id=<?=$row['resource_id']?>">Edit</a>
          <a class="btn btn-danger" href="delete_resource.php?id=<?=$row['resource_id']?>">Delete</a>
        <?php endif; ?>
      </div>
  </div>
  <hr>
  <p><?=nl2br(htmlspecialchars($row['description']))?></p>

  <?php if(is_previewable($row['file_mime'])): ?>
    <div class="mt-3">
      <?php if(strpos($row['file_mime'],'image/') === 0): ?>
        <img src="<?=htmlspecialchars($row['file_path'])?>" class="img-fluid" alt="Preview">
      <?php elseif($row['file_mime'] === 'application/pdf'): ?>
        <embed src="<?=htmlspecialchars($row['file_path'])?>" type="application/pdf" width="100%" height="700px" />
      <?php endif; ?>
    </div>
  <?php endif; ?>
</div>

<!-- Ratings -->
<div class="content-box">
  <h5>Ratings & Comments</h5>
  <?php if(isset($_SESSION['user_id'])): ?>
    <form method="post" action="add_rating.php">
      <input type="hidden" name="resource_id" value="<?=$row['resource_id']?>">
      <div class="mb-2"><label class="form-label">Rating (1-5)</label><input class="form-control" type="number" name="rating" min="1" max="5" required></div>
      <div class="mb-2"><label class="form-label">Comment</label><textarea class="form-control" name="comment"></textarea></div>
      <button class="btn btn-primary" type="submit">Submit</button>
    </form>
  <?php else: ?>
    <p class="small">Please <a href="login.php">login</a> to submit a rating.</p>
  <?php endif; ?>
  <hr>
  <?php while($rt = $ratings->fetch_assoc()): ?>
    <div class="mb-2 d-flex justify-content-between align-items-start">
      <div>
        <strong><?=htmlspecialchars($rt['username'])?></strong> — <span class="text-warning"><?=$rt['rating']?>★</span>
        <p class="small mb-1"><?=nl2br(htmlspecialchars($rt['comment']))?></p>
        <p class="small text-muted"><?=$rt['created_at']?></p>
      </div>

      <?php if(isset($_SESSION['user_id']) && $_SESSION['user_id'] == $rt['user_id']): ?>
        <div class="dropdown">
          <button class="btn btn-sm btn-light" type="button" data-bs-toggle="dropdown" aria-expanded="false">⋮</button>
          <ul class="dropdown-menu dropdown-menu-end">
            <li>
              <a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#editCommentModal<?=$rt['rating_id']?>">✏️ Edit</a>
            </li>
            <li>
              <form method="post" action="delete_comment.php" onsubmit="return confirm('Delete this comment?');">
                <input type="hidden" name="rating_id" value="<?=$rt['rating_id']?>">
                <button type="submit" class="dropdown-item text-danger">🗑 Delete</button>
              </form>
            </li>
          </ul>
        </div>

        <!-- Edit Comment Modal -->
        <div class="modal fade" id="editCommentModal<?=$rt['rating_id']?>" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="post" action="edit_comment.php">
        <div class="modal-header">
          <h5 class="modal-title">Edit Comment</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="rating_id" value="<?=$rt['rating_id']?>">
          <input type="hidden" name="resource_id" value="<?=$row['resource_id']?>">
          <textarea class="form-control" name="comment" required><?=htmlspecialchars($rt['comment'])?></textarea>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Save Changes</button>
        </div>
      </form>
    </div>
  </div>
</div>
      <?php endif; ?>
    </div>
    <hr>
  <?php endwhile; ?>
</div>

<!-- Report -->
<div class="content-box">
  <h5>Report this resource</h5>
  <?php if(isset($_SESSION['user_id'])): ?>
    <form method="post" action="report.php">
      <input type="hidden" name="resource_id" value="<?=$row['resource_id']?>">
      <div class="mb-2"><label class="form-label">Reason</label><textarea class="form-control" name="reason" required></textarea></div>
      <button class="btn btn-warning" type="submit">Report</button>
    </form>
  <?php else: ?>
    <p class="small">Please <a href="login.php">login</a> to report.</p>
  <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
