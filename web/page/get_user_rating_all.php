<?php

if (empty(@$_POST['user_id'])) {
    $action_data['code'] = '400';
    $action_data['masg'] = 'Has not user id data.';
    echo json_encode($action_data,
      JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES);
    return;
}

$user_id = $_POST['user_id'];

// $user_id = "7147fe199c61be0625a5323e24617375";
// $track_id = "167878";

include("_connectDB.php");
// 查看数据库中是否有该用户对该歌曲的评分
$sql = "SELECT song_information.song_name,
                song_information.artist_name,
                song_information.album_name,
                user_rating.rating
        FROM user_rating, song_information
        WHERE uid='$user_id'
            AND user_rating.song_id = song_information.song_id";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // 输出每行数据
    $ret = array();
    while($row = $result->fetch_assoc()) {
        array_push($ret, $row);
    }

    $result_data['code'] = '200';
    $result_data['ratings'] = $ret;
    echo json_encode($result_data,
      JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES);
    return;

} else {
    $action_data['code'] = '500';
    $action_data['masg'] = 'Has no ratings.';
    echo json_encode($action_data,
      JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES);
    return;
}

$conn->close();
?>

