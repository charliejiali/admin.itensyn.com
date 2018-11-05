<?php
$pageTitle = "编辑";
$pageNavId = 8;
$pageNavSub = 83;

include("function.php");
$act=isset($_GET["act"])&&trim($_GET["act"])==="edit"?"edit":"add";
if($act==="edit"){
    include("include/Crawler.class.php");
    $name=$_GET["name"];
    $old=Crawler::get_tieba($name);
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
                                <td>名称</td>
                                <td><input name="name" type="text" placeholder="" class="input-form" value="<?php echo $old["name"];?>"></td>
                            </tr>
                            <tr>
                                <td class="color-red"></td>
                                <td>关注度（万）</td>
                                <td><input name="follow" type="text" placeholder="" class="input-form" value="<?php echo $old["follow"];?>"></td>
                            </tr>
                            <tr>
                                <td class="color-red"></td>
                                <td>发帖量（万）</td>
                                <td><input name="post" type="text" placeholder="" class="input-form" value="<?php echo $old["post"];?>"></td>
                            </tr>
                            <tr>
                                <td class="color-red"></td>
                                <td>原著贴吧关注度与发帖量之比</td>
                                <td><input name="per" type="text" placeholder="" class="input-form" value="<?php echo $old["per"];?>"></td>
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
  

    $('#back').on('click',function(){
        window.location.href="crawler_tieba.php";
    });
    $('#submit').on('click',function(){
        var input={}
        input['act']=act;
       
        $('input[name]').each(function(){
            input[$(this).attr('name')]=$(this).val();
        });
        console.log(input)
        $.post('ajax/crawler_tieba_edit.php',input,function(json){
            __BDP.alertBox("提示",json.msg,"","",function(){
                if(json.r==1){window.location.href='crawler_tieba.php';}
            })
        },'json');
    });
</script>
</body>
</html>


