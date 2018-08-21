<?php
$pageTitle = "新建用户";
$pageNavId = 1;
$pageNavSub = 12;

include("function.php");
include_once("include/User.class.php");

$user_type=User::get_type();
$user_status=User::get_status();
$user_platform=User::get_platform();

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
                                <h3 class="title">基本信息</h3></td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td class="color-red">*</td>
                            <td>公 司 名 称</td>
                            <td><input name="company_name" type="text" placeholder="" class="input-form"></td>
                        </tr>
                        <tr>
                            <td class="color-red">*</td>
                            <td>联　系　人</td>
                            <td><input name="contact" type="text" placeholder="" class="input-form"></td>
                        </tr>
                        <tr>
                            <td class="color-red">*</td>
                            <td>联 系 电 话</td>
                            <td><input name="phone" type="text" placeholder="" class="input-form"></td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td>公 司 地 址</td>
                            <td><input name="address" type="text" placeholder="" class="input-form"></td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td>平 台</td>
                            <td>
                                <select id="platform" class="input-form">
                                    <option value=""></option> 
                                <?php foreach($user_platform as $p){ ?>
                                    <option value="<?php echo $p;?>"><?php echo $p;?></option>  
                                <?php } ?>    
                                </select>
                            </td>
                        </tr>
                        </tbody>
                    </table>
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
                            <td>账 号 邮 箱</td>
                            <td><input name="email" type="text" placeholder="" class="input-form">
                                <!--                                <div class="txt-comment">填写内容为数字和小数点、小数点后保留一位。</div>-->
                            </td>
                        </tr>
                        <tr>
                            <td class="color-red">*</td>
                            <td>登 录 密 码</td>
                            <td><input name="password" type="password" placeholder="" class="input-form">
                                <!--                                <div class="txt-comment">填写内容为数字和小数点、小数点后保留一位。</div>-->
                            </td>
                        </tr>

                        <tr>
                            <td class="color-red">*</td>
                            <td>确 认 密 码</td>
                            <td><input name="confirm_password" type="password" placeholder="" class="input-form">
                            </td>
                        </tr>
                        <tr>
                            <td class="color-red">*</td>
                            <td>账 户 类 型</td>
                            <td>
                                <?php foreach($user_type as $k=>$v){ ?>
                                <div class="pure-u-1-4">
                                    <label for="option-1" class="pure-radio">
                                        <input type="radio" name="type" value="<?php echo $k;?>">
                                        <?php echo $v;?>
                                    </label>
                                </div>
                                <?php } ?>
                            </td>
                        </tr>

                        <tr>
                            <td class="color-red">*</td>
                            <td>账 户 状 态</td>
                            <td>
                            <?php foreach($user_status as $k=>$v){ ?>
                            <div class="pure-u-1-4">
                                <label for="option-11" class="pure-radio">
                                    <input type="radio" name="status" value="<?php echo $k;?>">
                                    <?php echo $v;?>
                                </label>
                            </div>
                            <?php } ?>
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
        var input={};

        $('input[type="text"]').each(function(){
            input[$(this).attr('name')]=$(this).val();
        });
        $('input[type="password"]').each(function(){
            input[$(this).attr('name')]=$(this).val();
        });

        input["type"]=$('input[name="type"]:checked').val();
        input["status"]=$('input[name="status"]:checked').val();
        input["platform"]=$('#platform :selected').val(); 

        // console.log(input);return false;

        $.post('ajax/user_register.php',{input},function(json){
            __BDP.alertBox("提示",json.msg,"","",function(){
                if(json.r==1){window.location.href='user_list.php';}
            })
        },'json')
    });
    $('#back').on('click',function(){
        window.location.href="user_list.php";
    });
</script>
</body>
</html>