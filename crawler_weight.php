<?php
$pageTitle = "二级权重";
$pageNavId = 8;
$pageNavSub = 83;

include("function.php");
include_once("include/Crawler.class.php");
$list=Crawler::get_weight_list();
$category_list=Crawler::get_category_list();
foreach($category_list as $c){
    $category[$c["category_id"]]=$c["name"];
}
?>
<!DOCTYPE html>
<html>
<head>
    <?php include_once("module/head_tag.php"); ?>
</head>
<body>
<!-- Header Start -->

<div class="wrap">
    <div class="owl-mode">
        <?php include_once("module/mode.php"); ?>
    </div>
    <div class="owl-content">
        <?php include_once("module/header.php"); ?>
        <div class="content">
            <div class="pull-right">
                <a href="crawler_weight_edit.php?act=add" class="pure-btn btn-large btn-red">新建</a>
            </div>
            <h3 class="title">二级权重列表</h3>
            <!--            <br class="clear">-->

            <br class="clear">

            <div class="table-box">
                <div class="table-list">
                    <div id="table-data">
                        <table class="pure-table pure-table-line pure-table-striped pure-table-hover" style="width: 100%">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>类别</th>
                                <th>二级权重</th>
                                <th>备注</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach($list as $l) { ?>
                                <tr>
                                    <td><?php echo $l["weight_id"];?></a></td>
                                    <td><?php echo $category[$l["category_id"]];?></td>
                                    <td><?php echo $l["name"];?></td>
                                    <td><?php echo $l["content"];?></td>
                                    <td>
                                        <a id="<?php echo $l["weight_id"];?>" name="edit" href="javascript:;">编辑</a>
                                    </td>
                                </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
            <div class="table-footer">
                <div class="page-control">
                    每页显示<?php echo $pagecount;?>条 &nbsp; &nbsp;
                    <a id="page_first" href="javascript:;" class="btn-page">首页</a>
                    <a id="page_pre" href="javascript:;" class="btn-page">上一页</a>
                    <a id="page_next" href="javascript:;" class="btn-page">下一页</a>
                    <a id="page_last" href="javascript:;" class="btn-page">尾页</a>
                    <input id="pageNum" type="text" value="<?php echo $page;?>" class="input-num" size="2">
                </div>
                记录共<?php echo $list_count;?>条，<?php echo $page_count;?>页
            </div>
        </div>
        <!-- page01 End -->

        <?php include_once("module/footer.php"); ?>
    </div>

</div>
<link rel="stylesheet" href="jquery-ui-1.12.1/jquery-ui.min.css">
<script src="jquery-ui-1.12.1/jquery-ui.min.js"></script>
<script type="text/javascript">
    var params={};
    $('a[name]').on('click',function(){
        window.location.href='crawler_weight_edit.php?act=edit&id='+$(this).attr('id');
    });
</script>
</body>
</html>
