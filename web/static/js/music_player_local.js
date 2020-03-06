/*
    音乐播放
    参数： 歌曲 ID
 */
function play_music_local(song_id,  auto_play=0) {
    $.post("./page/get_playing_information_local.php",
            {"song_id" : song_id,
            },
            function(data,status){
                var data_obj = JSON.parse(data);
                console.log(data_obj);
                if (data_obj['code'] == '502') {
                    // alert("暂无歌曲信息!  code: " + data_obj['code']);

                    // 删除之前的歌曲信息
                    $("tr").remove(".tr-track-information-content-similar");
                    // 歌曲内容相似推荐的提示
                     $("#div-track-play-tip-information").html(
                            "<b>未能获取到歌曲内容用于分析</b>"
                        );
                     $("#div-juno-track-player").html("");

                     $("#div-name-picture-playing").html(
                            "<b>未能获取到歌曲详细信息</b>"
                        );
                    $("#div-music-audio-playing").html(
                        '<b>暂无该歌曲内容</b>\n'
                    );
                     return;
                }
                // 删除之前的歌曲信息
                // $("#div-music-playing-information .music-playing-information").remove();

                $("#div-name-picture-playing").html(
                    '<div id="div-track-artistoalbum-name-playing">\n' +
                        '<span class="music-playing-information" id="span-song-id-playing" hidden>\n'
                            + data_obj['song_id'] +
                        '</span>\n' +
                        '<div class="music-playing-information" id="div-music-name-playing">\n' +
                            data_obj['song_name'] +
                        '</div>\n' +
                        '<div class="music-playing-information" id="div-artist-name-playing">歌手：' +
                            data_obj['artist_name'] +
                        '</div>\n' +
                        '<div class="music-playing-information" id="div-album-name-playing">专辑：' +
                            data_obj['album_name'] +
                        '</div>\n' +
                    '</div>\n' +
                    '<div class="music-playing-information" id="div-album-picture-playing">' +
                        '<img id="album_picture_playing" src="' +
                            data_obj['album_picture'] +
                            '" alt="专辑图" id="album_picture_playing">' +
                    '</div>\n'
                );

                if (data_obj['code'] != "200") {
                    $("#div-music-audio-playing").html(
                        '<b>暂无该歌曲内容</b>\n'
                    );

                    // 删除之前的歌曲信息
                    $("tr").remove(".tr-track-information-content-similar");
                    // 歌曲内容相似推荐的提示
                    $("#div-track-play-tip-information").html(
                            "<b>未能获取到歌曲内容用于分析</b>"
                            );
                    return;
                }

                var autoplay_control = "";
                if (auto_play == 1) {
                    autoplay_control = 'autoplay="autoplay"';
                }
                $("#div-music-audio-playing").html(
                    '<audio id="audio-playing-track" controls="controls"' + autoplay_control +' preload="auto">' +
                        '<source src="' + data_obj['track_data_url'] + '" type="audio/mpeg">\n' +
                        '<source src="' + data_obj['track_data_url'] + '" type="audio/ogg">\n' +
                        '<source src="' + data_obj['track_data_url'] + '" type="audio/wav">\n' +
                    '</audio>\n'
                );

                // 删除之前的歌曲信息
                $("tr").remove(".tr-track-information-content-similar");
                $("#div-track-play-tip-information").html(
                    '<b style="text-align: center;">正在获得相似音频内容的推荐...</b>');
                // 获得内容相似的歌曲推荐
                get_content_similar_track(song_id);
            });
}

// 检测播放音乐事件
function click_play_track(song_id, autoplay=1) {
    play_music_local(song_id, autoplay);
    get_user_track_rating(song_id);
    add_similarity_track(song_id);
}
