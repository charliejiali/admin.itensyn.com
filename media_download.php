<?php
$pageTitle = "媒体未上传剧目";
$pageNavId = 3;
$pageNavSub = 31;

include("function.php");
include("include/Program.class.php");

$page=isset($_GET["p"])?intval($_GET["p"]):1;
$pagecount=isset($_GET["c"])?intval($_GET["c"]):5; //每页显示数量
$offset=($page-1)*$pagecount;

$result=Program::get_download_list($offset,$pagecount,$_GET);
$list=$result["data"];
$list_count=$result["count"];
$page_count=$result["page_count"];

$system_year=array("2018","2019","2020","2021");
$system_season=array("Q1","Q2","Q3","Q4");

$type_status=array(
    -1=>"delete",
    0=>"new",
    1=>"same",
    2=>"update"
);

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
            <h3 class="title">剧目列表</h3>
            <div class="form-eval">
                <div class="pure-g">
                    <div class="pure-u-1-3">
                        <label for="name">审批通过时间</label>
                        <input id="start_date" value="<?php echo $_GET["start_date"];?>" type="text" placeholder="" class="input-label" style="width:98px;"> -
                        <input id="end_date" value="<?php echo $_GET["end_date"];?>" type="text" placeholder="" class="input-label" style="width:98px">
                    </div>
                    <div class="pure-u-1-3">
                        <label for="position">播出时间</label>
                        <select class="form-control" id="select_year" style="width:90px">
                            <option value="">全部</option>
                            <?php foreach($system_year as $sy){ ?>
                                <option value="<?php echo $sy;?>" <?php if($sy==$_GET["year"]&&trim($_GET["year"])!==""){echo "selected";}?> ><?php echo $sy;?></option>
                            <?php } ?>
                        </select>
                        年
                        <select class="form-control" id="select_season" style="width:90px">
                            <option value="">全部</option>
                            <?php foreach($system_season as $ss){ ?>
                                <option value="<?php echo $ss;?>" <?php if($ss==$_GET["season"]&&trim($_GET["season"])!==""){echo "selected";}?> ><?php echo $ss;?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="pure-u-1-3">
                        <input id="program_name" value="<?php echo $_GET["program_name"];?>" type="text" placeholder="剧目名称" class="input-label" style="width: 170px;">
                        <button id="search" type="button" class="pure-btn btn-red" style="width:60px;padding-left: 1em; padding-right: 1em; ">查询</button>
                    </div>
                    <div class="pure-u-1-3">
                       <button id="export" type="button" class="pure-btn btn-red" style="width:60px;padding-left: 1em; padding-right: 1em; ">导出</button>
                    </div>
                    <div class="clear"></div>
                </div>
            </div>
        </div>
        <div class="content">
            <div class="table-box">
                <div class="table-list">
                    <div id="table-data">
                        <table class="pure-table pure-table-line pure-table-striped pure-table-hover" style="width: 3500px">
                            <thead>
                            <tr>
                                <th class="td-head" style="width: 65px;"></th>
                                <th class="td-head" style="width: 120px;">剧目名称</th>
                                <th class="td-head" style="width: 120px;">腾信名称</th>
                                <th>剧目原名</th>
                                <th>资源类型</th>
                                <th>播出时间</th>
                                <th>媒体平台</th>
                                <th>开播时间</th>
                                <th>版权情况</th>
                                <th>播出状态</th>
                                <th>播出卫视</th>
                                <th>主创/嘉宾</th>
                                <th>内容类型</th>
                                <th>制作团队</th>
                                <th width="500">简介</th>
                                <th>本季预估播放量</th>
                                <th>集数/期数</th>
                                <th>本季预估单机播放量</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach($list as $l){?>
                                <tr>
                                    <td class="td-head">
                                        <span class="movie-tag <?php echo $type_status[$l["type_status"]];?>"></span>
                                    </td>
                                    <td class="td-head"><?php echo $l["program_name"];?></td>
                                    <td class="td-head"><?php echo $l["tensyn_name"];?></td>
                                    <td><?php echo $l["program_default_name"];?></td>
                                    <td><?php echo $l["type"];?></td>
                                    <td><?php echo $l["play_time"];?></td>
                                    <td><?php echo $l["platform"];?></td>
                                    <td><?php echo $l["start_time"];?></td>
                                    <td><?php echo $l["copyright"];?></td>
                                    <td><?php echo $l["start_type"];?></td>
                                    <td><?php echo $l["satellite"];?></td>
                                    <td><?php echo $l["creator"];?></td>
                                    <td><?php echo $l["content_type"];?></td>
                                    <td><?php echo $l["team"];?></td>
                                    <td><?php echo csubstr($l["intro"],0,80);?></td>
                                    <td><?php echo $l["play1"];?></td>
                                    <td><?php echo $l["play3"];?></td>
                                    <td><?php echo $l["play6"];?></td>
                                </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="table-head" id="table-head" style="width: 305px;"></div>
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
    params["start_date"]='<?php echo $_GET["start_date"];?>';
    params["end_date"]='<?php echo $_GET["end_date"];?>'
    params["year"]='<?php echo $_GET["year"];?>'
    params["season"]='<?php echo $_GET["season"];?>'
    params["program_name"]='<?php echo $_GET["program_name"];?>'

    $('#search').on('click',function(){
        params["start_date"]=$('#start_date').val();
        params["end_date"]=$('#end_date').val();
        params["year"]=$('#select_year option:selected').val();
        params["season"]=$('#select_season option:selected').val();
        params["program_name"]=$('#program_name').val();

        window.location.href='media_download.php?'+$.param(params);
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
        window.location.href='media_download.php?'+$.param(params);
    });
    $('#pageNum').on('keypress',function(e){
        var value=parseInt($(this).val());
        if(e.keyCode==13){
            if(value<=0||value>page_count||isNaN(value)){
                return false;
            }else{
                params["p"]=value;
                window.location.href='media_download.php?'+$.param(params);
            }
        }
    });
//    $('a[id^="edit_"]').on('click',function(){
//        var id=$(this).attr('id').split('_')[1];
//        window.location.href='movie_edit.php?id='+id;
//    });
//    $('a[id^="delete"]').on('click',function(){
//        $.post('ajax/program_delete.php',{program_id:$(this).attr('id').split('_')[1]},function(json){
//            __BDP.alertBox("提示",json.msg);
//            // if(json.r==1){window.location.reload();}
//        },'json');
//    });
//    $('#add').on('click',function(){
//        window.location.href='movie_edit.php';
//    });
    $('#export').on('click',function(){
        window.open('export/media_movie_list.php?'+$.param(params));
    });
    $('#start_date').datepicker({
        dateFormat:'yy-mm-dd'
    });
    $('#end_date').datepicker({
        dateFormat:'yy-mm-dd'
    });

//    $('#table-head').on('click','button[name="update"]',function(){
//        var id=$(this).attr('id').split('_')[1];
//        var value=$('#table-head').find('input[id="name_'+id+'"]').val();
//
//        $.post('ajax/program_update_tensyn_name.php',{id:id,value:value},function(json){
//            __BDP.alertBox("提示",json.msg,'','',function(){
//                if(json.r==1){window.location.reload();}
//            });
//        },'json');
//    })
</script>

</body>
</html>
