<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $rating_id   = (int)$_POST['rating_id'];
    $comment     = trim($_POST['comment']);
    $user_id     = $_SESSION['user_id'];
    $resource_id = isset($_POST['resource_id']) ? (int)$_POST['resource_id'] : 0;

    if ($comment !== '') {
        // Only allow editing own comment
        $stmt = $mysqli->prepare("UPDATE ratings SET comment=? WHERE rating_id=? AND user_id=?");
        $stmt->bind_param("sii", $comment, $rating_id, $user_id);
        $stmt->execute();
    }

    // Redirect back to resource view with correct resource id
    header("Location: resource_view.php?id=" . $resource_id);
    exit;
}

// If accessed directly
header("Location: resources.php");
exit;
