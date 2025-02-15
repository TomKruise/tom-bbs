<?php
if(empty($_POST['name'])){
    skip('manage_add.php','管理员名称不得为空！','error');
}
if(mb_strlen($_POST['name'])>32){
    skip('manage_add.php','管理员名称不得多余32个字符！','error');
}
if(mb_strlen($_POST['pw'])<6){
    skip('manage_add.php','密码不得少于6位！','error');
}
$_POST=escape($link,$_POST);
$query="select * from bbs_manage where name='{$_POST['name']}'";
$result=execute($link,$query);
if(mysqli_num_rows($result)){
    skip('manage_add.php','这个名称已经有了！','error');
}
if(!isset($_POST['level'])){
    $_POST['level']=1;
}elseif ($_POST['level']=='0'){
    $_POST['level']=0;
}elseif ($_POST['level']=='1'){
    $_POST['level']=1;
}else{
    $_POST['level']=1;
}
?>