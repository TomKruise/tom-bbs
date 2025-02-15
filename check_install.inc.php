<?php

if (empty($db_host)) {
    exit('数据库地址不得为空！<a href="install.php">点击返回</a>');
}
if (empty($db_port)) {
    exit('数据库端口不得为空！<a href="install.php">点击返回</a>');
}
if (empty($db_user)) {
    exit('数据库用户名不得为空！<a href="install.php">点击返回</a>');
}
//    if (empty($db_pw)) {
//        exit('数据库密码不得为空！<a href="install.php">点击返回</a>');
//    }
if (empty($db_database)) {
    exit('数据库名称不得为空！<a href="install.php">点击返回</a>');
}
//    if (empty($manage_name)) {
//        exit('后台管理员名称不得为空！<a href="install.php">点击返回</a>');
//    }
if (empty($manage_pw)) {
    exit('后台管理员密码不得为空！<a href="install.php">点击返回</a>');
}

if (mb_strlen($manage_pw) < 6) {
    exit('后台管理员密码不得少于6位！<a href="install.php">点击返回</a>');
}

if ($manage_pw != $manage_pw_confirm) {
    exit('两次输入的密码不一致！<a href="install.php">点击返回</a>');
}

$link=@mysqli_connect($db_host, $db_user, $db_pw, '', $db_port);
if (mysqli_connect_errno()) {
    exit('数据库连接失败，请填写正确的数据库信息！<a href="install.php">点击返回</a>');
}

mysqli_set_charset($link, 'utf8');
if (!mysqli_select_db($link, $db_database)) {
    $create="CREATE DATABASE IF NOT EXISTS `{$db_database}` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci";
    mysqli_query($link, $create);
    if (mysqli_errno($link)) {
        exit('数据库创建失败，请检查数据库账户权限！<a href="install.php">点击返回</a>');
    }
    mysqli_select_db($link, $db_database);
}