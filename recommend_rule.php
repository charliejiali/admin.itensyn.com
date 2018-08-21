<?php
$pageTitle = "用户列表";
$pageNavId = 6;
$pageNavSub = 63;

include("function.php");
include("include/MailPush.class.php");

$config=MailPush::get_config();
?>
<!DOCTYPE html>
<html>
<head>
    <?php include_once("module/head_tag.php"); ?>
    <script src="./js/pages/user_list.js" type="text/javascript"></script>
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

            <h3 class="title">推荐规则设置</h3>

            <br class="clear">


            <div class="form-content">
                <form class="pure-form">

                    <div class="pure-g">
                        <div class="pure-u-2-5">
                            <h4 class="group-title">得分限制 </h4>
                            <label for="name">推荐的对象得分</label>
                            <select class="form-control" id="score" style="width:120px;">
                            <?php for($i=1;$i<3.6;$i=$i+0.1){ ?>
                            <option value="<?php echo $i;?>" <?php if(trim($config["score"])===trim($i)){echo "selected";}?> ><?php echo $i;?></option>
                            <?php } ?>
                            </select>
                            <!-- <input value="<?php echo $config["score"];?>" id="score" type="text" placeholder="" class="input-label" style="width:120px;"> -->
                        </div>
                        <div class="pure-u-1-5">
                            <h4 class="group-title">发送频率 </h4>
                            <input value="<?php echo $config["interval"];?>" id="interval" type="text" placeholder="" class="input-label" style="width:50px;"> 天
                        </div>
                        <div class="pure-u-2-5">
                            <h4 class="group-title">推送时间 </h4>
                            <select class="form-control" id="push_hour" style="width:50px;">
                            <?php for($i=0;$i<24;$i++){ ?>
                            <option value="<?php echo str_pad($i,2,"0",STR_PAD_LEFT);?>" <?php if(trim($config["push_hour"])===str_pad($i,2,"0",STR_PAD_LEFT)){echo "selected";}?> ><?php echo str_pad($i,2,"0",STR_PAD_LEFT);?></option>
                            <?php } ?> 
                            </select>时：
                            <select class="form-control" id="push_minute" style="width:50px;">
                            <?php for($i=0;$i<60;$i++){ ?>
                            <option value="<?php echo str_pad($i,2,"0",STR_PAD_LEFT)?>" <?php if(trim($config["push_minute"])===str_pad($i,2,"0",STR_PAD_LEFT)){echo "selected";}?> ><?php echo str_pad($i,2,"0",STR_PAD_LEFT)?></option>
                            <?php } ?>
                            </select>分
                            <!-- <input value="<?php echo $config["push_hour"];?>" id="push_hour" type="text" placeholder="" class="input-label" style="width:50px;"> 时：
                            <input value="<?php echo $config["push_minute"];?>" id="push_minute" type="text" placeholder="" class="input-label" style="width:50px;"> 分： -->
                            <!-- <input id="searchKey" type="text" placeholder="" class="input-label" style="width:50px;"> 秒 -->
                        </div>
                        <div class="pure-u-1">
                            <br><br>
                            <h4 class="group-title">推送起止日期 </h4>
                            <input value="<?php echo $config["start_date"];?>" id="start_date" type="text" placeholder="" class="input-label" style="width: 170px;" readonly> -
                            <input value="<?php echo $config["end_date"];?>" id="end_date" type="text" placeholder="" class="input-label" style="width: 170px;" readonly>
                        </div>

                        <div class="pure-u-1">
                            <div style="padding: 1em; text-align: center">
                                <br><br><br>
                                <button id="submit" type="button" class="pure-btn btn-large btn-red">保 存</button>
                                &nbsp; &nbsp; &nbsp; &nbsp;
                               <!--  <button id type="button" class="pure-btn btn-large btn-red">取 消</button>
                                <br><br><br> -->
                            </div>
                        </div>
                        <div class="clear"></div>
                    </div>


                </form>
                <br>
            </div>

            <br class="clear">
            <br>
        </div>
        <!-- page01 End -->

        <?php include_once("module/footer.php"); ?>
    </div>

</div>
<link rel="stylesheet" href="jquery-ui-1.12.1/jquery-ui.min.css">
<script src="jquery-ui-1.12.1/jquery-ui.min.js"></script>
<script type="text/javascript">
    $('#start_date').datepicker({
        dateFormat:'yy-mm-dd',
        minDate:0,
        onClose: function( selectedDate ) {
            $( "#end_date" ).datepicker( "option", "minDate", selectedDate );
        }
    });
    $('#end_date').datepicker({
        dateFormat:'yy-mm-dd',
        minDate:0,
        // onClose: function( selectedDate ) { 
        //     $( "#start_date" ).datepicker( "option", "maxDate", selectedDate );
        // }
    });
    $('#submit').on('click',function(){
        $.post('ajax/mail_set_config.php',{
            score:$('#score').val(),
            interval:$('#interval').val(),
            push_hour:$('#push_hour').val(),
            push_minute:$('#push_minute').val(),
            start_date:$('#start_date').val(),
            end_date:$('#end_date').val(),
        },function(json){
            __BDP.alertBox('提示',json.msg,'','',function(){
                 if(json.r==1){
                    window.location.href='recommend_rule.php';
                }
            });
        },'json');
    });
    
    $('#interval').on('input propertychange paste',function(){
        var value=$(this).val();
        if(!/^[0-9]+$/.test(value)){return false;}
        console.log(GetDateStr($('#start_date').val(),value),value);

    });
    function GetDateStr(date,AddDayCount) {
        var dd = new Date(date);
        dd.setDate(dd.getDate()+parseInt(AddDayCount));//获取AddDayCount天后的日期
        var y = dd.getFullYear();
        var m = dd.getMonth()+1;//获取当前月份的日期
        var d = dd.getDate();
        return y+"-"+m+"-"+d;
    }
</script>
</body>
</html>