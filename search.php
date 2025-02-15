<?php
include_once 'inc/mysql.inc.php';
include_once 'inc/tool.inc.php';
include_once 'inc/page.inc.php';
$link=connect();
$member_id=is_login($link);
$is_manage_login = is_manage_login($link);

$keyword = $_GET['keyword'];
if (!isset($_GET['keyword'])) {
    $keyword = '';
}
$keyword = trim($keyword);
$keyword = escape($link, $keyword);

$query="select count(1) from bbs_content where title like '%{$keyword}%'";
$count_all=num($link,$query);

if (!$count_all) {
    $count_all = 0;
}

$template['title']='搜索页';
$template['css']=array('style/public.css','style/list.css');
?>
<?php include 'inc/header.inc.php'?>
    <div id="position" class="auto">
        <a href="index.php">首页</a> &gt;  <?php echo $template['title']?>
    </div>
    <div id="main" class="auto">
        <div id="left">
            <div class="box_wrap">
                <h3>共有<?php echo $count_all?>条结果</h3>
                <div class="pages_wrap">
                    <div class="pages">
                        <?php
                        $page=page($count_all,5);
                        echo $page['html'];
                        ?>
                    </div>
                    <div style="clear:both;"></div>
                </div>
            </div>
            <div style="clear:both;"></div>
            <ul class="postsList">
                <?php
                $query="select
			bbs_content.title,bbs_content.id,bbs_content.time,bbs_content.times,bbs_content.member_id,bbs_member.name,bbs_member.photo
			from bbs_content,bbs_member where
			bbs_content.title like '%{$keyword}%' and
			bbs_content.member_id=bbs_member.id
			{$page['limit']}";
                $result_content=execute($link,$query);
                $data_content = array();
                while($data_content=mysqli_fetch_assoc($result_content)){
                    $data_content['title']=htmlspecialchars($data_content['title']);
                    $super_keyword="<span style='color: red'>{$keyword}</span>";
                    $super_title = str_replace($keyword, $super_keyword,$data_content['title']);

                    $query="select time from bbs_reply where content_id={$data_content['id']} order by id desc limit 1";
                    $result_last_reply = execute($link, $query);
                    if (!mysqli_num_rows($result_last_reply)) {
                        $last_time = '无';
                    } else {
                        $data_last_reply = mysqli_fetch_assoc($result_last_reply);
                        $last_time = $data_last_reply['time'];
                    }


                    $query="select count(1) from bbs_reply where content_id={$data_content['id']}";
                    ?>
                    <li>
                        <div class="smallPic">
                            <a href="member.php?id=<?php echo $data_content['member_id']?>" target="_blank">
                                <img width="45" height="45"src="<?php if($data_content['photo']!=''){echo SUB_URL.$data_content['photo'];}else{echo 'style/photo.jpg';}?>">
                            </a>
                        </div>
                        <div class="subject">
                            <div class="titleWrap"><h2><a href="show.php?id=<?php echo $data_content['id']?>"><?php echo $super_title?></a></h2></div>
                            <p>
                                楼主：<?php echo $data_content['name']?>&nbsp;<?php echo $data_content['time']?>&nbsp;&nbsp;&nbsp;&nbsp;最后回复：<?php echo $last_time?>
                                <?php
                                if (check_member_id($member_id, $data_content['member_id'], $is_manage_login)){
                                    $return_url=urlencode($_SERVER['REQUEST_URI']);
                                    $url=urlencode("first_module_delete.php?id={$data_content['id']}&return_url={$return_url}");
                                    $message="你确定要删除帖子<b>{$data_content['title']}</b>吗？";
                                    $delete_url="confirm.php?url={$url}&return_url={$return_url}&message={$message}";
                                    echo "<br/><a href='content_update.php?id={$data_content['id']}&return_url={$return_url}'>编辑</a> <a href='{$delete_url}'>删除</a>";
                                }
                                ?>
                            </p>
                        </div>
                        <div class="count">
                            <p>
                                回复<br /><span><?php echo num($link, $query)?></span>
                            </p>
                            <p>
                                浏览<br /><span><?php echo $data_content['times']?></span>
                            </p>
                        </div>
                        <div style="clear:both;"></div>
                    </li>
                <?php }?>
            </ul>
            <div class="pages_wrap">
                <div class="pages">
                    <?php
                    echo $page['html'];
                    ?>
                </div>
                <div style="clear:both;"></div>
            </div>
        </div>
        <div id="right">
            <div class="classList">
                <div class="title">版块列表</div>
                <ul class="listWrap">
                    <?php
                    $query="select * from bbs_first_module";
                    $result_father=execute($link, $query);
                    while($data_father=mysqli_fetch_assoc($result_father)){
                        ?>
                        <li>
                            <h2><a href="list_first.php?id=<?php echo $data_father['id']?>"><?php echo $data_father['module_name']?></a></h2>
                            <ul>
                                <?php
                                $query="select * from bbs_second_module where first_module_id={$data_father['id']}";
                                $result_son=execute($link, $query);
                                while($data_son=mysqli_fetch_assoc($result_son)){
                                    ?>
                                    <li><h3><a href="list_second.php?id=<?php echo $data_son['id']?>"><?php echo $data_son['module_name']?></a></h3></li>
                                    <?php
                                }
                                ?>
                            </ul>
                        </li>
                        <?php
                    }
                    ?>
                </ul>
            </div>
        </div>
        <div style="clear:both;"></div>
    </div>
<?php include 'inc/footer.inc.php'?>