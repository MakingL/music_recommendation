function get_user_track_rating() {
    var track_id = $("#span-song-id-playing").text();
    var user_id = $("#span-user-id").text();
    console.log("用户ID: " + user_id);
    console.log("歌曲ID: " + track_id);
    $.post("./page/get_user_2_track_rating.php",
        {"track_id" : track_id,"user_id" : user_id},
        function(data,status){
            var data_obj = JSON.parse(data);
            console.log(data_obj);
            if (data_obj['code'] == '200') {
                // 清楚元素中的内容
                $('#b-tip-rating').html("<font color='red'><b>您之前对该歌曲的评分: </b></font>");
                $('.starNum').html(data_obj['rating']+'分');
                //
                // alert("评分成功!");
            } else {
                $('#b-tip-rating').html("<font color='red'><b>请对此歌进行评分：</b></font>");
            }
        });
}
function rating_a_track(rating) {
    var track_id = $("#span-song-id-playing").text();
    var user_id = $("#span-user-id").text();
    $.post("./page/add_rating.php",
        {"track_id" : track_id,
        "user_id" : user_id,
        "rating" : rating},
        function(data,status){
            var data_obj = JSON.parse(data);
            // console.log(data_obj);
            if (data_obj['code'] == '200') {
                alert("评分成功!");
            } else {
                alert("评分失败: " + data_obj['masg']);
            }
        });
}
