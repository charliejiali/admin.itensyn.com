<?php
$pageTitle = "基本信息管理";
$pageNavId = 0;

include("function.php");
include_once("include/User.class.php");

$users=User::get_list(array("user_id"=>$_GET["id"]));
$user=$users[0];

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

            <h3 class="title">用户基本信息</h3>

            <br class="clear">

            <div class="table-box">
                <table class="pure-table pure-table-none table-info">
                    <tbody>
                    <tr>
                        <td class="head">联系人姓名</td>
                        <td><?php echo $user["contact"];?></td>
                    </tr>
                    <tr>
                        <td class="head">所属公司</td>
                        <td><?php echo $user["company_name"];?></td>
                    </tr>
                    <tr>
                        <td class="head">注册邮箱</td>
                        <td><?php echo $user["email"];?></td>
                    </tr>
                    <tr>
                        <td class="head">手机号码</td>
                        <td><?php echo $user["phone"];?></td>
                    </tr>
                    <tr>
                        <td class="head">联系地址</td>
                        <td><?php echo $user["address"];?></td>
                    </tr>
                   <!--  <tr>
                        <td class="head">固定电话</td>
                        <td>010-39485968</td>
                    </tr>
                    <tr>
                        <td class="head">QQ</td>
                        <td>12345678</td>
                    </tr> -->
                    </tbody>
                </table>

            </div>
            <br>
        </div>
        <!-- page01 End -->

        <?php include_once("module/footer.php"); ?>
    </div>

</div>


</body>
</html>