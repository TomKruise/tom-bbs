<?php
if (isset($_POST['submit'])) {
    var_dump($_POST);
}
?>
<!DOCTYPE HTML>
<html>
<head>

    <meta http-equiv="Content-Type" content="text/html;charset=utf-8"/>
    <title></title>
    <script type="text/javascript" src="wangeditor/wangEditor.min.js"></script>
</head>
<body>
    <form method="post">
        <div id="div1">
            <p>欢迎使用 <b>wangEditor</b> 富文本编辑器</p>
            <textarea></textarea>
        </div>
<!--        <textarea id="text1" style="width:100%; height:200px;"></textarea>-->

        <input class="publish" type="submit" name="submit" value="submit" />
    </form>




    <!-- 引入 wangEditor.min.js -->
    <script type="text/javascript">

        const E = window.wangEditor
        const editor = new E('#div1')
        // 或者 const editor = new E( document.getElementById('div1') )

        // const $text1 = $('#text1')
        // editor.config.onchange = function (html) {
        //     // 第二步，监控变化，同步更新到 textarea
        //     $text1.val(html)
        // }
        editor.create()

        // 第一步，初始化 textarea 的值
        // $text1.val(editor.txt.html())
    </script>

</body>


</html>