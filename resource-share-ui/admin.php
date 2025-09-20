<?php
include 'db.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// Handle deletion
if (isset($_GET['delete'])) {
    $resource_id = intval($_GET['delete']);

    // Delete from reports first
    $mysqli->query("DELETE FROM reports WHERE resource_id=$resource_id");

    // Delete the resource itself
    $res = $mysqli->query("SELECT file_path FROM resources WHERE resource_id=$resource_id");
    if ($row = $res->fetch_assoc()) {
        $filepath = __DIR__ . "/uploads/" . $row['file_path'];
        if (file_exists($filepath)) {
            unlink($filepath); // remove file from server
        }
    }
    $mysqli->query("DELETE FROM resources WHERE resource_id=$resource_id");

    header("Location: admin.php?msg=Resource+deleted");
    exit;
}

$result = $mysqli->query("SELECT r.report_id, r.reason, rs.resource_id, rs.title, u.username 
                          FROM reports r 
                          JOIN resources rs ON r.resource_id = rs.resource_id 
                          JOIN users u ON r.user_id = u.user_id 
                          ORDER BY r.created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Report Resources - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background:#fff; font-family:'Segoe UI', sans-serif; }
        .topbar {
            background:#000; border-radius:40px; padding:12px 30px;
            margin:20px auto 10px; max-width:1000px;
            display:flex; align-items:left; justify-content:left;
            color:#fff;
        }
        .topbar h2 { font-size:20px; font-weight:600; margin:0; }
        .action-buttons {
            display: flex;
            justify-content: flex-end;
            margin:10px auto 20px;
            max-width:1000px;
        }
        .btn-green {
            background:#32cd32; color:#fff; font-weight:600;
            border:none; border-radius:5px; padding:8px 20px;
            margin-right:10px;
        }
        .btn-green:hover { background:#28a428; }
        .btn-red {
            background:#dc3545; color:#fff; font-weight:600;
            border:none; border-radius:5px; padding:8px 20px;
        }
        .btn-red:hover { background:#b52a36; }
        .table-box {
            max-width:1000px; margin:20px auto; background:#f9f9f9;
            border-radius:8px; box-shadow:0 2px 4px rgba(0,0,0,0.1);
            padding:15px;
        }
        table {
            width:100%; border-collapse:collapse;
        }
        th, td {
            padding:12px; text-align:left; border:1px solid #ddd;
        }
        th { background:#f2f2f2; font-weight:600; }
    </style>
</head>
<body>

<!-- Header -->
<div class="topbar">
    <h2>Report Resources</h2>
</div>

<!-- Buttons below the header (left-aligned) -->
<div class="action-buttons">
    <a href="index.php" class="btn-green">View Index Page</a>
    <a href="logout.php" class="btn-red">Log out</a>
</div>

<div class="table-box">
    <?php if (isset($_GET['msg'])): ?>
        <div class="alert alert-success"><?= htmlspecialchars($_GET['msg']) ?></div>
    <?php endif; ?>

    <table>
        <thead>
            <tr>
                <th>Report ID</th>
                <th>Resource Title</th>
                <th>Reported By</th>
                <th>Reason</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $row['report_id'] ?></td>
                <td><?= htmlspecialchars($row['title']) ?></td>
                <td><?= htmlspecialchars($row['username']) ?></td>
                <td><?= htmlspecialchars($row['reason']) ?></td>
                <td>
                    <a href="admin.php?delete=<?= $row['resource_id'] ?>" 
                       onclick="return confirm('Are you sure you want to delete this resource?');" 
                       class="btn btn-danger btn-sm">Delete Resource</a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
