    <?php
$pageTitle = "腾信录入单列表";
$pageNavId = 3;
$pageNavSub = 34;

include("function.php");
include("include/TensynInput.class.php");

$page=isset($_GET["p"])?intval($_GET["p"]):1;
$pagecount=isset($_GET["c"])?intval($_GET["c"]):10; //每页显示数量
$offset=($page-1)*$pagecount;

$options=$_GET;
$options["status"]=1;

$result=TensynInput::get_list($options,$offset,$pagecount);
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

            <h3 class="title">腾信录入单列表</h3>
            <!--            <br class="clear">-->
            <div class="form-eval">
                <div class="pure-g">
                    <div class="pure-u-1-3">
                        <label for="name">日　　期</label>
                        <input value="<?php echo $_GET["start_date"];?>" id="start_date" type="text" placeholder="" class="input-label" style="width:98px;"> -
                        <input value="<?php echo $_GET["end_date"];?>" id="end_date" type="text" placeholder="" class="input-label" style="width:98px">
                    </div>
                    <!-- <div class="pure-u-1-3">
                        <label for="status">录入单状态</label>
                        <select class="form-control" id="status">
                            <option value="1">待审核</option>
                            <option value="2">已审核</option>
                        </select>
                    </div> -->
                    <div class="pure-u-1-3">
                       <!--  <label for="adduser">提交人</label>
                        <input id="adduser" type="text" placeholder="" class="input-label" style="width:120px;"> -->
                        <button type="submit" class="pure-btn btn-red"
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

    $('#search').on('click',function(){
        params["start_date"]=$('#start_date').val();
        params["end_date"]=$('#end_date').val();

        window.location.href="tensyn_movie_log_audited.php?"+$.param(params);
    });

    $('a[name="audit"]').on('click',function(){
        window.location.href='tensyn_movie_log_audited_view.php?id='+$(this).attr('id')+'&status=2';
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
        window.location.href='tensyn_movie_log_audited.php?'+$.param(params);
    });
    $('#pageNum').on('keypress',function(e){
        var value=parseInt($(this).val());
        if(e.keyCode==13){
            if(value<=0||value>page_count||isNaN(value)){
                return false;
            }else{
                params["p"]=value; 
                window.location.href='tensyn_movie_log_audited.php?'+$.param(params);
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