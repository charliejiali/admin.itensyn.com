<?php
$pageTitle = "资源推送";
$pageNavId = 6;
//$pageNavSub = 23;

include("function.php");
include("include/MailPush.class.php");

$mail_id=$_GET["id"];

$mail_fail_users=MailPush::get_mail_fail_users($mail_id);
$user_list=MailPush::get_user_list(array("status"=>1));
$user_list=$user_list["data"];

$mail_info=MailPush::get_mail_info($mail_id);
$data=$mail_info["data"];
$platform=$mail_info["platform"];
$start_play=$mail_info["start_play"];
$type=$mail_info["type"];
$create_date=explode("-",$data["create_date"]);
$mail_title=$create_date[0]."年".$create_date[1]."月".$create_date[2]."日推荐资源";
// 2017年10月20日推荐资源
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
            <h3 class="title">推送用户</h3>

            <div class="pure-g">
                <?php foreach($user_list as $u){ ?>
                <div class="pure-u-1-4">
                    <label class="pure-checkbox">
                        <input name="option-one" type="checkbox" value="<?php echo $u["user_id"];?>" <?php echo !array_key_exists($u["user_id"],$mail_fail_users)?"checked":"";?> > <?php echo $u["name"];?>
                    </label>
                </div>
                <?php } ?>
            </div>

            <div class="form-eval">

                <div class="pure-g">

                    <div class="pure-u-1-3">
                        <label for="company">媒体平台</label>
                        <select class="form-control" id="platform">
                            <option value="">全部</option>
                            <?php foreach($platform as $p){ ?>
                            <option value="<?php echo $p;?>"><?php echo $p;?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="pure-u-1-3">
                        <label for="position">上线时间</label>
                        <select class="form-control" id="start_play">
                            <option value="">全部</option>
                            <?php foreach($start_play as $s){ ?>
                            <option value="<?php echo $s;?>"><?php echo $s;?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="pure-u-1-3">
                        <label for="email">资源类型</label>
                        <select class="form-control" id="type">
                            <option value="">全部</option>
                            <?php foreach($type as $t){ ?>
                            <option value="<?php echo $t;?>"><?php echo $t;?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="clear"></div>
                </div>
            </div>
        </div>

        <div class="content">
            <div class="form-content" style="max-height: 500px; overflow: hidden">
                <div id="program_list">
                    <h2 class="text-center" id="mail_title"><?php echo $mail_title;?></h2>

                    <!-- <?php for ($i = 1; $i <= 5; $i++) { ?>
                        <div class="recommend-item">
                            <h4 class="title">新秀自制剧</h4>
                            <p>腾讯视频</p>
                            <table>
                                <tr>
                                    <td class="option-left"><div class="input-checkbox"></div></td>
                                    <td> 剧目名称：《独步天下》
                                        开播时间：2017年Q4<br>
                                        系统总评得分：2.8<br>
                                        推荐等级：XXXX<br>
                                        各维度得分：播放得分：2.6；渠道得分3；平台得分3；制作得分3；资源得分2；关注得分3；IP价值得分3；明星得分3；话题得分3
                                    </td>
                                </tr>
                            </table>
                        </div>

                    <?php } ?> -->
                </div>

            </div>


            <div style="padding: 1em;" class="text-center">
                <br>
                <button id="submit" type="button" class="pure-btn btn-large btn-red">确 定</button>
                &nbsp; &nbsp; &nbsp; &nbsp;
                <button id="back" type="reset" class="pure-btn btn-large btn-red">取 消</button>
                <br>
            </div>

        </div>
        <!-- page01 End -->

        <?php include_once("module/footer.php"); ?>
    </div>

</div>
<script type="text/javascript">
    var active;
    var unselected_id=[];
    var mail_id='<?php echo $mail_id;?>';
    var current_property_name='';

    create_program_list();

    $('#platform').on('change',function(){
        create_program_list();
    });
    $('#start_play').on('change',function(){
        create_program_list();
    });
    $('#type').on('change',function(){
        create_program_list();
    });

    function create_program_list(){
        $.get('ajax/mail_get_mail_detail.php',{
            mail_id:mail_id,
            platform:$('#platform option:selected').val(),
            start_play:$('#start_play option:selected').val(),
            type:$('#type option:selected').val()
        },function(json){
            if(json.r==1){
                make_list(json.data);
            }
        },'json');
    }
    function make_list(programs){
        var property_html;
        $('#mail_title').nextAll().remove();
        for(var i in programs){
            var program=programs[i];
            var property_name=program.property_name;
            if(current_property_name!=property_name){
                property_html='<h4 class="title">'+property_name+'</h4>'
            }else{
                property_html='';
            }
            active=program.status==0||unselected_id.indexOf(program.program_id)!==-1?"":"active";
            $('#program_list').append( 
                '<div class="recommend-item">'
                    +property_html
                    +'<p>'+program.platform_name+'</p>'
                    +'<table>'
                        +'<tr>'
                            +'<td class="option-left"><div value="'+program.program_id+'" class="input-checkbox '+active+'"></div></td>'
                            +'<td> 剧目名称：《'+program.program_name+'》'
                                +'开播时间：'+program.start_play+'<br>'
                                +'系统总评得分：'+program.score+'<br>'
                                +'推荐等级：'+program.level+'<br>'
                                +'各维度得分：'+program.weights_score
                            +'</td>'
                        +'</tr>'
                    +'</table>'
                +'</div>'
            );
            current_property_name=property_name;
        } 
    }
    $("#program_list").on("click",".input-checkbox", function () {
        var value=$(this).attr('value');
        if ($(this).hasClass("active")) {
            $(this).removeClass("active");
            unselected_id.push(value);
        } else {
            $(this).addClass("active");
            array_delete(unselected_id,value);
        }
        console.log(unselected_id) 
    });
    function array_delete(array) {
        var what, a = arguments, L = a.length, ax;
        while (L > 1 && array.length) {
            what = a[--L];
            while ((ax= array.indexOf(what)) !== -1) {
                array.splice(ax, 1);
            }
        }
        return array;
    }
    $('#back').on('click',function(){
        window.location.href='recommend_list.php';
    });
    $('#submit').on('click',function(){
        $.post('ajax/mail_set_mail.php',{
            mail_id:mail_id,
            user_id:$('input[name="option-one"]:not(:checked)').map(function(){return this.value;}).get().join(','),
            program_id:unselected_id.join(',')
        },function(json){
            __BDP.alertBox('提示',json.msg,'','',function(){
                 if(json.r==1){
                    window.location.reload();
                }
            });
        },'json');
    })
</script>
</body>
</html>