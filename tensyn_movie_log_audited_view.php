<?php
$pageTitle = "已审核录入单";
$pageNavId = 3;
$pageNavSub = 34;

include("function.php");
include("include/TensynInput.class.php");
include("include/Tensyn.class.php");

$input_id=$_GET["id"];
$status=$_GET["status"];

$page=isset($_GET["p"])?intval($_GET["p"]):1;
$pagecount=isset($_GET["c"])?intval($_GET["c"]):10; //每页显示数量
$offset=($page-1)*$pagecount;

$input=TensynInput::get_info($input_id);
$result=TensynInput::get_programs($input_id,$offset,$pagecount,$status);
$list=$result["data"];
$list_count=$result["total_count"];
$page_count=$result["page_count"]; 



?>
<!DOCTYPE html>
<html>
<head>
    <?php include_once("module/head_tag.php"); ?>

    <script type="text/javascript" src="js/libs/jquery.mCustomScrollbar.concat.min.js"></script>
    <script src="./js/pages/movie_log_audited_view.js" type="text/javascript"></script>
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
                <a href="tensyn_movie_log_audited.php" type="submit" class="pure-btn btn-red">返回</a> 
            </div>
            <div class="pure-g">
                <div class="pure-u-1-6"><span class="label-tag">已审核</span></div>
                <div class="pure-u-2-3">
                    <table class="pure-table pure-table-none">
                        <tr>
                            <!-- <td>供应商：<?php echo $input["supplier"];?></td> -->
                            <td>提交日期：<?php echo $input["create_date"];?></td>
                            <td>备注信息：<?php echo $input["remark"];?></td>
                        </tr>
                        <tr>
                            <td>媒体录入单：<?php echo $input["name"];?></td>
                            <td>审核时间：<?php echo $input["update_date"];?></td>
                            <!-- <td>审核人：吴瑞明</td> -->
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <div class="content">
            <div class="pull-right">
                <a target="_blank"  href="export/tensyn_movie_log_audited_view.php?id=<?php echo $input_id."&status=".$status;?>" class="pure-btn">导出</a>
            </div>
            <div class="tab-group">
                <div class="tab-menus">
                    <a href="tensyn_movie_log_audited_view.php?id=<?php echo $input_id;?>&status=2" class="tab-menu <?php if($status==2){echo "active";}?>"><span class="arrow-tag"></span>审核通过</a>
                    <a href="tensyn_movie_log_audited_view.php?id=<?php echo $input_id;?>&status=-2" class="tab-menu <?php if($status==-2){echo "active";}?>"><span class="arrow-tag"></span>审核拒绝</a>
                </div>
                <div class="tab-item active" id="tab-iten-01" >
                    <div class="table-box">
                        <div class="table-list">
                            <div id="table-data"> 
                                <table class="pure-table pure-table-line pure-table-striped pure-table-hover" style="width: 18600px"> 
                                    <thead>
                                    <tr>
                                <th class="td-head" style="width: 120px;">剧目名称</th>
                                <th>剧目原名</th>
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
                               <!--  <th>剧集海报</th>
                                <th>男主演照片</th>
                                <th>女主演照片</th>
                                <th>主持人照片</th>
                                <th>常驻嘉宾照片</th> -->
                                    </tr>
                                    </thead>
                                    <tbody>
                                     <?php foreach($list as $l){ ?>
                            <tr >
                                <td class="td-head"><?php echo $l["program_name"];?></td>
                                <td><?php echo $l["program_default_name"];?></td>
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
                                <?php 
                                    $program_id=$l["program_id"];
                                    $tprogram_id=$l["tprogram_id"]; 
                                    $attachs=Tensyn::check_attachs_log($program_id,$tprogram_id);
                                    foreach($upload_buttons as $b){
                                ?>
                                <td> 
                                    <?php if($attachs[$b]!=""){ ?>
                                    <img src="<?php echo UPLOAD_URL.$attachs[$b];?>" class="img-poster">
                                    <?php }?>
                                </td>
                                <?php } ?>
                            </tr>
                            <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
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
                <!--           Teb END     tab-item-01-->

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
    params["p"]=parseInt(page);
    params["c"]='<?php echo $pagecount;?>';
    params["id"]='<?php echo $input_id;?>';
    params["status"]='<?php echo $status;?>';
    
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
        window.location.href='tensyn_movie_log_audited_view.php?'+$.param(params);
    });
    $('#pageNum').on('keypress',function(e){
        var value=parseInt($(this).val());
        if(e.keyCode==13){
            if(value<=0||value>page_count||isNaN(value)){
                return false;
            }else{
                params["p"]=value; 
                window.location.href='tensyn_movie_log_audited_view.php?'+$.param(params);
            }
        }
    }); 
</script>
</body>
</html>