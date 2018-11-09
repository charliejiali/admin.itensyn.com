<?php
$pageTitle = "正在评估的剧目";
$pageNavId = 7;
$pageNavSub = 7;

include("function.php");
include("include/Tensyn.class.php");

$page=isset($_GET["p"])?intval($_GET["p"]):1;
$pagecount=isset($_GET["c"])?intval($_GET["c"]):5; //每页显示数量
$offset=($page-1)*$pagecount;

$result=Tensyn::get_valid_list($offset,$pagecount,$_GET);
$list=$result["data"];
$list_count=$result["count"];
$page_count=$result["page_count"];

$system_year=array("2018","2019","2020","2021","2022");
$system_season=array("Q1","Q2","Q3","Q4");

?>
<!DOCTYPE html>
<html>
<head>
    <?php include_once("module/head_tag.php"); ?>

    <script type="text/javascript" src="js/libs/jquery.mCustomScrollbar.concat.min.js"></script>
    <script src="./js/pages/tensyn_movie_list.js" type="text/javascript"></script>

    <style>
        #table-data .td-control, #table-control .td-control {
            /*width: 120px;*/
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
            <!--div class="pull-right">
                <button type="submit" class="pure-btn btn-large btn-red">+ 录入数据</button>
            </div-->
            <h3 class="title">数据管理</h3>

            <div class="form-eval">
                <div class="pure-g">
                    <div class="pure-u-1-3">
                        <label for="name">上线时间</label>
                        <input value="<?php echo $_GET["start_date"];?>" id="start_date" type="text" placeholder="" class="input-label" style="width:98px"> -
                        <input value="<?php echo $_GET["end_date"];?>" id="end_date" type="text" placeholder="" class="input-label" style="width:98px">
                    </div>
                    <div class="pure-u-1-3">
                        <label for="type">资源类型</label>
                        <input value="<?php echo $_GET["type"];?>" id="type" type="text" placeholder="" class="input-label" >
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
                        <input value="<?php echo $_GET["program_name"];?>" id="program_name" type="text" placeholder="剧目名称" class="input-label" style="width: 170px;">
                        <button id="btn_search" type="button" class="pure-btn btn-red" style="width:60px;padding-left: 1em; padding-right: 1em; ">查询</button>
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
                        <table class="pure-table pure-table-line pure-table-striped pure-table-hover" style="width: 20000px">
                            <thead>
                            <tr>
                                <th class="td-head" style="width: 120px;">剧目名称</th>
                                <th>剧目原名</th>
                                <th>腾信原名</th>
                                <th>资源类型</th>
                                <th width="500">简介</th>
                                <th>播出时间</th>
                                <th>开播时间</th>
                                <th>本季开播前3月新闻报道量（条）</th>
                                <th>上季播出时段内新闻报道量(条)</th>
                                <th>开播前3月百度指数（万）</th>
                                <th>开播前3月微指数（万）</th>
                                <th>上季播出周期内百度指数（万）</th>
                                <th>上季播出周期内微指数（万）</th>
                                <th>预告片播放量（万）</th>
                                <th>原著粉丝数（万）</th>
                                <th>原著贴吧发帖量（万）</th>
                                <th>原著贴吧关注度与发帖量之比</th>
                                <th>上季节目微博粉丝数（万）</th>
                                <th>贴吧关注人数（万人）</th>
                                <th>贴吧关注度与发帖量之比</th>
                                <th>前季微博话题量（万）</th>
                                <th>前季贴吧发帖量（万）</th>
                                <th>年龄</th>
                                <th>性别</th>
                                <th>地域</th>
                                <th>媒体平台</th>
                                <th>MAU</th>
                                <th>UV</th>
                                <th>过往自制剧数量（个）</th>
                                <th>过往自综艺数量（个）</th>
                                <th>新秀自制剧最高单集播放量（万）</th>
                                <th>新秀自制剧平均单集播放量（万）</th>
                                <th>自制剧最高单集播放量（万）</th>
                                <th>自制剧平均单集播放量（万）</th>
                                <th>新秀自制综艺最高单集播放量（万）</th>
                                <th>新秀自制综艺平均单集播放量（万）</th>
                                <th>自制综艺最高单集播放量（万）</th>
                                <th>自制综艺平均单集播放量（万）</th>
                                <th>版权情况</th>
                                <th>播出卫视</th>
                                <th>反输出电视收视率（%）</th>
                                <th>播出状态</th>
                                <th>本季预估播放量（单位：亿）</th>
                                <th>累计播放量（单位：亿）</th>
                                <th>集数/期数</th>
                                <th>已播集数</th>
                                <th>实际单集播放量（万）</th>
                                <th>本季预估单集播放量（万）</th>
                                <th>上季单集播放量（万）</th>
                                <th>内容类型</th>
                                <th>同档期同题材内容数量（个）</th>
                                <th>同类型综艺微博话题量（万）</th>
                                <th>同类型综艺微博粉丝数（万）</th>
                                <th>同类型综艺贴吧发帖量于关注人数比</th>
                                <th>主创/嘉宾</th>
                                <th>男主演</th>
                                <th>男主演代表作</th>
                                <th>男主演代表作单集播放量（万）</th>
                                <th>男主过往代表作微博话题量（万）</th>
                                <th>男主演前一内容播放期间百度指数（万）</th>
                                <th>男主演开播前3月百度指数（万）</th>
                                <th>男主演前一内容播放期间微指数（万）</th>
                                <th>男主演开播前3月微指数（万）</th>
                                <th>男主官方贴吧发帖数（万）</th>
                                <th>女主演</th>
                                <th>女主演代表作</th>
                                <th>女主演代表作单集播放量（万）</th>
                                <th>女主过往代表作微博话题量（万）</th>
                                <th>女主演前一内容播放期间百度指数（万）</th>
                                <th>女主演开播前3月百度指数（万）</th>
                                <th>女主演前一内容播放期间微指数（万）</th>
                                <th>女主演开播前3月微指数（万）</th>
                                <th>女主官方贴吧发帖数（万）</th>
                                <th>男女主演微博粉丝数（万）</th>
                                <th>大牌明星数（个：微博粉丝＞500万）</th>
                                <th>主持人</th>
                                <th>主持人代表作</th>
                                <th>主持人代表作单集播放量（万）</th>
                                <th>主持人过往代表作微博话题量（万）</th>
                                <th>主持人官方贴吧发帖量（万）</th>
                                <th>主持人演开播前3月百度指数（万）</th>
                                <th>主持人前一内容播放期间百度指数（万）</th>
                                <th>主持人演开播前3月微指数（万）</th>
                                <th>主持人前一内容播放期间微指数（万）</th>
                                <th>常驻嘉宾</th>
                                <th>常驻嘉宾代表作</th>
                                <th>常驻嘉宾微博话题量（万）</th>
                                <th>常驻嘉宾官方贴吧发帖量（万）</th>
                                <th>常驻嘉宾演开播前3月百度指数（万）</th>
                                <th>常驻嘉宾前一内容播放期间百度指数（万）</th>
                                <th>常驻嘉宾前一内容播放期间微指数（万）</th>
                                <th>常驻嘉宾演开播前3月微指数（万）</th>
                                <th>主持人及常驻嘉宾微博粉丝数（万）</th>
                                <th>大牌主持人数（个）</th>
                                <th>制作团队</th>
                                <th>制作团队/导演代表作品</th>
                                <th>制作团队代表作单集播放量（万）</th>
                                <th>单集制作经费（万元）</th>
                                <th>招商资源包售卖净价（万元）</th>
                                <th>招商资源包总刊例价（万元）</th>
                                <th>站内推广资源总价值（万元）</th>
                                <th>合作权益形式数量（种）</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach($list as $l){ ?>
                            <tr >
                                <td class="td-head"><a href="movie_info.php?mid=<?php echo $l["media_id"].'&tid='.$l["tensyn_id"];?>"><?php echo $l["program_name"];?></a></td>
                                <td><?php echo $l["program_default_name"];?></td>
                                <td><?php echo $l["tensyn_name"];?></td>
                                <td><?php echo $l["type"];?></td>
                                <td><?php echo csubstr($l["intro"],0,80);?></td>
                                <td><?php echo $l["play_time"];?></td>
                                <td><?php echo $l["start_time"];?></td>
                                <td><?php echo $l["channel1"];?></td>
                                <td><?php echo $l["channel2"];?></td>
                                <td><?php echo $l["attention1"];?></td>
                                <td><?php echo $l["attention2"];?></td>
                                <td><?php echo $l["attention3"];?></td>
                                <td><?php echo $l["attention4"];?></td>
                                <td><?php echo $l["attention5"];?></td>
                                <td><?php echo $l["IP1"];?></td>
                                <td><?php echo $l["IP2"];?></td>
                                <td><?php echo $l["IP3"];?></td>
                                <td><?php echo $l["IP4"];?></td>
                                <td><?php echo $l["IP8"];?></td>
                                <td><?php echo $l["IP9"];?></td>
                                <td><?php echo $l["topic6"];?></td>
                                <td><?php echo $l["topic7"];?></td>
                                <td><?php echo $l["match1"];?></td>
                                <td><?php echo $l["match2"];?></td>
                                <td><?php echo $l["mathc3"];?></td>
                                <td><?php echo $l["platform"];?></td>
                                <td><?php echo $l["platform1"];?></td>
                                <td><?php echo $l["platform2"];?></td>
                                <td><?php echo $l["platform3"];?></td>
                                <td><?php echo $l["platform4"];?></td>
                                <td><?php echo $l["platform5"];?></td>
                                <td><?php echo $l["platform6"];?></td>
                                <td><?php echo $l["platform7"];?></td>
                                <td><?php echo $l["platform8"];?></td>
                                <td><?php echo $l["platform9"];?></td>
                                <td><?php echo $l["platform10"];?></td>
                                <td><?php echo $l["platform11"];?></td>
                                <td><?php echo $l["platform12"];?></td>
                                <td><?php echo $l["copyright"];?></td>
                                <td><?php echo $l["satellite"];?></td>
                                <td><?php echo $l["channel3"];?></td>
                                <td><?php echo $l["start_type"];?></td>
                                <td><?php echo $l["play1"];?></td>
                                <td><?php echo $l["play2"];?></td>
                                <td><?php echo $l["play3"];?></td>
                                <td><?php echo $l["play4"];?></td>
                                <td><?php echo $l["play5"];?></td>
                                <td><?php echo $l["play6"];?></td>
                                <td><?php echo $l["tplay2"];?></td>
                                <td><?php echo $l["content_type"];?></td>
                                <td><?php echo $l["tplay3"];?></td>
                                <td><?php echo $l["IP5"];?></td>
                                <td><?php echo $l["IP6"];?></td>
                                <td><?php echo $l["IP7"];?></td>
                                <td><?php echo $l["creator"];?></td>
                                <td><?php echo $l["male_leader"];?></td>
                                <td><?php echo $l["male_main"];?></td>
                                <td><?php echo $l["make2"];?></td>
                                <td><?php echo $l["topic1"];?></td>
                                <td><?php echo $l["star3"];?></td>
                                <td><?php echo $l["star4"];?></td>
                                <td><?php echo $l["star5"];?></td>
                                <td><?php echo $l["star6"];?></td>
                                <td><?php echo $l["topic3"];?></td>
                                <td><?php echo $l["female_leader"];?></td>
                                <td><?php echo $l["female_main"];?></td>
                                <td><?php echo $l["make3"];?></td>
                                <td><?php echo $l["topic2"];?></td>
                                <td><?php echo $l["star7"];?></td>
                                <td><?php echo $l["star8"];?></td>
                                <td><?php echo $l["star9"];?></td>
                                <td><?php echo $l["star10"];?></td>
                                <td><?php echo $l["topic4"];?></td>
                                <td><?php echo $l["star1"];?></td>
                                <td><?php echo $l["make5"];?></td>
                                <td><?php echo $l["host"];?></td>
                                <td><?php echo $l["host_main"];?></td>
                                <td><?php echo $l["make4"];?></td>
                                <td><?php echo $l["topic5"];?></td>
                                <td><?php echo $l["topic9"];?></td>
                                <td><?php echo $l["star11"];?></td>
                                <td><?php echo $l["star12"];?></td>
                                <td><?php echo $l["star13"];?></td>
                                <td><?php echo $l["star14"];?></td>
                                <td><?php echo $l["guest"];?></td>
                                <td><?php echo $l["guest_main"];?></td>
                                <td><?php echo $l["topic8"];?></td>
                                <td><?php echo $l["topic10"];?></td>
                                <td><?php echo $l["star15"];?></td>
                                <td><?php echo $l["star16"];?></td>
                                <td><?php echo $l["star17"];?></td>
                                <td><?php echo $l["star18"];?></td>
                                <td><?php echo $l["star2"];?></td>
                                <td><?php echo $l["make6"];?></td>
                                <td><?php echo $l["team"];?></td>
                                <td><?php echo $l["team_main"];?></td>
                                <td><?php echo $l["make1"];?></td>
                                <td><?php echo $l["make7"];?></td>
                                <td><?php echo $l["resource1"];?></td>
                                <td><?php echo $l["resource2"];?></td>
                                <td><?php echo $l["resource3"];?></td>
                                <td><?php echo $l["resource4"];?></td>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
<!--                <div class="table-control" id="table-control" style="width: 120px;">-->
<!--                    <table class="pure-table pure-table-none pure-table-striped" style="width: 120px"></table>-->
<!--                </div>-->
                <div class="table-head" id="table-head" style="width: 120px;"></div>
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
    params["status"]='<?php echo $_GET["status"];?>'
    params["year"]='<?php echo $_GET["year"];?>'
    params["season"]='<?php echo $_GET["season"];?>'
    params["type"]='<?php echo $_GET["type"];?>'
    params["program_name"]='<?php echo $_GET["program_name"];?>'

    $('#btn_search').on('click',function(){
        params["start_date"]=$('#start_date').val();
        params["end_date"]=$('#end_date').val();
        params["status"]=$('#select_status option:selected').val();
        params["year"]=$('#select_year option:selected').val();
        params["season"]=$('#select_season option:selected').val();
        params["type"]=$('#type').val();
        params["program_name"]=$('#program_name').val();

        window.location.href='tensyn_movie_list.php?'+$.param(params);
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
        window.location.href='tensyn_movie_list.php?'+$.param(params);
    });
    $('#pageNum').on('keypress',function(e){
        var value=parseInt($(this).val());
        if(e.keyCode==13){
            if(value<=0||value>page_count||isNaN(value)){
                return false;
            }else{
                params["p"]=value;
                window.location.href='tensyn_movie_list.php?'+$.param(params);
            }
        }
    });
    // $('a[id^="edit_"]').on('click',function(){
    //     var id=$(this).attr('id').split('_')[1];
    //     window.location.href='movie_edit.php?id='+id;
    // });
//    $('#table-control').on('click','button[name="delete"]',function(){
//        $.post('ajax/tensyn_delete.php',{program_id:$(this).attr('id').split('_')[1]},function(json){
//            __BDP.alertBox("提示",json.msg);
//            if(json.r==1){window.location.reload();}
//        },'json');
//    });

    $('#export').on('click',function(){
        window.open('export/movie_list.php?'+$.param(params));
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
