<?php
include_once '../inc/tool.inc.php';
include_once '../inc/mysql.inc.php';
$link=connect();
$template['title']='子版块列表';
$template['css']=array('style/public.css');
include_once 'inc/is_manage_login.inc.php';
if (isset($_POST['submit'])) {
    $url = 'second_module.php';
    $message = '排序参数错误！';
    $pic = 'error';
    foreach ($_POST['sort'] as $k => $v) {
        if (!is_numeric($k) || !is_numeric($v)) {
            skip($url, $message, $pic);
        }
        $query[] = "update bbs_second_module set sort={$v} where id={$k}";
    }
    $link = connect();

    if (execute_multi($link, $query,$error)) {
        $pic = 'ok';
        $message = '子版块排序修改成功！';
        skip($url, $message, $pic);
    } else {
        skip($url, $error, $pic);
    }
}
?>
<?php include 'inc/header.inc.php'?>
    <div id="main">
        <div class="title">子版块列表</div>
        <form method="post">
            <table class="list">
                <tr>
                    <th>排序</th>
                    <th>版块名称</th>
                    <th>所属父版块</th>
                    <th>版主</th>
                    <th>操作</th>
                </tr>
                <?php
                $query="select s.id, s.module_name, f.module_name as f_name, s.member_id, s.sort from bbs_first_module as f, bbs_second_module as s where f.id = s.first_module_id order by f_name, s.sort asc";
                $result=execute($link,$query);
                while ($data=mysqli_fetch_assoc($result)){
                    $id = $data['id'];
                    $module_name = $data['module_name'];
                    $f_name = $data['f_name'];
                    $member_id = $data['member_id'];
                    $sort = $data['sort'];

                    $url=urlencode("second_module_delete.php?id={$id}");
                    $return_url=urlencode($_SERVER['REQUEST_URI']);
                    $message="你确定要删除子版块<b>{$module_name}</b>吗？";
                    $delete_url="confirm.php?url={$url}&return_url={$return_url}&message={$message}";

                    $html=<<<A
			<tr>
				<td><input class="sort" type="text" name="sort[{$id}]" value="{$sort}"/></td>
				<td>{$module_name}[id:{$id}]</td>
				<td>{$f_name}</td>
				<td>{$member_id}</td>
				<td><a href="#">[访问]</a>&nbsp;&nbsp;<a href="second_module_update.php?id={$id}">[编辑]</a>&nbsp;&nbsp;<a href="$delete_url">[删除]</a></td>
			</tr>
A;
                    echo $html;
                }
                ?>

            </table>
            <input style="margin-top: 20px;cursor: pointer;" class="btn" type="submit" name="submit" value="排序">
        </form>
    </div>
<?php include 'inc/footer.inc.php'?>