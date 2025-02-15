<?php
include_once '../inc/mysql.inc.php';
include_once '../inc/tool.inc.php';
if(!isset($_GET['id']) || !is_numeric($_GET['id'])){
    skip('manage.php','id参数错误！','error');
}
$link=connect();
include_once 'inc/is_manage_login.inc.php';
$query="delete from bbs_manage where id={$_GET['id']}";
execute($link,$query);
if(mysqli_affected_rows($link)==1){
    skip('manage.php','恭喜你删除成功！','ok');
}else{
    skip('manage.php','对不起删除失败，请重试！','error');
}
?>