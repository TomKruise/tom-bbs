<?php
if (!is_numeric($first_module_id)) {
    skip($url, '所属父版块不能为空！', $pic);
}

$query = "select * from bbs_first_module where id = '{$first_module_id}'";
$result = execute($link, $query);

if (0 == mysqli_num_rows($result)) {
    skip($url, '所属父版块不存在！', $pic);
}

if (empty($module_name)) {
    skip($url, '子版块名称不能为空！', $pic);
}
if (mb_strlen($module_name, 'utf-8') > 66) {
    skip($url, '子版块名称长度不能大于66个字符！', $pic);
}

if (mb_strlen($info, 'utf-8') > 255) {
    skip($url, '子版块简介长度不能大于255个字符！', $pic);
}

if (!is_numeric($sort)) {
    skip($url, '排序只能是数字！', $pic);
}
$module_name = escape($link, $module_name);
$info = escape($link, $info);
$query = "select * from bbs_second_module where module_name='{$module_name}'";
switch ($check_flag) {
    case 'add':
        break;
    case 'update':
        $query = $query. "and id!='{$id}'";
        break;
    default:
        skip($url, "{$check_flag}参数错误！", $pic);
}
$result = execute($link, $query);

if (mysqli_num_rows($result)) {
    skip($url, '子版块已存在！', $pic);
}