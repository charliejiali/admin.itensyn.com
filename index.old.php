<!DOCTYPE html>
<html>
<head>
    <?php include_once("module/head_tag.php"); ?>
    <style>

    </style>
</head>
<body>
<!-- Header Start -->
<table style="width: 100%; height: 100%; border-collapse: collapse; border-spacing: 0; box-sizing: border-box" cellspacing="0" cellpadding="0" border="0">
    <tr>
        <td style="width: 120px; background: #ddd; border: none; border-right:1px solid #aaa;  ">
            <h3 class="pure-menu-heading">模板清单</h3>

            <ul class="pure-menu-list">
<!--                <li class="pure-menu-item"><a href="home.php" class="pure-menu-link" target="page">首 页</a></li>-->
                <li class="pure-menu-item"><a href="user_list.php" class="pure-menu-link" target="page">用户列表</a></li>
                <li class="pure-menu-item"><a href="user_info.php" class="pure-menu-link" target="page">用户信息</a></li>
                <li class="pure-menu-item"><a href="user_add.php" class="pure-menu-link" target="page">新建用户</a></li>
                <li class="pure-menu-item">
                    <hr>
                   <h3> -- 媒 体 数 据</h3>
                </li>
                <li class="pure-menu-item"><a href="movie_log.php" class="pure-menu-link" target="page">待审核录入单</a></li>
                <li class="pure-menu-item"><a href="movie_log_auditing.php" class="pure-menu-link" target="page">审核录入单...详情</a></li>

                <li class="pure-menu-item"><a href="movie_log_audited.php" class="pure-menu-link" target="page">已审核录入单</a></li>
                <li class="pure-menu-item"><a href="movie_log_audited_view.php" class="pure-menu-link" target="page">已审核录入单...详情</a></li>

                <li class="pure-menu-item"><a href="movie_list.php" class="pure-menu-link" target="page">剧 目 列 表</a></li>
                <li class="pure-menu-item"><a href="movie_info.php" class="pure-menu-link" target="page">剧目详情</a></li>
                <li class="pure-menu-item"><a href="movie_list_delete.php" class="pure-menu-link" target="page">已删除剧目</a></li>

                <li class="pure-menu-item">
                    <hr>
                    <h3> -- 腾 信 数 据</h3>
                </li>

                <li class="pure-menu-item"><a href="tensyn_movie_list.php" class="pure-menu-link" target="page">数 据 管 理</a></li>
                
                <li class="pure-menu-item"><a href="tensyn_movie_log.php" class="pure-menu-link" target="page">待审腾信录入单</a></li>
                <li class="pure-menu-item"><a href="tensyn_movie_log_auditing.php" class="pure-menu-link" target="page">待审腾信录入单...审核</a></li>
                <li class="pure-menu-item"><a href="tensyn_movie_log_audit_h.php" class="pure-menu-link" target="page">待审腾信录入单...横向审核</a></li>

                <li class="pure-menu-item"><a href="tensyn_movie_edit.php" class="pure-menu-link" target="page">数 据 录 入</a></li>
                <li class="pure-menu-item"><a href="tensyn_movie_log_audited.php" class="pure-menu-link" target="page">腾信录入单管理</a></li>

                <li class="pure-menu-item">
                    <hr>
                </li>
                <li class="pure-menu-item"><a href="notification.php" class="pure-menu-link" target="page">通知管理</a></li>
                <li class="pure-menu-item"><a href="notification_add.php" class="pure-menu-link" target="page">新建推送内容</a></li>
                <li class="pure-menu-item">
                    <hr>
                </li>
            </ul>
        </td>
        <td>
            <iframe src="movie_log.php" id="page" name="page" style="width: 100%; height: 99.6%; border: none"></iframe>
        </td>
    </tr>
</table>
</body>
</html>