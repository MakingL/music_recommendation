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

// 执行 python 脚本
$command_recommenda = "sudo python3 ./cf_recommend.py ".$user_id." 2>&1";

$recommend_result = exec($command_recommenda, $arr, $ret);

// var_dump($recommend_result);
// echo "</br></br>";
// var_dump($arr);
// echo "</br></br>";
// var_dump($ret);
// echo "</br></br>";
$result_data['code'] = '200';
$result_data['recommendations'] = json_decode($recommend_result);
echo json_encode($result_data,
      JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES);
