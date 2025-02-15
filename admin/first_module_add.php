<?php
include_once '../inc/tool.inc.php';
include_once '../inc/mysql.inc.php';


$template['title'] = '添加父版块';
$template['css'] = array('style/public.css');

include_once 'inc/is_manage_login.inc.php';

if (isset($_POST['submit'])) {
    $link = connect();
    $module_name = $_POST['module_name'];
    $sort = $_POST['sort'];

    $url = 'first_module_add.php';
    $pic = 'error';

    $check_flag='add';

    include_once 'inc/check_first_module.php';

    $insert = "insert into bbs_first_module(module_name, sort) values ('{$module_name}', '{$sort}')";
    execute($link, $insert);

    $url = 'first_module.php';
    $message = '恭喜你，添加成功！';
    $pic = 'ok';
    if (mysqli_affected_rows($link)) {
        skip($url, $message, $pic);
    } else {
        $url = 'first_module_add.php';
        $message = '添加失败，请重试！';
        $pic = 'error';
        skip($url, $message, $pic);
    }
}
?>
<?php
include_once 'inc/header.inc.php';
?>
    <div id="main">
        <div class="title" style="margin-bottom: 20px;">添加父版块</div>
        <form method="post">
            <table class="au">
                <tr>
                    <td>版块名称</td>
                    <td><input name="module_name" type="text"/></td>
                    <td>版块名称不能为空，不能超过66个字符！</td>
                </tr>
                <tr>
                    <td>排序</td>
                    <td><input name="sort" type="text" value="0"/></td>
                    <td>填写数字即可！</td>
                </tr>
            </table>
            <input style="margin-top: 20px;cursor: pointer;" class="btn" type="submit" name="submit" value="添加">
        </form>
    </div>
<?php include 'inc/footer.inc.php' ?>