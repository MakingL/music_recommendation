/*
    获取热歌榜信息
    参数： 无
 */
function get_hot_track() {
    $.post("./page/get_hotest_music.php",
            {'limit' : 10},
            function(data,status){
                var data_obj = JSON.parse(data);
                if (data_obj['code'] != '200') {
                    alert("获取新歌榜出错!  code: " + data_obj['code']);
                    return;
                }
                // 删除之前的歌曲信息
                $("tr").remove(".tr-track-hot");
                // console.log(data_obj);
                for (key in data_obj) {
                    if (key == 'code') continue;

                    var track_data = data_obj[key];
                    var love_icon_regular = '<i class="far fa-heart fa-1x"></i>';
                    var love_icon_solid = '<i class="fas fa-heart fa-1x"></i>';
                    var play_icon_solid = '<i class="fas fa-play-circle fa-1x"></i>';

                    // console.log(track_data);
                    $("#tbody-track-information-hot").append(
                        '<tr class="tr-track-hot">\n' +
                        '<td class="td-track-number">' + key +'</td>\n'+
                        // '<td class="track_love" onclick="click_love_track(this,'+track_data['song_id']+')">'+ love_icon_regular +'</td>\n' +
                        '<td class="td-track-play" ' +
                        ' onclick="click_play_track('+track_data['song_id']+')">'+ play_icon_solid +'</td>' +
                        // '<td class="track_play" id="' +
                        // track_data['song_id'] +
                        // '" onclick="click_play_track(this)">'+ play_icon_solid +'</td>' +
                        '<td class="td-track-name">'+ track_data['song_name'] +'</td>\n' +
                        '<td class="td-track-artist">'+ track_data['artist_name'] +'</td>\n' +
                        '<td class="td-track-album">'+ track_data['album_name'] +'</td>\n' +
                        '</tr>\n');
                }
            });
}
