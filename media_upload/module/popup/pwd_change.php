<div class="win win-form" id="pwdChange">
    <h3 class="heading">修改密码</h3>
    <div class="win-content">
        <div class="box-form">
            <p class="space">* 请输入你的当前密码和新密码！</p>
            <!-- form 拷贝的原有标签 -->
            <div class="pure-form pure-form-aligned" method="post" id="formPwdChange">
                <fieldset>
                    <div class="pure-control-group">
                        <label for="password">当前密码</label>
                        <input name="password" id="password" type="password" placeholder="">
                    </div>

                    <div class="pure-control-group">
                        <label for="passwordNew">新密码</label>
                        <input name="passwordNew" id="passwordNew" type="password" placeholder="" >
                    </div>

                    <div class="pure-control-group">
                        <label for="passwordNewR">确认密码</label>
                        <input name="passwordNewR" id="passwordNewR" type="password" placeholder="" >
                    </div>

                    <div class="pure-control-group" style="margin: 30px auto;">
                        <button id="btn_update_password" type="button" class="pure-btn btn-large btn-red">确定提交</button>
                    </div>
                    <div class="error-msg"></div>
                </fieldset>
            </div>
            <!-- form End -->
            <div class="clear"></div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $('#btn_update_password').on('click',function(){
        $.post('ajax/user_change_password.php',{
            old_password:$('#password').val(),
            password:$('#passwordNew').val(),
            confirm_password:$('#passwordNewR').val()
        },function(json){
            __BDP.alertBox("提示",json.msg);
        },'json');  
    });
</script>