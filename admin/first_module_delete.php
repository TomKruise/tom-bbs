<?php
include_once '../inc/mysql.inc.php';
include_once '../inc/tool.inc.php';

$url = 'first_module.php';
$message = '恭喜你删除成功！';
$pic = 'ok';

$link=connect();
include_once 'inc/is_manage_login.inc.php';
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $message = 'id参数错误！';
    $pic = 'error';
    skip($url, $message, $pic);
} else {
    $query="select * from bbs_second_module where first_module_id={$_GET['id']}";
    $result = execute($link, $query);
    if (mysqli_num_rows($result)) {
        $message = '父版块包含子版块，请先删除对应的子版块后再进行此操作！';
        $pic = 'error';
        skip($url, $message, $pic);
    }

    $query="delete from bbs_first_module where id={$_GET['id']}";
    execute($link,$query);

    if(mysqli_affected_rows($link)==1){
        skip($url, $message, $pic);
    } else {
        $message = '删除失败，请重试！';
        $pic = 'error';
        skip($url, $message, $pic);
    }
}