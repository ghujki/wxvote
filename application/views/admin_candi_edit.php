<?php
?>
<style>
    .edit-panel {border:1px solid #ccc;padding-top:2em;}
    .edit-panel .gallery {width:200px;margin-bottom:5px;float:left;margin-right:5px;}
    .gallery-close {position:absolute;}
    input[type=file] {display:none;}
    .add {
        background-image:url('application/views/images/add.png');
        background-size:cover;
    }
    .load {
        background-image:url('application/views/images/loading.gif');
        background-size:cover;
    }
    .file-label {
        padding:1em;
        background-size: cover;
        border: 1px solid #ccc;
        margin-right: 1em;
        width: 3em;
        height: 3em;
    }
    .wx-related {
        background-color:green;
        padding:.2em .5em;;
        color:#fff;
        font-size:small;
    }
    #user-info {
        padding:1em;
    }

    .wxuser-content {
        margin-bottom:1em ;
    }
    .wxuser-content .wxuser_item {
        width:100px;
        float:left;
        margin-right:10px;
        font-size:smaller;
        word-break: break-all;
    }
    .wxuser-content .wxuser_item img {
        width:100px;
    }
    .wxuser-content .wxuser_item.checked {
        border-color:green;
    }
</style>
<div class="edit-panel">
    <?php echo validation_errors(); ?>
    <?php echo form_open_multipart('AdminCandidateController/save',array('class'=>"form-horizontal","id"=>"enroll_form",'role'=>"form")); ?>
    <div class="form-group">
        <label for="name" class="col-xs-2 control-label">姓名</label>
        <div class="col-xs-4">
            <input name="name" id="name" value="<?=$candi['name']?>" class="form-control" />
        </div>
    </div>
    <div class="form-group">
        <label for="phone" class="col-xs-2 control-label">电话</label>
        <div class="col-xs-4">
            <input name="phone" id="phone" value="<?=$candi['phone']?>" class="form-control" />
        </div>
    </div>
    <div class="form-group">
        <label for="address" class="col-xs-2 control-label">地址</label>
        <div class="col-xs-4">
            <input name="address" id="address" value="<?=$candi['address']?>" class="form-control" />
        </div>
    </div>
    <div class="form-group">
        <label for="desc" class="col-xs-2 control-label">描述</label>
        <div class="col-xs-4">
            <input name="desc" id="desc" value="<?=$candi['desc']?>" class="form-control" />
        </div>
    </div>

    <div class="form-group">
        <label for="enroll_time" class="col-xs-2 control-label">报名时间</label>
        <div class="col-xs-4">
            <input name="enroll_time" id="enroll_time" value="<?=$candi['enroll_time']?>" class="form-control" data-toggle="time"/>
        </div>
    </div>

    <div class="form-group">
        <label for="priority" class="col-xs-2 control-label">预设票数</label>
        <div class="col-xs-4">
            <input name="priority" id="priority" value="<?=$candi['priority']?>" class="form-control" />
        </div>
    </div>

    <div class="form-group">
        <label for="status" class="col-xs-2 control-label">状态</label>
        <div class="col-xs-4">
            <select class="form-control" id="status" name="status">
                <option value="0" <?php if ($candi['status'] == 0):?> selected <?php endif;?>> 正常</option>
                <option value="1" <?php if ($candi['status'] == 1):?> selected <?php endif;?>> 冻结</option>
            </select>
        </div>
    </div>

    <div class="form-group">
        <label for="album" class="col-xs-2 control-label">相册</label>
        <div class="col-xs-10">
            <?php foreach ($gallery as $pic) :?>
            <div class="gallery" >
                <a href="javascript:;" onclick="removePicture('<?=$pic['id']?>',this)" class="gallery-close" title="删除"><i class="fa fa-times"></i></a>
                <img src="<?=$pic['pic']?>" class="img-responsive"/>
                <input type="hidden" name="file_path[]" value="<?=$pic['pic']?>" />
            </div>
            <?php endforeach;?>

            <div id="album-add">
                <label class="file-label add" for="pic"></label>
                <input type="file" name="file1" id="pic" onchange="uploadFile(this)"/>
            </div>

        </div>
    </div>
    <div class="form-group">
        <div class="col-xs-2"></div>
        <div class="col-xs-10">
            <input type="hidden" name="vote_id" value="<?=$vote_id?>" />
            <input type="hidden" name="candi_id" value="<?=$candi['id']?>" />
            <input type="submit" class="btn btn-default" value="确认"/>
        </div>
    </div>
    <div class="form-group">
        <label class="col-xs-2 control-label">微信信息</label>
        <div class="col-xs-10">
            <?php if($candi['id']) {?>
            <?php if ($candi['user_id']) {?>
                <span class="wx-related"><i class="fa fa-check"></i>微信绑定</span>&nbsp;
                    <a href="javascript:;" data-target="#modal2" data-src="index.php/AdminCandidateController/ajaxSyncUsers" onclick="showWxUserPanel(this)">重新绑定</a>
            <?php }else { ?>
                <a href="javascript:;" data-target="#modal2"
                   data-src="index.php/AdminCandidateController/ajaxSyncUsers" onclick="showWxUserPanel(this)">关联微信</a>
            <?php };?>
            <div id="user-info">
                <div>昵称：<?=$user_info['nickname']?></div>
                <div>头像：<img src="<?=$user_info['headimgurl']?>" style="width:100px;"></div>
                <div>性别：<?=$user_info['sex']?></div>
                <div>年龄：<?=$user_info['age']?></div>
                <div>国家：<?=$user_info['country']?></div>
                <div>省份：<?=$user_info['province']?></div>
                <div>城市：<?=$user_info['city']?></div>
                <div>地区：<?=$user_info['district']?></div>
                <div>关注时间：<?php echo date('Y-m-d H:i:s',$user_info['subscribe_time']);?></div>
                <div>openid：<?=$user_info['user_open_id']?></div>
                <div>unionid：<?=$user_info['union_id']?></div>
            </div>
            <?php }?>
        </div>
    </div>
    </form>
</div>
<div class="modal fade " id="modal2" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="close" ></div>
            <div class="modal-body" >

            </div>
        </div><!-- /.modal-content -->
    </div>
</div>
<link rel="stylesheet" href="application/views/css/jquery.datetimepicker.css">
<script>
    function removePicture(gallery_id,obj) {
        if (confirm('确定要删除吗？')) {
            $.ajax({
                url: 'index.php/AdminCandidateController/removeGallery',
                dataType: 'json',
                data: {'gallery_id': gallery_id},
                success: function (data) {
                    $(obj).parent().remove();
                }
            });
        }
    }

    function uploadFile(obj) {
        var id  = $(obj).attr("id");
        $("#enroll_form").ajaxSubmit({
            url:"/index.php/VoteController/upload?vote_id=<?=$vote_id?>",
            dataType:'json',
            type:'post',
            beforeSubmit:function() {
                $("label[for='" + id + "']").removeClass("add").addClass("load");
                $(obj).prop("disabled",true);
            },
            success:function (data) {
                if (data.error) {
                    $("label[for='" + id + "']").removeClass("load").addClass("add");
                    alert(data.error.error);
                } else {
                    var doc = "<div class= \"gallery\" > "  +
                        " <img src=\"" + data.file + "\" class=\"img-responsive\"/> " +
                        " <input type=\"hidden\" name=\"file_path[]\" value=\"" + data.file + "\" /> </div>";

                    $("label[for='" + id + "']").removeClass("load").addClass("add");
                    $("#album-add").before($(doc));

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

    function showWxUserPanel(obj) {
        var target = $(obj).attr("data-target");
        var src = $(obj).attr("data-src");
        $.ajax({
            url:src,
            dateType:"json",
            data:{vote_id:'<?=$vote_id?>',candi_id:'<?=$candi['id']?>'},
            success:function(data) {
                $(target).modal();
                $(".modal-body").html(JSON.parse(data).content);
                $(".default-img").on("error",function(){
                    $(this).attr("src",$(this).attr("data-src"));
                });
            }
        });
    }
</script>

