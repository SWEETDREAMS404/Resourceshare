<?php
session_start(); require 'db.php';
if (!isset($_SESSION['user_id'])) { header('Location: login.php'); exit; }
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $rid = (int)$_POST['resource_id'];
    $rating = (int)$_POST['rating'];
    $comment = trim($_POST['comment']);
    if ($rating < 1 || $rating > 5) { header('Location: resource_view.php?id='.$rid); exit; }
    $stmt = $mysqli->prepare('INSERT INTO ratings (resource_id,user_id,rating,comment) VALUES (?,?,?,?)');
    $uid = $_SESSION['user_id'];
    $stmt->bind_param('iiis', $rid, $uid, $rating, $comment);
    $stmt->execute();
    header('Location: resource_view.php?id='.$rid);
}
?>