<?php
include_once 'inc/mysql.inc.php';
include_once 'inc/tool.inc.php';

$link=connect();
if ($member_id=is_login($link)) {
    skip('index.php','你已经是会员了，请不要重复注册！','error');
}

if(isset($_POST['submit'])){
    $name = $_POST['name'];
    $name = escape($link, $name);
    $pw = $_POST['pw'];
    $re_pw = $_POST['confirm_pw'];
    $vcode = $_POST['vcode'];

    $url = 'register.php';
    $message = '';
    $pic = 'error';

    include 'inc/check_register.inc.php';

    $query="insert into bbs_member(name,pw,register_time) values('{$name}',md5('{$pw}'),now())";
    execute($link,$query);
    if(mysqli_affected_rows($link)==1){
        setcookie('bbs[name]', $name);
        setcookie('bbs[pw]', sha1(md5($pw)));
        skip('index.php','注册成功！','ok');
    }else{
        skip('register.php','注册失败,请重试！','error');
    }
}

$template['title']='注册会员';
$template['css']=array('style/public.css','style/register.css');
?>
<?php include_once 'inc/header.inc.php'?>
<div id="register" class="auto">
    <h2>欢迎注册成为 会员</h2>
    <form method="post">
        <label>用户名：<input type="text" name="name"  /><span>*用户名不得为空，并且长度不得超过32个字符</span></label>
        <label>密码：<input type="password" name="pw"  /><span>*密码不得少于6位</span></label>
        <label>确认密码：<input type="password" name="confirm_pw"  /><span>*请输入与上面一致</span></label>
        <label>验证码：<input name="vcode" name="vcode" type="text"  /><span>*请输入下方验证码</span></label>
        <img class="vcode" src="show_code.php" />
        <div style="clear:both;"></div>
        <input class="btn" name="submit" type="submit" value="确定注册" />
    </form>
</div>
<div id="footer" class="auto">
    <div class="bottom">
        <a>bbs</a>
    </div>
    <div class="copyright">Powered by bbs ©2021 bbs.com</div>
</div>
</body>
</html>