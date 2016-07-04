<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
?>

<div class="wxuser-content container-fluid">
    <div>用户列表 &nbsp; <a href="javascript:;" onclick="syncWxUsers()">同步微信用户</a>
        <input type="text" id="keywords" onblur="ajax_page('<?=$start?>')">
        <div class="float-right" >已选：<span id="selected"></span><input type="hidden" id="selectedId"></div>
    </div>
    <?php if($users) :?>
        <div class="row wxuser-list">
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
    <p><?=$links?></p>
</div>
<button class="btn btn-default customed-confirm" onclick="bindUser()">确定</button>
<script>
    function checkThis(id,obj) {
        $(".wxuser_item").removeClass("checked");
        $(obj).addClass("checked");
        $("#selected").text($(obj).find(".caption").text());
        $("#selectedId").val(id);
    }

    function syncWxUsers() {
        if (confirm("本次操作可能会造成几分钟的卡顿,是否继续?")) {
            $.ajax({
                url: 'index.php/AdminCandidateController/syncWxUser',
                dateType: 'json',
                data: {'number_id': '<?=$number_id?>'},
                success: function () {
                    $("#modal2").modal("hide");
                }
            });
        }
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

    function ajax_page(start) {
        var t = event.target;
        var keywords = $("#keywords").val();
        $.ajax({
            url:"index.php/AdminOfficialNumber/ajaxShowUsers",
            dataType:"text",
            data: {'id':'<?=$number_id?>',start:start,keywords:keywords},
            success:function(data) {
                $(t).parents(".wxuser-content").parent().html(data);
                //$("#subContent<?=$number_id?>").html(data);
                window.setTimeout(function(){
                    $(".wxuser-list").masonry({
                        itemSelector : '.wxuser_item'
                    });
                },500);
            }
        });
    }
</script>