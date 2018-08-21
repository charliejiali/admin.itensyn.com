<?php
$pageTitle = "新建用户";
$pageNavId = 4;
$pageNavSub = 42;

include("function.php");
include_once("include/User.class.php");
include("include/Notice.class.php");

$notice_id=$_GET["id"];

$user_list=User::get_user(array("type"=>0));
$notice=Notice::get_info($notice_id);
$notice_user=Notice::get_user($notice_id);
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
            <h3 class="title">新建推送内容</h3>

            <div class="form-user-add">
                <form class="pure-form">
                    <table class="pure-table pure-table-none" style="width: 80%">
                        <tbody>
                        <tr>
                            <td class="color-red">*</td>
                            <td width="100">推送用户</td>
                            <td>
                                <!--textarea name="msg-user" id="" cols="64" rows="3" class="input-form"></textarea-->
                                
                                <div class="pure-g">
                                <?php foreach($user_list as $u){ ?>
                                <div class="pure-u-1-4">
                                    <label class="pure-checkbox">
                                        <input <?php if(in_array($u["user_id"],$notice_user)){echo "checked";}?> name="option-one" type="checkbox" value="<?php echo $u["user_id"];?>"> <?php echo $u["email"];?>
                                    </label>
                                </div>
                                <?php } ?>
                                	
								</div>
                            </td>
                        </tr>
                        <tr>
                            <td class="color-red">*</td>
                            <td>标 题</td>
                            <td><input value="<?php echo $notice["title"];?>" id="title" name="msg-title" type="text" placeholder="" class="input-form"></td>
                        </tr>
                        <tr>
                            <td class="color-red">*</td>
                            <td>内 容</td>
                            <td>
                                <textarea id="content" name="msg-content" id="" cols="64" rows="10" class="input-form"><?php echo $notice["content"];?></textarea>
                        </tr>

                        <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>
                                <div style="padding: 1em;">
                                    <br>
                                    <button id="back" type="button" class="pure-btn btn-large btn-red">返 回</button>
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
    $('#back').on('click',function(){
        history.go(-1);
    });
</script>

</body>
</html>