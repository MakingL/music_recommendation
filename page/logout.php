<?php session_start(); ?>
<?php
//注销登录
if(isset($_GET['action']) && $_GET['action'] == "logout"){
    unset($_SESSION['userid']);
    unset($_SESSION['username']);
    $_SESSION["loginFlag"] = "fail";
    session_destroy();
    exit('<script>alert("注销成功!"); location.href="../login.html";</script>');
}

?>
