<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<link rel="stylesheet" href="application/views/css/font-awesome.min.css" >
<link rel="stylesheet" href="application/views/css/font-awesome-ie7.min.css">
<style>
    input[type='file'] {
        /* opacity: 0; */
        text-indent: 0;
        font-size: 100px;
        overflow: hidden;
        display: inline-block;
        height: 0em;
        width: 0em;
        position: absolute;
    }
    .file-label {
        padding:1em;
        background-size: cover;
        border: 1px solid #ccc;
        margin-right: 1em;
        width: 3em;
        height: 3em;
    }
    .add {
        background-image:url('application/views/images/add.png');
        background-size:cover;
    }
    .load {
        background-image:url('application/views/images/loading.gif');
        background-size:cover;
    }
    .check {
        float: right;
        margin-right: 5px;
        font-size: x-large;
        color: green;
        margin-top: -1.2em;
        display:none;
    }
    .wrong {
        border:1px solid red;
    }
    #error-captcha {
        color:red;
    }
</style>
<section class="row light-green main-content">
    <?php echo form_open_multipart("VoteController/join?vote_id=$vote_id",array("id"=>"enroll_form"))?>
        <div class="form-group">
            <label for="name">姓名</label>
            <input type="text" class="form-control" id="name" name="name"
                   placeholder="请输入名称" required>
        </div>
        <div class="form-group">
            <label for="phone">联系电话</label>
            <input type="number" class="form-control" id="phone" name="phone"
                   placeholder="请输入手机号" required>
        </div>

        <div class="form-group">
            <label for="captcha">验证码</label>
            <input type="text" id="captcha" name="captcha" class="form-control" required onblur="captchaBlur()" onfocus="captchaFocus()"/>
            <i class="fa fa-spinner check"></i>
            <div id="error-captcha"></div>
            <p class="help-block">在公众号中输入“验证码”即可获得验证码。
                <a href="javascript:;" data-toggle="modal" data-target="#modal1">关注我们</a>
            </p>
        </div>

        <div class="form-group">
            <label>上传照片</label>
            <div>
                <label class=" file-label add" for="file1"></label>
                <input type="file" id="file1" name="file1" onchange="fileChange(this)" />
                <input type="hidden" name="file1_path" id="file1_path"/>

                <label class=" file-label add" for="file2"></label>
                <input type="file" id="file2" name="file1" onchange="fileChange(this)"/>
                <input type="hidden" name="file2_path" id="file2_path"/>

                <label class=" file-label add" for="file3"></label>
                <input type="file" id="file3" name="file1" onchange="fileChange(this)"/>
                <input type="hidden" name="file3_path" id="file3_path"/>

                <label class=" file-label add" for="file4"></label>
                <input type="file" id="file4" name="file1" onchange="fileChange(this)"/>
                <input type="hidden" name="file4_path" id="file4_path"/>
            </div>
            <p class="help-block">上传1~4张图片，至少一张。</p>
            <div id="error"></div>
        </div>
        <div class="form-group">
            <label for="desc">描述</label>
            <input type="text" id="desc" name="desc" class="form-control"  multiple>
        </div>
        <input type="hidden" name="user_id" id="user_id"/>
        <button type="submit" class="btn form-control" disabled="disabled">提交</button>
    </form>
</section>
<script>
    function fileChange(obj) {
        var id = obj.id;
        var value = obj.value;
        $("#enroll_form").ajaxSubmit({
            url:"/index.php/VoteController/upload?vote_id=<?=$vote_id?>",
            dataType:'json',
            type:'post',
            target:"#error",
            beforeSubmit:function() {
                $("label[for='" + id + "']").removeClass("add").addClass("load");
                $(obj).prop("disabled",true);
            },
            success:function (data) {
                if (data.error) {
                    $("label[for='" + id + "']").removeClass("load").addClass("add");
                    //alert(data.error.error);
                } else {
                    $("label[for='" + id + "']").removeClass("load").css("background-image", "url('" + data.file + "')");
                    $("#" + id + "_path").val(data.file);
                }
                $(obj).prop("disabled",false);
                $("input[name='token_wxvote']").val(data.hash);
            },
            error:function(e) {
                $("label[for='" + id + "']").removeClass("load").addClass("add");
                alert(e.responseText);
                $(obj).prop("disabled",false);
            }
        });
    }

    function captchaFocus() {
        $("#captcha").removeClass("wrong");
        $("#error-captcha").hide();
    }
    function captchaBlur() {
        var captcha = $("#captcha").val();
        $(".check").show();
        $.ajax({
            url:"index.php/VoteController/checkCaptcha?vote_id=<?=$vote_id?>",
            type:"get",
            dataType:"json",
            data:{'captcha':captcha},
            success:function(data) {
                if (data.error == 0) {
                    $(".check").removeClass("fa-spinner").addClass("fa-check");
                    $("#user_id").val(data.user_id);
                } else {
                    $(".check").removeClass("fa-check").addClass("fa-spinner").hide();
                    $("#user_id").val("");
                    $("#captcha").addClass("wrong");
                    $("#error-captcha").text(data.info).show();
                }
            }

        });
    }


</script>
