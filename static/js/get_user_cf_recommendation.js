/*
    获取新歌榜信息
    参数： 无
 */
function get_user_cf_recommendation() {
    $.post("./page/_get_userinfor.php",
            function(data,status){
                var data_obj = JSON.parse(data);
                if (data_obj['code'] != '200') {
                    return;
                }
                var user_id = data_obj['userid'];
                console.log("user id for recommendations:" + user_id);
                $.post("./page/collaborative_filtering/get_user_recommendation.php",
                    {"user_id" : user_id},
                    function(data,status){
                        console.log(data);
                        var data_obj = JSON.parse(data);
                        console.log(data_obj);
                        if (data_obj['code'] == '200') {
                            // 清楚元素中的内容
                            var recommendations = data_obj["recommendations"];
                            // 删除之前的歌曲信息
                            $("tr").remove(".tr-track-information-cf-recommendation");

                            var key_index = 0;
                            for (key in recommendations) {
                                var track_id_recommend = recommendations[key];
                                // var key_index = key;
                                // 查询出推荐 的歌曲信息详情
                                $.post("./page/get_music_detail.php",
                                        {"id" : track_id_recommend},
                                        function(data,status){
                                            var track_data = JSON.parse(data);
                                            if (track_data['code'] != '200') {
                                                console.log("推荐的歌曲未找到!  code: " + track_data['code']);
                                                return;
                                            }

                                            var play_icon_solid = '<i class="fas fa-play-circle fa-1x"></i>';

                                            // console.log(track_data);
                                            $("#tbody-track-information-userCF").append(
                                                '<tr class="tr-track-information-cf-recommendation">\n' +
                                                    '<td class="td-track-number">' + key_index +'</td>\n'+
                                                    '<td class="td-track-play" '
                                                    + ' onclick="click_play_track('+track_data['song_id']
                                                    +')">'+ play_icon_solid +'</td>'
                                                    + '<td class="td-track-name">'+ track_data['song_name'] +'</td>\n' +
                                                    '<td class="td-track-artist">'+ track_data['artist_name'] +'</td>\n' +
                                                    '<td class="td-track-album">'+ track_data['album_name'] +'</td>\n' +
                                                '</tr>\n');
                                            key_index = key_index + 1;
                                        });
                            }
                        } else {
                            $("#tbody-track-information-userCF").html(
                                "<b>获取用户CF推荐失败..</b>"
                            );
                            console.log("获取用户CF推荐失败!");
                        }
                    });
            });

}
