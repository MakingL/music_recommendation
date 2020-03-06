<?php
# just download the NeteaseMusicAPI.php into directory, require it with the correct path.
# in weapi, you should also put BigInteger.php into same directory, but don't require it.

if (empty(@$_POST['id'])) {
    $action_data['code'] = '400';
    $action_data['masg'] = 'Has no id. Please post id in post method.';
    echo json_encode($action_data,
      JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES);
    return;
}

$song_id = $_POST['id'];
// $song_id = '494865824';


require_once 'NeteaseMusicWeapi.php';

$api = new NeteaseMusicWEAPI();

# Get data
$result = $api->similar_song($song_id);

# return JSON, just use it
$data = json_decode($result, true);

// var_dump($data);

$similar_song['code'] = $data['code'];
$song_d = $data['songs'];

foreach ($song_d as $index => $song_data) {

    $detail['song_name'] = $song_data['name'];
    $detail['song_id'] = $song_data['id'];
    foreach ($song_data['artists'] as $key => $value) {
      if ($key == 0) $artist_name = $value['name'];
      else $artist_name = $artist_name . '/' . $value['name'];
    }
    $detail['artist_name'] = $artist_name;
    $detail['album_name'] = $song_data['album']['name'];
    $detail['album_id'] = $song_data['album']['id'];
    $detail['album_picture'] = $song_data['album']['picUrl'];
    $detail['algorithm'] = $song_data['alg'];

    $similar_song[$index] = $detail;
}


$detail_data = json_encode($similar_song,
      JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES);

echo $detail_data;

