<?php
$pageTitle = "剧目管理";
$pageNavId = 3;
$pageNavSub = 33;

include("function.php");
include("include/Tensyn.class.php");

$list=Tensyn::get_program_log();

$unvalids=Tensyn::get_unvalid_fields();

$upload_buttons=array("poster","male","female","host","guest");
$cols=array("program_name",
"program_default_name",
"type",
"intro",
"play_time",
"start_time",
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
"platform",
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
"copyright",
"satellite",
"channel3",
"start_type",
"play1",
"mplay2",
"play3",
"mplay4",
"mplay5",
"play6",
"tplay2",
"content_type",
"tplay3",
"IP5",
"IP6",
"IP7",
"creator",
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
"team",
"team_main",
"make1",
"make7",
"resource1",
"resource2",
"resource3",
"resource4");
?>
<!DOCTYPE html>
<html>
<head>
    <?php include_once("module/head_tag.php"); ?>

    <script type="text/javascript" src="js/libs/jquery.mCustomScrollbar.concat.min.js"></script>
    <script src="./js/pages/tensyn_movie_edit.js" type="text/javascript"></script>
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
            <!-- <div class="pull-right">
                <button type="submit" class="pure-btn btn-large btn-red">资料上传</button>
            </div> -->
            <!--            <h3 class="title">剧目列表</h3>-->
            <div>
                <button type="button" class="pure-btn <?php if(count($unvalids)>0){echo "pure-btn-disabled";}?> " id="<?php if(count($unvalids)===0){echo "audit";}?>" style="width: 100px; margin-right: 1em;">提交审核</button>
                <button id="export" type="button" class="pure-btn">导出录入单</button>
            </div>
            <br class="clear">
            <div class="form-eval">
                <div class="pure-g">
                    <div class="pure-u-1-3">
                        <label for="name">日　　期</label>
                        <input value="<?php echo date("Y-m-d");?>" id="date" type="text" placeholder="" class="input-label">
                    </div>
                    <!-- <div class="pure-u-1-3">
                        <label for="company">录入单状态</label>
                        <select class="form-control" id="select_score">
                            <option value="">全部</option>
                            <option value="1">0.0-0.5</option>
                            <option value="2">0.5-1.0</option>
                            <option value="3">1.0-1.5</option>
                            <option value="4">1.5-2.0</option>
                            <option value="5">2.0-2.5</option>
                            <option value="6">2.5-3.0</option>
                            <option value="7">3.0-3.5</option>
                            <option value="8">3.5-4.0</option>
                        </select>
                    </div> -->
                    <div class="pure-u-1-3">
                        <label for="remark">备注信息</label>
                        <input id="remark" type="text" placeholder="" class="input-label" style="width: 170px;">
                    </div>
                    <div class="clear"></div>
                </div>
            </div>

            <br><br>
            <div class="table-box">
                <div class="table-list" style="width: 100%; ">
                    <div id="table-data">
                        <table class="pure-table pure-table-line pure-table-striped pure-table-hover" style="width: 14500px;">
                            <thead>
                            <tr>
                                <th class="td-head" style="width: 120px;">剧目名称</th>
                                <th>剧目原名</th>
                                <th>资源类型</th>
                                <th width="500">简介</th>
                                <th>播出时间</th>
                                <th>开播时间</th>
                                <th>本季开播前3月<br>新闻报道量(条)</th>
                                <th>上季播出时段内<br>新闻报道量(条)</th>
                                <th>开播前3月<br>百度指数(万)</th>
                                <th>开播前3月<br>微指数(万)</th>
                                <th>上季播出周期内<br>百度指数(万)</th>
                                <th>上季播出周期内<br>微指数(万)</th>
                                <th>预告片<br>播放量(万)</th>
                                <th>原著粉丝数(万)</th>
                                <th>原著贴吧<br>发帖量(万)</th>
                                <th>原著贴吧<br>关注度与发帖量之比</th>
                                <th>上季节目<br>微博粉丝数(万)</th>
                                <th>贴吧关注人数(万人)</th>
                                <th>贴吧关注度<br>与发帖量之比</th>
                                <th>前季微博<br>话题量(万)</th>
                                <th>前季贴吧<br>发帖量(万)</th>
                                <th>年龄</th>
                                <th>性别</th>
                                <th>地域</th>
                                <th>媒体平台</th>
                                <th>MAU</th>
                                <th>UV</th>
                                <th>过往自制剧<br>数量(个)</th>
                                <th>过往自综艺<br>数量(个)</th>
                                <th>新秀自制剧<br>最高单集播放量(万)</th>
                                <th>新秀自制剧<br>平均单集播放量(万)</th>
                                <th>自制剧最高<br>单集播放量(万)</th>
                                <th>自制剧平均<br>单集播放量(万)</th>
                                <th>新秀自制综艺<br>最高单集播放量(万)</th>
                                <th>新秀自制综艺<br>平均单集播放量(万)</th>
                                <th>自制综艺<br>最高单集播放量(万)</th>
                                <th>自制综艺<br>平均单集播放量(万)</th>
                                <th>版权情况</th>
                                <th>播出卫视</th>
                                <th>反输出<br>电视收视率(%)</th>
                                <th>播出状态</th>
                                <th>本季预估<br>播放量(单位：亿)</th>
                                <th>累计播放量(单位：亿)</th>
                                <th>集数/期数</th>
                                <th>已播集数</th>
                                <th>实际单集<br>播放量(万)</th>
                                <th>本季预估<br>单集播放量(万)</th>
                                <th>上季单集播放量(万)</th>
                                <th>内容类型</th>
                                <th>同档期同题材<br>内容数量(个)</th>
                                <th>同类型综艺<br>微博话题量(万)</th>
                                <th>同类型综艺<br>微博粉丝数(万)</th>
                                <th>同类型综艺贴吧<br>发帖量于关注人数比</th>
                                <th>主创/嘉宾</th>
                                <th>男主演</th>
                                <th>男主演代表作</th>
                                <th>男主演代表作单集播放量(万)</th>
                                <th>男主过往代表作微博话题量(万)</th>
                                <th>男主演前一内容播放期间百度指数(万)</th>
                                <th>男主演开播前3月百度指数(万)</th>
                                <th>男主演前一内容播放期间微指数(万)</th>
                                <th>男主演开播前3月微指数(万)</th>
                                <th>男主官方贴吧发帖数(万)</th>
                                <th>女主演</th>
                                <th>女主演代表作</th>
                                <th>女主演代表作<br>单集播放量(万)</th>
                                <th>女主过往代表作<br>微博话题量(万)</th>
                                <th>女主演前一内容<br>播放期间百度指数(万)</th>
                                <th>女主演开播<br>前3月百度指数(万)</th>
                                <th>女主演前一内容<br>播放期间微指数(万)</th>
                                <th>女主演开播<br>前3月微指数(万)</th>
                                <th>女主官方贴吧<br>发帖数(万)</th>
                                <th>男女主演微博<br>粉丝数(万)</th>
                                <th>大牌明星数<br>(个：微博粉丝＞500万)</th>
                                <th>主持人</th>
                                <th>主持人代表作</th>
                                <th>主持人代表作<br>单集播放量(万)</th>
                                <th>主持人过往代表作<br>微博话题量(万)</th>
                                <th>主持人官方贴吧<br>发帖量(万)</th>
                                <th>主持人演开播<br>前3月百度指数(万)</th>
                                <th>主持人前一内容<br>播放期间百度指数(万)</th>
                                <th>主持人演开播<br>前3月微指数(万)</th>
                                <th>主持人前一内容<br>播放期间微指数(万)</th>
                                <th>常驻嘉宾</th>
                                <th>常驻嘉宾代表作</th>
                                <th>常驻嘉宾微<br>博话题量(万)</th>
                                <th>常驻嘉宾官方<br>贴吧发帖量(万)</th>
                                <th>常驻嘉宾演开播<br>前3月百度指数(万)</th>
                                <th>常驻嘉宾前一内容<br>播放期间百度指数(万)</th>
                                <th>常驻嘉宾前一内容<br>播放期间微指数(万)</th>
                                <th>常驻嘉宾演<br>开播前3月微指数(万)</th>
                                <th>主持人及常驻嘉宾<br>微博粉丝数(万)</th>
                                <th>大牌主持人数(个)</th>
                                <th>制作团队</th>
                                <th>制作团队<br>/导演代表作品</th>
                                <th>制作团队代表作<br>单集播放量(万)</th>
                                <th>单集制作<br>经费(万元)</th>
                                <th>招商资源包<br>售卖净价(万元)</th>
                                <th>招商资源包<br>总刊例价(万元)</th>
                                <th>站内推广资源<br>总价值(万元)</th>
                                <th>合作权益<br>形式数量(种)</th>
                                <th>剧集海报</th>
                                <th>男主演照片</th>
                                <th>女主演照片</th>
                                <th>主持人照片</th>
                                <th>常驻嘉宾照片</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                                foreach($list as $l){
                                $program_id=$l["tprogram_id"];
                            ?>
                            <tr >
                            <?php foreach($cols as $key => $c){ ?>
                            <td <?php if($key==0){echo ' class="td-head"';}?> style="<?php if(in_array($c,$unvalids[$program_id])){echo "background-color:#FFFF00";}?>"><?php echo $c=="intro"?csubstr($l["intro"],0,80):$l[$c];?></td>
                            <?php } ?>
                                <!-- <td><?php echo $l["program_name"];?></td>
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
                                <td><?php echo $l["resource4"];?></td> -->
                                <?php
                                    $program_id=$l["program_id"];
                                    $tprogram_id=$l["tprogram_id"];
                                    $attachs=Tensyn::check_attachs_log($program_id,$tprogram_id);
                                    foreach($upload_buttons as $b){
                                ?>
                                <td>
                                    <?php if($attachs[$b]!=""){ ?>
                                    <img id="<?php echo $b.'_'.$l["tprogram_id"].'_'.$l["program_default_name"];?>" src="<?php echo UPLOAD_URL.$attachs[$b];?>" class="img-poster">
                                    <?php }?>
                                    <?php if($b!="poster"){ ?>
                                    <button name="upload" id="<?php echo $b.'_'.$l["program_default_name"].'_'.$l["tprogram_id"];?>" type="button" class="pure-btn btn-xsmall">上传</button>
                                    <?php } ?>
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
            <!--br>
            <div class="table-footer">
                <div class="page-control">
                    每页显示20条 &nbsp; &nbsp;
                    <a href="#" class="btn-page">首页</a>
                    <a href="#" class="btn-page">上一页</a>
                    <a href="#" class="btn-page">下一页</a>
                    <a href="#" class="btn-page">尾页</a>
                    <input id="pageNum" type="text" placeholder="" class="input-num" size="2">
                </div>
                记录共120条
            </div-->
        </div>
        <div class="content">
            <div class="tab-group">
                <div class="tab-menus">
                    <a href="#" class="tab-menu active"><span class="arrow-tag"></span>Excel批量导入</a>
                </div>
                <div class="tab-item active" id="tab-iten-02" style="height: 400px;">
                    <br><br><br><br>
                    <form class="pure-form">
                        <table class="pure-table pure-table-none" style="width: 50%;">
                            <tbody>
                            <tr>
                                <td>&nbsp;</td>
                                <td>第一步</td>
                                <td>
                                    <button id="template" type="button" class="pure-btn btn-large btn-red">导出Excel模板</button>
                                </td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td>第二步</td>
                                <td>
                                    <button id="upload_excel" type="button" class="pure-btn btn-large btn-red">导入Excel</button>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </form>
                    <br>
                </div>
                <!--           Teb END     tab-item-02-->
            </div>

        </div>
        <!-- page01 End -->

        <?php include_once("module/footer.php"); ?>
    </div>

</div>
<link rel="stylesheet" href="jquery-ui-1.12.1/jquery-ui.min.css">
<script src="jquery-ui-1.12.1/jquery-ui.min.js"></script>
<script src="js/plupload.full.min.js"></script>
<script type="text/javascript">
    $('button[name="upload"]').each(function(){
        var button_id=$(this).attr('id');
        var temp=button_id.split('_');
        var type=temp[0];
        var program_id=temp[2];
        var program_name=temp[1];
        make_upload(button_id,program_id,program_name,type);
    });
    $('#date').datepicker({
        dateFormat:'yy-mm-dd'
    });

    make_excel_upload();

    function make_excel_upload(){
        var uploader = new plupload.Uploader({
            browse_button: 'upload_excel', // this can be an id of a DOM element or the DOM element itself
            url: 'upload_excel.php',
            filters: {
              mime_types : [
                { title : "Excel files", extensions : "xls,xlsx" }
              ]
            }
        });

        uploader.init();

        uploader.bind('FilesAdded', function(up, files) {
            uploader.start();
        });
        uploader.bind('FileUploaded',function(up,file,obj){
            var json=$.parseJSON(obj.response);
            __BDP.alertBox('提示',json.msg,'','',function(){
                 if(json.result=='success'){
                    window.location.href=window.location.href;
                }
            });
        });
    }

    function make_upload(button_id,program_id,program_name,type){
        var uploader = new plupload.Uploader({
            browse_button: button_id, // this can be an id of a DOM element or the DOM element itself
            url: 'upload.php',
            multipart_params : {
                "type":type,
                "program_name":program_name,
                "program_id":program_id
            },
            filters: {
              mime_types : [
                { title : "Image files", extensions : "jpg,gif,png" }
              ]
            }
        });

        uploader.init();

        uploader.bind('FilesAdded', function(up, files) {
            uploader.start();
        });
        uploader.bind('FileUploaded',function(up,file,obj){
            var json=$.parseJSON(obj.response);
            if(json.result=='success'){
                var img_id='#'+type+'_'+program_id+'_'+program_name;
                console.log(img_id,button_id)
                if($(img_id).length>0){
                    $(img_id).attr('src',json.path);
                }else{
                    $('#'+button_id).before('<img src="'+json.path+'" class="img-poster">');
                }
            }else{
                alert('上传错误，请重新上传');
            }
        });
    }

    $('#audit').on('click',function(){
        $.post('ajax/tensyn_input_add.php',{
            date:$('#date').val(),
            remark:$('#remark').val()
        },function(json){
            __BDP.alertBox("提示",json.msg,'','',function(){
                if(json.r==1){
                    window.location.href=window.location.href;
                }
            });
        },'json');
    });

    $('#template').on('click',function(){
        window.open('export/movie_list.php');
    });
    $('#export').on('click',function(){
        window.open('export/tensyn_movie_edit.php');
    });
</script>

</body>
</html>
