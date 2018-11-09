<?php
$pageTitle = "编辑";
$pageNavId = 8;
$pageNavSub = 81;

include("function.php");
include("include/Crawler.class.php");
$act=isset($_GET["act"])&&trim($_GET["act"])==="edit"?"edit":"add";
if($act==="edit"){
    $id=$_GET["id"];
    $old=Crawler::get_video($id);
    $ex=Crawler::get_program($old["ex_program_name"]);
    $staff=Crawler::get_staff($old["program_name"],$old["platform_name"]);
    $creator=$staff["creator"];
    $team=$staff["team"];
}
$play_status=Crawler::get_play_status();
$crawler_status=Crawler::get_crawler_status();

?>
    <html>
<head>
    <?php include_once("module/head_tag.php"); ?>
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
            <h3 class="title"><?php echo $act_text;?></h3>
            <div class="form-user-add">
                <form class="pure-form">
                    <table class="pure-table pure-table-none" style="width: 90%">
                        <tbody>
                            <tr>
                                <td>&nbsp;</td>
                                <td>
                                    <h3 class="title">基本信息</h3></td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td class="color-red">*</td>
                                <td>剧目名称</td>
                                <td><input <?php if($act==="edit"){echo "disabled";}?> name="program_name" type="text" placeholder="" class="input-form" value="<?php echo $old["program_name"];?>"></td>
                            </tr>
                            <tr>
                                <td class="color-red">*</td>
                                <td>播放状态</td>
                                <td>
                                    <select id="play_status">
                                    <?php foreach($play_status as $ps){ ?>
                                        <option <?php if($old["play_status"]==$ps){echo "selected";}?> value="<?php echo $ps;?>"><?php echo $ps;?></option>
                                    <?php } ?>
                                    </select>    
                                </td>
                            </tr>
                             <tr>
                                <td class="color-red">*</td>
                                <td>采集状态</td>
                                <td>
                                    <select id="crawler_status">
                                    <?php foreach($crawler_status as $cs){ ?>
                                        <option <?php if($old["crawler_status"]==$cs){echo "selected";}?> value="<?php echo $cs;?>"><?php echo $cs;?></option>
                                    <?php } ?>
                                    </select>    
                                </td>
                            </tr>
                            <tr>
                                <td class="color-red"></td>
                                <td>剧目URL</td>
                                <td><input name="url" type="text" placeholder="" class="input-form" value="<?php echo $old["url"];?>"></td>
                            </tr>
                            <tr>
                                <td class="color-red"></td>
                                <td>单集播放量（万）</td>
                                <td><input name="pv_avg" type="text" placeholder="" class="input-form" value="<?php echo $old["pv_avg"];?>"></td>
                            </tr>
                            <tr>
                                <td class="color-red"></td>
                                <td>预告片播放量（万）</td>
                                <td><input name="preview_pv_avg" type="text" placeholder="" class="input-form" value="<?php echo $old["preview_pv_avg"];?>"></td>
                            </tr>
                             <tr>
                                <td class="color-red"></td>
                                <td>上季剧目名称</td>
                                <td><input name="ex_program" type="text" placeholder="" class="input-form" value="<?php echo $old["ex_program_name"];?>"></td>
                            </tr>
                             <tr> 
                                <td class="color-red"></td>
                                <td>上季剧目URL</td>
                                <td><input name="ex_url" type="text" placeholder="" class="input-form" value="<?php echo $ex["url"];?>"></td>
                            </tr>
                             <tr>
                                <td class="color-red"></td>
                                <td>上季剧目单集播放量</td>
                                <td><input name="ex_pv" type="text" placeholder="" class="input-form" value="<?php echo $ex["pv_avg"];?>"></td>
                            </tr>
                            <tr>
                                <td class="color-red"></td>
                                <td>男主演</td>
                                <td><input name="male" type="text" placeholder="" class="input-form" value="<?php echo $old["male"];?>"></td>
                            </tr>
                            <?php if($creator!=""){ ?>
                            <tr>
                                <td></td>
                                <td></td>
                                <td class="color-red"><?php echo $creator;?></td>
                            </tr>
                            <?php } ?>
                            <tr>
                                <td class="color-red"></td>
                                <td>男主演代表作</td>
                                <td><input name="male_program" type="text" placeholder="" class="input-form" value="<?php echo $old["male_program"];?>"></td>
                            </tr>
                            <tr>
                                <td class="color-red"></td>
                                <td>男主演代表作URL</td>
                                <td><input name="male_url" type="text" placeholder="" class="input-form" value="<?php echo $old["male_url"];?>"></td>
                            </tr>
                            <tr>
                                <td class="color-red"></td>
                                <td>男主演代表作单集播放量（万）</td>
                                <td><input name="male_pv" type="text" placeholder="" class="input-form" value="<?php echo $old["male_pv"];?>"></td>
                            </tr>
                            <tr>
                                <td class="color-red"></td>
                                <td>女主演</td>
                                <td><input name="female" type="text" placeholder="" class="input-form" value="<?php echo $old["female"];?>"></td>
                            </tr>
                            <?php if($creator!=""){ ?>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td class="color-red"><?php echo $creator;?></td>
                                </tr>
                            <?php } ?>
                            <tr>
                                <td class="color-red"></td>
                                <td>女主演代表作</td>
                                <td><input name="female_program" type="text" placeholder="" class="input-form" value="<?php echo $old["female_program"];?>"></td>
                            </tr>
                            <tr>
                                <td class="color-red"></td>
                                <td>女主演代表作URL</td>
                                <td><input name="female_url" type="text" placeholder="" class="input-form" value="<?php echo $old["female_url"];?>"></td>
                            </tr>
                            <tr>
                                <td class="color-red"></td>
                                <td>女主演代表作单集播放量（万）</td>
                                <td><input name="female_pv" type="text" placeholder="" class="input-form" value="<?php echo $old["female_pv"];?>"></td>
                            </tr>
                            <tr>
                                <td class="color-red"></td>
                                <td>主持人</td>
                                <td><input name="host" type="text" placeholder="" class="input-form" value="<?php echo $old["host"];?>"></td>
                            </tr>
                            <?php if($creator!=""){ ?>
                            <tr>
                                <td></td>
                                <td></td>
                                <td class="color-red"><?php echo $creator;?></td>
                            </tr>
                            <?php } ?>
                            <tr>
                                <td class="color-red"></td>
                                <td>主持人代表作</td>
                                <td><input name="host_program" type="text" placeholder="" class="input-form" value="<?php echo $old["host_program"];?>"></td>
                            </tr>
                            <tr>
                                <td class="color-red"></td>
                                <td>主持人代表作URL</td>
                                <td><input name="host_url" type="text" placeholder="" class="input-form" value="<?php echo $old["host_url"];?>"></td>
                            </tr>
                            <tr>
                                <td class="color-red"></td>
                                <td>主持人代表作单集播放量（万）</td>
                                <td><input name="host_pv" type="text" placeholder="" class="input-form" value="<?php echo $old["host_pv"];?>"></td>
                            </tr>
                            <tr>
                                <td class="color-red"></td>
                                <td>制作团队</td>
                                <td><input name="team" type="text" placeholder="" class="input-form" value="<?php echo $old["team"];?>"></td>
                            </tr>
                            <?php if($team!=""){ ?>
                            <tr>
                                <td></td>
                                <td></td>
                                <td class="color-red"><?php echo $team;?></td>
                            </tr>
                            <?php } ?>
                            <tr>
                                <td class="color-red"></td>
                                <td>制作团队代表作</td>
                                <td><input name="team_program" type="text" placeholder="" class="input-form" value="<?php echo $old["team_program"];?>"></td>
                            </tr>
                            <tr>
                                <td class="color-red"></td>
                                <td>制作团队代表作URL</td>
                                <td><input name="team_url" type="text" placeholder="" class="input-form" value="<?php echo $old["team_url"];?>"></td>
                            </tr>
                            <tr>
                                <td class="color-red"></td>
                                <td>制作团队代表作单集播放量（万）</td>
                                <td><input name="team_pv" type="text" placeholder="" class="input-form" value="<?php echo $old["team_pv"];?>"></td>
                            </tr>
                            <tr>
                                <td class="color-red"></td>
                                <td>常驻嘉宾</td>
                                <td><input name="guest" type="text" placeholder="" class="input-form" value="<?php echo $old["guest"];?>"></td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>
                                    <div style="padding: 1em;">
                                        <br>
                                        <button id="submit" type="button" class="pure-btn btn-large btn-red">保 存</button>
                                        &nbsp; &nbsp; &nbsp; &nbsp;
                                        <button id="back" type="button" class="pure-btn btn-large btn-red">取 消</button>
                                        <br>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </form>
                <br>
            </div>
            <!--           Teb END     tab-item-01-->
        </div>
        <!-- page01 End -->
        <?php include_once("module/footer.php"); ?>
    </div>

</div>
<script type="text/javascript">
    var act='<?php echo $act;?>';
    var id='<?php echo $id;?>';

    $('#back').on('click',function(){
        window.close();
    }); 
    $('#submit').on('click',function(){
        var input={}
        input['act']=act;
        input['id']=id;
        $('input[name]').each(function(){
            input[$(this).attr('name')]=$(this).val();
        });
        input["play_status"]=$('#play_status option:selected').val();
        input["crawler_status"]=$('#crawler_status option:selected').val();
        console.log(input)
        $.post('ajax/crawler_video_edit.php',input,function(json){
            __BDP.alertBox("提示",json.msg,"","",function(){
                if(json.r==1){window.close();}
            })
        },'json');
    });

    $('[name="program_name"]').on('blur',function(){
        $.get('ajax/crawler_video_check.php',{
            program_name:$('[name="program_name"]').val()
        },function(json){
            if(json.r==0){
               __BDP.alertBox("提示",json.msg); 
            }
        },'json'); 
    });
    $('[name="male"],[name="female"],[name="host"],[name="team"]').on('blur',function(){
        var i=$(this).attr('name');
        $.get('ajax/crawler_video_get_masterpiece.php',{
            name:$(this).val(),
            identity:i
        },function(json){
            $('[name="'+i+'_program"]').val(json.program_name)
            $('[name="'+i+'_url"]').val(json.url)
            $('[name="'+i+'_pv"]').val(json.pv_avg)
        },'json')
    });
    $('[name="male_program"],[name="female_program"],[name="host_program"],[name="team_program"],[name="ex_program"]').on('blur',function(){
        var i=$(this).attr('name').split('_')[0];
        $.get('ajax/crawler_video_get_program.php',{
            program_name:$('[name="'+i+'_program"]').val()
        },function(json){
            $('[name="'+i+'_url"]').val(json.url)
            $('[name="'+i+'_pv"]').val(json.pv_avg)
        },'json')
    })
</script>
</body>
    </html><?php


