<?php
$pageTitle = "剧目详情";
$pageNavId = 9;
$pageNavSub = 9;

include("function.php");
$db=db_connect();

$media_id=$db->escape($_GET["mid"]);
$tensyn_id=$db->escape($_GET["tid"]);

$head="
    select *,
    m.play1 as mplay1,m.play2 as mplay2,m.play3 as mplay3,m.play4 as mplay4,m.play5 as mplay5,m.play6 as mplay6,
    t.play2 as tplay2,t.play3 as tplay3
";

$body="
    from media_program_history as m
    inner join tensyn_program_history as t
        on m.program_default_name=t.program_default_name
        and m.platform=t.platform
";
$where=" where m.program_id='{$media_id}' and t.program_id='{$tensyn_id}' ";


$sql=$head.$body.$where;
$old=$db->get_row($sql,ARRAY_A);

$start_types=array("待播出","播出中","已播完");

$table_name=array(
    "剧目名称",
    "剧目原名",
    "资源类型",
    "播出时间",
    "媒体平台",
    "开播时间",
    "版权情况",
    "播出状态",
    "播出卫视",
    "主创/嘉宾",
    "内容类型",
    "制作团队",
    "简介",
    "本季预估播放量（单位：亿）",
    "累计播放量（单位：亿）",
    "集数/期数",
    "已播集数",
    "实际单集播放量（万）",
    "本季预估单集播放量（万）",
    "本季开播前3月新闻报道量（条）",
    "上季播出时段内新闻报道量(条)",
    "开播前3月百度指数（万）",
    "开播前3月微指数（万）",
    "上季播出周期内百度指数（万）",
    "上季播出周期内微指数（万）",
    "预告片播放量（万）",
    "原著粉丝数（万）",
    "原著贴吧发帖量（万）",
    "原著贴吧关注度与发帖量之比",
    "上季节目微博粉丝数（万）",
    "贴吧关注人数（万人）",
    "贴吧关注度与发帖量之比",
    "前季微博话题量（万）",
    "前季贴吧发帖量（万）",
    "年龄",
    "性别",
    "地域",
    "MAU",
    "UV",
    "过往自制剧数量（个）",
    "过往自综艺数量（个）",
    "新秀自制剧最高单集播放量（万）",
    "新秀自制剧平均单集播放量（万）",
    "自制剧最高单集播放量（万）",
    "自制剧平均单集播放量（万）",
    "新秀自制综艺最高单集播放量（万）",
    "新秀自制综艺平均单集播放量（万）",
    "自制综艺最高单集播放量（万）",
    "自制综艺平均单集播放量（万）",
    "反输出电视收视率（%）",
    "上季单集播放量（万）",
    "同档期同题材内容数量（个）",
    "同类型综艺微博话题量（万）",
    "同类型综艺微博粉丝数（万）",
    "同类型综艺贴吧发帖量于关注人数比",
    "男主演",
    "男主演代表作",
    "男主演代表作单集播放量（万）",
    "男主过往代表作微博话题量（万）",
    "男主演前一内容播放期间百度指数（万）",
    "男主演开播前3月百度指数（万）",
    "男主演前一内容播放期间微指数（万）",
    "男主演开播前3月微指数（万）",
    "男主官方贴吧发帖数（万）",
    "女主演",
    "女主演代表作",
    "女主演代表作单集播放量（万）",
    "女主过往代表作微博话题量（万）",
    "女主演前一内容播放期间百度指数（万）",
    "女主演开播前3月百度指数（万）",
    "女主演前一内容播放期间微指数（万）",
    "女主演开播前3月微指数（万）",
    "女主官方贴吧发帖数（万）",
    "男女主演微博粉丝数（万）",
    "大牌明星数（个：微博粉丝＞500万）",
    "主持人",
    "主持人代表作",
    "主持人代表作单集播放量（万）",
    "主持人过往代表作微博话题量（万）",
    "主持人官方贴吧发帖量（万）",
    "主持人演开播前3月百度指数（万）",
    "主持人前一内容播放期间百度指数（万）",
    "主持人演开播前3月微指数（万）",
    "主持人前一内容播放期间微指数（万）",
    "常驻嘉宾",
    "常驻嘉宾代表作",
    "常驻嘉宾微博话题量（万）",
    "常驻嘉宾官方贴吧发帖量（万）",
    "常驻嘉宾演开播前3月百度指数（万）",
    "常驻嘉宾前一内容播放期间百度指数（万）",
    "常驻嘉宾前一内容播放期间微指数（万）",
    "常驻嘉宾演开播前3月微指数（万）",
    "主持人及常驻嘉宾微博粉丝数（万）",
    "大牌主持人数（个）",
    "制作团队/导演代表作品",
    "制作团队代表作单集播放量（万）",
    "单集制作经费（万元）",
    "招商资源包售卖净价（万元）",
    "招商资源包总刊例价（万元）",
    "站内推广资源总价值（万元）",
    "合作权益形式数量（种）");

$table_field=array(
    "program_name",
    "program_default_name",
    "type",
    "play_time",
    "platform",
    "start_time",
    "copyright",
    "start_type",
    "satellite",
    "creator",
    "content_type",
    "team",
    "intro",
    "mplay1",
    "mplay2",
    "mplay3",
    "mplay4",
    "mplay5",
    "mplay6",
    "channel1",
    "channel2",
    "attention1",
    "attention2",
    "attention3",
    "attention4",
    "attention5",
    "IP1",
    "IP2",
    "IP3",
    "IP4",
    "IP8",
    "IP9",
    "topic6",
    "topic7",
    "match1",
    "match2",
    "match3",
    "platform1",
    "platform2",
    "platform3",
    "platform4",
    "platform5",
    "platform6",
    "platform7",
    "platform8",
    "platform9",
    "platform10",
    "platform11",
    "platform12",
    "channel3",
    "tplay2",
    "tplay3",
    "IP5",
    "IP6",
    "IP7",
    "male_leader",
    "male_main",
    "make2",
    "topic1",
    "star3",
    "star4",
    "star5",
    "star6",
    "topic3",
    "female_leader",
    "female_main",
    "make3",
    "topic2",
    "star7",
    "star8",
    "star9",
    "star10",
    "topic4",
    "star1",
    "make5",
    "host",
    "host_main",
    "make4",
    "topic5",
    "topic9",
    "star11",
    "star12",
    "star13",
    "star14",
    "guest",
    "guest_main",
    "topic8",
    "topic10",
    "star15",
    "star16",
    "star17",
    "star18",
    "star2",
    "make6",
    "team_main",
    "make1",
    "make7",
    "resource1",
    "resource2",
    "resource3",
    "resource4"
);


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
            <div class="pull-right">
                <a href="program_list.php" class="pure-btn btn-large btn-red">返回</a>
            </div>
            <h3 class="title">剧目详情</h3>
            <br class="clear">
            <div class="tab-group">
                <div class="tab-menus">
                    <a href="#" class="tab-menu active"><span class="arrow-tag"></span>基本信息 </a>
                </div>
                <div class="tab-item active" id="tab-iten-01">
                    <table class="pure-table pure-table-none" style="width: 80%">
                        <tbody>
                        <?php
                            foreach($table_name as $t){
                                if($t=="播出状态"){
                        ?>
                            <tr>
                                <td>&nbsp;</td>
                                <td>播出状态</td>
                                <td>
                                    <select id="start_type">
                                        <?php foreach($start_types as $st){ ?>
                                            <option value="<?php echo $st;?>" <?php if($st==$old["start_type"]){echo "selected";}?> ><?php echo $st;?></option>
                                        <?php } ?>
                                    </select>
                                </td>
                            </tr>
                        <?php }else { ?>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td><?php echo $t; ?></td>
                                        <td><input disabled value="<?php echo $old[current($table_field)]; ?>" name="<?php echo current($table_field);?>" type="text" placeholder="" class="input-form">
                                        </td>
                                    </tr>
                        <?php
                                }
                                next($table_field);
                            }
                        ?>
                        <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>
                                <div style="padding: 1em;">
                                    <br>
                                    <button id="submit" type="button" class="pure-btn btn-large btn-red">修 改</button>
                                    &nbsp;&nbsp;&nbsp;
                                    <button id="quit" type="button" class="pure-btn btn-large btn-red">取 消</button>
                                </div>

                            </td>
                        </tr>
                        </tbody>
                    </table>

                    <br>
                </div>
            </div>

        </div>
        <!-- page01 End -->

        <?php include_once("module/footer.php"); ?>
    </div>

</div>
<script type="text/javascript">
    var media_id='<?php echo $media_id;?>';
    var tensyn_id='<?php echo $tensyn_id;?>';

    $('#submit').on('click',function(){
        $.post('ajax/update_history.php',{
            media_id:media_id,
            tensyn_id:tensyn_id,
            start_type:$('#start_type option:selected').val(),
        },function(json){
             __BDP.alertBox('提示',json.msg,'','',function(){
                 if(json.r=1){
                    window.location.href='history_list.php';
                }
            });
        },'json');
    });
</script>
</body>
</html>
