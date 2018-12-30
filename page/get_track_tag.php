<?php

// 利用 Last.fm API 获取歌曲标签
// http://ws.audioscrobbler.com/2.0/?method=artist.getTags
// &artist=Red%20Hot%20Chili%20Peppers&
// user=RJ&
// api_key=ad81205c1fa62e5e85aa53f64e6d36a1&
// format=json
//
$_USERAGENT='Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/35.0.1916.157 Safari/537.36';
$_COOKIE='os=pc; osver=Microsoft-Windows-10-Professional-build-10586-64bit; appver=2.0.2; channel=netease; __remember_me=true';
$_REFERER='http://ws.audioscrobbler.com/2.0/';

// CURL
function curl($url,$data=null){
    $curl=curl_init();
    curl_setopt($curl,CURLOPT_URL,$url);
    if($data){
        if(is_array($data))$data=http_build_query($data);
        curl_setopt($curl,CURLOPT_POSTFIELDS,$data);
        curl_setopt($curl,CURLOPT_POST,1);
    }
    curl_setopt($curl,CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl,CURLOPT_CONNECTTIMEOUT, 10);
    curl_setopt($curl,CURLOPT_REFERER,$GLOBALS['_REFERER']);
    curl_setopt($curl,CURLOPT_COOKIE,$GLOBALS['_COOKIE']);
    curl_setopt($curl,CURLOPT_USERAGENT,$GLOBALS['_USERAGENT']);
    $result=curl_exec($curl);
    curl_close($curl);
    return $result;
}

function track_tag($track_name,$artist_name){
    $url='http://ws.audioscrobbler.com/2.0/';
    $data=array(
        'method'=>'track.getTags',
        'api_key'=>'ad81205c1fa62e5e85aa53f64e6d36a1',
        'artist'=>$artist_name,
        'track'=>$track_name,
        'format'=>'json',
        // 'user'=>'RJ',
    );
    return curl($url,$data);
}

var_dump(track_tag($track_name='hello', $artist_name='adele'));
