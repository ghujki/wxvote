<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
?>

<div class="wxuser-content container-fluid">
    <div>用户列表 &nbsp; <a href="javascript:;" onclick="syncWxUsers()">同步微信用户</a>
        <div class="float-right" >已选：<span id="selected"></span><input type="hidden" id="selectedId"></div>
    </div>
    <?php if($users) :?>
        <div class="row">
        <?php foreach ($users as $user) {?>
        <div class="thumbnail wxuser_item" onclick="checkThis('<?=$user['id']?>',this)">
            <img src="<?=$user['headimgurl']?>" alt="没有图片">
            <div class="caption">
                <?=$user['nickname']?>
            </div>
        </div>
        <?php } ?>
        </div>
    <?php endif;?>
    <p></p>
</div>
<button class="btn btn-default" onclick="bindUser()">确定</button>
<script>
    function checkThis(id,obj) {
        $(".wxuser_item").removeClass("checked");
        $(obj).addClass("checked");
        $("#selected").text($(obj).find(".caption").text());
        $("#selectedId").val(id);
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

    function bindUser() {
        var userId = $("#selectedId").val();
        $.ajax({
            url:'index.php/AdminCandidateController/ajaxBindUser',
            dataType:'json',
            data:{candi_id:'<?=$candi_id?>',user_id:userId},
            success:function(data) {
                if (data == 'ok'){
                    window.location.href = window.location.href;
                }
            }
        });
    }
</script>