<?php
include_once 'inc/mysql.inc.php';
include_once 'inc/tool.inc.php';
$link=connect();
$member_id=is_login($link);
$is_manage_login = is_manage_login($link);
if(!$member_id && !$is_manage_login){
    skip('login.php', '请登录之后再执行删除操作!', 'error');
}

if(!isset($_GET['id']) || !is_numeric($_GET['id'])){
    skip('index.php', '帖子id参数不合法!', 'error');
}

$query="select * from bbs_content where id={$_GET['id']}";
$result_content = execute($link, $query);
if (mysqli_num_rows($result_content) == 1) {
    $data_content = mysqli_fetch_assoc($result_content);
    if (check_member_id($member_id, $data_content['member_id'], $is_manage_login)) {
        $query="delete from bbs_content where id={$_GET['id']}";
        execute($link, $query);

        if (isset($_GET['return_url'])) {
            $return_url = $_GET['return_url'];
        } else {
            $return_url = "member.php?id={$member_id}";
        }

        if (mysqli_num_rows($link) == 1) {
            skip($return_url, '删除成功!', 'ok');
        } else {
            skip($return_url, '删除失败!', 'error');
        }
    } else {
        skip('index.php', '无操作权限!', 'error');
    }
} else {
    skip('index.php', '帖子不存在!', 'error');
}