<?php
include_once '../inc/tool.inc.php';
include_once '../inc/mysql.inc.php';
$template['title'] = '编辑父版块';
$template['css'] = array('style/public.css');

$id = $_GET['id'];
$url = 'first_module.php';
$pic = 'error';
include_once 'inc/is_manage_login.inc.php';

if (!isset($id) || !is_numeric($id)) {
    skip($url, '非法的ID参数！', $pic);
}

$link = connect();
$query = "select * from bbs_first_module where id='{$id}'";
$result = execute($link, $query);

if (!mysqli_num_rows($result)) {
    skip($url, '没有查询到此id对应到版块！', $pic);
}

$data = mysqli_fetch_assoc($result);
$module_name = $data['module_name'];
$sort = $data['sort'];

if (isset($_POST['submit'])) {
    $module_name = $_POST['module_name'];
    $sort = $_POST['sort'];

    $check_flag='update';

    include_once 'inc/check_first_module.php';

    $update = "update bbs_first_module set module_name='{$module_name}', sort='{$sort}' where id={$id}";
    execute($link, $update);

    $url = 'first_module.php';
    $message = '恭喜你，修改成功！';
    $pic = 'ok';
    if (mysqli_affected_rows($link)) {
        skip($url, $message, $pic);
    } else {
        $url = 'first_module_update.php';
        $message = '修改失败，请重试！';
        $pic = 'error';
        skip($url, $message, $pic);
    }
}



?>
<?php
include_once 'inc/header.inc.php'?>
<div id="main">
    <div class="title" style="margin-bottom: 20px;">修改父版块 - <?php echo $module_name?></div>
    <form method="post">
        <table class="au">
            <?php
                $html = <<<h
            <tr>
                <td>版块名称</td>
                <td><input name="module_name" type="text" value="{$module_name}"/></td>
                <td>版块名称不能为空，不能超过66个字符！</td>
            </tr>
            <tr>
                <td>排序</td>
                <td><input name="sort" type="text" value="{$sort}"></td>
                <td>填写数字即可！</td>
            </tr>
h;
            echo $html;
            ?>

        </table>
        <input style="margin-top: 20px;cursor: pointer;" class="btn" type="submit" name="submit" value="修改">
    </form>
</div>
<?php
include_once 'inc/footer.inc.php'?>
