<?php
if (empty(@$_POST['song_id'])) {
    $action_data['code'] = '400';
    $action_data['masg'] = 'Has no song_id. Please post song_id.';
    echo json_encode($action_data,
      JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES);
    return;
}

$song_id = $_POST['song_id'];
// $song_id = '40147463';

require_once("./get_music_url.php");
require_once("./download_track.php");

// 获得歌曲 URL
$url_data = get_music_url($song_id);
$url_data = json_decode($url_data, true);

if ($url_data['code'] != '200') {
    $action_data['code'] = '500';
    $action_data['masg'] = 'Cannot got track url.';
    echo json_encode($action_data,
      JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES);
    return;
}

$track_url = $url_data['song_url'];
$track_type = $url_data['type'];
if (empty($url_data["type"]) || $url_data["type"] == "") {
  $track_type = "mp3";
}

// 下载歌曲
$track_save_path = "../music_data/".$song_id.".".$track_type;
if (file_exists($track_save_path) == false) {
    $download_information = download_track($track_url, $track_save_path);
    $download_information = json_decode($download_information, true);
    if ($download_information['code'] != '200') {
      $action_data['code'] = '500';
      $action_data['masg'] = 'Download music failed.';
      echo json_encode($action_data,
        JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES);
      return;
    }
}

// 得到下载好的歌曲文件路径
$path_track = realpath($track_save_path);
if ($path_track == false) {
    // 程序运行出错
    $action_data['code'] = '500';
    $action_data['masg'] = 'Track file not exit.';
    echo json_encode($action_data,
      JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES);
    return;
}

// var_dump($path_track);
/**
 ** @desc 封装 curl 的调用接口，post的请求方式
  ** 数据格式，JSON
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

$request_data["msg"] = "dl_recommend";
$request_data["track_path"] = $track_save_path;
$data = json_encode(
  $request_data,
  JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES
);

$recommend_result = doCurlPostRequest("http://recommend_servce:6016/dl_recommend", $data);

if (empty($recommend_result) || $recommend_result == "500") {
  $action_data['code'] = '500';
  $action_data['masg'] = 'Error opearat';
  echo json_encode(
    $action_data,
    JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES
  );
  return;
}

// var_dump($recommend_result);

// 执行 python 脚本
// $command_recommenda = "python3 ./recommend_similar_track.py ".$path_track." 2>&1";

// $recommend_result = exec($command_recommenda, $arr, $ret);
// var_dump($recommend_result);
// var_dump($return);
// var_dump($arr);

$result_data['code'] = '200';
$result_data['recommendations'] = json_decode($recommend_result, true);
echo json_encode($result_data,
      JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES);



