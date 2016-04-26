<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
?>

<div class="wxuser-content clearfix">
    <p>用户列表 &nbsp; <a href="javascript:;" onclick="syncWxUsers()">同步微信用户</a></p>
    <ul>
        <?php if($users) :?>
        <?php foreach ($users as $user) {?>
            <li onclick="checkThis(this)">
                <input type="radio" name="wx_user" id="wx_user_1">
                <label for="wx_user_1">
                    <img src="application/views/images/babe1.png" class="img-responsive" />
                    <span>小儿</span>
                </label>
            </li>
        <?php } ?>
        <?php endif;?>
    </ul>
    <p></p>
</div>
<button class="btn btn-default">确定</button>
<script>
    function checkThis(obj) {
        $(".wxuser-content li").removeClass("checked");
        $(obj).addClass("checked");
    }

    function syncWxUsers() {
        $.ajax({
            url:'index.php/AdminCandidateController/syncWxUser',
            dateType:'json',
            data:{'number_id':'<?=$number_id?>'},
            success:function() {
                $("#modal2").modal("hide");
            }
        });
    }
</script>