<?php
$pageTitle = "待审腾信核录入单";
$pageNavId = 3;
$pageNavSub = 32;

include("function.php");
include("include/TensynInput.class.php");

$page=isset($_GET["p"])?intval($_GET["p"]):1;
$pagecount=isset($_GET["c"])?intval($_GET["c"]):10; //每页显示数量
$offset=($page-1)*$pagecount;

$options=$_GET;
$options["status"]=0;

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
    <script src="./js/pages/tensyn_movie_log.js" type="text/javascript"></script>
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
            <!--div class="pull-right">
                <button type="submit" class="pure-btn btn-large btn-red">资料上传</button>
            </div-->
            <h3 class="title">待审腾信核录入单</h3>
            <!--            <br class="clear">-->
            <div class="form-eval">
                <div class="pure-g">
                    <div class="pure-u-1-3">
                        <label for="name">日　　期</label>
                        <input value="<?php echo $_GET["start_date"];?>" id="start_date" type="text" placeholder="" class="input-label" style="width:98px;"> -
                        <input value="<?php echo $_GET["end_date"];?>" id="end_date" type="text" placeholder="" class="input-label" style="width:98px">
                    </div>
                    <div class="pure-u-1-3">
                        <button id="btn_search" type="button" class="pure-btn btn-red" style="width:60px;padding-left: 1em; padding-right: 1em; ">查询</button>
                    </div>
                    <div class="clear"></div>
                </div>
            </div>

            <br><br>
            <div class="table-box">
                <div class="table-list">
                    <div id="table-data">
                        <table class="pure-table pure-table-line pure-table-striped pure-table-hover"
                               style="width: 1000px;">
                            <thead>
                            <tr>
                                <th>录入单号</th>
                                <th>数量</th>
                                <th>提交日期</th>
                                <th>备注</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach($list as $l){ ?>
                                <tr>
                                    <td><a href="tensyn_movie_log_auditing.php?id=<?php echo $l["input_id"];?>"><?php echo $l["name"];?></a></td>
                                    <td><?php echo $l["total"];?></td>
                                    <td><?php echo $l["create_date"];?></td>
                                    <td><?php echo $l["remark"];?></td>
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

    params["p"]=page;
    params["c"]='<?php echo $pagecount;?>';
    params["start_date"]='<?php echo $_GET["start_date"];?>';
    params["end_date"]='<?php echo $_GET["end_date"];?>'
 

    $('#btn_search').on('click',function(){
        params["start_date"]=$('#start_date').val();
        params["end_date"]=$('#end_date').val();

        window.location.href='tensyn_movie_log.php?'+$.param(params);
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
        window.location.href='tensyn_movie_log.php?'+$.param(params);
    });
    $('#pageNum').on('keypress',function(e){
        var value=parseInt($(this).val());
        if(e.keyCode==13){
            if(value<=0||value>page_count||isNaN(value)||value==page){
                return false;
            }else{
                params["p"]=value;
                window.location.href='tensyn_movie_log.php?'+$.param(params);
            }
        }
    }); 

    $( "#start_date" ).datepicker({
        dateFormat:'yy-mm-dd'
    });
    $( "#end_date" ).datepicker({
        dateFormat:'yy-mm-dd'
    });
</script>
</body>
</html>