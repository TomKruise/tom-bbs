<?php
include_once 'inc/mysql.inc.php';

if (file_exists('inc/install.log')) {
    header("Location:index.php");
}

header('Content-type:text/html;charset=utf-8');
if (isset($_POST['submit'])) {
    $db_host = $_POST['db_host'];
    $db_port = $_POST['db_port'];
    $db_user = $_POST['db_user'];
    $db_pw = $_POST['db_pw'];
    $db_database = $_POST['db_database'];
    $manage_name = 'admin';
    $manage_pw = $_POST['manage_pw'];
    $manage_pw_confirm = $_POST['manage_pw_confirm'];


    include_once 'check_install.inc.php';
    $link=mysqli_connect($db_host, $db_user, $db_pw, $db_database, $db_port);
        $query = array();
        $query['bbs_content']="
            CREATE TABLE IF NOT EXISTS `bbs_content`  (
              `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
              `module_id` int(10) UNSIGNED NOT NULL,
              `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
              `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
              `time` datetime(0) NOT NULL,
              `member_id` int(10) UNSIGNED NOT NULL,
              `times` int(10) UNSIGNED NOT NULL DEFAULT 0,
              PRIMARY KEY (`id`) USING BTREE
            ) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;
        ";

        $query['bbs_first_module']="
            CREATE TABLE IF NOT EXISTS `bbs_first_module`  (
              `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
              `module_name` varchar(66) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
              `sort` int(11) NOT NULL DEFAULT 0,
              PRIMARY KEY (`id`) USING BTREE
            ) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '一级版块信息' ROW_FORMAT = Dynamic;
        ";

        $query['bbs_info']="
            CREATE TABLE IF NOT EXISTS `bbs_info`  (
              `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
              `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT 'bbs',
              `keywords` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT 'bbs',
              `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT 'bbs',
              PRIMARY KEY (`id`) USING BTREE
            ) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;
        ";

        $query['bbs_manage']="
            CREATE TABLE IF NOT EXISTS `bbs_manage`  (
              `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
              `name` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
              `pw` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
              `create_time` datetime(0) NOT NULL,
              `level` tinyint(4) NOT NULL DEFAULT 1,
              PRIMARY KEY (`id`) USING BTREE
            ) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;
        ";

        $query['bbs_member']="
            CREATE TABLE IF NOT EXISTS `bbs_member`  (
              `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
              `name` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
              `pw` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
              `photo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
              `register_time` datetime(0) NOT NULL,
              `last_time` datetime(0) NOT NULL DEFAULT current_timestamp(0),
              PRIMARY KEY (`id`) USING BTREE
            ) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;
        ";

        $query['bbs_reply']="
            CREATE TABLE IF NOT EXISTS `bbs_reply`  (
              `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
              `content_id` int(10) UNSIGNED NOT NULL,
              `quote_id` int(10) UNSIGNED NOT NULL DEFAULT 0,
              `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
              `time` datetime(0) NOT NULL,
              `member_id` int(10) UNSIGNED NOT NULL,
              PRIMARY KEY (`id`) USING BTREE
            ) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;
        ";

        $query['bbs_second_module']="
            CREATE TABLE IF NOT EXISTS `bbs_second_module`  (
              `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
              `first_module_id` int(10) UNSIGNED NOT NULL,
              `module_name` varchar(66) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
              `info` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
              `member_id` int(10) UNSIGNED NOT NULL DEFAULT 0,
              `sort` int(10) UNSIGNED NOT NULL DEFAULT 0,
              PRIMARY KEY (`id`) USING BTREE
            ) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;
        ";

        foreach ($query as $k => $v) {
            mysqli_query($link, $v);
            if (mysqli_errno($link)) {
                echo "数据表{$k}创建失败，请检查数据库账户是否拥有创建权限！<a href='install.php'>点击返回</a><br/>";
            }
        }

        //初始化信息
        $query_info="select * from bbs_info where id=1";
        $result_info=mysqli_query($link, $query_info);
        if (!mysqli_num_rows($result_info)) {
            $insert_info="INSERT INTO `bbs_info` (`id`, `title`, `keywords`, `description`) VALUES(1, 'bbs', 'bbs', 'bbs')";
            mysqli_query($link, $insert_info);
            if (mysqli_errno($link)) {
                exit("数据库bbs_info写入数据失败，请检查相应权限！<a href='install.php'>点击返回</a>");
            }
        }

        $query_manage="select * from bbs_manage where name='admin'";
        $result_manage=mysqli_query($link, $query_manage);
        if (!mysqli_num_rows($result_manage)) {
            $insert_manage="INSERT INTO `bbs_manage` (`name`, `pw`, `create_time`, `level`) VALUES('admin', md5('{$manage_pw}'), now(), 0)";
            mysqli_query($link, $insert_manage);
            if (mysqli_errno($link)) {
                exit("管理员创建失败，请检查数据表bbs_manage是否有写权限！<a href='install.php'>点击返回</a>");
            }
        }

    $filename = 'inc/config.inc.php';
    $str_file = file_get_contents($filename);
    $pattern = "/'DB_HOST', .*?\)/";
    $db_host = addslashes($db_host);
    if (preg_match($pattern, $str_file)) {
        $str_file = preg_replace($pattern, "'DB_HOST', '{$db_host}')", $str_file);
    }

    $pattern = "/'DB_USER', .*?\)/";
    $db_user = addslashes($db_user);
    if (preg_match($pattern, $str_file)) {
        $str_file = preg_replace($pattern, "'DB_USER', '{$db_user}')", $str_file);
    }

    $pattern = "/'DB_PWD', .*?\)/";
    $db_pw = addslashes($db_pw);
    if (preg_match($pattern, $str_file)) {
        $str_file = preg_replace($pattern, "'DB_PWD', '{$db_pw}')", $str_file);
    }

    $pattern = "/'DB_DATABASE', .*?\)/";
    $db_database = addslashes($db_database);
    if (preg_match($pattern, $str_file)) {
        $str_file = preg_replace($pattern, "'DB_DATABASE', '{$db_database}')", $str_file);
    }

    $pattern = "/\('DB_PORT', .*?\)/";
    $db_port = addslashes($db_port);
    if (preg_match($pattern, $str_file)) {
        $str_file = preg_replace($pattern, "('DB_PORT', {$db_port})", $str_file);
    }

    if (!file_put_contents($filename, $str_file)) {
        exit("配置文件写入失败，请检查config.inc.php文件的权限！<a href='install.php'>点击返回</a>");
    }

    file_put_contents('inc/install.log', 'success');

    echo "<div style='font-size: 20px; color: #00f000;'>安装成功！ <a href='index.php'>访问首页</a> | <a href='admin/login.php'>访问后台</a></div>";
    exit();
}
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8"/>
    <title>欢迎使用引导安装程序</title>
    <meta name="keywords" content="欢迎使用引导安装程序"/>
    <meta name="description" content="欢迎使用引导安装程序"/>
    <style type="text/css">
        body {
            background: #f7f7f7;
            font-size: 14px;
        }

        #main {
            width: 560px;
            height: 490px;
            background: #fff;
            border: 1px solid #ddd;
            position: absolute;
            top: 50%;
            left: 50%;
            margin-left: -280px;
            margin-top: -280px;
        }

        #main .title {
            height: 48px;
            line-height: 48px;
            color: #333;
            font-size: 16px;
            font-weight: bold;
            text-indent: 30px;
            border-bottom: 1px dashed #eee;
        }

        #main form {
            width: 400px;
            margin: 20px 0 0 10px;
        }

        #main form label {
            margin: 10px 0 0 0;
            display: block;
            text-align: right;
        }

        #main form label input.text {
            width: 200px;
            height: 25px;
        }

        #main form label input.submit {
            width: 204px;
            display: block;
            height: 35px;
            cursor: pointer;
            float: right;
        }
    </style>
</head>
<body>
<div id="main">
    <div class="title">
        欢迎使用引导安装程
    </div>
    <form method="post">
        <label>数据库地址：<input class="text" type="text" name="db_host" value="localhost"/></label>
        <label>端口：<input class="text" type="text" name="db_port" value="3306"/></label>
        <label>数据库用户名：<input class="text" type="text" name="db_user"/></label>
        <label>数据库密码：<input class="text" type="password" name="db_pw"/></label>
        <label>数据库名称<input class="text" type="text" name="db_database"/></label>
        <br/><br/>
        <label>后台管理员名称：<input class="text" type="text" name="manage_name" readonly="readonly" value="admin"/></label>
        <label>密码：<input class="text" type="password" name="manage_pw"/></label>
        <label>密码确认：<input class="text" type="password" name="manage_pw_confirm"/></label>
        <label><input class="submit" type="submit" name="submit" value="确定安装"/></label>
    </form>
</div>
</body>
</html>
