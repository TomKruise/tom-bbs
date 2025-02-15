<?php
if(empty($_POST['name'])){
    skip('login.php', '用户名不得为空！', 'error');
}
if(mb_strlen($_POST['name'])>32){
    skip('login.php', '用户名长度不要超过32个字符！', 'error');
}
if(empty($_POST['pw'])){
    skip('login.php', '密码不得为空！', 'error');
}
if(strtolower($_POST['vcode'])!=strtolower($_SESSION['vcode'])){
    skip('login.php', '验证码输入错误！','error');
}
if(empty($_POST['time']) || is_numeric($_POST['time']) || $_POST['time']>2592000){
    $_POST['time']=2592000;
}