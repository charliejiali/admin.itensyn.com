<?php
$pageTitle = "剧目管理";
$pageNavId = 7;

// $public_page=true;
include("../function.php");
include("include/Program.class.php");

$media_users=Program::get_media_users();
$user_id=isset($_GET["mid"])?$_GET["mid"]:5;

$lists=Program::get_programs(array(
    "user_id"=>$user_id,
    "status"=>0
));


if(isset($_GET["id"])){
    $p_id=$_GET["id"];
    $programs=Program::get_programs(array(
        "program_id"=>$p_id
    ));
    if($programs){$program=$programs[0];}
}else{
    $p_id="";
}

$play_time_years=get_play_time_year();
$play_time_months=array("","Q1","Q2","Q3","Q4");
$platforms=array("优酷土豆","爱奇艺","乐视TV","腾讯视频","搜狐视频","PPTV","芒果TV");
$copyrights=array("独播","非独播");
$start_types=array("待播出","播出中","已播完");
$types=array("新秀自制综艺","新秀自制剧","迭代自制剧","迭代自制综艺","新秀版权综艺","新秀版权剧","迭代版权综艺","迭代版权剧");

function get_play_time_year(){
    $this_year=intval(date("Y"));
    $data=array();
    for($i=0;$i<5;$i++){
        $data[]=$this_year+$i;
    }
    return $data;
}

$cols=array("program_name",
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
"play1",
// "play2",
"play3",
// "play4",
// "play5",
"play6");

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
            <!-- <div class="pull-right">
                <button id="upload" type="button" class="pure-btn btn-large btn-red">资料上传</button>
            </div> -->
            <!--            <h3 class="title">剧目列表</h3>-->
            <div>
               <!--  <button type="submit" class="pure-btn" style="width: 100px; margin-right: 1em;">保 存</button> -->
                <button id="audit" type="button" class="pure-btn" style="width: 100px; margin-right: 1em;">提交审核</button>
                <button id="export" type="button" class="pure-btn">导出录入单</button>
                <select id="media">
                    <?php foreach($media_users as $k=>$v){ ?>
                        <option value="<?php echo $k;?>" <?php if($k==$user_id){echo "selected";}?>><?php echo $v;?></option>
                    <?php } ?>
                </select>
            </div>
            <br class="clear">
            <div class="form-eval">
                <div class="pure-g">
                    <div class="pure-u-1-3">
                        <label for="supplier">供应商</label>
                        <input id="supplier" type="text" placeholder="" class="input-label" value="tensyn">
                    </div>
                    <div class="pure-u-1-3">
                        <label for="typein_date">录入日期</label> 
                        <input id="typein_date" type="text" value="<?php echo date('Y-m-d');?>" class="input-label">
                        <!-- <select class="form-control" id="">
                            <option value="">全部</option>
                            <option value="1">0.0-0.5</option>
                            <option value="2">0.5-1.0</option>
                            <option value="3">1.0-1.5</option>
                            <option value="4">1.5-2.0</option>
                            <option value="5">2.0-2.5</option>
                            <option value="6">2.5-3.0</option>
                            <option value="7">3.0-3.5</option>
                            <option value="8">3.5-4.0</option>
                        </select> -->
                    </div>
                    <div class="pure-u-1-3">
                        <label for="searchKey">备注信息</label>
                        <input id="remark" type="text" placeholder="" class="input-label" style="width: 170px;">
                    </div>
                    <div class="clear"></div>
                </div>
            </div>

            <br><br>

            <div class="table-box">
                <div class="table-list">
                    <div id="table-data">
                        <table class="pure-table pure-table-line pure-table-striped pure-table-hover" style="width: 3200px">
                            <thead>
                            <tr>
                                <th class="td-head" style="width: 120px;">剧目名称</th>
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
                                <!-- <th>累计播放量</th> -->
                                <th>集数/期数</th>
                                <!-- <th>已播集数</th> -->
                                <!-- <th>实际单集播放量</th> -->
                                <th>本季预估单集播放量</th>
                                <th>剧集海报</th>
                                <th>招商资源包</th>
                                <th>视频</th>
                                <th class="td-control" style="width:145px;">操作</th>
                            </tr>
                            </thead>
                            <tbody>

                            <?php
                                $upload_buttons=array("poster","resource","video");
                                $check_unvalid=0;
                                foreach($lists as $list){ 
                                    $program_default_name=$list["program_default_name"];
                                    $program_id=$list["program_id"];
                                    $attachs=Program::get_attach_log($user_id,$program_default_name);
                                    $unvalids=Program::get_unvalid_fields($program_id);
                                    if($check_unvalid===0&&count($unvalids)>0){$check_unvalid=1;}
                            ?>
                            <tr>
                            <?php foreach($cols as  $key => $c){ ?>
                            <td <?php if($key==0){echo ' class="td-head"';}?> style="<?php if(in_array($c,$unvalids)){echo "background-color:#FFFF00";}?>" ><?php echo $c=="intro"?csubstr($list["intro"],0,80):$list[$c];?></td>
                            <?php } ?>
                                <!-- <td><?php echo $list["program_name"];?></td>
                                <td><?php echo $list["program_default_name"];?></td>
                                <td><?php echo $list["type"];?></td>
                                <td><?php echo $list["play_time"];?></td>
                                <td><?php echo $list["platform"];?></td>
                                <td><?php echo $list["start_time"];?></td>
                                <td><?php echo $list["copyright"];?></td>
                                <td><?php echo $list["start_type"];?></td>
                                <td><?php echo $list["satellite"];?></td>
                                <td><?php echo $list["creator"];?></td>
                                <td><?php echo $list["content_type"];?></td>
                                <td><?php echo $list["team"];?></td>
                                <td><?php echo csubstr($list["intro"],0,80);?></td>
                                <td><?php echo $list["play1"];?></td>
                                <td><?php echo $list["play2"];?></td>
                                <td><?php echo $list["play3"];?></td>
                                <td><?php echo $list["play4"];?></td>
                                <td><?php echo $list["play5"];?></td>
                                <td><?php echo $list["play6"];?></td> -->
                                <?php foreach($upload_buttons as $b){?>
                                <td>
                                    <?php if($b=="poster"&&isset($attachs["poster_url"])){ ?>
                                        <img id="img_<?php echo $list["program_default_name"];?>" src="<?php echo UPLOAD_URL.$attachs["poster_url"];?>" class="img-poster">
                                    <?php } ?>
                                    <button name="upload" id="<?php echo $b.'_'.$list["program_default_name"].'_'.$list["program_id"].'_'.$list["platform"];?>" type="button" class="pure-btn btn-xsmall"><?php echo $attachs[$b];?></button>
                                </td>
                                <?php } ?>
                                <td class="td-control">
                                    <!-- <a id="edit_<?php echo $list["program_id"];?>" type="button" class="pure-btn btn-min">编辑</a> -->
                                    <a id="delete_<?php echo $list["program_id"];?>" type="button" class="pure-btn btn-min">删除</a> 
                                </td>
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
                    <a href="#" class="tab-menu "><span class="arrow-tag"></span>单品录入</a>
                    <a href="#" class="tab-menu active"><span class="arrow-tag"></span>Excel批量导入</a>
                </div>
                <!--           Teb END     tab-item-01-->
                
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
    var program_id='<?php echo $p_id;?>';
    var user_id='<?php echo $user_id;?>'

    // if(program_id===""){
    //     $('input[name="program_name"]').on('change paste keyup',function(){
    //         $('input[name="program_default_name"]').val($(this).val());
    //     });
    // }

    var check_unvalid='<?php echo $check_unvalid;?>';

    if(check_unvalid=='1'){$('#audit').addClass('pure-btn-disabled');}

    $('button[name="upload"]').each(function(){
        var button_id=$(this).attr('id');
        var temp=button_id.split('_'); 
        var type=temp[0];
        var program_id=temp[2];
        var program_name=temp[1];
        var platform=temp[3];
        make_upload(button_id,program_id,program_name,type,platform);
    }); 
    $('input[name="start_time"]').datepicker({
        dateFormat:'yy年mm月dd日'
    });

    make_excel_upload();

    function make_excel_upload(){
        var uploader = new plupload.Uploader({
            browse_button: 'upload_excel', // this can be an id of a DOM element or the DOM element itself
            url: 'upload_excel.php',
            multipart_params : {
                user_id:user_id
                // "type":type,
                // "program_name":program_name,
                // "program_id":program_id
            },
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
            console.log(json)
            if(json.hasOwnProperty('error')){
                __BDP.alertBox('提示',json.error.message,'','',function(){

                });
            }else {
                __BDP.alertBox('提示', json.msg, '', '', function () {
                    if (json.result == 'success') {
                        window.location.href = window.location.href;
                    }
                });
            }
        });
    }

    function make_upload(button_id,program_id,program_name,type,platform){
        var mime_type={};
        switch(type){
            case "poster":
                mime_type={title:"Image files",extensions:"jpg,jpeg,png,gif"};
                break;
            case "resource":
                mime_type={title:"Excel files",extensions:"xls,xlsx"};
                break;
            case "video":
                mime_type={title:"Video files",extensions:"avi,wmv,mpeg,mp4,mov,mkv,flv,f4v,m4v,rmvb,rm,3gp,dat,mts,vob"};
                break;
        }

        var uploader = new plupload.Uploader({
            browse_button: button_id, // this can be an id of a DOM element or the DOM element itself
            url: 'upload.php',
            multipart_params : {
                "user_id":user_id,
                "type":type,
                "program_name":program_name,
                "program_id":program_id,
                "platform":platform
            },
            filters: {
              // mime_types : [
              //   { title : "Image files", extensions : "jpg,gif,png" },
              //   { title : "Excel files", extensions : "xls,xlsx" },
              //   { title : "Zip files", extensions : "zip" },
              // ]
              mime_types : [mime_type]
            }
        });
     
        uploader.init();
     
        uploader.bind('FilesAdded', function(up, files) {
            uploader.start();
        });
        uploader.bind('FileUploaded',function(up,file,obj){
            var json=$.parseJSON(obj.response);
            if(json.result=='success'){
                if(type=='poster'){ 
                    if($('#img_'+program_name).length>0){
                        $('#img_'+program_name).attr('src',json.path);
                    }else{
                        $('#'+button_id).before('<img id="img_'+program_name+'" src="'+json.path+'" class="img-poster">');
                    }
                }
                $('#'+button_id).text('修改');
            }else{
                alert('上传错误，请重新上传');
            }
        });
    }


    $('#upload').on('click',function(){
        var input={};
        var play_time_year='';
        var play_time_month='';

        $('input[name]').each(function(){
            var box=$(this);
            var name=box.attr('name');

            input[name]=box.val(); 
        });
        $('select[name]').each(function(){
            var box=$(this);
            var name=box.attr('name');
            var value=box.find('option:selected').text();
            if(name=='play_time_year'){
                play_time_year=value;
            }else if(name=='play_time_month'){
                play_time_month=value;
            }else{
                input[name]=value;
            }
        });

        input["play_time"]=play_time_year=='时间待定'?'时间待定':play_time_year+'年'+play_time_month;
        input["intro"]=$('#intro').val();

        $.post('ajax/program_add.php',{input},function(json){
            __BDP.alertBox("提示",json.msg,'','',function(){
                if(json.result=="success"){
                    window.location.href=window.location.href;
                }
            });
        },'json')
    });
 
    $('#audit').on('click',function(json){
        $.post('ajax/input_add.php',{
            user_id:user_id,
            supplier:$('#supplier').val(),
            remark:$('#remark').val()
        },function(json){
            __BDP.alertBox("提示",json.msg,'','',function(){
                if(json.r==1){
                    window.location.href=window.location.href;
                }
            });
        },'json');
    });

    $('#export').on('click',function(){
        window.open('export/movie_edit.php'); 
    });
    $('#template').on('click',function(){
        window.open('excel_template.xlsx');
    });
   

    $('a[id^="edit_"]').on('click',function(){

        $.get('ajax/program_get.php',{program_id:$(this).attr('id').split('_')[1]},function(json){
           console.log(json);
           set_info(json);
        },'json');
    });
    $('a[id^="delete"]').on('click',function(){
        $.post('ajax/program_delete_log.php',{program_id:$(this).attr('id').split('_')[1]},function(json){
            __BDP.alertBox('提示',json.msg,'','',function(){
                if(json.r==1){window.location.href=window.location.href;}
            }); 
        },'json');
    });
    function set_info(data){  
        $('input[name="program_name"]').val(data.program_name);
        // $('input[name="program_default_name"]').val(data.program_default_name);
        $('select[name="type"]').find('option[value="'+data.type+'"]').prop('selected',true);
        if(data.play_time=='时间待定'){
            $('select[name="play_time_year"]').find('option[value="时间待定"]').prop('selected',true);
            $('select[name="play_time_month"]').find('option[value=""]').prop('selected',true);
        }else{
            var temp=data.play_time.split('年');
            $('select[name="play_time_year"]').find('option[value="'+temp[0]+'"]').prop('selected',true);
            $('select[name="play_time_month"]').find('option[value="'+temp[1]+'"]').prop('selected',true);
        }
        if(data.start_time=="时间待定"){
            $('input[name="start_time"]').val('');
        }else{
            $('input[name="start_time"]').val(data.start_time);
        }
        $('select[name="copyright"]').find('option[value="'+data.copyright+'"]').prop('selected',true);
        $('select[name="start_type"]').find('option[value="'+data.start_type+'"]').prop('selected',true);
        $('input[name="satellite"]').val(data.satellite);
        $('input[name="creator"]').val(data.creator);
        $('input[name="content_type"]').val(data.content_type);
        $('input[name="team"]').val(data.team);
        $('#intro').text(data.intro); 
        $('input[name="play1"]').val(data.play1);
        // $('input[name="play2"]').val(data.play2);
        $('input[name="play3"]').val(data.play3);
        // $('input[name="play4"]').val(data.play4);
    }

    $('#media').on('change',function(){
        console.log('12')
        window.location.href='movie_edit.php?mid='+$(this).val();
    })
</script>

</body>
</html>
