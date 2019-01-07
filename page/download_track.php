<?php
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


