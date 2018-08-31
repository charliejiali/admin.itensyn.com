<?php
$pageTitle = "新建";
$pageNavId = 8;
$pageNavSub = 81;

include("function.php");
include_once("include/Crawler.class.php");

$act=trim($_GET["act"]);
$id=trim($_GET["id"]);

if($act==="edit"){
    $act_text="编辑";
    $old=Crawler::get_url_by_id($id);
    if(!$old){
        die("未能获取数据");
    }
}else if($act==="add"){
    $act_text="新建";
}else{
    die("can't get act or id");
}

$category=Crawler::get_category_list();
$weight=Crawler::get_weight_list();
$platforms=Crawler::get_media_platforms();
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
                            <td>剧 目 原 名</td>
                            <td><input name="program_default_name" type="text" placeholder="" class="input-form" value="<?php echo $old["program_default_name"];?>"></td>
                        </tr>
                        <tr>
                            <td class="color-red">*</td>
                            <td>媒 体 平 台</td>
                            <td>
                                <select id="platform" class="input-form">
                                    <option value="">请选择</option>
                                    <?php foreach($platforms as $p){ ?>
                                        <option value="<?php echo $p["platform"];?>" <?php if($p["platform"]==$old["platform_name"]){echo "selected";}?> ><?php echo $p["platform"];?></option>
                                    <?php } ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td class="color-red">*</td>
                            <td>二 级 权 重</td>
                            <td>
                                <select id="weight" class="input-form">
                                    <option value=""></option>
                                    <?php foreach($weight as $w){ ?>
                                        <option value="<?php echo $w["weight_id"];?>" <?php if($w["weight_id"]==$old["weight_id"]){echo "selected";}?> ><?php echo $w["name"];?></option>
                                    <?php } ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td class="color-red">*</td>
                            <td>爬 虫 地 址</td>
                            <td><input name="url" type="text" placeholder="" class="input-form" value="<?php echo $old["url"];?>"></td>
                            <td><button id="create" type="button" class="pure-btn btn-large btn-red">生 成</button></td>
                        </tr>
                        <tr>
                            <td class="color-red">*</td>
                            <td>时 间 间 隔</td>
                            <td><input name="interval" type="text" placeholder="" class="input-form" value="<?php echo $old["interval"];?>"></td>
                        </tr>
                        <tr>
                            <td class="color-red">*</td>
                            <td>备 注</td>
                            <td><input name="content" type="text" placeholder="" class="input-form" value="<?php echo $old["content"];?>"></td>
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
    $('#submit').on('click',function(){
        var input={};

        $('input[name]').each(function(){
            input[$(this).attr('name')]=$(this).val();
        });


        input['platform']=$('#platform option:selected').val();
        input['weight']=$('#weight option:selected').val();
        input['act']='<?php echo $act;?>';
        input['id']='<?php echo $id;?>';

        $.post('ajax/crawler_url_edit.php',input,function(json){
            __BDP.alertBox("提示",json.msg,"","",function(){
                if(json.r==1){window.location.href='crawler_url.php';}
            })
        },'json')
    });
    $('#back').on('click',function(){
        window.location.href="crawler_url.php";
    });
    $('#create').on('click',function(){
        $.get('ajax/crawler_make_url.php',{
            weight_id:$('#weight option:selected').val(),
            program_default_name:$('input[name="program_default_name"]').val(),
            platform:$('#platform option:selected').val()
        },function(json){
            if(json.r==1){
                $('input[name="url"]').val(json.url)
            }
        },'json');
    })
</script>
</body>
    </html><?php


