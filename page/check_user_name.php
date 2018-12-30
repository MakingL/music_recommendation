<?php

//检查用户名是否重复
$username = htmlspecialchars($_POST['user_name']);

//包含数据库连接文件
require_once('_connectDB.php');

$sql = "SELECT *
        FROM user_information
        WHERE user_name='$username'";

$res = $conn->query($sql);
$row = $res->fetch_assoc();

if($row){
    // 该用户名已经存在
    $result['code'] = '500';
    $detail_data = json_encode($result,
    JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES);
    echo $detail_data;
} else {
    $result['code'] = '200';
    $detail_data = json_encode($result,
    JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES);
    echo $detail_data;
}

$conn->close();
