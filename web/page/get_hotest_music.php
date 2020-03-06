<?php
# just download the NeteaseMusicAPI.php into directory, require it with the correct path.
# in weapi, you should also put BigInteger.php into same directory, but don't require it.
require_once 'NeteaseMusicWeapi.php';
require_once('_addSongInfor.php');

if (empty(@$_POST['limit'])) {
    $limit = 20;
} else {
    $limit = $_POST['limit'];
}

# Initialize
$api = new NeteaseMusicWEAPI();

# Get music url to play (array)
# 网易云热门歌曲
$result = $api->topList('1');

# return JSON, just use it
$data = json_decode($result, true);

$song_detail['code'] = $data['code'];

$result_data = $data['result'];
// var_dump($result_data);
$tracks_data = $result_data['tracks'];

foreach ($tracks_data as $index => $song_data) {
    if ($index >= $limit) break;

    $detail['song_name'] = $song_data['name'];
    $detail['song_id'] = $song_data['id'];
    foreach ($song_data['artists'] as $key => $value) {
      if ($key == 0) $artist_name = $value['name'];
      else $artist_name = $artist_name . '/' . $value['name'];
    }
    $detail['artist_name'] = $artist_name;
    $detail['album_id'] = $song_data['album']['id'];
    $detail['album_name'] = $song_data['album']['name'];
    $detail['album_picture'] = $song_data['album']['picUrl'];

    _add_song_information($detail['song_id'], $detail['song_name'], $detail['artist_name'], $detail['album_id'], $detail['album_name'], $detail['album_picture']);
    $song_detail[$index] = $detail;
}

$detail_data = json_encode($song_detail,
    JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES);

echo $detail_data;
// var_dump($detail_data);
