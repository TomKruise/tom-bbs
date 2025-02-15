<?php
include_once 'inc/mysql.inc.php';
include_once 'inc/tool.inc.php';
$link=connect();
if(!$member_id=is_login($link)){
    skip('index.php','你没有登录，不需要注销！','error');
}
setcookie('bbs[name]','',time()-1);
setcookie('bbs[pw]','',time()-1);
skip('index.php','注销成功！','ok');