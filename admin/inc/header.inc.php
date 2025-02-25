<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8" />
    <title><?php echo $template['title'] ?></title>
    <meta name="keywords" content="后台界面" />
    <meta name="description" content="后台界面" />

    <?php
    foreach ($template['css'] as $css) {
        echo "<link rel='stylesheet' type='text/css' href='{$css}' />";
    }
    ?>
</head>
<body>
<div id="top">
    <div class="logo">
        管理中心
    </div>
    <ul class="nav">
        <li><a href="#" target="_blank">bbs</a></li>
    </ul>
    <div class="login_info">
        <a href="#" style="color:#fff;">网站首页</a>&nbsp;|&nbsp;
        管理员：<?php echo $_SESSION['manage']['name']?> <a href="logout.php">[注销]</a>
    </div>
</div>
<div id="sidebar">
    <ul>
        <li>
            <div class="small_title">系统</div>
            <ul class="child">
                <li><a <?php if (basename($_SERVER['SCRIPT_NAME']) == 'index.php') {echo 'class="current"';} ?>href="index.php">系统信息</a></li>
                <li><a <?php if (basename($_SERVER['SCRIPT_NAME']) == 'manage.php') {echo 'class="current"';} ?> href="manage.php">管理员</a></li>
                <li><a <?php if (basename($_SERVER['SCRIPT_NAME']) == 'manage_add.php') {echo 'class="current"';} ?> href="manage_add.php">添加管理员</a></li>
                <li><a <?php if (basename($_SERVER['SCRIPT_NAME']) == 'web_set.php') {echo 'class="current"';} ?>href="web_set.php">站点设置</a></li>
            </ul>
        </li>
        <li><!--  class="current" -->
            <div class="small_title">内容管理</div>
            <ul class="child">
                <li><a <?php if (basename($_SERVER['SCRIPT_NAME']) == 'first_module.php') {echo 'class="current"';} ?> href="first_module.php">父板块列表</a></li>
                <li><a <?php if (basename($_SERVER['SCRIPT_NAME']) == 'first_module_add.php') {echo 'class="current"';} ?>  href="first_module_add.php">父板块添加</a></li>
                <?php
                if (basename($_SERVER['SCRIPT_NAME']) == 'first_module_update.php') {
                    echo "<li><a class='current'>父版块编辑</a></li>";
                }
                if (basename($_SERVER['SCRIPT_NAME']) == 'second_module_update.php') {
                    echo "<li><a class='current'>子版块编辑</a></li>";
                }
                ?>
                <li><a <?php if (basename($_SERVER['SCRIPT_NAME']) == 'second_module.php') {echo 'class="current"';} ?>href="second_module.php">子板块列表</a></li>
                <li><a <?php if (basename($_SERVER['SCRIPT_NAME']) == 'second_module_add.php') {echo 'class="current"';} ?>href="second_module_add.php">子板块添加</a></li>
                <li><a target="_blank" href="../index.php">帖子管理</a></li>
            </ul>
        </li>
        <li>
            <div class="small_title">用户管理</div>
            <ul class="child">
                <li><a href="#">用户列表</a></li>
            </ul>
        </li>
    </ul>
</div>