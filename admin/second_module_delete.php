<?php
include_once '../inc/mysql.inc.php';
include_once '../inc/tool.inc.php';

$url = 'second_module.php';
$message = '恭喜你删除子版块成功！';
$pic = 'ok';

$link=connect();
include_once 'inc/is_manage_login.inc.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $message = '子版块id参数错误！';
    $pic = 'error';
    skip($url, $message, $pic);
} else {
    $query="delete from bbs_second_module where id={$_GET['id']}";
    execute($link,$query);

    if(mysqli_affected_rows($link)==1){
        skip($url, $message, $pic);
    } else {
        $message = '删除失败，请重试！';
        $pic = 'error';
        skip($url, $message, $pic);
    }
}