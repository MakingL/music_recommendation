function get_user_track_rating(track_id) {
     $.post("./page/_get_userinfor.php",
                function(data,status){
                    var data_obj = JSON.parse(data);
                    if (data_obj['code'] != '200') {
                        return;
                    }
                    var user_id = data_obj['userid'];
                    $.post("./page/get_user_2_track_rating.php",
                        {"track_id" : track_id,"user_id" : user_id},
                        function(data,status){
                            var data_obj = JSON.parse(data);
                            // console.log(data_obj);
                            if (data_obj['code'] == '200') {
                                // 清楚元素中的内容
                                $('#b-tip-rating').html("<font color='red'><b>您对该歌曲的评分: </b></font>");
                                var rating = data_obj['rating'];
                                $('.starNum').html(rating+'.0分');
                                $('.photo span').eq(rating-1).prevAll().find('.high').css('z-index',1);
                                $('.photo span').eq(rating-1).find('.high').css('z-index',1);
                                $('.photo span').eq(rating-1).nextAll().find('.high').css('z-index',0);
                                // alert("评分成功!");
                            } else {
                                $('#b-tip-rating').html("<font color='red'><b>请对此歌进行评分：</b></font>");
                                var rating = 0
                                $('.photo span').find('.high').css('z-index',0);
                                $('.starNum').html(rating.toFixed(1)+'分');
                            }
                        });
                });
}
function rating_a_track(rating) {
    var track_id = $("#span-song-id-playing").text();
    var user_id = $("#span-user-id").text();
    track_id = $.trim(track_id);
    user_id = $.trim(user_id);
    console.log("user_id: " + user_id);
    console.log("track_id: " + track_id);
    console.log("rating: " + rating);
    $.post("./page/add_rating.php",
        {"track_id" : track_id,
        "user_id" : user_id,
        "rating" : rating},
        function(data,status){
            var data_obj = JSON.parse(data);
            // console.log(data_obj);
            if (data_obj['code'] == '200') {
                alert("修改评分成功!");
                $('#b-tip-rating').html("<font color='red'><b>您对该歌曲的评分: </b></font>");
                get_user_all_rating();
            } else {
                alert("评分失败: " + data_obj['masg']);
            }
        });
}


function get_user_all_rating() {
    $.post("./page/_get_userinfor.php",
                function(data,status){
                    var data_obj = JSON.parse(data);
                    if (data_obj['code'] != '200') {
                        return;
                    }
                    var user_id = data_obj['userid'];
                    $.post("./page/get_user_rating_all.php",
                        {"user_id" : user_id},
                        function(data,status){
                            var data_obj = JSON.parse(data);
                            console.log(data_obj);
                            if (data_obj['code'] == '200') {
                                // 删除之前的歌曲信息
                                $("tr").remove(".tr-user-rating");
                                var rating_data = data_obj['ratings'];
                                console.log(rating_data);
                                for (key in rating_data) {

                                    var track_name = rating_data[key]['song_name'];
                                    var artist_name = rating_data[key]['artist_name'];
                                    var album_name = rating_data[key]['album_name'];
                                    var rating = rating_data[key]['rating'];
                                    $("#tbody-user-rating-data").append(
                                        '<tr class="tr-user-rating">\n' +
                                        '<td class="td-track-number">' + key +'</td>\n'+
                                        // '<td class="track_love" onclick="click_love_track(this,'+track_data['song_id']+')">'+ love_icon_regular +'</td>\n' +

                                        // '<td class="track_play" id="' +
                                        // track_data['song_id'] +
                                        // '" onclick="click_play_track(this)">'+ play_icon_solid +'</td>' +
                                        '<td class="td-track-name">'+ track_name +'</td>\n' +
                                        '<td class="td-track-artist">'+ artist_name +'</td>\n' +
                                        '<td class="td-track-album">'+ album_name +'</td>\n' +
                                        '<td class="td-track-play" >' + rating +'</td>' +
                                        '</tr>\n');
                                }
                            } else {
                                console.log("get user rating failed");
                            }
                        });
                });
}
