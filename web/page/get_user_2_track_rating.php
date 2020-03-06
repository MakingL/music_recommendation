<?php

if (empty(@$_POST['track_id']) || empty(@$_POST['user_id'])) {
    $action_data['code'] = '400';
    $action_data['masg'] = 'Has not enough data.';
    echo json_encode($action_data,
      JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES);
    return;
}

$user_id = $_POST['user_id'];
$track_id = $_POST['track_id'];

// $user_id = "7147fe199c61be0625a5323e24617375";
// $track_id = "167878";

include("_connectDB.php");
// 查看数据库中是否有该用户对该歌曲的评分
$sql = "SELECT rating
        FROM user_rating
        WHERE uid='$user_id' AND song_id='$track_id'";
$res = $conn->query($sql);
$row = $res->fetch_assoc();

if (!empty($row)) {
    $rating = $row['rating'];
    $action_data['code'] = '200';
    $action_data['rating'] = $rating;
    echo json_encode($action_data,
      JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES);
    return;
} else {
    $action_data['code'] = '500';
    $action_data['masg'] = 'Has no rating.';
    echo json_encode($action_data,
      JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES);
    return;
}

$conn->close();
?>

