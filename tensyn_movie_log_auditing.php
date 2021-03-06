<?php
$pageTitle = "审核录入单";
$pageNavId = 3;
$pageNavSub = 32;

include("function.php");
include("include/Program.class.php");
include("include/Tensyn.class.php");
include("include/TensynInput.class.php");

$input_id=$_GET["id"];
$page=isset($_GET["p"])?intval($_GET["p"]):1;
$pagecount=isset($_GET["c"])?intval($_GET["c"]):500; //每页显示数量
$offset=($page-1)*$pagecount;
 
$result=TensynInput::get_programs($input_id,$offset,$pagecount);
$list=$result["data"];
$list_count=$result["total_count"];
$page_count=$result["page_count"];

$input=TensynInput::get_info($input_id);
$type_status=array(
    -1=>"delete",
    0=>"new",
    1=>"same",
    2=>"update"
);
$status=Program::get_status();
$upload_buttons=array("poster","male","female","host","guest");
?>
<!DOCTYPE html>
<html>
<head>
    <?php include_once("module/head_tag.php"); ?>

    <script type="text/javascript" src="js/libs/jquery.mCustomScrollbar.concat.min.js"></script>
    <script src="./js/pages/movie_log_auditing.js" type="text/javascript"></script>
    <style>
        #table-data .td-control, #table-control .td-control {
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
                <a href="#" type="submit" class="pure-btn btn-red">返回</a>
            </div>
            <div class="pure-g">
                <div class="pure-u-1-6"><span class="label-tag">审核中</span></div>
                <div class="pure-u-2-3">
                    <table class="pure-table pure-table-none">
                        <tr>
                            <!-- <td>供应商：北京爱奇艺科技有限公司</td> -->
                            <td>提交日期：<?php echo $input["create_date"];?></td>
                            <td>备注信息：<?php echo $input["remark"];?></td>
                        </tr>
                    </table>
                </div>
            </div>

            <div style="padding-top: 2em;text-align: center">
                <button id="audit" type="button" class="pure-btn btn-large btn-red">审 核</button>
                &nbsp;
                &nbsp;
                <a href="tensyn_movie_log_audit_h.php?id=<?php echo $input_id;?>"  class="pure-btn btn-large btn-red">横向审核方式</a>
            </div>
        </div>
        <div class="content">
            <div class="pull-right">
                <button id="batch_yes" type="button" class="pure-btn">批量通过</button>
                <button id="batch_no" type="button" class="pure-btn">批量拒绝</button>
            </div>
            <h3 class="title">录入单: <?php echo $input["name"];?></h3>
            <br class="clear">
            <div class="table-box">
                <div class="table-list">
                    <div id="table-data">
                        <table class="pure-table pure-table-line pure-table-striped pure-table-hover" style="width: 18800px">
                            <thead>
                            <tr>
                                <th>标记</th>
                                <th>状态</th>
                                <th>剧目名称</th>
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
                                <th>剧集海报</th>
                                <th>男主演照片</th>
                                <th>女主演照片</th>
                                <th>主持人照片</th>
                                <th>常驻嘉宾照片</th>
                                <th class="td-control">编辑</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach($list as $l){ ?>
                            <tr >
                                <td>
                                    <div name="select" id="<?php echo $l["tprogram_id"];?>" class="input-checkbox"></div>
                                        <span class="movie-tag <?php echo $type_status[$l["type_status"]];?>"></span></td>
                                <td id="<?php echo $l["tprogram_id"];?>"><?php echo $status[$l["tstatus"]];?></td>
                                <td><?php echo $l["program_name"];?></td>
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
                                <td><?php echo $l["match3"];?></td>
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
                                <td><?php echo $l["mplay2"];?></td>
                                <td><?php echo $l["play3"];?></td>
                                <td><?php echo $l["mplay4"];?></td>
                                <td><?php echo $l["mplay5"];?></td>
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
                                <td class="td-control">
                                <?php if($l["status"]!=3){ ?>
                                    <a name="yes" id="<?php echo $l["tprogram_id"];?>" type="button" class="pure-btn btn-min">通过</a>
                                    <a name="no" id="<?php echo $l["tprogram_id"];?>" type="button" class="pure-btn btn-min">拒绝</a> 
                                <?php } ?>
                                </td>
                            </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="table-control" id="table-control" style="width: 150px;">
                    <!-- <table class="pure-table pure-table-none pure-table-striped" style="width: 1200px"></table>-->
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
                <div class="input-checkbox-all"></div>
                全选 &nbsp; 记录共<?php echo $list_count;?>条，<?php echo $page_count;?>页
            </div>
        </div>
        <!-- page01 End -->

        <?php include_once("module/footer.php"); ?>
    </div>

</div>
<script type="text/javascript">
    var page=parseInt('<?php echo $page;?>');
    var page_count='<?php echo $page_count;?>';
    var input_id='<?php echo $input_id;?>';
    var params={};
    params["p"]=page;
    params["c"]='<?php echo $pagecount;?>';
    params["id"]=input_id;

    $('#audit').on('click',function(){
        $.post('ajax/tensyn_input_audit.php',{input_id:input_id},function(json){
             __BDP.alertBox('提示',json.msg,'','',function(){
                if(json.r==1){window.location.href='tensyn_movie_log.php';}
            });
        },'json');
    });
    $('#table-control ').on('click','a[name]',function(){
        var program_id=$(this).attr('id');
        var type=$(this).attr('name');
        $.post('ajax/tensyn_audit.php',{program_id:program_id,type:type},function(json){
            __BDP.alertBox('提示',json.msg,'','',function(){
                if(json.r==1){$('td[id="'+program_id+'"]').text(json.text);}
            });
        },'json');
    }); 
    // $('#table-control ').on('click','a[name="yes"]',function(){
    //     var program_id=$(this).attr('id');
    //     $.post('ajax/program_audit.php',{program_id:program_id,type:'yes'},function(json){
    //         __BDP.alertBox('提示',json.msg,'','',function(){
    //             if(json.r==1){$('td[id="'+program_id+'"]').text('审批通过');}
    //         });
    //     },'json');
    // }); 
    $('button[id^="batch"]').on('click',function(){
        var program_id=$('div[name="select"].active').map(function(){
            return $(this).attr('id');
        }).get().join(',');
        var type=$(this).attr('id').split('_')[1];

        $.post('ajax/tensyn_audit.php',{program_id:program_id,type:type},function(json){
            __BDP.alertBox('提示',json.msg,'','',function(){
                if(json.r==1){window.location.href=window.location.href;}
            }); 
        },'json');
    });
    // $('#table-control ').on('click','a[name="no"]',function(){
    //     var program_id=$(this).attr('id');
    //     $.post('ajax/program_audit.php',{program_id:program_id,type:'no'},function(json){
    //         __BDP.alertBox('提示',json.msg,'','',function(){
    //             if(json.r==1){$('td[id="'+program_id+'"]').text('审批未通过');}
    //         });
    //     },'json');
    // });
    // $('#batch_no').on('click',function(){
    //     var program_id=$('div[name="select"].active').map(function(){
    //         return $(this).attr('id');
    //     }).get().join(',');

    //     $.post('ajax/tensyn_audit.php',{program_id:program_id,type:'no'},function(json){
    //         __BDP.alertBox('提示',json.msg,'','',function(){
    //             if(json.r==1){window.location.href=window.location.href;}
    //         }); 
    //     },'json');
    // });
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
        window.location.href='tensyn_movie_log_auditing.php?'+$.param(params);
    });
    $('#pageNum').on('keypress',function(e){
        var value=parseInt($(this).val());
        if(e.keyCode==13){
            if(value<=0||value>page_count||isNaN(value)){
                return false;
            }else{
                params["p"]=value; 
                window.location.href='tensyn_movie_log_auditing.php?'+$.param(params);
            }
        }
    }); 
</script>
</body>
</html>
