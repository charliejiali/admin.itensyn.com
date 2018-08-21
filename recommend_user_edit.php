<?php
$pageTitle = "新建用户";
$pageNavId = 6;
$pageNavSub = 62;

include("function.php");
include("include/MailPush.class.php");

$user_id=$_GET["id"];
$user=MailPush::get_user_info($user_id);
?>
<!DOCTYPE html>
<html>
<head>
    <?php include_once("module/head_tag.php"); ?>

    <!--    <script src="./js/pages/user_add.js" type="text/javascript"></script>-->
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
            <h3 class="title">新建用户</h3>

            <div class="form-user-add">
                <form class="pure-form">

                    <table class="pure-table pure-table-none" style="width: 80%">
                        <tbody>
                        <tr>
                            <td>&nbsp;</td>
                            <td>
                                <h3 class="title">账号信息</h3></td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td class="color-red">*</td>
                            <td>用户名称</td>
                            <td><input value="<?php echo $user["name"];?>" id="name" name="user-name" type="text" placeholder="" class="input-form">
                            </td>
                        </tr>

                        <tr>
                            <td class="color-red">*</td>
                            <td>用户邮箱</td>
                            <td><input value="<?php echo $user["email"];?>" id="email" name="user-email" type="text" placeholder="" class="input-form">
                            </td>
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
        $.post('ajax/mail_user_edit.php',{
            user_id:'<?php echo $user_id;?>',
            email:$('#email').val(),
            name:$('#name').val()
        },function(json){
            __BDP.alertBox('提示',json.msg,'','',function(){
                 if(json.r==1){
                    window.location.href='recommend_user.php';
                }
            });
        },'json');
    });
    $('#back').on('click',function(){
        window.location.href='recommend_user.php';
    })
</script>

</body>
</html>