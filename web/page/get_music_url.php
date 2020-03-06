<?php

// if (empty(@$_POST['song_id'])) {
//     $action_data['code'] = '400';
//     $action_data['masg'] = 'Has no song_id. Please post song song_id in post method.';
//     echo json_encode($action_data,
//       JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES);
//     return;
// }


function get_music_url($song_id) {
    require_once('_addSongURL.php');
    require_once('_getSongURL.php');

    // 查看本地数据库中是否存在该数据
    $data_db = _get_song_url($song_id);
    $data = json_decode($data_db, true);
    if ($data['code'] == '200') {
        return $data_db;
    }

    require_once 'NeteaseMusicWeapi.php';
    # Initialize
    $api = new NeteaseMusicWEAPI();

    # Get music url to play (array)
    $result = $api->url($song_id); # v2 only
    // $result = $api->url('35847388'); # v2 only

    # return JSON, just use it
    $data = json_decode($result, true);

    $song['code'] = $data['code'];
    // 请求成功
    if ($data['code'] == '200') {
        $result_data = $data['data'];

        $song['song_id'] = $result_data[0]['id'];
        $song['song_url'] = $result_data[0]['url'];
        $song['type'] = $result_data[0]['type'];
        $song['song_md5'] = $result_data[0]['md5'];
        $song['size'] = $result_data[0]['size'];

        _add_track_url($song['song_id'], $song['song_url'], $song['type'], $song['song_md5'], $song['size']);
    }

    $detail_data = json_encode($song,
        JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES);

    return $detail_data;
}

// echo $detail_data;
// var_dump($detail_data);

// $song_id = '553813178';
// get_music_url($song_id);
