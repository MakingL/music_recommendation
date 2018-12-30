<?php

if (empty(@$_POST['track_id']) || empty(@$_POST['user_id']) ||empty(@$_POST['rating'])) {
    $action_data['code'] = '400';
    $action_data['masg'] = 'Has not enough data.';
    echo json_encode($action_data,
      JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES);
    return;
}

$record_id = md5(uniqid());
$user_id = $_POST['user_id'];
$track_id = $_POST['track_id'];
$rating = $_POST['rating'];

// $record_id = md5(uniqid());
// $user_id = "7147fe199c61be0625a5323e24617375";
// $track_id = "167878";
// $rating = "4";

include("_connectDB.php");
// 先查看数据库中是否有该用户对该歌曲的评分
$sql = "SELECT record_id
        FROM user_rating
        WHERE uid='$user_id' AND song_id='$track_id'";
$res = $conn->query($sql);
$row = $res->fetch_assoc();

if ($row == null) {
    $sql = "INSERT INTO user_rating (record_id, uid, song_id, rating)
            VALUES ('$record_id', '$user_id', '$track_id', '$rating')";

    $result_insert = $conn->query($sql);
    if ($result_insert === TRUE) {
        $action_data['code'] = '200';
        $action_data['masg'] = 'Insert action successed.';
        echo json_encode($action_data,
          JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES);
        return;
    } else {
        $action_data['code'] = '500';
        $action_data['masg'] = 'Insert action failed.';
        echo json_encode($action_data,
          JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES);
        return;
    }

} else {
    $record_id = $row['record_id'];
    $sql = "UPDATE user_rating
            SET rating='$rating'
            WHERE record_id='$record_id'";

    $result_insert = $conn->query($sql);
    if ($result_insert === TRUE) {
        $action_data['code'] = '200';
        $action_data['masg'] = 'Update action successed.';
        echo json_encode($action_data,
          JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES);
        return;
    } else {
        $action_data['code'] = '500';
        $action_data['masg'] = 'Update action failed.';
        echo json_encode($action_data,
          JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES);
        return;
    }
}

$conn->close();
?>

