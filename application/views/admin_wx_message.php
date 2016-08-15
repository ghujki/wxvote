<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<style>
    .chat-box {
        width: 100%;
        border: 1px solid #ccc;
        margin-top: 10px;
        min-height: 250px;
        overflow-y: auto;
        overflow-x: hidden;
        padding: .5em;
        background: #fff;
        border-top-left-radius: 5px;
        border-top-right-radius: 5px;
        max-height: 550px;

    }
    .chat-box .msg-item {
        margin:.5em;
    }
    .chat-box .from {
        background: gray;
        float: left;
    }
    .chat-box .to {
        float: right;
        background: green;
    }
    .chat-box .to ,.chat-box .from {
        color: #fff;
        padding: .5em;
        border-radius: .2em;
        word-break: break-all;
    }

    .ctl-panel {
        background: #999;
        padding: .5em;
    }

    #message {
        width: 100%;
        height: 100px;
        overflow-y: scroll;
        resize: none;
    }
</style>
<div class="row">
    <div class="col-xs-12">
        <form class="admin-query">
            <input type="text" placeholder="查询条件" name="query">
            <input type="submit" value="查询">
        </form>
    </div>

    <div class="col-xs-12">
        <div>与<span><?=$user['nickname']?></span>聊天</div>
        <div class="chat-box">
            <?php foreach ($messages as $message) {?>
            <div class="msg-item clearfix">
                <div class="<?php if ($openid == $message['fromusername']) { echo 'from'; } else {echo 'to';}?>">
                    <span><?=date('Y-m-d H:i:s',$message['msg_time'])?></span>
                    <div data-id="<?=$message['id']?>">
                        <?=$message['content']?>
                    </div>
                </div>
            </div>
            <?php } ?>
        </div>

    </div>

    <div class="col-xs-12">
        <div class="ctl-panel">
            <textarea namd="message" id="message" ></textarea>
            <input type="button" value="发送" onclick="sendMessage()"/>
        </div>
    </div>
</div>
<script>
    function sendMessage() {
        var userId = '<?=$user['id']?>';
        var message = $("#message").val();
        $.ajax({
            url:"/index.php/AdminOfficialNumber/chat",
            dataType:"json",
            data:{userId:userId,message:message},
            success:function(data) {
                if (data.errcode) {
                    alert(data.errmsg);
                } else {
                    window.location.href = window.location.href;
                }
            }
        });
    }
</script>
