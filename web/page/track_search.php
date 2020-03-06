<?php
require_once('_search_song_f_name.php');

if (empty(@$_POST['name'])) {
    $action_data['code'] = '400';
    $action_data['masg'] = 'Has no track name. Please post track name in post method.';
    echo json_encode($action_data,
      JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES);
    return;
}

$track_name = $_POST['name'];
if (empty(@$_POST['limit'])) {
    $tracks_limit = 20;
} else {
    $tracks_limit = @$_POST['limit'];
}

$_search_song_f_name($track_name, $tracks_limit)

