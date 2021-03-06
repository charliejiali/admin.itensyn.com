<a href="../" class="logo"></a>
<div class="pure-menu menu-mode">
    <ul class="pure-menu-list">
        <li class="pure-menu-item <?php if ($pageNavId == 1) {echo " pure-menu-selected";} ?>"><a href="../user_list.php" class="pure-menu-link"><span class="icon-tools ico-home"></span>用 户 管 理</a></li>
        <li class="pure-menu-item top-ten <?php if ($pageNavId == 1) {echo " pure-menu-selected";} ?>">
            <a href="../user_list.php" class="pure-menu-link <?php if ($pageNavSub == 11) {echo " active";} ?>">用户列表</a>
            <a href="../user_add.php" class="pure-menu-link <?php if ($pageNavSub == 12) {echo " active";} ?>">新建用户</a>
        </li>


        <li class="pure-menu-item <?php if ($pageNavId == 2) {echo " pure-menu-selected";} ?>"><a href="../movie_log.php" class="pure-menu-link"><span class="icon-tools ico-movie"></span>媒 体 数 据</a>
            <?php if($unaudit_count>0){ ?>
                <div class="tag-notification"><?php echo $unaudit_count;?></div>
            <?php } ?>
        </li>
        <li class="pure-menu-item top-ten <?php if ($pageNavId == 2) {echo " pure-menu-selected";} ?>">
            <a href="../movie_log.php" class="pure-menu-link <?php if ($pageNavSub == 21) {echo " active";} ?>">待审核录入单</a>
            <!--            <a href="../movie_log_audited.php" class="pure-menu-link --><?php //if ($pageNavSub == 22) {echo " active";} ?><!--">已审核录入单</a>-->
            <a href="../movie_list.php" class="pure-menu-link <?php if ($pageNavSub == 23) {echo " active";} ?>">剧 目 列 表</a>
            <a href="movie_edit.php" class="pure-menu-link <?php if ($pageNavSub == 27) {echo " active";} ?>">补充媒体数据</a>
            <a href="../movie_list_delete_audit.php" class="pure-menu-link <?php if ($pageNavSub == 25) {echo " active";} ?>">删 除 审 核</a>
            <a href="../movie_list_delete.php" class="pure-menu-link <?php if ($pageNavSub == 24) {echo " active";} ?>">已删除剧目</a>
        </li>


        <!--        <li class="pure-menu-item --><?php //if ($pageNavId == 7) {echo " pure-menu-selected";} ?><!--"><a href="../media_upload/movie_edit.php" class="pure-menu-link"><span class="icon-audit ico-data"></span>补充媒体数据</a></li>-->
        <!--        <li class="pure-menu-item top-ten --><?php //if ($pageNavId == 7) {echo " pure-menu-selected";} ?><!--">-->
        <!--            <a href="../media_upload/movie_edit.php" class="pure-menu-link --><?php //if ($pageNavSub == 7) {echo " active";} ?><!--">补充媒体数据</a>-->
        <!--        </li>-->


        <li class="pure-menu-item <?php if ($pageNavId == 8) {echo " pure-menu-selected";} ?>"><a href="../crawler_video.php" class="pure-menu-link"><span class="icon-audit ico-recommend"></span>爬 虫 数 据</a></li>
        <li class="pure-menu-item top-ten <?php if ($pageNavId == 8) {echo " pure-menu-selected";} ?>">
            <!-- <a href="../crawler_video_masterpiece.php" class="pure-menu-link <?php if ($pageNavSub == 81) {echo " active";} ?>">代 表 作</a> -->
            <a href="../crawler_video.php" class="pure-menu-link <?php if ($pageNavSub == 81) {echo " active";} ?>">待补充爬虫数据内容列表</a>
            <a href="../crawler_weibo.php" class="pure-menu-link <?php if ($pageNavSub == 82) {echo " active";} ?>">微博数据</a>
            <a href="../crawler_tieba.php" class="pure-menu-link <?php if ($pageNavSub == 83) {echo " active";} ?>">贴吧数据</a>
            <!-- <a href="../crawler_index.php" class="pure-menu-link <?php if ($pageNavSub == 84) {echo " active";} ?>">指 数</a> -->
        </li>

        <li class="pure-menu-item <?php if ($pageNavId == 3) {echo " pure-menu-selected";} ?>"><a href="../tensyn_movie_list.php" class="pure-menu-link"><span class="icon-audit ico-data"></span>待人工采集数据</a></li>
        <li class="pure-menu-item top-ten <?php if ($pageNavId == 3) {echo " pure-menu-selected";} ?>">
            <a href="../tensyn_movie_list.php" class="pure-menu-link <?php if ($pageNavSub == 31) {echo " active";} ?>">已爬完剧目列表下载</a>
            <a href="../tensyn_movie_edit.php" class="pure-menu-link <?php if ($pageNavSub == 33) {echo " active";} ?>">人工补充完剧目上传</a>
            <a href="../tensyn_movie_log.php" class="pure-menu-link <?php if ($pageNavSub == 32) {echo " active";} ?>">待审录入单</a>
            <!--            <a href="../tensyn_movie_log_audited.php" class="pure-menu-link --><?php //if ($pageNavSub == 34) {echo " active";} ?><!--">腾信录入单管理</a>-->
        </li>
        <li class="pure-menu-item <?php if ($pageNavId == 7) {echo " pure-menu-selected";} ?>"><a href="../program_list.php" class="pure-menu-link"><span class="icon-audit ico-data"></span>正在评估剧目</a></li>
        <li class="pure-menu-item <?php if ($pageNavId == 9) {echo " pure-menu-selected";} ?>"><a href="../history_list.php" class="pure-menu-link"><span class="icon-audit ico-message"></span>历史剧目</a></li>

        <!--        <li class="pure-menu-item --><?php //if ($pageNavId == 1) {echo " pure-menu-selected";} ?><!--"><a href="../user_list.php" class="pure-menu-link"><span class="icon-tools ico-home"></span>用 户 管 理</a></li>-->
<!--        <li class="pure-menu-item top-ten --><?php //if ($pageNavId == 1) {echo " pure-menu-selected";} ?><!--">-->
<!--           <a href="../user_list.php" class="pure-menu-link --><?php //if ($pageNavSub == 11) {echo " active";} ?><!--">用户列表</a>-->
<!--           <a href="../user_add.php" class="pure-menu-link --><?php //if ($pageNavSub == 12) {echo " active";} ?><!--">新建用户</a>-->
<!--        </li>-->
<!---->
<!--        <li class="pure-menu-item --><?php //if ($pageNavId == 7) {echo " pure-menu-selected";} ?><!--"><a href="movie_edit.php" class="pure-menu-link"><span class="icon-audit ico-data"></span>媒 体 录 入</a></li>-->
<!--        <li class="pure-menu-item top-ten --><?php //if ($pageNavId == 7) {echo " pure-menu-selected";} ?><!--">-->
<!--            <a href="movie_edit.php" class="pure-menu-link --><?php //if ($pageNavSub == 7) {echo " active";} ?><!--">媒体数据录入</a>-->
<!--        </li> -->
<!---->
<!--        <li class="pure-menu-item --><?php //if ($pageNavId == 2) {echo " pure-menu-selected";} ?><!--"><a href="../movie_log.php" class="pure-menu-link"><span class="icon-tools ico-movie"></span>媒 体 数 据</a>-->
<!--        </li>-->
<!--        <li class="pure-menu-item top-ten --><?php //if ($pageNavId == 2) {echo " pure-menu-selected";} ?><!--">-->
<!--            <a href="../movie_log.php" class="pure-menu-link --><?php //if ($pageNavSub == 21) {echo " active";} ?><!--">待审核录入单</a>-->
<!--            <a href="../movie_log_audited.php" class="pure-menu-link --><?php //if ($pageNavSub == 22) {echo " active";} ?><!--">已审核录入单</a>-->
<!--            <a href="../movie_list.php" class="pure-menu-link --><?php //if ($pageNavSub == 23) {echo " active";} ?><!--">剧 目 列 表</a>-->
<!--            <a href="../movie_list_delete_audit.php" class="pure-menu-link --><?php //if ($pageNavSub == 25) {echo " active";} ?><!--">删 除 审 核</a>-->
<!--            <a href="../movie_list_delete.php" class="pure-menu-link --><?php //if ($pageNavSub == 24) {echo " active";} ?><!--">已删除剧目</a>-->
<!--        </li>-->
<!---->
<!--        <li class="pure-menu-item --><?php //if ($pageNavId == 3) {echo " pure-menu-selected";} ?><!--"><a href="../tensyn_movie_list.php" class="pure-menu-link"><span class="icon-audit ico-data"></span>腾 信 数 据</a></li>-->
<!--        <li class="pure-menu-item top-ten --><?php //if ($pageNavId == 3) {echo " pure-menu-selected";} ?><!--">-->
<!--            <a href="../tensyn_movie_list.php" class="pure-menu-link --><?php //if ($pageNavSub == 31) {echo " active";} ?><!--">数 据 管 理</a>-->
<!--            <a href="../tensyn_movie_log.php" class="pure-menu-link --><?php //if ($pageNavSub == 32) {echo " active";} ?><!--">待审腾信录入单</a>-->
<!--            <a href="../tensyn_movie_edit.php" class="pure-menu-link --><?php //if ($pageNavSub == 33) {echo " active";} ?><!--">数 据 录 入</a>-->
<!--            <a href="../tensyn_movie_log_audited.php" class="pure-menu-link --><?php //if ($pageNavSub == 34) {echo " active";} ?><!--">腾信录入单管理</a>-->
<!--        </li>-->
<!---->
<!--        <li class="pure-menu-item --><?php //if ($pageNavId == 4) {echo " pure-menu-selected";} ?><!--"><a href="../notification.php" class="pure-menu-link"><span class="icon-audit ico-message"></span>通 知 管 理</a></li>-->
<!--        <li class="pure-menu-item --><?php //if ($pageNavId == 5) {echo " pure-menu-selected";} ?><!--"><a href="../#" class="pure-menu-link"><span class="icon-audit ico-setting"></span>系 统 管 理</a></li>-->
<!---->
<!---->
<!--        <li class="pure-menu-item --><?php //if ($pageNavId == 6) {echo " pure-menu-selected";} ?><!--"><a href="../recommend_list.php" class="pure-menu-link"><span class="icon-audit ico-recommend"></span>资 源 推 送</a></li>-->
<!--        <li class="pure-menu-item top-ten --><?php //if ($pageNavId == 6) {echo " pure-menu-selected";} ?><!--">-->
<!--            <a href="../recommend_list.php" class="pure-menu-link --><?php //if ($pageNavSub == 61) {echo " active";} ?><!--">推 荐 内 容</a>-->
<!--            <a href="../recommend_user.php" class="pure-menu-link --><?php //if ($pageNavSub == 62) {echo " active";} ?><!--">推 荐 用 户</a>-->
<!--            <a href="../recommend_rule.php" class="pure-menu-link --><?php //if ($pageNavSub == 63) {echo " active";} ?><!--">推 荐 规 则</a>-->
<!--        </li>-->

    </ul>
</div>
