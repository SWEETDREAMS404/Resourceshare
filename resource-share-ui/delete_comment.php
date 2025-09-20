<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $rating_id = (int)$_POST['rating_id'];
    $user_id   = $_SESSION['user_id'];

    // Only delete own comment
    $stmt = $mysqli->prepare("DELETE FROM ratings WHERE rating_id=? AND user_id=?");
    $stmt->bind_param("ii", $rating_id, $user_id);
    $stmt->execute();
}

// Redirect back
header("Location: " . $_SERVER['HTTP_REFERER']);
exit;
