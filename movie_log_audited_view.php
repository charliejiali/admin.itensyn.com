<?php
$pageTitle = "已审核录入单";
$pageNavId = 2;
$pageNavSub = 22;

include("function.php");
include("include/Input.class.php");
include("include/Program.class.php");

$input_id=$_GET["id"];
$status=$_GET["status"];

$page=isset($_GET["p"])?intval($_GET["p"]):1;
$pagecount=isset($_GET["c"])?intval($_GET["c"]):10; //每页显示数量
$offset=($page-1)*$pagecount;

$input=Input::get_info($input_id);
$result=Input::get_programs($input_id,$offset,$pagecount,$status);
$list=$result["data"];
$list_count=$result["total_count"];
$page_count=$result["page_count"]; 

?>
<!DOCTYPE html>
<html>
<head>
    <?php include_once("module/head_tag.php"); ?>

    <script type="text/javascript" src="js/libs/jquery.mCustomScrollbar.concat.min.js"></script>
    <script src="./js/pages/movie_log_audited_view.js" type="text/javascript"></script>
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
                <a href="#" type="submit" class="pure-btn btn-red">返回</a>
            </div>
            <div class="pure-g">
                <div class="pure-u-1-6"><span class="label-tag">已审核</span></div>
                <div class="pure-u-2-3">
                    <table class="pure-table pure-table-none">
                        <tr>
                            <td>供应商：<?php echo $input["supplier"];?></td>
                            <td>提交日期：<?php echo $input["create_date"];?></td>
                            <td>备注信息：<?php echo $input["remark"];?></td>
                        </tr>
                        <tr>
                            <td>媒体录入单：<?php echo $input["name"];?></td>
                            <td>审核时间：<?php echo $input["update_date"];?></td>
                            <!-- <td>审核人：吴瑞明</td> -->
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <div class="content">
            <div class="pull-right">
                <a target="_blank"  href="export/movie_log_audited_view.php?id=<?php echo $input_id."&status=".$status;?>" type="submit" class="pure-btn">导出</a>
            </div>
            <div class="tab-group">
                <div class="tab-menus">
                    <a href="movie_log_audited_view.php?id=<?php echo $input_id;?>&status=2" class="tab-menu <?php if($status==2){echo "active";}?>"><span class="arrow-tag"></span>审核通过</a>
                    <a href="movie_log_audited_view.php?id=<?php echo $input_id;?>&status=-2" class="tab-menu <?php if($status==-2){echo "active";}?>"><span class="arrow-tag"></span>审核拒绝</a>
                </div>
                <div class="tab-item active" id="tab-iten-01" >
                    <div class="table-box">
                        <div class="table-list">
                            <div id="table-data">
                                <table class="pure-table pure-table-line pure-table-striped pure-table-hover" style="width: 2200px">
                                    <thead>
                                    <tr>
                                        <th class="td-head" style="width: 120px;">剧目名称</th>
                                        <th>剧目原名</th>
                                        <th>资源类型</th>
                                        <th>播出时间</th>
                                        <th>媒体平台</th>
                                        <th>开播时间</th>
                                        <th>版权情况</th>
                                        <th>播出状态</th>
                                        <th>播出卫视</th>
                                        <th>主创/嘉宾</th>
                                        <th>内容类型</th>
                                        <th>制作团队</th>
                                        <th width="500">简介</th>
                                        <th>本季预估播放量</th>
                                        <th>累计播放量</th>
                                        <th>集数/期数</th>
                                        <th>已播集数</th>
                                        <th>实际单集播放量</th>
                                        <th>本季预估单机播放量</th>
                                        <th>海报</th>
                                        <th>资源</th>
                                        <th>视频</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                     <?php 
                                        $upload_buttons=array("poster","resource","video");
                                        foreach($list as $l){ 
                                            $program_id=$l["program_id"];
                                            $program_default_name=$l["program_default_name"];
                                            $platform=$l["platform"];
                                            $attachs=Program::check_attach_log($program_id,$program_default_name,$platform);
                                            $program_status=$l["status"];
                                    ?> 
                                    <tr>
                                        <td class="td-head" id="<?php echo $l["program_id"];?>"><?php echo $l["program_name"];?></td>
                                        <td><?php echo $l["program_default_name"];?></td>
                                        <td><?php echo $l["type"];?></td>
                                        <td><?php echo $l["play_time"];?></td>
                                        <td><?php echo $l["platform"];?></td>
                                        <td><?php echo $l["start_time"];?></td>
                                        <td><?php echo $l["copyright"];?></td>
                                        <td><?php echo $l["start_type"];?></td>
                                        <td><?php echo $l["satellite"];?></td>
                                        <td><?php echo $l["creator"];?></td>
                                        <td><?php echo $l["content_type"];?></td>
                                        <td><?php echo $l["team"];?></td>
                                        <td><?php echo csubstr($l["intro"],0,80);?></td>
                                        <td><?php echo $l["play1"];?></td>
                                        <td><?php echo $l["play2"];?></td>
                                        <td><?php echo $l["play3"];?></td>
                                        <td><?php echo $l["play4"];?></td>
                                        <td><?php echo $l["play5"];?></td>
                                        <td><?php echo $l["play6"];?></td>
                                        
                                        <?php foreach($upload_buttons as $b){?>
                                        <td>
                                            <?php echo $attachs[$b];?>
                                        </td>
                                        <?php } ?>
                                    </tr>
                                    <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="table-head" id="table-head" style="width: 120px;">
                            <!-- <table class="pure-table pure-table-none pure-table-striped" style="width: 200px"></table>-->
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
                    </div>
                </div>
                <!--           Teb END     tab-item-01-->

            </div>

        </div>
        <!-- page01 End -->

        <?php include_once("module/footer.php"); ?>
    </div>

</div>
<script type="text/javascript">
    var page=parseInt('<?php echo $page;?>');
    var page_count='<?php echo $page_count;?>';
    
    var params={};
    params["p"]=parseInt(page);
    params["c"]='<?php echo $pagecount;?>';
    params["id"]='<?php echo $input_id;?>';
    params["status"]='<?php echo $status;?>';
    
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
        window.location.href='movie_log_audited_view.php?'+$.param(params);
    });
    $('#pageNum').on('keypress',function(e){
        var value=parseInt($(this).val());
        if(e.keyCode==13){
            if(value<=0||value>page_count||isNaN(value)){
                return false;
            }else{
                params["p"]=value; 
                window.location.href='movie_log_audited_view.php?'+$.param(params);
            }
        }
    }); 
    $('td[id]').on('click',function(){
        window.location.href='movie_info.php?id='+$(this).attr('id');
    });
</script>
</body>
</html>