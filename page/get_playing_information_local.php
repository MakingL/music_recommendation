<?php

if (empty(@$_POST['song_id']) ) {
    $action_data['code'] = '400';
    $action_data['masg'] = 'Has not enough data.';
    echo json_encode($action_data,
      JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES);
    return;
}

$track_id = $_POST['song_id'];
// $track_id = "167876";

require_once("download_track.php");
require_once("_getSongDetail.php");
require_once("get_music_url.php");

$track_detail = _get_song_information($track_id);
$track_detail = json_decode($track_detail, true);
if ($track_detail['code'] != '200') {
    $action_data['code'] = '502';
    $action_data['masg'] = 'Get track details failed.';
    echo json_encode($action_data,
      JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES);
    return;
}

$tack_playing_data['song_id'] = $track_detail['song_id'];
$tack_playing_data['song_name'] = $track_detail['song_name'];
$tack_playing_data['artist_name'] = $track_detail['artist_name'];
$tack_playing_data['album_name'] = $track_detail['album_name'];
$tack_playing_data['album_id'] = $track_detail['album_id'];
$tack_playing_data['album_picture'] = $track_detail['album_picture'];

// 获得歌曲 URL
$url_data = get_music_url($track_id);
$url_data = json_decode($url_data, true);

if ($url_data['code'] != '200') {
    global $tack_playing_data;
    $tack_playing_data['code'] = '400';
    $tack_playing_data['masg'] = 'Cannot got track url.';
    echo json_encode($tack_playing_data,
      JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES);
    return;
}

$track_url = $url_data['song_url'];
$track_type = $url_data['type'];

$track_save_path = "../music_data/".$track_id.".".$track_type;
if (file_exists($track_save_path) == false) {
    // 下载歌曲
    $download_information = download_track($track_url, $track_save_path);
    $download_information = json_decode($download_information, true);
    if ($download_information['code'] != '200') {
        global $tack_playing_data;
        $tack_playing_data['code'] = '500';
        $tack_playing_data['masg'] = 'Download music failed.';
        echo json_encode($tack_playing_data,
          JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES);
        return;
    }
}

// 所有数据均正常获取
$tack_playing_data['code'] = '200';
$tack_playing_data['track_data_url'] = "./music_data/".$track_id.".".$track_type;

echo json_encode($tack_playing_data,
      JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES);

?>

