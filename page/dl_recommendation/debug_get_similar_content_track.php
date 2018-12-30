<?php
// if (empty(@$_POST['song_id'])) {
//     $action_data['code'] = '400';
//     $action_data['masg'] = 'Has no song_id. Please post song_id.';
//     echo json_encode($action_data,
//       JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES);
//     return;
// }

// $song_id = $_POST['song_id'];
$song_id = '168091';

require_once("../get_music_url.php");
require_once("../download_track.php");

// 获得歌曲 URL
$url_data = get_music_url($song_id);
$url_data = json_decode($url_data, true);

if ($url_data['code'] != '200') {
    $action_data['code'] = '500';
    $action_data['masg'] = 'Cannot got track url.';
    echo json_encode($action_data,
      JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES);
    return;
}

$track_url = $url_data['song_url'];
$track_type = $url_data['type'];

// 下载歌曲
// $track_save_path = "./data/music.mp3";
$track_save_path = "../../music_data/".$song_id.".".$track_type;
if (file_exists($track_save_path) == false) {
    $download_information = download_track($track_url, $track_save_path);
    $download_information = json_decode($download_information, true);
    if ($download_information['code'] != '200') {
      $action_data['code'] = '500';
      $action_data['masg'] = 'Download music failed.';
      echo json_encode($action_data,
        JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES);
      return;
    }
}

// 得到下载好的歌曲文件路径
$path_track = realpath($track_save_path);
if ($path_track == false) {
    // 程序运行出错
    $action_data['code'] = '500';
    $action_data['masg'] = 'Track file not exit.';
    echo json_encode($action_data,
      JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES);
    return;
}

// 执行 python 脚本
$command_recommenda = "python3 ./recommend_similar_track.py ".$path_track." 2>&1";

$recommend_result = exec($command_recommenda, $arr, $ret);

$result_data['code'] = '200';
$result_data['recommendations'] = json_decode($recommend_result, true);
echo json_encode($result_data,
      JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES);

var_dump($recommend_result);
var_dump($return);
var_dump($arr);

