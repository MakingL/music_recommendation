/*
    获取热歌榜信息
    参数： 无
 */
function get_similar_content() {
    var track_url = "0";
    $.post("./page/get_music_url.php",
            {'id' : '38592312'},
            function(data,status){
                var data_obj = JSON.parse(data);
                if (data_obj['code'] != '200') {
                    alert("获取歌曲URL错误!  code: " + data_obj['code']);
                    return;
                }
                // 删除之前的歌曲信息
                // $("tr").remove(".tr-track-hot");
                console.log(data_obj);
                for (key in data_obj) {
                    if (key == 'code') continue;

                    var track_data = data_obj[key];
                    track_url = data_obj['song_url'];
                    // // console.log(track_data);
                    // $("#tbody-track-information-hot").append('<tr class="tr-track-hot">'+
                    //     '<td class="track_index">'+ key +'</td>'+
                    //     '<td class="track_love">'+ 'love' +'</td>' +
                    //     '<td class="track_name">'+ track_data['song_name'] +'</td>' +
                    //     '<td class="artist_name">'+ track_data['artist_name'] +'</td>' +
                    //     '<td class="album_name">'+ track_data['album_name'] +'</td>' +
                    //     // '<td class="track_play">'+ 'play' +'</td>' +
                    //     '</tr>');
                }
            });
    console.log(track_url);
     $.post("./page/download_track.php",
            {'url' : track_url},
            function(data,status){
                var data_obj = JSON.parse(data);
                if (data_obj['code'] != '200') {
                    alert("下载错误!  code: " + data_obj['code']);
                    return;
                }
                // 删除之前的歌曲信息
                // $("tr").remove(".tr-track-hot");
                console.log(data_obj);
            });

}
