<?php
/**
 * Created by PhpStorm.
 * User: ghujk
 * Date: 2016/8/9
 * Time: 15:06
 */
?>
<div class="row">
    <?php foreach ($users as $user) {?>
        <div class="col-xs-4" >
            <figure class="robot-item" onclick="select_item(this)">
                <a class="fa fa-times robot-item-op" title="点击删除" onclick="remove_robot(this,'<?=$user['uuid']?>')"></a>
                <img src="<?php if (strpos($user['desc'],"wait for scan") !== false ) { echo "/application/modules/weixin_robot/qr_".$user['uuid'].".png"; }
                        elseif ($user['uin']){ echo "/application/modules/weixin_robot/robots/".$user['uin']."/head.jpg";} ?>">
                <figcaption>
                    <?=$user['nickname']?>
                    <div class="robot-status robot-status<?=$user['status']?>">
                        <?=$user['desc']?>
                    </div>
                </figcaption>
                <a href="javascript:;" class="fa fa-navicon robot-item-conf" title="配置" onclick="loadConfig('<?=$user['uin']?>')"></a>
            </figure>
        </div>
    <?php }?>

    <div class="col-xs-4">
        <figure class="robot-item add-item">
            <a href="javascript:;" onclick="new_robot(this)"><i class="fa fa-plus"></i></a>
        </figure>
    </div>
</div>

<div class="modal fade" id="configModel" tabindex="-1" role="dialog" aria-hidden="false">
    <div class="modal-dialog">
    	
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close"
                        data-dismiss="modal" aria-hidden="true">
                    &times;
                </button>
                <h4 class="modal-title" id="myModalLabel">
                    机器人设置
                </h4>
            </div>
            <?php echo form_open_multipart("module/weixin_robot/IndexController/upload",array("id"=>"robot_config_form"))?>
            <div class="modal-body">

            </div>
            </form>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">关闭
                </button>
                <button type="button" class="btn btn-primary" onclick="postRule()">
                    提交
                </button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal -->
</div>


<script>

    function postRule() {
        var uin = $("#cuin").val();
        var auto_verify = $("#auto_verify").prop('checked');


        var p = $("#robot_verify_panel .robot-config-item:first").nextAll();
        var verify_rule = {"msg_type":'add',"keywords":'',"reply":[]};
        for (var i = 0 ;i < p.length ; i++) {
            var type = $(p[i]).find("select").val();
            var content = "";
            if (type == "text") {
                content = $(p[i]).find("input[name='verify_reply_text[]']").val();
            } else if (type == "image") {
                content = $(p[i]).find("img").attr("data");
            }
            var v_reply  = {"type":type,"content":content};
            verify_rule.reply.push(v_reply);
        }

        var rules = {"auto_add":auto_verify,"reply_rules":[verify_rule]};

        var rp = $("#robot_reply_panel .robot-config-item");
        for (var i = 0 ; i < rp.length ; i++ ) {
            var item = rp[i];
            var reply_rule = {"msg_type":"text","keywords":$(item).find("input[name='keywords[]']").val(),"reply":[]};

            var reply_types_dom = $(item).find("select[name='reply_rule_types[]']");
            var reply_content_dom = $(item).find("input[name='reply_text[]']");
            var reply_img_dom = $(item).find("img");
            for (var j = 0; j < reply_types_dom.length ;j++) {
                var reply_type = $(reply_types_dom[j]).val();
                var reply_content = "";
                if (reply_type == "text") {
                    reply_content = $(reply_content_dom[j]).val();
                } else if (reply_type == "image") {
                    reply_content = $(reply_img_dom[j]).attr("data");
                }
                reply_rule.reply.push({"type":reply_type,"content":reply_content});
            }
            rules.reply_rules.push(reply_rule);
        }

        $.ajax({
            url:"/index.php/module/weixin_robot/indexController/saveRule",
            dataType:"json",
            data:{"uin":uin,"rules":JSON.stringify(rules)},
            success:function(data) {
                if (data.error) {
                    alert(data.error);
                } else {
                    alert("保存成功");
                }
                $("#configModel").modal("hide");
            }
        })
    }
    function uploadImages(obj) {
        $("#robot_config_form").ajaxSubmit({
            dataType:"json",
            success:function(data){
                $(obj).val('');
                if (data.error) {
                    alert(data.error);
                } else {
                    $(obj).siblings("img").attr("src","/application/modules/weixin_robot/"+data.img).attr("data",data.img).show();
                }
                $("input[name='token_wxvote']").val(data.hash);
            }
        });
    }

    function loadConfig(uin) {
        $('#configModel').modal({backdrop: 'static', keyboard: false});
        $(".modal-body").load("/index.php/module/weixin_robot/IndexController/loadConfig/" + uin,function(){});
    }

    function new_reply_rule(obj) {
        var dom = "<div class='robot-config-item'> "+
            "<input type='text' name='keywords[]' placeholder='关键字'>回复 "+
            "<select name='reply_rule_types[]' onchange='reply_rule_changed(this)'><option value='text'>文本</option><option value='image'>图片</option></select>" +
            "<input type='text' placeholder='回复内容' name='reply_text[]'> "+
            "<img src='' style='display:none' onclick='$(this).next().click()'>" +
            "<input type='file' name='files[]' onchange='uploadImages(this)'> "+
            "<a href='javascript:;' onclick='remove_reply_rule(this)'>删除</a> "+
            "<a href='javascript:;' onclick='new_sub_reply_rule(this)'>增加</a> "+
            "</div>";
        if ($(obj).siblings(".robot-config-item").length == 0 ) {
            $(obj).after($(dom));
        } else {
            $(obj).siblings(".robot-config-item:last").after($(dom));
        }
    }

    function reply_rule_changed(obj) {
        if (obj.value == 'text') {
            $(obj).siblings("input[name='reply_text[]']").show();
            $(obj).siblings("input[name='files[]']").hide();
        } else if (obj.value == 'image') {
            $(obj).siblings("input[name='reply_text[]']").hide();
            $(obj).siblings("input[name='files[]']").show();
        }
    }

    function remove_reply_rule(obj) {
        if (confirm('将删除整条回复规则,是否继续?')) {
            $(obj).parents(".robot-config-item").remove();
        }
    }

    function remove_sub_reply_rule( obj) {
        $(obj).parents(".sub-item").remove();
    }

    function new_sub_reply_rule(obj) {
        var dom =  "<div class='sub-item'> "+
            "<select name='reply_rule_types[]' onchange='reply_rule_changed(this)'><option value='text'>文本</option><option value='image'>图片</option></select>" +
            "<input type='text' placeholder='回复内容' name='reply_text[]'> "+
            "<img src='' onclick='$(this).next().click()' style='display:none'>" +
            "<input type='file' name='files[]' onchange='uploadImages(this)'>" +
            "<a href='javascript:;' onclick='remove_sub_reply_rule(this)'>删除</a> "+
            "</div> ";
        if ($(obj).siblings(".sub-item").length  == 0) {
            $(obj).after($(dom));
        } else {
            $(obj).siblings(".sub-item:last").after($(dom));
        }
    }

    function reply_type_changed(obj) {
        if (obj.value == 'text') {
            $(obj).parent().find("input[name='verify_reply_text[]']").show();
            $(obj).parent().find("input[name='files[]']").hide();
        } else if (obj.value == 'image'){
            $(obj).parent().find("input[name='verify_reply_text[]']").hide();
            $(obj).parent().find("input[name='files[]']").show();
        }
    }

    function remove_verify_rule(obj) {
        $(obj).parents(".robot-config-item").remove();
    }
    function new_verify_rule (obj) {
        var dom = "<div class='robot-config-item'> " +
            "<select name='verify_reply_type[]' onchange='reply_type_changed(this)'> " +
            "<option value='text' placeholder='回复内容'>文本</option>" +
            "<option value='image'>图片</option>" +
            "</select>" +
            "<input type='text' name='verify_reply_text[]'> " +
            "<img src='' style='display:none' onclick='$(this).next().click()'> " +
            "<input type='file' name='files[]' onchange='uploadImages(this)'> " +
            "<a href='javascript:;' onclick='remove_verify_rule(this)'>删除</a>" +
            "<a href='javascript:;' onclick='new_verify_rule(this)'>增加</a>" +
            "</div>";
        var p = $(obj).parents(".robot-config-item").parent().find(".robot-config-item:last").after($(dom));

    }

    function new_robot(obj) {
        $.ajax({
            url:"/index.php/module/weixin_robot/IndexController/new_robot",
            dataType:"json",
            success:function(data) {
                if (data.err) {
                    alert(data.err);
                    return;
                }
                getQrCode();
            }
        });
    }

    function getQrCode () {
        var abc = setInterval( function() {
            var obj = $(".robot-item.add-item");
            $.ajax({
                url:"/index.php/module/weixin_robot/IndexController/getQrCode",
                dataType:"json",
                success:function(data) {
                    if (data.img) {
                        var qrcode = "<div class='col-xs-4'><figure class='robot-item' data='" + data.uuid + "'><img src='" + data['img'] + "'><figcaption> " +
                            "扫码登录</figcaption></figure></div>";
                        $(qrcode).insertBefore($(obj).parents(".col-xs-4"));
                        clearInterval(abc);
                        get_user(data.uuid);
                    }
                }
        })},1000);
    }

    function remove_robot(obj,uuid) {
        $.ajax({
            url:"/index.php/module/weixin_robot/IndexController/remove_robot",
            dataType:"json",
            data:{uuid:uuid},
            success:function(data) {
                if (data.errcode == 0) {
                    $(obj).parents(".col-xs-4").remove();
                } else {
                    alert(obj.errmsg);
                }
            }
        });
    }

    function select_item(obj) {
        $(".robot-item").removeClass("current");
        $(obj).addClass("current");
    }


    function get_user(uuid) {
        var a  = setInterval( function() {
        $.ajax({
            url:"/index.php/module/weixin_robot/IndexController/syncUserInfo",
            dataType:"json",
            data:{uuid:uuid},
            success:function(data) {
                if (data.uuid) {
                    window.location.href = window.location.href;
                }
            }
        })},1000);
    }
</script>