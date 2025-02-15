<?php
if (empty($name)) {
    skip($url, '用户名不得为空！', $pic);
}

if (mb_strlen($name) > 32) {
    skip($url, '用户名长度不要超过32个字符！', $pic);
}

$query = "select * from bbs_member where name='{$name}'";
$result = execute($link, $query);
if (mysqli_num_rows($result)) {
    skip($url, '用户名已存在，请换一个用户名！', $pic);
}

if (mb_strlen($pw) < 6) {
    skip($url, '密码不得少于6位！', $pic);
}

if ($pw != $re_pw) {
    skip($url, '两次密码输入不一致！', $pic);
}

if (strtolower($vcode) != strtolower($_SESSION['vcode'])) {
    skip($url, '验证码输入错误！', $pic);
}