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
    $old=Crawler::get_category_by_id($id);
    if(!$old){
        die("未能获取数据");
    }
}else if($act==="add"){
    $act_text="新建";
}else{
    die("can't get act or id");
}
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
                            <td>类 型 名 称</td>
                            <td><input name="type" type="text" placeholder="" class="input-form" value="<?php echo $old["type"];?>"></td>
                        </tr>
                        <tr>
                            <td class="color-red">*</td>
                            <td>URL</td>
                            <td><input name="url" type="text" placeholder="" class="input-form" value="<?php echo $old["url"];?>"></td>
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

        $('input[type="text"]').each(function(){
            input[$(this).attr('name')]=$(this).val();
        });
        input['act']='<?php echo $act;?>';
        input['id']='<?php echo $id;?>';

        $.post('ajax/crawler_category_edit.php',input,function(json){
            __BDP.alertBox("提示",json.msg,"","",function(){
                if(json.r==1){window.location.href='crawler_category.php';}
            })
        },'json')
    });
    $('#back').on('click',function(){
        window.location.href="crawler_category.php";
    });
</script>
</body>
    </html><?php

