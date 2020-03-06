/*
    获取登录的用户信息
    参数： 无
 */
function get_user_information() {
    $.post("./page/_get_userinfor.php",
            function(data,status){
                var data_obj = JSON.parse(data);
                if (data_obj['code'] != '200') {
                    alert("未登录,请登录后使用");
                    location.href='./login.html'
                    return;
                }
                // 删除之前的歌曲信息
                // $("tr").remove(".tr-track-hot");
                // console.log(data_obj);
                $("#span-user-name").append(data_obj['username']);
                $("#span-user-id").append(data_obj['userid']);
            });
}

// function get_user_name_id() {
//     $.post("./page/_get_userinfor.php",
//             function(data,status){
//                 console.log("user-information data:" + data);
//                 console.log("user-information data type:" + typeof(data));
//                 var data_obj = JSON.parse(data);
//                 if (data_obj['code'] != '200') {
//                     alert("未登录,请登录后使用");
//                     location.href='./login.html'
//                     return;
//                 }
//                 return data;
//             });
// }
