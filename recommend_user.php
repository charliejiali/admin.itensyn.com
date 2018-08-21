<?php
$pageTitle = "用户列表";
$pageNavId = 6;
$pageNavSub = 62;

include("function.php");
include("include/MailPush.class.php");

$page=isset($_GET["p"])?intval($_GET["p"]):1;
$pagecount=isset($_GET["c"])?intval($_GET["c"]):10; //每页显示数量
$offset=($page-1)*$pagecount;

$result=MailPush::get_user_list(array(),$offset,$pagecount);
$list=$result["data"]; 
$list_count=$result["total_count"];
$page_count=$result["page_count"];
$user_status=MailPush::get_user_status();
?>
<!DOCTYPE html>
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

            <div class="pull-right">
                <a href="recommend_user_add.php" class="pure-btn btn-large btn-red">新建用户</a>
            </div>

            <h3 class="title">用户列表</h3>

            <br class="clear">

            <div class="table-box">
                <div class="table-list">
                    <div id="table-data">
                        <table class="pure-table pure-table-line pure-table-striped pure-table-hover" style="width: 100%">
                            <thead>
                            <tr>
                                <th>名称</th>
                                <th>邮箱</th>
                                <th>创建时间</th>
                                <th>状态</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach($list as $l){ ?>
                                <tr>
                                    <td><?php echo $l["name"];?></td>
                                    <td><a href="user_info.php"><?php echo $l["email"];?></a></td>
                                    <td><?php echo $l["create_date"];?></td>
                                    <td><?php echo $user_status[$l["status"]];?></td>
                                    <?php if($l["status"]==1){ ?>
                                    <td><a id="edit_<?php echo $l["user_id"];?>" href="javascript:;">编辑</a>  <a id="delete_<?php echo $l["user_id"];?>" href="javascript:;">删除</a></td>
                                    <?php }else{ ?>
                                    <td><a id="recover_<?php echo $l["user_id"];?>" href="javascript:;">恢复</a></td>
                                    <?php } ?>
                                </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
 

                <br>
            <div class="table-footer">
                <div class="page-control">
                    每页显示<?php echo $pagecount;?>条 &nbsp; &nbsp;
                    <a id="page_first" href="javascript:;" class="btn-page">首页</a>
                    <a id="page_pre" href="javascript:;" class="btn-page">上一页</a>
                    <a id="page_next" href="javascript:;" class="btn-page">下一页</a>
                    <a id="page_last" href="javascript:;" class="btn-page">尾页</a>
                    <input id="pageNum" type="text" value="<?php echo $page;?>" class="input-num" size="2">
                </div>
                记录共<?php echo $list_count;?>条，<?php echo $page_count;?>页
               <!--  <div class="input-checkbox-all"></div>
                全选 &nbsp; 记录共<?php echo $list_count;?>条 -->
            </div>

            </div>
            <br>
        </div>
        <!-- page01 End -->

        <?php include_once("module/footer.php"); ?>
    </div>

</div>
<script type="text/javascript">
    var page=parseInt('<?php echo $page;?>');
    var page_count='<?php echo $page_count;?>';
    var params={};
    params["p"]=page;
    params["c"]='<?php echo $pagecount;?>';
    
    $('a[id^="page"]').on('click',function(){
        var type=$(this).attr('id').split('_')[1];
        
        switch(type){
            case "first":
                params["p"]=1;
                break;
            case "pre":
                if(page-1<=0){return false;}
                params["p"]=page-1;
                break;
            case "next":
                if(page+1>page_count){return false;}
                params["p"]=page+1;
                break;
            case "last":
                params["p"]=page_count; 
                break; 
        }
        window.location.href='recommend_user.php?'+$.param(params);
    });
    $('#pageNum').on('keypress',function(e){
        var value=parseInt($(this).val());
        if(e.keyCode==13){
            if(value<=0||value>page_count||isNaN(value)){
                return false;
            }else{
                params["p"]=value; 
                window.location.href='recommend_user.php?'+$.param(params);
            }
        }
    }); 
    $('a[id^="edit_"]').on('click',function(){
        window.location.href='recommend_user_edit.php?id='+$(this).attr('id').split('_')[1];
    });
    $('a[id^="delete_"]').on('click',function(){
        $.post('ajax/mail_user_update_status.php',{
            user_id:$(this).attr('id').split('_')[1],
            status:0
        },function(json){
            __BDP.alertBox('提示',json.msg,'','',function(){
                 if(json.r==1){
                    window.location.href='recommend_user.php';
                }
            });
        },'json');
    });
    $('a[id^="recover_"]').on('click',function(){
        $.post('ajax/mail_user_update_status.php',{
            user_id:$(this).attr('id').split('_')[1],
            status:1
        },function(json){
            __BDP.alertBox('提示',json.msg,'','',function(){
                 if(json.r==1){
                    window.location.href='recommend_user.php';
                }
            });
        },'json');
    })

</script>

</body>
</html>