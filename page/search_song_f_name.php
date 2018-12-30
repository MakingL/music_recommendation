<?php

# just download the NeteaseMusicAPI.php into directory, require it with the correct path.
# in weapi, you should also put BigInteger.php into same directory, but don't require it.

if (empty(@$_POST['track_name'])) {
    $action_data['code'] = '400';
    $action_data['masg'] = 'Has no track name. Please post track name in post method.';
    echo json_encode($action_data,
      JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES);
    return;
}
if (empty(@$_POST['limit'])) {
    $tracks_limit = 20;
} else {
    $tracks_limit = @$_POST['limit'];
}

$track_name = $_POST['track_name'];

// $track_name = 'MDC   Cockrocker';
// $tracks_limit = 2;


require_once('_addSongInfor.php');
require_once 'NeteaseMusicWeapi.php';

# Initialize
$api = new NeteaseMusicWEAPI();

# Get song detail from song name
$result = $api->search($track_name, $tracks_limit);
// $result = $api->search('清明雨上');

# return JSON, just use it
$data = json_decode($result, true);
$code_result = $data['code'];
$result_song = $data['result'];
$count_song = $result_song['songCount'];

// var_dump($data);
$detail_song['code'] = $code_result;
if ($code_result != '200') {
    $detail_song['code'] = $code_result;
    $detail_song['massg'] = 'Responds error!';
} elseif ($count_song == 0) {
    $detail_song['code'] = '500';
    $detail_song['massg'] = 'has no data!';
} else {
    $data_song  = $result_song['songs'];

    foreach ($data_song as $index => $song_data) {
        // var_dump($song_data);
        if ($index >= $tracks_limit) break;

        $detail['song_name'] = $song_data['name'];
        $detail['song_id'] = $song_data['id'];
        foreach ($song_data['ar'] as $key => $value) {
          if ($key == 0) $artist_name = $value['name'];
          else $artist_name = $artist_name . '/' . $value['name'];
        }
        $detail['artist_name'] = $artist_name;
        $detail['album_name'] = $song_data['al']['name'];
        $detail['album_id'] = $song_data['al']['id'];
        $detail['album_picture'] = $song_data['al']['picUrl'];
        _add_song_information($detail['song_id'], $detail['song_name'],
            $detail['artist_name'], $detail['album_id'],
             $detail['album_name'], $detail['album_picture']);
        $detail_song[$index] = $detail;
        // $detail_song[$index] = json_encode($detail,
        // JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES);
    }
}

$detail_data = json_encode($detail_song,
    JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES);

echo $detail_data;
// var_dump($detail_song);
