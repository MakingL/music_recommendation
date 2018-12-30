<?php

$servername = "localhost";
$username_DB_user = "music_system";
$password_DB_user = "music_system";
$dbname = "music_recommender";

// 创建连接
$conn = @new mysqli($servername, $username_DB_user, $password_DB_user, $dbname);
// 检测连接
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$conn->query("SET NAMES 'utf8'");

?>
