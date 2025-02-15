<?php
include_once '../inc/tool.inc.php';
include_once '../inc/mysql.inc.php';
$template['title'] = '添加子版块';
$template['css'] = array('style/public.css');
$link = connect();
include_once 'inc/is_manage_login.inc.php';
if (isset($_POST['submit'])) {
    $first_module_id = $_POST['first_module_id'];
    $module_name = $_POST['module_name'];
    $info = $_POST['info'];
    $member_id = $_POST['member_id'];
    $sort = $_POST['sort'];

    $url = 'second_module_add.php';
    $pic = 'error';

    $check_flag='add';

    include_once 'inc/check_second_module.php';

    $insert = "insert into bbs_second_module(first_module_id, module_name, info, member_id, sort) values ('{$first_module_id}', '{$module_name}', '{$info}', '{$member_id}', '{$sort}')";
    execute($link, $insert);

    $url = 'second_module.php';
    $message = '恭喜你，添加子版块成功！';
    $pic = 'ok';

    if (mysqli_affected_rows($link)) {
        skip($url, $message, $pic);
    } else {
        $url = 'second_module_add.php';
        $message = '添加子版块失败，请重试！';
        $pic = 'error';
        skip($url, $message, $pic);
    }
}
?>

<?php include_once 'inc/header.inc.php' ?>

    <div id="main">
        <div class="title" style="margin-bottom: 20px;">添加子版块</div>
        <form method="post">
            <table class="au">
                <tr>
                    <td>所属父版块</td>
                    <td><select name="first_module_id">
                            <option value="0">=====选择一个父版块=====</option>
                            <?php
                            $query = "select * from bbs_first_module";
                            $first_result = execute($link, $query);
                            while ($first_data = mysqli_fetch_assoc($first_result)) {
                                $module_name = $first_data['module_name'];
                                $id = $first_data['id'];
                                echo "<option value='{$id}'>{$module_name}</option>";
                            }
                            ?>
                        </select></td>
                    <td>必须选择一个所属父版块！</td>
                </tr>
                <tr>
                    <td>版块名称</td>
                    <td><input name="module_name" type="text"/></td>
                    <td>版块名称不能为空，不能超过66个字符！</td>
                </tr>
                <tr>
                    <td>版块简介</td>
                    <td><textarea name="info"></textarea></td>
                    <td>版块简介，不能超过255个字符！</td>
                </tr>
                <tr>
                    <td>版主</td>
                    <td><select name="member_id">
                            <option value="0">=====选择一个会员作为版主=====</option>
                        </select></td>
                    <td>可以选择一个会员作为版主！</td>
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

<?php include_once 'inc/footer.inc.php'?>