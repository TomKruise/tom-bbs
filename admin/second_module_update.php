<?php
include_once '../inc/tool.inc.php';
include_once '../inc/mysql.inc.php';
$template['title'] = '编辑子版块';
$template['css'] = array('style/public.css');

$id = $_GET['id'];
$url = 'second_module.php';
$pic = 'error';
if (!isset($id) || !is_numeric($id)) {
    skip($url, '非法的ID参数！', $pic);
}

$link = connect();
include_once 'inc/is_manage_login.inc.php';

$query="select s.id, s.module_name, f.module_name as f_name, s.info, s.member_id, s.sort from bbs_first_module as f, bbs_second_module as s where f.id = s.first_module_id and s.id = {$id}";
$result = execute($link, $query);

if (!mysqli_num_rows($result)) {
    skip($url, '没有查询到此id对应的子版块！', $pic);
}

$data = mysqli_fetch_assoc($result);
$module_name = $data['module_name'];
$f_name = $data['f_name'];
$info = $data['info'];
$member_id = $data['member_id'];
$sort = $data['sort'];

if (isset($_POST['submit'])) {
    $first_module_id = $_POST['first_module_id'];
    $module_name = $_POST['module_name'];
    $info = $_POST['info'];
    $member_id = $_POST['member_id'];
    $sort = $_POST['sort'];

    $url = 'second_module_update.php';
    $pic = 'error';

    $check_flag='update';

    include_once 'inc/check_second_module.php';

    $update = "update bbs_second_module set first_module_id={$first_module_id}, module_name='{$module_name}', info='{$info}', member_id={$member_id}, sort={$sort}  where id = {$id}";
    execute($link, $update);

    $url = 'second_module.php';
    $message = '恭喜你，修改子版块成功！';
    $pic = 'ok';

    if (mysqli_affected_rows($link)) {
        skip($url, $message, $pic);
    } else {
        $url = 'second_module_add.php';
        $message = '修改子版块失败，请重试！';
        $pic = 'error';
        skip($url, $message, $pic);
    }
}
?>

<?php include_once 'inc/header.inc.php' ?>

    <div id="main">
        <div class="title" style="margin-bottom: 20px;">编辑子版块 - <?php echo $module_name?></div>
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
                                $f_module_name = $first_data['module_name'];
                                $f_id = $first_data['id'];
                                if ($f_module_name == $f_name) {
                                    echo "<option value='{$f_id}' selected>{$f_module_name}</option>";
                                } else{
                                    echo "<option value='{$f_id}'>{$f_module_name}</option>";
                                }
                            }
                            ?>
                        </select></td>
                    <td>必须选择一个所属父版块！</td>
                </tr>
                <tr>
                    <td>版块名称</td>
                    <td><input name="module_name" type="text" <?php echo "value ='{$module_name}'"?>></td>
                    <td>版块名称不能为空，不能超过66个字符！</td>
                </tr>
                <tr>
                    <td>版块简介</td>
                    <td><textarea name="info" ><?php echo "{$info}"?></textarea></td>
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
                    <td><input name="sort" type="text" <?php echo "value = {$sort}"?>></td>
                    <td>填写数字即可！</td>
                </tr>
            </table>
            <input style="margin-top: 20px;cursor: pointer;" class="btn" type="submit" name="submit" value="修改">
        </form>
    </div>

<?php include_once 'inc/footer.inc.php'?>