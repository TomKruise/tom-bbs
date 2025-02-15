<?php
include_once 'inc/mysql.inc.php';
include_once 'inc/tool.inc.php';
$link=connect();
$member_id=is_login($link);
$is_manage_login = is_manage_login($link);
if(!$member_id && !$is_manage_login){
    skip('login.php', '请登录之后再执行编辑操作操作!', 'error');
}

if(!isset($_GET['id']) || !is_numeric($_GET['id'])){
    skip('index.php', '帖子id参数不合法!', 'error');
}

$query="select * from bbs_content where id={$_GET['id']}";
$result_content = execute($link, $query);
if (mysqli_num_rows($result_content) == 1) {
    $data_content = mysqli_fetch_assoc($result_content);
    $data_content['title'] = htmlspecialchars($data_content['title']);
    if (check_member_id($member_id, $data_content['member_id'], $is_manage_login)) {
        if (isset($_POST['submit'])) {
            include 'inc/check_publish.inc.php';
            $_POST = escape($link, $_POST);
            $query = "update bbs_content set module_id={$_POST['module_id']}, title='{$_POST['title']}', content='{$_POST['content']}' where id={$_GET['id']}";
            execute($link, $query);

            if (isset($_GET['return_url'])) {
                $return_url = $_GET['return_url'];
            } else {
                $return_url = "member.php?id={$member_id}";
            }

            if(mysqli_affected_rows($link)==1){
                skip($return_url, '修改成功！', 'ok');
            }else{
                skip($return_url, '修改失败，请重试！', 'error');
            }
        }
    } else {
        skip('index.php', '无操作权限!', 'error');
    }
} else {
    skip('index.php', '帖子不存在!', 'error');
}

$template['title']='帖子编辑页';
$template['css']=array('style/public.css','style/publish.css');
?>
<?php include 'inc/header.inc.php'?>
    <div id="position" class="auto">
        <a href="index.php">首页</a> &gt; 发布帖子
    </div>
    <div id="publish">
        <form method="post">
            <select name="module_id">
                <option value="-1">请选择一个子版块</option>
                <?php
                $query="select * from bbs_first_module order by sort desc";
                $result_father=execute($link, $query);
                while ($data_father=mysqli_fetch_assoc($result_father)){
                    echo "<optgroup label='{$data_father['module_name']}'>";
                    $query="select * from bbs_second_module where first_module_id={$data_father['id']} order by sort desc";
                    $result_son=execute($link, $query);
                    while ($data_son=mysqli_fetch_assoc($result_son)){
                        if ($data_son['id'] == $data_content['module_id']) {
                            echo "<option value='{$data_son['id']}' selected='selected'>{$data_son['module_name']}</option>";
                        } else {
                            echo "<option value='{$data_son['id']}'>{$data_son['module_name']}</option>";
                        }
                    }
                    echo "</optgroup>";
                }
                ?>
            </select>
            <input class="title" placeholder="请输入标题" value="<?php echo $data_content['title']?>" name="title" type="text" />
            <textarea name="content" class="content"><?php echo $data_content['content']?></textarea>
            <input class="publish" type="submit" name="submit" value="" />
            <div style="clear:both;"></div>
        </form>
    </div>
<?php include 'inc/footer.inc.php'?>