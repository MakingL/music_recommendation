<?php
# just download the NeteaseMusicAPI.php into directory, require it with the correct path.
# in weapi, you should also put BigInteger.php into same directory, but don't require it.
require_once 'NeteaseMusicWeapi.php';

# Initialize
$api = new NeteaseMusicWEAPI();

# Get data
$result = $api->search('hello');
// or $result = $api->mini()->search('hello');
// $result = $api->artist('46487');
// $result = $api->detail('494865824');
// $result = $api->album('3377030');
// $result = $api->playlist('124394335');
// $result = $api->url('35847388'); # v2 only
// $result = $api->lyric('35847388');
// $result = $api->mv('501053');
// $result = $api->similar_song('35847388');
// $result = $api->topList('1');

// echo $result;
var_dump($result);

# return JSON, just use it
// $data=json_decode($result);
// // var_dump($data);
// header('Content-type: application/json; charset=UTF-8');
// echo json_encode($data,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES);
