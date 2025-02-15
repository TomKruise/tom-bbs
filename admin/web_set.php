<?php
include_once '../inc/tool.inc.php';
include_once '../inc/mysql.inc.php';
$template['title'] = '站点设置';
$template['css'] = array('style/public.css');
$link = connect();
include_once 'inc/is_manage_login.inc.php';

$query="select * from bbs_info";
$result = execute($link, $query);
$data = mysqli_fetch_assoc($result);

$title = $data['title'];
$keywords = $data['keywords'];
$description = $data['description'];

if (isset($_POST['submit'])) {
    $_POST = escape($link, $_POST);
    $title = $_POST['title'];
    $keywords = $_POST['keywords'];
    $description = $_POST['description'];

    $update = "update bbs_info set title='{$title}', keywords='{$keywords}', description='{$description}'";
    execute($link, $update);

    $url = 'index.php';
    $message = '恭喜你，设置成功！';
    $pic = 'ok';

    if (mysqli_affected_rows($link)) {
        skip($url, $message, $pic);
    } else {
        $url = 'web_set.php';
        $message = '设置失败，请重试！';
        $pic = 'error';
        skip($url, $message, $pic);
    }
}
?>

<?php include_once 'inc/header.inc.php' ?>

    <div id="main">
        <div class="title" style="margin-bottom: 20px;">网站设置</div>
        <form method="post">
            <table class="au">
                <tr>
                    <td>网站标题</td>
                    <td><input name="title" type="text" value="<?php echo $title?>"/></td>
                    <td>前台页面的标题！</td>
                </tr>
                <tr>
                    <td>关键字</td>
                    <td><input name="keywords" type="text" value="<?php echo $keywords?>"/></td>
                    <td>关键字！</td>
                </tr>
                <tr>
                    <td>描述</td>
                    <td><textarea name="description"><?php echo $description?></textarea></td>
                    <td>描述！</td>
                </tr>
            </table>
            <input style="margin-top: 20px;cursor: pointer;" class="btn" type="submit" name="submit" value="设置">
        </form>
    </div>

<?php include_once 'inc/footer.inc.php'?>