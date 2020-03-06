/*
    获取相似歌曲  ===  Item-CF
    参数： 歌曲 ID
 */
function add_similarity_track(song_id) {
    $.post("./page/get_similar_song.php",
            {"id" : song_id},
            function(data,status){
                // alert("Data: " + data + " Status: " + status);
                // console.log(data);
                // console.log("数据长度: " + data.length);
                var data_obj = JSON.parse(data);
                if (data_obj['code'] != '200') {
                    alert("歌曲未找到!  code: " + data_obj['code']);
                    return;
                }
                // 删除之前的歌曲信息
                $("tr").remove(".tr-track-information-similar");
                console.log(data_obj);
                for (key in data_obj) {
                    if (key == 'code') continue;

                    var track_data = data_obj[key];
                    var play_icon_solid = '<i class="fas fa-play-circle fa-1x"></i>';

                    // console.log(track_data);
                    $("#tbody-track-information-similar").append(
                        '<tr class="tr-track-information-similar">\n' +
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
