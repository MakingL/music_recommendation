<?php
# 音乐下载

// if (empty(@$_POST['url'])) {
//     $action_data['code'] = '400';
//     $action_data['masg'] = 'Has no url. Please post id in post method.';
//     echo json_encode($action_data,
//       JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES);
//     return;
// }

// $song_url = $_POST['url'];
// $song_url = 'http://m8c.music.126.net/20180517101046/3e9f3175baefa0a626a33e9e91e5c1d2/ymusic/f127/6c2c/e75f/90f743e7c9ce6a0835cdf27578c88845.mp3';

function download_track($song_url, $track_save_path) {
    require_once 'NeteaseMusicAPI.php';
    $api = new NeteaseMusicAPI();

    # Get data
    $result = $api->download($song_url);

    if (empty($result) || !isset($result)) {
        $action_data['code'] = '500';
        $action_data['masg'] = 'Cannot got track data';
    } else {
        $file =fopen($track_save_path, "wb+");
        fwrite($file, $result);
        fclose($file);

        $action_data['code'] = '200';
        $action_data['masg'] = 'Done';
    }

    return json_encode($action_data,
        JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES);
}

// $song_url = 'http://m8c.music.126.net/20180517161626/3d84c35839e2e7a6ddbf56038ba19228/ymusic/b56f/0b1e/94d6/281ebcccb0606c34cd94898233bd15c9.mp3';
// download_track($song_url, './track_download.mp3');

