<?php
$pageTitle = "新建";
$pageNavId = 8;
$pageNavSub = 83;

include("function.php");
include_once("include/Crawler.class.php");

$act=trim($_GET["act"]);
$id=trim($_GET["id"]);

if($act==="edit"){
    $act_text="编辑";
    $old=Crawler::get_weight_by_id($id);
    if(!$old){
        die("未能获取数据");
    }
}else if($act==="add"){
    $act_text="新建";
}else{
    die("can't get act or id");
}

$category=Crawler::get_category_list();
$weight=Crawler::get_system_weight_list()
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
                            <td>类 别 名 称</td>
                            <td>
                                <select id="category" class="input-form">
                                    <option value=""></option>
                                    <?php foreach($category as $c){ ?>
                                        <option value="<?php echo $c["category_id"];?>" <?php if($c["category_id"]==$old["category_id"]){echo "selected";}?> ><?php echo $c["name"];?></option>
                                    <?php } ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td class="color-red">*</td>
                            <td>二 级 权 重</td>
                            <td><input id="weight" type="text" placeholder="" class="input-form" value="<?php echo $old["name"];?>"></td>
                        </tr>
                        <tr>
                            <td class="color-red">*</td>
                            <td>备 注</td>
                            <td><input id="content" type="text" placeholder="" class="input-form" value="<?php echo $old["content"];?>"></td>
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

        input['category']=$('#category option:selected').val();
        input['weight']=$('#weight').val();
        input['content']=$('#content').val();
        input['act']='<?php echo $act;?>';
        input['id']='<?php echo $id;?>';

        $.post('ajax/crawler_weight_edit.php',input,function(json){
            __BDP.alertBox("提示",json.msg,"","",function(){
                if(json.r==1){window.location.href='crawler_weight.php';}
            })
        },'json')
    });
    $('#back').on('click',function(){
        window.location.href="crawler_weight.php";
    });
</script>
</body>
    </html><?php


