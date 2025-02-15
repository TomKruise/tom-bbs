<?php
function skip($url, $message, $pic)
{
    $html = <<<H
<!DOCTYPE html>
<html lang="zh-CN">
<head>
<meta charset="utf-8"/>
<meta http-equiv="refresh" content="3;URL={$url}">
<title>正在跳转中</title>
<link rel="stylesheet" type="text/css" href="style/remind.css" />
</head>
<body>
<div class="notice">
    <span class="pic {$pic}"></span>
    {$message}<a href="{$url}"> 3秒后自动跳转...</a>
</div>
</body>
</html>
H;
echo $html;
exit;
}

function is_login($link)
{
    if (isset($_COOKIE['bbs']['name']) && isset($_COOKIE['bbs']['pw'])) {
        $name = $_COOKIE['bbs']['name'];
        $pw = $_COOKIE['bbs']['pw'];
        $query = "select * from bbs_member where name='{$name}' and sha1(pw)='{$pw}'";
        $result = execute($link, $query);
        if (mysqli_num_rows($result) == 1) {
            $data = mysqli_fetch_assoc($result);
            return $data['id'];
        } else {
            return false;
        }
    } else {
        return false;
    }
}

function check_member_id($member_id, $content_member_id, $is_manage_login)
{
    return $member_id == $content_member_id || $is_manage_login;
}

//验证后台管理员是否登录
function is_manage_login($link){
    if(isset($_SESSION['manage']['name']) && isset($_SESSION['manage']['pw'])){
        $query="select * from bbs_manage where name='{$_SESSION['manage']['name']}' and sha1(pw)='{$_SESSION['manage']['pw']}'";
        $result=execute($link,$query);
        if(mysqli_num_rows($result)==1){
            return true;
        }else{
            return false;
        }
    }else{
        return false;
    }
}