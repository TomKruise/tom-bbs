<?php
date_default_timezone_set('Asia/Shanghai');//设置时区
session_start();
header('context-type:text/html;charset=utf-8');
define('DB_HOST', '127.0.0.1');
define('DB_USER', 'root');
define('DB_PWD', '');
define('DB_DATABASE', 'bbs');
define('DB_PORT', 3306);
define('SERVER_ABSOLUTE_PATH', dirname(dirname(__FILE__)));
$arr=explode('/', $_SERVER['DOCUMENT_ROOT']);
define('SUB_URL', '/'.end($arr).'/');

if (!file_exists(SERVER_ABSOLUTE_PATH. '/inc/install.log')) {
    header('Location:'.SUB_URL.'install.php');
}