<?php
include_once 'mysql.inc.php';
$link = connect();
$query="select * from bbs_info";
$result = execute($link, $query);
$data = mysqli_fetch_assoc($result);

$title = $data['title'];
$keywords = $data['keywords'];
$description = $data['description'];
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8" />
    <title><?php echo $template['title'] ?> - <?php echo $title?></title>
    <meta name="keywords" content="<?php echo $keywords?>" />
    <meta name="description" content="<?php echo $description?>" />
    <script src="ueditor/ueditor.config.js"></script>
    <script src="ueditor/ueditor.all.min.js"></script>
    <script src="ueditor/lang/zh-cn/zh-cn.js"></script>
    <script>

        UE.getEditor('editor', {
            initialFrameWidth: 500, initialFrameHeight: 300, autoHeightEnabled: false,
            toolbars: [['fullscreen', 'source', 'undo', 'redo', 'simpleupload', 'insertimage']]
        });

    </script>
    <?php
    foreach ($template['css'] as $val){
        echo "<link rel='stylesheet' type='text/css' href='{$val}' />";
    }
    ?>
</head>
<body>
<div class="header_wrap">
    <div id="header" class="auto">
        <div class="logo">bbs</div>
        <div class="nav">
            <a class="hover" href="index.php">首页</a>
        </div>
        <div class="serarch">
            <form action="search.php">
                <input class="keyword" type="text" name="keyword" placeholder="搜索其实很简单" value="<?php if (isset($_GET['keyword'])) echo $_GET['keyword']?>"/>
                <input class="submit" type="submit" value="" />
            </form>
        </div>
        <div class="login">
            <?php
            if(isset($member_id) && $member_id){
                $str=<<<A
					<a href="member.php?id={$member_id}" target="_blank">{$_COOKIE['bbs']['name']}</a>&nbsp;
					<a href="logout.php">注销</a>
A;
                echo $str;
            }else{
                $str=<<<A
					<a href="login.php">登录</a>&nbsp;
					<a href="register.php">注册</a>
A;
                echo $str;
            }
            ?>
        </div>
    </div>
</div>
<div style="margin-top:55px;"></div>
