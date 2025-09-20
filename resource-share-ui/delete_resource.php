<?php
session_start(); require 'db.php';
if (!isset($_SESSION['user_id'])) { header('Location: login.php'); exit; }
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$stmt = $mysqli->prepare('SELECT file_path, user_id FROM resources WHERE resource_id=?');
$stmt->bind_param('i',$id); $stmt->execute(); $res = $stmt->get_result();
if (!$row = $res->fetch_assoc()) die('Not found');
if ($row['user_id'] != $_SESSION['user_id']) die('Forbidden');
@unlink($row['file_path']);
$stmt2 = $mysqli->prepare('DELETE FROM resources WHERE resource_id=?'); $stmt2->bind_param('i',$id); $stmt2->execute();
header('Location: index.php');
?>