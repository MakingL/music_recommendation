<?php
# just download the NeteaseMusicAPI.php into directory, require it with the correct path.
# in weapi, you should also put BigInteger.php into same directory, but don't require it.
if (empty(@$_POST['id'])) {
    $action_data['code'] = '400';
    $action_data['masg'] = 'Has no id. Please post id.';
    echo json_encode($action_data,
      JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES);
    return;
}

$song_id = $_POST['id'];
// $song_id = '32019002';
//$song_id = '109734';

require_once('_addSongInfor.php');
require_once('_getSongDetail.php');
require_once 'NeteaseMusicAPI.php';
$api_v0 = new NeteaseMusicAPI();

// 查看本地数据库中是否存在该数据
$data_db = _get_song_information($song_id);
$data = json_decode($data_db, true);
if ($data['code'] == '200') {
  $song_detail = $data_db;
  echo $song_detail;
  return;
}

// 请求远程API
$result_v0 = $api_v0->detail($song_id);

$data = json_decode($result_v0, true);

$song_detail['code'] = $data['code'];

if ($song_detail['code'] == 200) {

    $song_d = $data['songs'];

    foreach ($song_d as $index => $song_data) {

        $detail['song_name'] = $song_data['name'];
        $detail['song_id'] = $song_data['id'];
        foreach ($song_data['artists'] as $key => $value) {
          if ($key == 0) $artist_name = $value['name'];
          else $artist_name = $artist_name . '/' . $value['name'];
        }
        $detail['artist_name'] = $artist_name;
        $detail['album_id'] = $song_data['album']['id'];
        $detail['album_name'] = $song_data['album']['name'];
        $detail['album_picture'] = $song_data['album']['blurPicUrl'];

        _add_song_information($detail['song_id'], $detail['song_name'], $detail['artist_name'], $detail['album_id'], $detail['album_name'], $detail['album_picture']);
        $song_detail[$index] = $detail;
    }
} else {

  require_once 'NeteaseMusicWeapi.php';

  # Initialize
  $api = new NeteaseMusicWEAPI();

  # Get data
  $result = $api->detail($song_id);

  //var_dump($result);
  # return JSON, just use it
  $data = json_decode($result, true);

  //var_dump($data);

  $song_detail['code'] = $data['code'];
  $song_d = $data['songs'];

  foreach ($song_d as $index => $song_data) {

      $detail['song_name'] = $song_data['name'];
      $detail['song_id'] = $song_data['id'];
      foreach ($song_data['ar'] as $key => $value) {
        if ($key == 0) $artist_name = $value['name'];
        else $artist_name = $artist_name . ' / ' . $value['name'];
      }
      $detail['artist_name'] = $artist_name;
      $detail['album_id'] = $song_data['al']['id'];
      $detail['album_name'] = $song_data['al']['name'];
      $detail['album_picture'] = 'NULL';

      _add_song_information($detail['song_id'], $detail['song_name'], $detail['artist_name'], $detail['album_id'], $detail['album_name'], $detail['album_picture']);
      $song_detail[$index] = $detail;
  }
}

$detail_data = json_encode($song_detail,
      JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES);

echo $detail_data;

