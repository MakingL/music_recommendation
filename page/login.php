<?php session_start();?>
<?php
// //注销登录
// if(isset($_GET['action']) && $_GET['action'] == "logout"){
//     unset($_SESSION['userid']);
//     unset($_SESSION['username']);
//     echo '注销登录成功！点击此处 <a href="login.html">登录</a>';
//     exit;
// }


//登录
if(!isset($_POST['submit'])){
    exit('非法访问!');
}
$username = htmlspecialchars($_POST['username']);
$password = MD5($_POST['password']);
// $password = $_POST['password'];

//包含数据库连接文件
require_once('_connectDB.php');

$sql = "SELECT uid
        FROM user_information
        WHERE user_name='$username'";

$res = $conn->query($sql);
$row = $res->fetch_assoc();

if(empty($row)){
    exit('<script>alert("该用户名不存在\n 请重试！"); location.href="../login.html";</script>');
}

//检测用户名及密码是否正确
$sql = "SELECT *
        FROM user_information
        WHERE user_name='$username' and passwd='$password'
        limit 1";

$res = $conn->query($sql);

if($res->num_rows > 0){
    //登录成功
    $row = $res->fetch_assoc();
    // $_SESSION["loginFlag"] = "success";
    $_SESSION['username'] = $username;
    $_SESSION['userid'] = $row['uid'];

    exit('<script>alert("登陆成功"); location.href="../index.html";</script>');

} else {
    exit('<script>alert("用户密码不正确\n 请重试！"); location.href="../login.html";</script>');

}

 $conn->close();


?>
