<?php
$host = 'localhost';
$user = 'root';
$pass = '';
$db   = 'resource_share';
$mysqli = new mysqli($host, $user, $pass, $db);
if ($mysqli->connect_errno) {
    die('DB Connect Error: ' . $mysqli->connect_error);
}
$mysqli->set_charset('utf8mb4');

function esc($s){
    global $mysqli;
    return htmlspecialchars($mysqli->real_escape_string($s));
}
?>
