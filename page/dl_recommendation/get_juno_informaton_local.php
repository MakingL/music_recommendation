<?php

if (empty(@$_POST['juno_id']) ) {
    $action_data['code'] = '400';
    $action_data['masg'] = 'Has not juno id.';
    echo json_encode($action_data,
      JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES);
    return;
}

if (empty(@$_POST['track_url']) ) {
    $action_data['code'] = '400';
    $action_data['masg'] = 'Has not track_url.';
    echo json_encode($action_data,
      JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES);
    return;
}


$track_id = $_POST['juno_id'];
$track_url = $_POST['track_url'];

$track_save_path = "../../juno_track_data/".$track_id.".mp3";
// $track_save_path = "../../juno_track_data/369419.mp3";
if (file_exists($track_save_path) == false) {
    // 下载歌曲
    // 执行 python 脚本
    $command_recommenda = "python ./download_juno_music.py ".$track_url." ".$track_save_path." 2>&1";

    $recommend_result = exec($command_recommenda, $arr, $ret);
    if ($recommend_result != "200") {
        $tack_playing_data['code'] = '500';
        $tack_playing_data['masg'] = "Download juno data failed!";

        echo json_encode($tack_playing_data,
              JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES);
        return;
    }
}

// 所有数据均正常获取
$tack_playing_data['code'] = '200';
$tack_playing_data['track_data_url'] = "http://music.makepace.top/juno_track_data/".$track_id.".mp3";

echo json_encode($tack_playing_data,
      JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES);

?>

