<?php
if (empty(@$_POST['user_id'])) {
    $action_data['code'] = '400';
    $action_data['masg'] = 'Has no user_id. Please post user_id.';
    echo json_encode($action_data,
      JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES);
    return;
}

$user_id = $_POST['user_id'];

// $user_id = "b519c5812ab84bf8f6bfadf81af11c13";
// $user_id = "b80344d063b5ccb3212f76538f3d9e43d87dca9e";

/**
** @desc 封装 curl 的调用接口，post的请求方式
**/
function doCurlPostRequest($url, $data = null){
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

$request_data["msg"] = "cf_recommend";
$request_data["raw_uid"] = $user_id;
$data = json_encode(
  $request_data,
  JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES
);

$recommend_result  = doCurlPostRequest("http://recommend_servce:6016/cf_recommend", $data);
if (empty($recommend_result)) {
  $result_data['code'] = '500';
  $result_data['masg'] = "Server Error";
  echo json_encode(
    $result_data,
    JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES
  );
  return;
}


// 执行 python 脚本
// $command_recommenda = "sudo python3 ./cf_recommend.py ".$user_id." 2>&1";

// $recommend_result = exec($command_recommenda, $arr, $ret);

// var_dump($recommend_result);

$result_data['code'] = '200';
$result_data['recommendations'] = json_decode($recommend_result);
echo json_encode($result_data,
      JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES);
