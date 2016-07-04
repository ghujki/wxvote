<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
?>
<style>
    table {
        margin:auto;
        margin-top:100px;
    }
</style>
<div>
    <?php echo validation_errors(); ?>
    <?php echo form_open('AdminAccountController/saveAcount',array("id"=>"edit_forom","onSubmit"=>"return submit_adapter();")); ?>
    <table width="70%" border="1px" class="admin-form">
        <tr>
            <td width="30%">用户名</td>
            <td width="70%"><input type="text" required name="username" placeholder="输入用户名" value="<?=$account['username']?>" <?php if ($account) { echo "readonly";}?> /> </td>
        </tr>
        <tr>
            <td width="30%">密码</td>
            <td width="70%"><input type="password" required name="password" placeholder="输入密码" /> </td>
        </tr>
        <tr>
            <td width="30%">输入验证码</td>
            <td width="70%"><input type="text" required name="captcha" /> <img src="index.php/AdminController/captcha" onclick="this.src=this.src"></td>
        </tr>
        <tr>
            <td><input type="hidden" name="id" value="<?=$account['id']?>"></td>
            <td><input type="submit"  class="btn btn-default" value="提交"/> </td>
        </tr>
    </table>
</div>
<script>
    function submit_adapter() {
        $("#edit_forom").ajaxSubmit({
            dataType:"json",
            success:function(data){
                $("input[name='token_wxvote']").val(data.hash);
                alert(data.err);
            }
        });
        return false;
    }
</script>