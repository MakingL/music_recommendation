/*
    音乐播放
    参数： 歌曲 ID
 */
function play_music(song_id, auto_play=0) {
    $.post("./page/get_music_detail.php",
            {"id" : song_id},
            function(data,status){
                var data_obj = JSON.parse(data);
                if (data_obj['code'] != '200') {
                    alert("歌曲未找到!  code: " + data_obj['code']);
                    return;
                }
                // 删除之前的歌曲信息
                $("#div-music-playing-information .music-playing-information").remove();

                console.log(data_obj);
                $("#div-music-playing-information").append(
                    '<span class="music-playing-information" id="span-song-id-playing" hidden>'
                    + data_obj['song_id'] + '</span>\n' +
                    '<div class="music-playing-information" id="div-music-name-playing">' +
                    data_obj['song_name'] + '</div>\n' +
                    '<div class="music-playing-information" id="div-artist-name-playing">歌手：' +
                    data_obj['artist_name'] + '</div>\n' +
                    '<div class="music-playing-information" id="div-album-name-playing">所属专辑：' +
                    data_obj['album_name'] + '</div>\n' +
                    // '<div class="music-playing-information" id="div-album-picture-playing">' +
                    // + '<img src="' +
                    // data_obj['album_picture'] +
                    // '" alt="专辑图" id="album_picture_playing">' + '</div>\n' +
                    '<div class="music-playing-information" id="div-music-iframe-playing">' +
                        '<iframe frameborder="no" border="0" ' +
                        ' marginwidth="0" marginheight="0" '+
                        ' width=330 height=86 ' +
                        ' src="//music.163.com/outchain/player?type=2&id='+
                        data_obj['song_id'] +
                        '&auto='+auto_play+'&height=66"></iframe>' +
                    '</div>\n'
                );
            });
}

// 检测播放音乐事件
// function click_play_track(song_id) {
//     // var song_id = obj.id;
//     // alert("play song id: " + song_id);
//     play_music(song_id, 1);
//     get_user_track_rating(song_id);
//     add_similarity_track(song_id);
//     get_similar_click(song_id);

// }




