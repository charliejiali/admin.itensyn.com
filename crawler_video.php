<?php
$pageTitle = "视频列表";
$pageNavId = 8;
$pageNavSub = 81;

if(!isset($_GET["ps"])&&!isset($_GET["cs"])){
    header('Location:crawler_video.php?ps=待播出&cs=未完成');
}

include("function.php");
include("include/Crawler.class.php");

$masterpiece_type=Crawler::get_masterpiece_type(); 
$play_status=Crawler::get_play_status();
$crawler_status=Crawler::get_crawler_status();



$page=isset($_GET["p"])?intval($_GET["p"]):1;
$pagecount=isset($_GET["c"])?intval($_GET["c"]):8; //每页显示数量
$offset=($page-1)*$pagecount;

$get_ps=isset($_GET["ps"])?trim($_GET["ps"]):"待播出";
$get_cs=isset($_GET["cs"])?trim($_GET["cs"]):"未完成"; 

$result=Crawler::get_videos($offset,$pagecount,$_GET);
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
            <h3 class="title">剧目列表</h3>

            <div class="form-eval">
                <div class="pure-g"> 
                    <div class="pure-u-1-3">
                        <input id="q" value="<?php echo $_GET["q"];?>" type="text" placeholder="剧目/男主演/女主演/主持人/团队" class="input-label" style="width: 200px;">
                    </div>
                    <div class="pure-u-1-3">
                        剧目状态：
                        <select class="form-control" id="play_status" style="width:90px">
                            <option value="">全部</option>
                            <?php foreach($play_status as $ps){ ?>
                            <option value="<?php echo $ps;?>" <?php if($get_ps===$ps){echo "selected";}?> ><?php echo $ps;?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="pure-u-1-3">
                        爬虫状态：
                        <select class="form-control" id="crawler_status" style="width:90px">
                            <option value="">全部</option>
                            <?php foreach($crawler_status as $cs){ ?>
                            <option value="<?php echo $cs;?>" <?php if($get_cs===$cs){echo "selected";}?> ><?php echo $cs;?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="pure-u-1-3">
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
                        <table class="pure-table pure-table-line pure-table-striped pure-table-hover" style="width: 6000px">  
                            <thead>
                                <tr>
                                    <th class="td-head" style="width: 250px;">剧目名称</th>
                                    <th>剧目状态</th>
                                    <th>爬虫状态</th>
                                    <th>上季剧目名称</th>
                                    <th>剧目URL</th>
                                    <th>单集播放量（万）</th>
                                    <th>预告片播放量（万）</th>
                                    <th>男主演</th>
                                    <th>男主演代表作</th>
                                    <th>男主演代表作URL</th>
                                    <th>男主演代表作单集播放量（万）</th>
                                    <th>女主演</th>
                                    <th>女主演代表作</th>
                                    <th>女主演代表作URL</th>
                                    <th>女主演代表作单集播放量（万）</th>
                                    <th>主持人</th>
                                    <th>主持人代表作</th>
                                    <th>主持人代表作URL</th>
                                    <th>主持人代表作单集播放量（万）</th>
                                    <th>制作团队</th>
                                    <th>制作团队代表作</th>
                                    <th>制作团队代表作URL</th>
                                    <th>制作团队代表作单集播放量（万）</th>
                                    <th>常驻嘉宾</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php foreach($list as $l){ ?>
                                <tr>
                                    <td class="td-head"><a style="cursor:pointer;" id="edit_<?php echo $l["id"];?>"><?php echo $l["program_name"];?></a></td>
                                    <td><?php echo $l["play_status"];?></td>
                                    <td><?php echo $l["crawler_status"];?></td>
                                    <td><?php echo $l["ex_program_name"];?></td>
                                    <td><?php echo $l["url"];?></td>
                                    <td><?php echo $l["pv_avg"];?></td>
                                    <td><?php echo $l["preview_pv_avg"];?></td>
                                    <?php 
                                    foreach($masterpiece_type as $k=>$v){
                                        $m=Crawler::get_masterpiece($l[$k],$k);
                                    ?>
                                    <td><?php echo $l[$k];?></td>
                                    <td><?php echo $m["program_name"];?></td>
                                    <td><?php echo $m["url"];?></td>
                                    <td><?php echo $m["pv_avg"];?></td>
                                    <?php } ?>
                                    <td><?php echo $l["guest"];?></td>
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

        params['q']=$.trim($('#q').val());
        params['ps']=$('#play_status option:selected').val();
        params['cs']=$('#crawler_status option:selected').val()
        
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
        window.location.href='crawler_video.php?'+$.param(params);
    });
    $('#pageNum').on('keypress',function(e){
        var value=parseInt($(this).val());
        if(e.keyCode==13){
            if(value<=0||value>page_count||isNaN(value)){
                return false;
            }else{
                params["p"]=value;
                window.location.href='crawler_video.php?'+$.param(params);
            }
        }
    }); 

    $('#new').on('click',function(){
        window.open('crawler_video_edit.php?act=add');
    });
    $('#table-head').on('click','a[id^="edit_"]',function(){
        window.open('crawler_video_edit.php?act=edit&id='+$(this).attr('id').split('_')[1]);
    });
    $('#search').on('click',function(){
        window.location.href='crawler_video.php?q='+$.trim($('#q').val())+'&ps='+$('#play_status option:selected').val()+'&cs='+$('#crawler_status option:selected').val();
    });
    $('#q').on('keypress',function(e){
        if(e.keyCode==13){
            window.open('crawler_video.php?q='+$.trim($(this).val())+'&ps='+$('#play_status option:selected').val()+'&cs='+$('#crawler_status option:selected').val());
        }
    }); 
</script>
</body>
</html>