<?php
include_once '../inc/tool.inc.php';
include_once '../inc/mysql.inc.php';
$link=connect();
$template['title']='父版块列表';
$template['css']=array('style/public.css');

if (isset($_POST['submit'])) {
    $url = 'first_module.php';
    $message = '排序参数错误！';
    $pic = 'error';
    foreach ($_POST['sort'] as $k => $v) {
        if (!is_numeric($k) || !is_numeric($v)) {
            skip($url, $message, $pic);
        }
        $query[] = "update bbs_first_module set sort={$v} where id={$k}";
    }
    $link = connect();

    if (execute_multi($link, $query,$error)) {
        $pic = 'ok';
        $message = '排序修改成功！';
        skip($url, $message, $pic);
    } else {
        skip($url, $error, $pic);
    }
}
?>
<?php
include 'inc/header.inc.php'?>
    <div id="main">
        <div class="title">父版块列表</div>
        <form method="post">
            <table class="list">
                <tr>
                    <th>排序</th>
                    <th>版块名称</th>
                    <th>操作</th>
                </tr>
                <?php
                $query="select * from bbs_first_module order by sort asc";
                $result=execute($link,$query);
                while ($data=mysqli_fetch_assoc($result)){
                    $id = $data['id'];
                    $module_name = $data['module_name'];
                    $sort = $data['sort'];

                    $url=urlencode("first_module_delete.php?id={$id}");
                    $return_url=urlencode($_SERVER['REQUEST_URI']);
                    $message="你确定要删除父版块<b>{$module_name}</b>吗？";
                    $delete_url="confirm.php?url={$url}&return_url={$return_url}&message={$message}";

                    $html=<<<A
			<tr>
				<td><input class="sort" type="text" name="sort[{$id}]" value="{$sort}"/></td>
				<td>{$module_name}[id:{$id}]</td>
				<td><a href="#">[访问]</a>&nbsp;&nbsp;<a href="first_module_update.php?id={$id}">[编辑]</a>&nbsp;&nbsp;<a href="$delete_url">[删除]</a></td>
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