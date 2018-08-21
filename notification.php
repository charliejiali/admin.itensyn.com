<?php
$pageTitle = "通知管理";
$pageNavId = 4;

include("function.php");
include("include/Notice.class.php");

$page=isset($_GET["p"])?intval($_GET["p"]):1;
$pagecount=isset($_GET["c"])?intval($_GET["c"]):10; //每页显示数量
$offset=($page-1)*$pagecount;

$result=Notice::get_list($offset,$pagecount,$_GET); 
$list=$result["data"];
$list_count=$result["count"];
$page_count=$result["page_count"];

?>
<!DOCTYPE html>
<html>
<head>
    <?php include_once("module/head_tag.php"); ?>

    <script type="text/javascript" src="js/libs/jquery.mCustomScrollbar.concat.min.js"></script>
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
                <a href="notification_add.php" class="pure-btn btn-large btn-red">新建推送内容</a>
            </div>

            <h3 class="title">通知管理</h3>
            <div class="form-eval">
                <div class="pure-g">
                    <div class="pure-u-1-3">
                        <input value="<?php echo $_GET["title"];?>" id="title" type="text" placeholder="" class="input-label" style="width: 170px;">
                        <button id="btn_search" type="button" class="pure-btn btn-red" style="width:60px;padding-left: 1em; padding-right: 1em; margin-left: 1em ">查询</button>
                    </div>
                    <div class="clear"></div>
                </div>
            </div>
            <br class="clear">

            <div class="table-box">
                <div class="table-list">
                    <div id="table-data">
                        <table class="pure-table pure-table-line pure-table-striped pure-table-hover" style="width: 100%">
                            <thead>
                            <tr>
                                <th>标题</th>
                                <!-- <th>内容</th> -->
                                <th>时间</th>
                                <!-- <th>状态</th> -->
                                <!-- <th>操作</th> -->
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach($list as $l){ ?>
                            <tr>
                                <td><a name="notice" id="<?php echo $l["notice_id"];?>"><?php echo $l["title"];?></a></td>
                                <!-- <td>内容</td> -->
                                <td><?php echo $l["create_time"];?></td>
                                <!-- <td>已读</td> -->
                                <!-- <td><a href="#">删除</a></td> -->
                            </tr>
                            <?php } ?>
                            
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
            <br>
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
<script type="text/javascript">
    var page=parseInt('<?php echo $page;?>');
    var page_count='<?php echo $page_count;?>';
    var params={};
    params["p"]=page;
    params["c"]='<?php echo $pagecount;?>';
    params["title"]='<?php echo $_GET["title"];?>';

    $('#btn_search').on('click',function(){
        params["title"]=$('#title').val();

        window.location.href='notification.php?'+$.param(params);
    }); 

    $('a[id^="page"]').on('click',function(){
        var type=$(this).attr('id').split('_')[1];
        
        switch(type){
            case "first":
                params["p"]=1;
                break;
            case "pre":
                if(page-1<=0){return false;}
                params["p"]=page-1;
                break;
            case "next":
                if(page+1>page_count){return false;}
                params["p"]=page+1;
                break;
            case "last":
                params["p"]=page_count; 
                break; 
        }
        window.location.href='notification.php?'+$.param(params);
    });
    $('#pageNum').on('keypress',function(e){
        var value=parseInt($(this).val());
        if(e.keyCode==13){
            if(value<=0||value>page_count||isNaN(value)){
                return false;
            }else{
                params["p"]=value;
                window.location.href='notification.php?'+$.param(params);
            }
        }
    }); 
    $('a[name="notice"]').on('click',function(){
        window.location.href="notification_view.php?id="+$(this).attr('id');
    });
</script>

</body>
</html>