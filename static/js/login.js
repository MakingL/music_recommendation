$(function(){

  $('#switch_qlogin').click(function(){
    $('#switch_login').removeClass("switch_btn_focus").addClass('switch_btn');
    $('#switch_qlogin').removeClass("switch_btn").addClass('switch_btn_focus');
    $('#switch_bottom').animate({left:'0px',width:'70px'});
    $('#qlogin').css('display','none');
    $('#web_qr_login').css('display','block');

    });
  $('#switch_login').click(function(){

    $('#switch_login').removeClass("switch_btn").addClass('switch_btn_focus');
    $('#switch_qlogin').removeClass("switch_btn_focus").addClass('switch_btn');
    $('#switch_bottom').animate({left:'154px',width:'70px'});

    $('#qlogin').css('display','block');
    $('#web_qr_login').css('display','none');
    });

  if(getParam("a")=='0')
  {
    $('#switch_login').trigger('click');
  }

});

function logintab(){
  scrollTo(0);
  $('#switch_qlogin').removeClass("switch_btn_focus").addClass('switch_btn');
  $('#switch_login').removeClass("switch_btn").addClass('switch_btn_focus');
  $('#switch_bottom').animate({left:'154px',width:'96px'});
  $('#qlogin').css('display','none');
  $('#web_qr_login').css('display','block');

}


//根据参数名获得该参数 pname等于想要的参数名
function getParam(pname) {
    var params = location.search.substr(1); // 获取参数 平且去掉？
    var ArrParam = params.split('&');
    if (ArrParam.length == 1) {
        //只有一个参数的情况
        return params.split('=')[1];
    }
    else {
         //多个参数参数的情况
        for (var i = 0; i < ArrParam.length; i++) {
            if (ArrParam[i].split('=')[0] == pname) {
                return ArrParam[i].split('=')[1];
            }
        }
    }
}


var reMethod = "GET",
  pwdmin = 6;
var name_exist = false;

$(document).ready(function() {
  name_exist = false;
  $('#user').blur(function(){
    $.ajax({
      type: 'POST',
      url: "page/check_user_name.php",
      data: {'user_name' :  $("#user").val()},
      dataType: 'json',
      success: function(result) {
        console.log(result);
        if (result['code'] != '200') {
          $('#userCue').html("<font color='red'><b>×该用户名已经被注册</b></font>");
          name_exist = true;
          return false;
        }
        else {
          if (name_exist == true) {
            $('#userCue').html("快速注册请注意格式");
          }
          name_exist = false;
        }
      }
    });
  });

  $('#reg').click(function() {

    if ($('#user').val() == "") {
      $('#user').focus().css({
        border: "1px solid red",
        boxShadow: "0 0 2px red"
      });
      $('#userCue').html("<font color='red'><b>×用户名不能为空</b></font>");
      return false;
    }

    if ($('#user').val().length < 4 || $('#user').val().length > 16) {

      $('#user').focus().css({
        border: "1px solid red",
        boxShadow: "0 0 2px red"
      });
      $('#userCue').html("<font color='red'><b>×用户名位4-16字符</b></font>");
      return false;

    }
    // $.ajax({
    //   type: reMethod,
    //   url: "page/register.php",
    //   data: "uid=" + $("#user").val() + '&temp=' + new Date(),
    //   dataType: 'html',
    //   success: function(result) {

    //     if (result.length > 2) {
    //       $('#user').focus().css({
    //         border: "1px solid red",
    //         boxShadow: "0 0 2px red"
    //       });$("#userCue").html(result);
    //       return false;
    //     } else {
    //       $('#user').css({
    //         border: "1px solid #D7D7D7",
    //         boxShadow: "none"
    //       });
    //     }

    //   }
    // });

    if ($('#passwd').val().length < pwdmin) {
      $('#passwd').focus();
      $('#userCue').html("<font color='red'><b>×密码不能小于" + pwdmin + "位</b></font>");
      return false;
    }
    if ($('#passwd2').val() != $('#passwd').val()) {
      $('#passwd2').focus();
      $('#userCue').html("<font color='red'><b>×两次密码不一致！</b></font>");
      return false;
    }

    if (name_exist == true) {
      $('#userCue').html("<font color='red'><b>×该用户名已经被注册</b></font>");
      return false;
    }
    $('#regUser').submit();
  });


});
