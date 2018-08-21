<?php
$pageTitle = "已审核录入单";
$pageNavId = 2;
$pageNavSub = 22;

include("function.php");
include("include/Input.class.php");

$suppliers=array("优酷土豆","爱奇艺","腾讯视频","搜狐视频","乐视TV","芒果TV","PPTV");

$page=isset($_GET["p"])?intval($_GET["p"]):1;
$pagecount=isset($_GET["c"])?intval($_GET["c"]):10; //每页显示数量
$offset=($page-1)*$pagecount;

$start_date=isset($_GET["start_date"])&&trim($_GET["start_date"])!==""?trim($_GET["start_date"]):"";
$end_date=isset($_GET["end_date"])&&trim($_GET["end_date"])!==""?trim($_GET["end_date"]):"";
$supplier=isset($_GET["supplier"])&&trim($_GET["supplier"])!==""?trim($_GET["supplier"]):"";

$status=1;
$result=Input::get_list($status,$offset,$pagecount,$_GET);
$list=$result["data"];
$list_count=$result["total_count"];
$page_count=$result["page_count"];

?>
<!DOCTYPE html>
<html>
<head>
    <?php include_once("module/head_tag.php"); ?>

    <script type="text/javascript" src="js/libs/jquery.mCustomScrollbar.concat.min.js"></script>
    <script src="./js/pages/movie_edit.js" type="text/javascript"></script>
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

            <h3 class="title">已审核录入单</h3>
            <!--            <br class="clear">-->
            <div class="form-eval">
                <div class="pure-g">
                    <div class="pure-u-1-3">
                        <label for="name">日　　期</label>
                        <input id="start_date" value="<?php echo $start_date;?>" type="text" placeholder="" class="input-label" style="width:98px;"> -
                        <input id="end_date" value="<?php echo $end_date;?>" type="text" placeholder="" class="input-label" style="width:98px">
                    </div>
                    <div class="pure-u-1-3">
                        <label for="company">供 应 商</label>
                        <select class="form-control" id="supplier">
                            <option value="">全部</option>
                            <?php foreach($suppliers as $s){ ?>
                            <option value="<?php echo $s;?>" <?php if($s==$supplier){echo "selected";}?> ><?php echo $s;?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="pure-u-1-3">
                        <button id="search" type="button" class="pure-btn btn-red"
                                style="width:60px;padding-left: 1em; padding-right: 1em; ">查询
                        </button>
                    </div>
                    <div class="clear"></div>
                </div>
            </div>

            <br><br>
            <div class="table-box">
                <div class="table-list">
                    <div id="table-data">
                        <table class="pure-table pure-table-line pure-table-striped pure-table-hover" style="width: 100%">
                            <thead>
                            <tr>
                                <th>录入单号</th>
                                <th>总量</th>
                                <th>审核通过</th>
                                <th>审核拒接</th>
                                <th>供应商</th>
                                <th>提交日期</th>
                                <th>审核时间</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach($list as $l){ ?>
                            <tr>
                                <td><a name="audit" id="<?php echo $l["input_id"];?>" href="javascript:;">
                                    <?php echo $l["name"];?></a>
                                </td>
                                <td><?php echo $l["total"];?></td>
                                <td><?php echo $l["pass"];?></td>
                                <td><?php echo $l["fail"];?></td>
                                <td><?php echo $l["supplier"];?></td>
                                <td><?php echo $l["create_date"];?></td>
                                <td><?php echo $l["update_date"];?></td>
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
<link rel="stylesheet" href="jquery-ui-1.12.1/jquery-ui.min.css">
<script src="jquery-ui-1.12.1/jquery-ui.min.js"></script>
<script type="text/javascript">
    var page=parseInt('<?php echo $page;?>');
    var page_count='<?php echo $page_count;?>';
    
    var params={};
    params["p"]=parseInt(page);
    params["c"]='<?php echo $pagecount;?>';
    params["start_date"]='<?php echo $start_date;?>';
    params["end_date"]='<?php echo $end_date;?>';
    params["supplier"]='<?php echo $supplier;?>';

    $('#search').on('click',function(){
        params["start_date"]=$('#start_date').val();
        params["end_date"]=$('#end_date').val();
        params["supplier"]=$('#supplier option:selected').val();

        window.location.href="movie_log_audited.php?"+$.param(params);
    });

    $('a[name="audit"]').on('click',function(){
        window.location.href='movie_log_audited_view.php?id='+$(this).attr('id')+'&status=2';
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
        window.location.href='movie_log_audited.php?'+$.param(params);
    });
    $('#pageNum').on('keypress',function(e){
        var value=parseInt($(this).val());
        if(e.keyCode==13){
            if(value<=0||value>page_count||isNaN(value)){
                return false;
            }else{
                params["p"]=value; 
                window.location.href='movie_log_audited.php?'+$.param(params);
            }
        }
    }); 
    $('#start_date').datepicker({
        dateFormat:'yy-mm-dd' 
    });
    $('#end_date').datepicker({
        dateFormat:'yy-mm-dd' 
    });
</script>
</body>
</html>