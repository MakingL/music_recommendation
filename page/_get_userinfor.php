<?php
//获取session 值
session_start();

if (isset($_SESSION['userid']) && isset($_SESSION['username'])) {
    $result['code'] = "200";
    $result['username'] = $_SESSION['username'];
    $result['userid'] = $_SESSION['userid'];
} else {
    $result['code'] = "400";
}

$detail_data = json_encode($result,
    JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES);
echo $detail_data;
