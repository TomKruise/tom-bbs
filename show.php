<?php
include_once 'inc/mysql.inc.php';
include_once 'inc/tool.inc.php';
include_once 'inc/page.inc.php';
$link=connect();
$member_id=is_login($link);

if(!isset($_GET['id']) || !is_numeric($_GET['id'])){
    skip('index.php', '帖子id参数不合法!', 'error');
}
$query="select sc.id cid,sc.module_id,sc.title,sc.content,sc.time,sc.member_id,sc.times,sm.name,sm.photo from bbs_content sc,bbs_member sm where sc.id={$_GET['id']} and sc.member_id=sm.id";
$result_content=execute($link,$query);
if(mysqli_num_rows($result_content)!=1){
    skip('index.php', '本帖子不存在!', 'error');
}

$update = "update bbs_content set times=times+1 where id={$_GET['id']}";
execute($link, $update);

$data_content=mysqli_fetch_assoc($result_content);
$data_content['title']=htmlspecialchars($data_content['title']);
$data_content['content']=nl2br(htmlspecialchars($data_content['content']));
$query="select * from bbs_second_module where id={$data_content['module_id']}";
$result_son=execute($link,$query);
$data_son=mysqli_fetch_assoc($result_son);

$query="select * from bbs_first_module where id={$data_son['first_module_id']}";
$result_father=execute($link,$query);
$data_father=mysqli_fetch_assoc($result_father);

$template['title']=$data_content['title'];
$template['css']=array('style/public.css','style/show.css');

?>
<?php include 'inc/header.inc.php'?>
    <div id="position" class="auto">
        <a href="index.php">首页</a> &gt; <a href="list_first.php?id=<?php echo $data_father['id']?>"><?php echo $data_father['module_name']?></a> &gt; <a href="list_second.php?id=<?php echo $data_son['id']?>"><?php echo $data_son['module_name']?></a> &gt; <?php echo $data_content['title']?>
    </div>
    <div id="main" class="auto">
        <div class="wrap1">
            <div class="pages">
                <?php
                $query = "select count(1) from bbs_reply where content_id={$_GET['id']}";
                $count_reply = num($link, $query);
                $page_size = 5;
                $page = page($count_reply, $page_size);
                echo $page['html'];
                ?>
            </div>
            <a class="btn reply" href="reply.php?id=<?php echo $_GET['id']?>" target="_blank"></a>
            <div style="clear:both;"></div>
        </div>
        <?php
        if ($_GET['page']==1)
        {
        ?>
        <div class="wrapContent">
            <div class="left">
                <div class="face">
                    <a target="_blank" href="">
                        <img width=120 height=120 src="<?php if($data_content['photo']!=''){echo $data_content['photo'];}else{echo 'style/photo.jpg';}?>" />
                    </a>
                </div>
                <div class="name">
                    <a href=""><?php echo $data_content['name']?></a>
                </div>
            </div>
            <div class="right">
                <div class="title">
                    <h2><?php echo $data_content['title']?></h2>
                    <span>阅读：<?php echo $data_content['times']?>&nbsp;|&nbsp;回复：<?php echo $count_reply?></span>
                    <div style="clear:both;"></div>
                </div>
                <div class="pubdate">
                    <span class="date">发布于：<?php echo $data_content['time']?> </span>
                    <span class="floor" style="color:red;font-size:14px;font-weight:bold;">楼主</span>
                </div>
                <div class="content">
                    <?php echo $data_content['content']?>
                </div>
            </div>
            <div style="clear:both;"></div>
        </div>
        <?php
        }
        ?>
        <?php
        $query="select m.name, r.member_id, r.quote_id, m.photo, r.time, r.id, r.content from bbs_reply r, bbs_member m where r.member_id=m.id and r.content_id={$_GET['id']} order by id asc {$page['limit']}";
        $result_reply = execute($link, $query);
        $i=($_GET['page']-1)*$page_size + 1;
        while ($data_reply=mysqli_fetch_assoc($result_reply)) {
            $data_reply['content'] = nl2br(htmlspecialchars($data_reply['content']));
        ?>
        <div class="wrapContent">
            <div class="left">
                <div class="face">
                    <a target="_blank" href="">
                        <img width=120 height=120 src="<?php if($data_reply['photo']!=''){echo $data_reply['photo'];}else{echo 'style/photo.jpg';}?>" />
                    </a>
                </div>
                <div class="name">
                    <a href=""><?php echo $data_reply['name']?></a>
                </div>
            </div>
            <div class="right">
                <div class="pubdate">
                    <span class="date">回复时间：<?php echo $data_reply['time']?></span>
                    <span class="floor"><?php echo $i++?>楼&nbsp;|&nbsp;<a href="quote.php?id=<?php echo $_GET['id']?>&reply_id=<?php echo $data_reply['id']?>">引用</a></span>
                </div>
                <div class="content">
                    <?php
                    if ($data_reply['quote_id']) {
                        $query="select count(*) from bbs_reply where content_id={$_GET['id']} and id<={$data_reply['quote_id']}";
                        $floor=num($link,$query);

                        $query="select bbs_reply.content,bbs_member.name from bbs_reply,bbs_member where bbs_reply.id={$data_reply['quote_id']} and bbs_reply.content_id={$_GET['id']} and bbs_reply.member_id=bbs_member.id";
                        $result_quote=execute($link, $query);
                        $data_quote=mysqli_fetch_assoc($result_quote);
                    ?>
                    <div class="quote">
                        <h2>引用 <?php echo $floor?>楼 <?php echo $data_quote['name']?> 发表的: </h2>
                        <?php echo $data_quote['content']?>
                    </div>
                    <?php
                    }
                    ?>
                    <?php echo nl2br($data_reply['content'])?>
                </div>
            </div>
            <div style="clear:both;"></div>
        </div>
        <?php
        }
        ?>
        <div class="wrap1">
            <div class="pages">
                <?php
                echo $page['html'];
                ?>
            </div>
            <a class="btn reply" href="reply.php?id=<?php echo $_GET['id']?>" target="_blank"></a>
            <div style="clear:both;"></div>
        </div>
    </div>
<?php include 'inc/footer.inc.php'?>