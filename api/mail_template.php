<?php
ob_start();
$current_property_name="";
$index=1;
$preview_path="http://demo-arts.tensynad.com/demo/2016/yili/evaluation_test/details_preview.php?";
?>
<div style="width: 100%; font-size: 14px;  font-family: '微软雅黑', 'Microsoft YaHei', STHeiti, SimHei, Helvetica, Arial, Tahoma, sans-serif;">
    <table width="860" border="0" cellpadding="5" cellspacing="0" style="border: 1px solid #cccccc; margin: 20px auto; font-family: '微软雅黑', 'Microsoft YaHei', STHeiti, SimHei, Helvetica, Arial, Tahoma, sans-serif;">
        <tr>
            <td colspan="4" style="padding: 8px 16px; color: #fff; background: #5247bd no-repeat center right">
                <h3 style="margin: 10px 0;font-size: 24px; font-weight: 400; "><?php echo date("Y");?>年<?php echo date("m");?>月<?php echo date("d");?>日腾信推荐自制内容资源</h3>
                <p style=" color: #cccccc; font-size: 12px;margin: 10px 0;">本次推荐内容为猫头鹰系统内 <strong>B+</strong> 级内容，即总评得分<?php echo $mail["score"];?>以上内容。由于距离内容上线时间较远，部分数据维度数据缺失，因此部分2018年内容评估结果仅供参考</p>
            </td>
        </tr>

        <?php 
        foreach($programs as $p){ 
            $property_name=trim($p["property_name"]);
            if($property_name!==$current_property_name){
                $index=1;
        ?>
        <tr>
            <td colspan="4" style="padding: 8px 16px; background: #f3f4f9; color: #00b1f4; font-weight: 600">
                <?php echo $property_name; ?>
            </td>
        </tr>
        <?php
            }
        ?>
        <tr>
            <td colspan="4" style="padding: 3px 16px;"></td>
        </tr>
        <tr>
            <td width="280" style="padding: 8px 16px; font-weight: 600;">
                <?php echo $p["platform_name"]."《".$p["program_name"]."》";?>
            </td>
            <td>开播时间：<?php echo $p["start_play"];?></td>
            <td>系统总评得分：<strong style="color: #cc0000; font-size: 18px;"><?php echo $p["score"];?></strong> </td>
            <td>推荐等级：<?php echo $p["level"];?> </td>
        </tr>
        <tr>
            <td colspan="4" style="padding: 8px 16px;">
                <table border="0" cellpadding="0" cellspacing="0" style="width: 100%">
                    <tr>
                        <td style="color: #888; line-height: 160%;">
                            <span style="background: #e6e6e6; padding:1px 4px; border-radius: 4px; margin-left: 15px;">内容类型：</span> <strong><?php echo $p["type_name"];?></strong>
                            <span style="background: #e6e6e6; padding:1px 4px; border-radius: 4px; margin-left: 15px;">集数/期数：</span> <strong><?php echo $p["episode"];?></strong>
                            <span style="background: #e6e6e6; padding:1px 4px; border-radius: 4px; margin-left: 15px;">制作团队/导演：</span> <strong><?php echo $p["team"];?></strong>
                            <br><span style="background: #e6e6e6; padding:1px 4px; border-radius: 4px; margin-left: 15px;">男主演：</span> <strong><?php echo $p["male_leader"];?></strong>
                            <span style="background: #e6e6e6; padding:1px 4px; border-radius: 4px; margin-left: 15px;">女主演：</span> <strong><?php echo $p["female_leader"];?></strong>
                            <br><span style="background: #e6e6e6; padding:1px 4px; border-radius: 4px; margin-left: 15px;">预览：</span> <strong><a href="<?php echo $preview_path."name=".$p["program_name"]."&platform=".$p["platform_name"];?>">查看</a></strong>
                        </td>
                        <td style="padding-right: 8px;line-height: 1; float: right; font-size: 50px; color: #00b1f4; font-style: italic; font-family: Arial;"><?php echo str_pad($index,2,"0",STR_PAD_LEFT);?></td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td colspan="4" style="padding: 3px 16px; border-bottom: solid 1px #e6e6e6; color: #999"></td>
        </tr>
        <?php 
            $current_property_name=$property_name;
            $index++;
        }
        ?>           
        </tr>
    </table>
</div>
<?php 
$mail_body = ob_get_clean();
?>