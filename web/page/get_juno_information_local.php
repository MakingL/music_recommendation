<?php

if (empty(@$_POST['juno_id']) ) {
    $action_data['code'] = '400';
    $action_data['masg'] = 'Has not juno id.';
    echo json_encode($action_data,
      JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES);
    return;
}

if (empty(@$_POST['track_url']) ) {
    $action_data['code'] = '400';
    $action_data['masg'] = 'Has not track_url.';
    echo json_encode($action_data,
      JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES);
    return;
}

$track_id = $_POST['juno_id'];
$track_url = $_POST['track_url'];

// $track_id = "276468";
// $track_url = "https://www.junodownload.com/MP3/SF3058645-02-01-04.mp3";

/**
 ** @desc 封装 curl 的调用接口，post的请求方式
 **/
function doCurlPostRequest($url, $data = null)
{
    $curl = curl_init();

    curl_setopt($curl, CURLOPT_URL, $url);
    if ($data) {
        if (is_array($data)) $data = http_build_query($data);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data)
        ));
        curl_setopt($curl, CURLOPT_POST, 1);
    }
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 10);

    $result = curl_exec($curl);
    curl_close($curl);
    return $result;
}

$track_save_path = "../juno_track_data/".$track_id.".mp3";
if (file_exists($track_save_path) == false) {
    // curl($track_url);
    $request_data["msg"] = "download_juno";
    $request_data["track_url"] = $track_url;
    $data = json_encode(
        $request_data,
        JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES
    );
    $result = doCurlPostRequest("http://localhost:6016/download_juno", $data);

    if (empty($result) || $result == "500" ) {
        $tack_playing_data['code'] = '500';
        $tack_playing_data['masg'] = "Download juno data failed!";
        echo json_encode(
            $tack_playing_data,
            JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES
        );
        return;
    } else {
        $file = fopen($track_save_path, "wb+");
        fwrite($file, $result);
        fclose($file);
    }
    // 下载歌曲
    // 执行 python 脚本
    // $command_recommenda = "python ./download_juno_music.py ".$track_url." ".$track_save_path." 2>&1";

    // $recommend_result = exec($command_recommenda, $arr, $ret);
    // if ($recommend_result != "200") {
    //     $tack_playing_data['code'] = '500';
    //     $tack_playing_data['masg'] = "Download juno data failed!";

    //     echo json_encode($tack_playing_data,
    //           JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES);
    //     return;
    // }
}

// 所有数据均正常获取
$tack_playing_data['code'] = '200';
$path_data = "./juno_track_data/" . $track_id . ".mp3 ";
$tack_playing_data['track_data_url'] = $path_data;

echo json_encode(
    $tack_playing_data,
    JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES
);
?>

