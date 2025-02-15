<?php
if(mb_strlen($_POST['content'])<3){
    skip($_SERVER['REQUEST_URI'], '对不起回复内容不得少于3个字!', 'error');
}