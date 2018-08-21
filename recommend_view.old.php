<?php
$pageTitle = "推荐内容";
$pageNavId = 6;
$pageNavSub = 61;
?>
<!DOCTYPE html>
<html>
<head>
    <?php include_once("module/head_tag.php"); ?>

    <script type="text/javascript" src="js/libs/jquery.mCustomScrollbar.concat.min.js"></script>
    <script src="./js/pages/recommend.js" type="text/javascript"></script>

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
            <!--div class="pull-right">
                <button type="submit" class="pure-btn btn-large btn-red">+ 录入数据</button>
            </div-->
            <h3 class="title">推荐内容</h3>

            <div class="form-content" style="max-height: 500px; overflow: hidden">
                <div>
                    <h3 class="text-center">2017年10月20日推荐资源</h3>
                    <?php for ($i = 1; $i <= 5; $i++) { ?>
                        <div class="recommend-item">
                            <h4 class="title">新秀自制剧</h4>
                            <p>腾讯视频</p>
                            <table>
                                <tr>
                                    <td> 剧目名称：《独步天下》
                                        开播时间：2017年Q4<br>
                                        系统总评得分：2.8<br>
                                        推荐等级：XXXX<br>
                                        各维度得分：播放得分：2.6；渠道得分3；平台得分3；制作得分3；资源得分2；关注得分3；IP价值得分3；明星得分3；话题得分3
                                    </td>
                                </tr>
                            </table>
                        </div>

                    <?php } ?>
                </div>

            </div>


            <div style="padding: 1em;" class="text-center">
                <br>
                <button type="submit" class="pure-btn btn-large btn-red">下 载</button>
                <br>
            </div>

        </div>
        <!-- page01 End -->

        <?php include_once("module/footer.php"); ?>
    </div>

</div>


</body>
</html>