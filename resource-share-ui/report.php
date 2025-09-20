<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $resource_id = (int)$_POST['resource_id'];
    $reason = trim($_POST['reason']);
    $user_id = $_SESSION['user_id'];

    if ($reason !== '') {
        $stmt = $mysqli->prepare("INSERT INTO reports (resource_id, user_id, reason, created_at) VALUES (?, ?, ?, NOW())");
        $stmt->bind_param('iis', $resource_id, $user_id, $reason);
        $stmt->execute();
    }
    header("Location: resource_view.php?id=$resource_id&reported=1");
    exit;
}
?>