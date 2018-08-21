<?php
$pageTitle = "剧目详情";
$pageNavId = 2;
$pageNavSub = 23;

include("function.php");
$db=db_connect();

$program_id=$db->escape($_GET["id"]);

$sql=" select * from media_program_log where program_id='{$program_id}' limit 1 ";
$program=$db->get_row($sql,ARRAY_A);

$sql=" select * from media_attach where program_id='{$program_id}' ";
$attachs_data=$db->get_results($sql,ARRAY_A);
$attachs=array();
if($attachs_data){
    foreach($attachs_data as $a){
        $attachs[$a["type"]]=$a["url"];
    }
}
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
                    <a href="#" class="tab-menu"><span class="arrow-tag"></span>播放量</a>
                    <a href="#" class="tab-menu"><span class="arrow-tag"></span>资源文件</a>
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
                            <td><input value="<?php echo $program["start_type"];?>" name="movie-name-real" type="text" placeholder="" class="input-form"></td>
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
                        </tbody>
                    </table>

                    <br>
                </div>
                <!--           Teb END     tab-item-01-->
                <div class="tab-item" id="tab-iten-02" style="min-height: 500px;">
                    <table class="pure-table pure-table-none" style="width: 80%">
                        <tbody>
                        <tr>
                            <td>&nbsp;</td>
                            <td>
                                <h3 class="title">播放量</h3></td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td class="color-red">*</td>
                            <td>本季预估播放量</td>
                            <td><input value="<?php echo $program["play1"];?>" name="movie-name" type="text" placeholder="" class="input-form">
                                <div class="txt-comment">填写内容为数字和小数点、小数点后保留一位。</div>
                            </td>
                        </tr>
                        <tr>
                            <td class="color-red">*</td>
                            <td>累计播放量</td>
                            <td><input value="<?php echo $program["play2"];?>" name="movie-name-real" type="text" placeholder="" class="input-form">
                                <div class="txt-comment">填写内容为数字和小数点、小数点后保留一位。</div>
                            </td>
                        </tr>

                        <tr>
                            <td class="color-red">*</td>
                            <td>集数/期数</td>
                            <td><input value="<?php echo $program["play3"];?>" name="movie-name" type="text" placeholder="" class="input-form">
                                <div class="txt-comment">填写正整数且不得大于150</div>
                            </td>
                        </tr>
                        <tr>
                            <td class="color-red">*</td>
                            <td>已播集数</td>
                            <td><input value="<?php echo $program["play4"];?>" name="movie-name-real" type="text" placeholder="" class="input-form">
                                <div class="txt-comment">正整数。“播出状态”维度是“待播出”的不可填写</div>
                            </td>
                        </tr>

                        <tr>
                            <td class="color-red">*</td>
                            <td>实际单集播放量</td>
                            <td><input value="<?php echo $program["play5"];?>" name="movie-name" type="text" placeholder="" class="input-form">
                            </td>
                        </tr>
                        <tr>
                            <td class="color-red">*</td>
                            <td>本季预估单集播放量</td>
                            <td><input value="<?php echo $program["play6"];?>" name="movie-name-real" type="text" placeholder="" class="input-form"></td>
                        </tr>
                        </tbody>
                    </table>
                    <br>
                </div>
                <!--           Teb END     tab-item-02-->

                <div class="tab-item" id="tab-iten-03" style="min-height: 500px;">
                    <table class="pure-table pure-table-none" style="width: 80%">
                        <tbody>
                        <tr>
                            <td>&nbsp;</td>
                            <td>
                                <h3 class="title">资源文件</h3></td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td colspan="2">
                                <table class="pure-table pure-table-none">
                                    <tbody>
                                    <tr>
                                        <td>海报图片</td>
                                        <td> 
                                            <a <?php if(array_key_exists("poster",$attachs)){echo "href='../evaluation_media/".$attachs["poster"]."'";} ?> type="button" class="pure-btn btn-large">下载图片</a>
                                        </td>
                                        <td>招商资源包</td>
                                        <td>
                                            <button type="button" class="pure-btn btn-large">下载资源包</button>
                                        </td>
                                        <td>视频片段</td>
                                        <td>
                                            <button type="button" class="pure-btn btn-large">下载视频</button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td><img src="<?php echo array_key_exists("poster",$attachs)?"../evaluation_media/".$attachs["poster"]:"";?>" class="img-thumb"></td>
                                        <td>&nbsp;</td>
                                        <td><img src="images/ico_upfile_type.png"></td>
                                        <td>&nbsp;</td>
                                        <td><img src="images/ico_upfile_movie.png"></td>
                                    </tr>
                                    </tbody>
                                </table>

                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <br>
                </div>
                <!--           Teb END     tab-item-03-->

            </div>

        </div>
        <!-- page01 End -->

        <?php include_once("module/footer.php"); ?>
    </div>

</div>
<script type="text/javascript">
$(".tab-group").on("click", ".tab-menu", function (e) {
    e.preventDefault();
    var $tabGroup = $(this).parents(".tab-group");
    var tabActiveID = $(this).index();
    console.log(tabActiveID);
    $(this).siblings().removeClass("active");
    $(this).addClass("active");
    $tabGroup.find(".tab-item").removeClass("active");
    $tabGroup.find(".tab-item").eq(tabActiveID).addClass("active");
});
</script>
</body>
</html>