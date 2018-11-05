<?php
$pageTitle = "微博列表";
$pageNavId = 8;
$pageNavSub = 82;

include("function.php");
include("include/Crawler.class.php");

$page=isset($_GET["p"])?intval($_GET["p"]):1;
$pagecount=isset($_GET["c"])?intval($_GET["c"]):10; //每页显示数量
$offset=($page-1)*$pagecount;

$result=Crawler::get_weibos($offset,$pagecount,$_GET);
$list=$result["data"];
$list_count=$result["count"];
$page_count=$result["page_count"];
 
?>
<!DOCTYPE html>
<html>
<head>
    <?php include_once("module/head_tag.php"); ?>

    <script type="text/javascript" src="js/libs/jquery.mCustomScrollbar.concat.min.js"></script>
    <script src="./js/pages/movie_list.js" type="text/javascript"></script>

    <style>
        #table-data .td-control, #table-control .td-control{
            width: 150px;
        }
    </style>
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
                <button id="new" type="button" class="pure-btn btn-large btn-red">创建</button>
            </div>
            <h3 class="title">微博列表</h3>

            <div class="form-eval">
                <div class="pure-g"> 
                    <div class="pure-u-1-3">
                        <input id="q" value="<?php echo $_GET["q"];?>" type="text" placeholder="名称" class="input-label" style="width: 200px;">
                        <button id="search" type="button" class="pure-btn btn-red" style="width:60px;padding-left: 1em; padding-right: 1em; ">查询</button>
                    </div>
                    <div class="clear"></div>
                </div>
            </div>
        </div>

        <div class="content">
            <div class="table-box">
                <div class="table-list">
                    <div id="table-data">
                        <table class="pure-table pure-table-line pure-table-striped pure-table-hover" style="width: 1500px">  
                            <thead>
                                <tr>
                                    <th class="td-head" style="width: 250px;">名称</th>
                                    <th>URL</th>
                                    <th>粉丝（万）</th>
                                    <th>阅读量（万）</th>
                                    <th>讨论量（万）</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php foreach($list as $l){ ?>
                                <tr>
                                    <td class="td-head"><a style="cursor:pointer;" id="edit_<?php echo $l["name"];?>"><?php echo $l["name"];?></a></td>
                                    <td><?php echo $l["url"];?></td>
                                    <td><?php echo $l["followers"];?></td>
                                    <td><?php echo $l["reading"];?></td>
                                    <td><?php echo $l["discuss"];?></td>
                                </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="table-head" id="table-head" style="width: 250px;"></div>
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
<link rel="stylesheet" href="jquery-ui-1.12.1/jquery-ui.min.css">
<script src="jquery-ui-1.12.1/jquery-ui.min.js"></script>
<script type="text/javascript">
    var page=parseInt('<?php echo $page;?>');
    var page_count='<?php echo $page_count;?>';
    var params={};
    params["p"]=parseInt('<?php echo $page;?>');
    params["c"]='<?php echo $pagecount;?>';

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
        window.location.href='crawler_weibo.php?'+$.param(params);
    });
    $('#pageNum').on('keypress',function(e){
        var value=parseInt($(this).val());
        if(e.keyCode==13){
            if(value<=0||value>page_count||isNaN(value)){
                return false;
            }else{
                params["p"]=value;
                window.location.href='crawler_weibo.php?'+$.param(params);
            }
        }
    }); 

    $('#new').on('click',function(){
        window.location.href='crawler_weibo_edit.php?act=add'
    });
    $('#table-head').on('click','a[id^="edit_"]',function(){
        window.location.href='crawler_weibo_edit.php?act=edit&name='+$(this).attr('id').split('_')[1];
    });
    $('#search').on('click',function(){
        window.location.href='crawler_weibo.php?q='+$.trim($('#q').val())
    });
    $('#q').on('keypress',function(e){
        if(e.keyCode==13){
            window.location.href='crawler_weibo.php?q='+$.trim($(this).val())
        }
    })
</script>
</body>
</html>