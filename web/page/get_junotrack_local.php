<?php

if (empty(@$_POST['juno_id'])) {
    $action_data['code'] = '400';
    $action_data['masg'] = 'Has not enough data. Please input juno id ';
    echo json_encode($action_data,
      JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES);
    return;
}

if (empty(@$_POST['track_url'])) {
    $action_data['code'] = '400';
    $action_data['masg'] = 'Has not enough data. Please input track url';
    echo json_encode($action_data,
      JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES);
    return;
}

$track_id = $_POST['juno_id'];
$track_url = $_POST['track_url'];

echo "track_id: ".$track_id;
echo "track_url: ".$track_url;

$track_save_path = "../juno_track_data/".$track_id.".mp3";

// 下载歌曲
// CURL
function curl($url,$data=null){
    $_USERAGENT='Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/35.0.1916.157 Safari/537.36';
    $curl=curl_init();
    curl_setopt($curl,CURLOPT_URL,$url);
    if($data){
        if(is_array($data))$data=http_build_query($data);
        curl_setopt($curl,CURLOPT_POSTFIELDS,$data);
        curl_setopt($curl,CURLOPT_POST,1);
    }
    curl_setopt($curl,CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl,CURLOPT_CONNECTTIMEOUT, 10);
    // curl_setopt($curl,CURLOPT_REFERER,$this->_REFERER);
    // curl_setopt($curl,CURLOPT_COOKIE,$this->_COOKIE);

    curl_setopt($curl,CURLOPT_USERAGENT,$_USERAGENT);
    $result=curl_exec($curl);
    curl_close($curl);
    return $result;
}

if (file_exists($track_save_path) == true) {
    $action_data['code'] = '200';
    $action_data['track_url'] = "../juno_track_data/".$track_id.".mp3";
} else {
    $result = curl($track_url);

    if (empty($result) || !isset($result)) {
        $action_data['code'] = '500';
        $action_data['masg'] = 'Download juno music failed.';
    } else {
        $file =fopen($track_save_path, "wb+");
        fwrite($file, $result);
        fclose($file);

        $action_data['code'] = '200';
        $action_data['track_url'] = "../juno_track_data/".$track_id.".mp3";
    }
}


echo json_encode($action_data,
      JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES);

?>

