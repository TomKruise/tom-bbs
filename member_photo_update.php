<?php
include_once 'inc/mysql.inc.php';
include_once 'inc/tool.inc.php';
include_once 'inc/upload.inc.php';
$link=connect();
if(!$member_id=is_login($link)){
    skip('login.php', '请登录之后再执行此操作!', 'error');
}

$query="select * from bbs_member where id={$_GET['id']}";
$result_memebr=execute($link, $query);
$data_member=mysqli_fetch_assoc($result_memebr);
$photo = $data_member['photo'];
if (isset($_POST['submit'])) {
    $save_path='uploads'.date('/Y/m/d');
    $upload = upload($save_path, '8M', 'photo');
    if ($upload['return']) {
        $query="update bbs_member set photo='{$upload['save_path']}' where id={$member_id}";
        execute($link, $query);
        if (mysqli_affected_rows($link)) {
            //检查是否是第一次修改，不是第一次修改需要删除之前存储的图片！
            if ($photo != '') {
                unlink(SERVER_ABSOLUTE_PATH . '/' . $photo);
            }
            skip("member.php?id={$member_id}", '头像设置成功！', 'ok');
        } else {
            skip('member_photo_upload.php', '头像设置出错，请重试！', 'error');
        }
    } else {
        skip('member_photo_upload.php', $upload['error'], 'error');
    }
}
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
<meta charset="utf-8" />
<title>更改头像</title>
<meta name="keywords" content="" />
<meta name="description" content="" />
<style type="text/css">
    body {
    font-size:12px;
	font-family:微软雅黑;
}
h2 {
    padding:0 0 10px 0;
	border-bottom: 1px solid #e3e3e3;
	color:#444;
}
.submit {
    background-color: #3b7dc3;
	color:#fff;
	padding:5px 22px;
	border-radius:2px;
	border:0px;
	cursor:pointer;
	font-size:14px;
}
#main {
	width:80%;
	margin:0 auto;
}
</style>
</head>
<body>
	<div id="main">
		<h2>更改头像</h2>
		<div>
			<h3>原头像：</h3>
			<img src="<?php if($photo!=''){echo SUB_URL.$data_member['photo'];}else{echo 'style/photo.jpg';}?>" />
		</div>
		<div style="margin:15px 0 0 0;">
			<form method="post" enctype="multipart/form-data">
				<input style="cursor:pointer;" width="100" type="file" name="photo"/><br /><br />
                <input class="submit" type="submit" name="submit" value="保存" />
            </form>
        </div>
	</div>
</body>
</html>