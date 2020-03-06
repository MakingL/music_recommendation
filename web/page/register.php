<?php
//注册
$username = htmlspecialchars($_POST['user']);
$password = $_POST['passwd'];
$md5_passwd = md5($password);
$user_id = md5(uniqid());

if(!isset($username)||!isset($password)){
    exit('<script>alert("用户名或密码为空!"); location.href="../login.html";</script>');
}

//包含数据库连接文件
require_once('_connectDB.php');
//检测用户名及密码是否正确
$sql = "INSERT INTO user_information
        (uid, user_name, passwd)
        VALUES  ('$user_id', '$username', '$md5_passwd')";

if($conn->query($sql) == TRUE){
    //注册成功
    exit('<script>alert("注册成功！\n 请登录！"); location.href="../login.html";</script>');
} else {
    //注册失败
    exit('<script>alert("注册失败 请重试！"); location.href="../login.html"; </script>');
}

 $conn->close();


 ?>
