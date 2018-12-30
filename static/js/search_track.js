function search_track_onclick() {
    var track_name = $('#track_name_search').val();
    if (track_name.length == 0) {
      alert("未捕获到歌曲名，请输入要查询的歌曲名!");
      return;
    }
    $.post("./page/search_song_f_name.php",
            {"track_name" : track_name,
                "limit" : 15,
            },
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
                $("tr").remove(".tack_searched");
                console.log(data_obj);
                for (key in data_obj) {
                    if (key == 'code') continue;

                    var track_data = data_obj[key];
                    var love_icon_regular = '<i class="far fa-heart fa-1x"></i>';
                    var love_icon_solid = '<i class="fas fa-heart fa-1x"></i>';
                    var play_icon_solid = '<i class="fas fa-play-circle fa-1x"></i>';

                    $("#tbody-track-information").append(
                        '<tr class="tack_searched"">\n' +
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
