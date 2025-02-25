<?php
include_once 'inc/mysql.inc.php';
include_once 'inc/tool.inc.php';
$link=connect();
if($member_id=is_login($link)){
    skip('index.php','你已经登录，请不要重复登录！','error');
}
if(isset($_POST['submit'])){
    include 'inc/check_login.inc.php';
    escape($link,$_POST);
    $query="select * from bbs_member where name='{$_POST['name']}' and pw=md5('{$_POST['pw']}')";
    $result=execute($link, $query);
    if(mysqli_num_rows($result)==1){
        setcookie('bbs[name]',$_POST['name'],time()+$_POST['time']);
        setcookie('bbs[pw]',sha1(md5($_POST['pw'])),time()+$_POST['time']);
        skip('index.php','登录成功！','ok');
    }else{
        skip('login.php', '用户名或密码填写错误！', 'error');
    }
}
$template['title']='登录会员 ';
$template['css']=array('style/public.css','style/register.css');
?>
<?php include 'inc/header.inc.php'?>
    <div id="register" class="auto">
        <h2>请登录</h2>
        <form method="post">
            <label>用户名：<input type="text" name="name"  /><span></span></label>
            <label>密码：<input type="password" name="pw"  /><span></span></label>
            <label>验证码：<input name="vcode" type="text"  /><span>*请输入下方验证码</span></label>
            <img class="vcode" src="show_code.php" />
            <label>自动登录：
                <select style="width:236px;height:25px;" name="time">
                    <option value="3600">1小时内</option>
                    <option value="86400">1天内</option>
                    <option value="259200">3天内</option>
                    <option value="2592000">30天内</option>
                </select>
                <span>*公共电脑上请勿长期自动登录</span>
            </label>
            <div style="clear:both;"></div>
            <input class="btn" type="submit" name="submit" value="登录" />
        </form>
    </div>
<?php include 'inc/footer.inc.php'?>