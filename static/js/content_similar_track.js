
function click_play_juno_track(juno_id, track_url) {
    console.log(juno_id);
    $("#div-juno-track-player").html(
        "<b>正在获取音乐数据..</b>"
    );
    $.post("./page/get_juno_information_local.php",
        {"juno_id" : juno_id,
        "track_url" : track_url},
        function(data,status){
            console.log(data);
            var data_obj = JSON.parse(data);
            console.log(data_obj);
            if (data_obj['code'] != '200') {
                $("#div-juno-track-player").html(
                        "<b>获取该歌曲数据失败...</b>"
                    );
                return;
            }
            var track_url = data_obj['track_data_url'];
            $("#div-juno-track-player").html(
                '<audio controls="controls" autoplay="autoplay">' +
                    '<source src="' + track_url + '" type="audio/mpeg">' +
                    '<source src="' + track_url + '" type="audio/wav">' +
                '</audio>'
           );
        });
}


function get_content_similar_track(song_id) {

    // alert("开始推荐内容相似的歌曲! 请等待...");
    // var song_id = $('#span-song-id-playing').text();
    // 去除首尾空格
    song_id = $.trim(song_id);

    $("#div-juno-track-player").html("");

    $.post("./page/get_similar_content_track.php",
        {"song_id" : song_id},
        function(data, status){
            console.log(data);

            var data_obj = JSON.parse(data);
            if (data_obj['code'] != '200' || data_obj['recommendations'] == null) {
                // alert("歌曲推荐失败!  code: " + data_obj['code']);
                $("#div-track-play-tip-information").html(
                    "<b color='red'>获取基于CNN的推荐失败..</b>"
                );
                return;
            }
            var play_icon_solid = '<i class="fas fa-play-circle fa-1x"></i>';

            // 删除之前的歌曲信息
            $("tr").remove(".tr-track-information-content-similar");
            $("#div-track-play-tip-information").html("<i></i>");
            var reommendation_obj = data_obj['recommendations'];
            for(j = 0,len=reommendation_obj.length; j < len; j++) {
                var track_id = reommendation_obj[j][0]['id'];
                var artist_name = reommendation_obj[j][0]['release_artist'];
                var track_url = reommendation_obj[j][0]['track_url'];
                var track_name = reommendation_obj[j][0]['track_name'].split('-')[0];
                var parent_genre = reommendation_obj[j][0]['parent_genre'];

                // console.log(track_data);
                $("#tbody-track-information-content-similar").append(
                    '<tr class="tr-track-information-content-similar">'+
                        '<td class="td-track-index">'+ j +'</td>'+
                        '<td class="td-track-play" ' +
                            ' onclick="click_play_juno_track(' + track_id + ',\''+track_url+'\')">' +
                            play_icon_solid +'</td>' +
                        '<td class="td-track-name">'+ track_name + '</td>' +
                        '<td class="td-track-artist">'+ artist_name +'</td>' +
                        '<td class="td-track-album">'+ parent_genre +'</td>' +
                    '</tr>'
                );

            }
        });
}
