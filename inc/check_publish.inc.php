<?php
if(empty($_POST['module_id']) || !is_numeric($_POST['module_id'])){
    skip('publish.php', '所属版块id不合法！', 'error');
}
$query="select * from bbs_second_module where id={$_POST['module_id']}";
$result=execute($link, $query);
if(mysqli_num_rows($result)!=1){
    skip('publish.php', '请选择一个所属版块！', 'error');
}
if(empty($_POST['title'])){
    skip('publish.php', '标题不得为空！', 'error');
}
if(mb_strlen($_POST['title'])>255){
    skip('publish.php', '标题不得超过255个字符！', 'error');
}
?>