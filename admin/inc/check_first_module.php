<?php
if (empty($module_name)) {
    skip($url, '版块名称不能为空！', $pic);
}
if (mb_strlen($module_name, 'utf-8') > 66) {
    skip($url, '版块名称长度不能大于66个字符！', $pic);
}
if (!is_numeric($sort)) {
    skip($url, '排序只能是数字！', $pic);
}
$module_name = escape($link, $module_name);
$query = "select * from bbs_first_module where module_name='{$module_name}'";
switch ($check_flag) {
    case 'add':
        $query = "select * from bbs_first_module where module_name='{$module_name}'";
        break;
    case 'update':
        $query = "select * from bbs_first_module where module_name='{$module_name} and id!={$id}'";
        break;
    default:
        skip($url, "{$check_flag}参数错误！", $pic);
}
$result = execute($link, $query);

if (mysqli_num_rows($result)) {
    skip($url, '版块已存在！', $pic);
}