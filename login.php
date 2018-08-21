<?php
$pageTitle = "";
$pageNavId = 0;
?>
<!DOCTYPE html>
<html>
<head>
    <?php include_once("module/head_tag.php"); ?>
</head>
<body>
<!-- Header Start -->

<div class="wrap-full index">
    <div class="logo"></div>

    <div class="login-box">
        <h2 class="title">用户登录</h2>
        <div class="form-login">
            <!-- form 拷贝的原有标签 -->
            <form class="pure-form">
                <input id="email" type="email" placeholder="登录邮箱" class="input-login label-email">
                <input id="password" type="password" placeholder="登录密码" class="input-login label-passwd">

                <label for="remember" class="pure-checkbox">
                    <input id="remember" type="checkbox"> 自动登录
                </label>
                <p class="text-center">
                    <button id="submit" type="button" class="pure-btn btn-large btn-submit">登 录</button>
                </p>
            </form>
        </div>
    </div>

    <?php include_once("module/footer.php"); ?>
</div>
<script type="text/javascript">
    $('input').on('keypress',function(e){
        if(e.keyCode==13){
            login();
        }
    });

    $('#submit').on('click',function(){
        login();
    });

    function login(){
        $.post('ajax/user_login.php',{
            email:$('#email').val(),
            password:$('#password').val(), 
            remember:$('#remember').is(':checked')?1:0
        },function(json){
            __BDP.alertBox("提示",json.msg,"","",function(){
                if(json.r==1){
                    window.location.href='user_list.php';
                }
            });
        },'json')
    }
</script>
</body>
</html>