<?php
$pageTitle = "用户列表";
$pageNavId = 1;
$pageNavSub = 11;

include("function.php");
include_once("include/User.class.php");

$list=User::get_list($_GET);
$user_type=User::get_type();
$user_status=User::get_status();
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

            <div class="pull-right">
                <a href="user_add.php" class="pure-btn btn-large btn-red">新建用户</a>
            </div>

            <h3 class="title">用户列表</h3>

<!--            <br class="clear">-->
            <div class="form-eval">
                <div class="pure-g">
                    <div class="pure-u-1-3">
<!--                        <label for="name">时　　间</label>-->
                        <input value="<?php echo $_GET["start_date"];?>" id="start_date" type="text" placeholder="" class="input-label" style="width:120px;"> -
                        <input value="<?php echo $_GET["end_date"];?>" id="end_date" type="text" placeholder="" class="input-label" style="width:120px">
                    </div>
                    <div class="pure-u-1-6">
                        <select class="form-control" id="select_type" style="width: 120px;">
                            <option value="">账号类型</option>
                            <?php foreach($user_type as $k=>$v){ ?>
                            <option value="<?php echo $k;?>" <?php if($_GET["type"]==$k&&trim($_GET["type"])!==""){echo "selected";}?>><?php echo $v;?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="pure-u-1-6">
                        <select class="form-control" id="select_status" style="width: 120px;">
                            <option value="">账号状态</option>
                            <?php foreach($user_status as $k=>$v){ ?>
                            <option value="<?php echo $k;?>" <?php if($_GET["status"]==$k&&trim($_GET["status"])!==""){echo "selected";}?>><?php echo $v;?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="pure-u-1-3">
                        <input value="<?php echo $_GET["name"];?>" id="name" type="text" placeholder="公司名称" class="input-label" style="width: 170px;">
                        <button id="search" type="button" class="pure-btn btn-red" style="width:60px;padding-left: 1em; padding-right: 1em; margin-left: 1em ">查询</button>
                    </div>
                    <div class="clear"></div>
                </div>
            </div>
            <br class="clear">

            <div class="table-box">
                <div class="table-list">
                    <div id="table-data">
                        <table class="pure-table pure-table-line pure-table-striped pure-table-hover" style="width: 100%">
                            <thead>
                            <tr>
                                <th>账户邮箱</th>
                                <th>公司名称</th>
                                <th>联系人</th>
                                <th>联系电话</th>
                                <th>账户类型</th>
                                <th>创建时间</th>
                                <th>状态</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach($list as $l) { ?>
                                <tr>
                                    <td><a href="user_info.php?id=<?php echo $l["user_id"];?>"><?php echo $l["email"];?></a></td>
                                    <td><?php echo $l["company_name"];?></td>
                                    <td><?php echo $l["contact"];?></td>
                                    <td><?php echo $l["phone"];?></td>
                                    <td><?php echo $user_type[$l["type"]];?></td>
                                    <td><?php echo $l["create_time"];?></td>
                                    <td><?php echo $user_status[$l["status"]];?></td>
                                    <td>
                                    <?php if($l["status"]==1){ ?>
                                        <a id="<?php echo $l["user_id"];?>" name="no" href="javascript:;">冻结</a>
                                    <?php }else{ ?>
                                        <a id="<?php echo $l["user_id"];?>" name="yes" href="javascript:;">激活</a>
                                    <?php } ?> 
                                    </td>
                                </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
            <br>
        </div>
        <!-- page01 End -->

        <?php include_once("module/footer.php"); ?>
    </div>

</div>
<link rel="stylesheet" href="jquery-ui-1.12.1/jquery-ui.min.css">
<script src="jquery-ui-1.12.1/jquery-ui.min.js"></script>
<script type="text/javascript">
    var params={};
    $('#table-data a[name]').on('click',function(){
        var user_id=$(this).attr('id');
        var status=$(this).attr('name');

        $.post('ajax/user_change_status.php',{user_id:user_id,status:status},function(json){
            __BDP.alertBox("提示",json.msg,"","",function(){
                if(json.r==1){window.location.href='user_list.php';}
            });
        },'json');
    }); 
    $('#search').on('click',function(){
        params["start_date"]=$('#start_date').val();
        params["end_date"]=$('#end_date').val();
        params["type"]=$('#select_type option:selected').val();
        params["status"]=$('#select_status option:selected').val();
        params["name"]=$('#name').val();

        window.location.href='user_list.php?'+$.param(params);
    });
    $('#start_date').datepicker({
        dateFormat:'yy-mm-dd' 
    });
    $('#end_date').datepicker({
        dateFormat:'yy-mm-dd' 
    });
</script>
</body>
</html>