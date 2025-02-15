<?php include_once 'inc/vcode.inc.php'?>
<?php
session_start();
$_SESSION['vcode']=vcode();