<?php
$pageTitle = "剧目详情";
$pageNavId = 2;
$pageNavSub = 23;

include("function.php");
$db=db_connect();

$program_id=$db->escape($_GET["id"]);

$sql=" select * from media_program where program_id='{$program_id}' limit 1 ";
$program=$db->get_row($sql,ARRAY_A);
$start_type=$program["start_type"];

$start_types=array("待播出","播出中","已播完");

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
                <a href="movie_list.php" class="pure-btn btn-large btn-red">返回</a>
            </div>
            <h3 class="title">剧目详情: <?php echo $program["program_name"];?></h3>
            <br class="clear">
            <div class="tab-group">
                <div class="tab-menus">
                    <a href="#" class="tab-menu active"><span class="arrow-tag"></span>基本信息 </a>
                    <!-- <a href="#" class="tab-menu"><span class="arrow-tag"></span>播放量</a>
                    <a href="#" class="tab-menu"><span class="arrow-tag"></span>资源文件</a> -->
                </div>
                <div class="tab-item active" id="tab-iten-01">

                    <table class="pure-table pure-table-none" style="width: 80%">
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
                            <td><input value="<?php echo $program["program_name"];?>" name="movie-name" type="text" placeholder="" class="input-form"></td>
                        </tr>
                        <tr>
                            <td class="color-red">*</td>
                            <td>剧目原名</td>
                            <td><input value="<?php echo $program["program_default_name"];?>" name="movie-name-real" type="text" placeholder="" class="input-form"></td>
                        </tr>
                        <tr>
                            <td class="color-red">*</td>
                            <td>资源类型</td>
                            <td>
                                <input value="<?php echo $program["type"];?>" name="movie-name-real" type="text" placeholder="" class="input-form">
                            </td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td>播出时间</td>
                            <td><input value="<?php echo $program["play_time"];?>" name="movie-name-real" type="text" placeholder="" class="input-form"></td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td>媒体平台</td>
                            <td><input value="<?php echo $program["platform"];?>" name="movie-name-real" type="text" placeholder="" class="input-form">
                            </td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td>版权情况</td>
                            <td><input value="<?php echo $program["copyright"];?>" name="movie-name-real" type="text" placeholder="" class="input-form"></td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td>播出状态</td>
                            <td>
                                <select id="start_type">
                                <?php foreach($start_types as $st){ ?>
                                <option value="<?php echo $st;?>" <?php if($st==$start_type){echo "selected";}?> ><?php echo $st;?></option>
                                <?php } ?>
                                </select>
                                <!-- <input value="<?php echo $program["start_type"];?>" name="movie-name-real" type="text" placeholder="" class="input-form"> -->
                            </td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td>播出卫视</td>
                            <td><input value="<?php echo $program["satellite"];?>" name="movie-name-real" type="text" placeholder="" class="input-form"></td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td>主创/嘉宾</td>
                            <td><input value="<?php echo $program["creator"];?>" name="movie-name-real" type="text" placeholder="" class="input-form"></td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td>内容类型</td>
                            <td><input value="<?php echo $program["content_type"];?>" name="movie-name-real" type="text" placeholder="" class="input-form"></td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td>制作团队</td>
                            <td><input value="<?php echo $program["team"];?>" name="movie-name-real" type="text" placeholder="" class="input-form"></td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td>简　　介</td>
                            <td>
                                <textarea name="intro" id="" cols="30" rows="10" class="input-form"><?php echo $program["intro"];?></textarea>
                            </td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>
                                <div style="padding: 1em;">
                                    <br>
                                    <button id="submit" type="button" class="pure-btn btn-large btn-red">确 定</button>
                                    <br>
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
    var program_id='<?php echo $program_id;?>';
    $('#submit').on('click',function(){
        $.post('ajax/tensyn_update_st.php',{
            program_id:program_id,
            start_type:$('#start_type option:selected').val()
        },function(json){
             __BDP.alertBox('提示',json.msg,'','',function(){
                 if(json.r=1){
                    window.location.href='tensyn_movie_list.php';
                }
            });
        },'json');
    });
</script>
</body>
</html>