<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
?>
<style>
    .m-head {
        border-bottom:1px solid #ccc;
        padding-bottom:1em;
    }
    .grid {
        margin-top:10px;
    }
    .grid-item { width: 200px;margin-bottom:50px; border:1px solid #ccc;padding:1em;background:#fff;}
    .grid-item.item-synched {
        border:#2e6da4 2px solid ;
    }
    .grid-item.checked {border-color:green;}
    .grid-item figure {
        padding-bottom:10px;
    }
    .grid-item figcaption {
        padding: .2em;
        font-size: small;
        margin-top: -20px;
        background-color: black;
        width: 100%;
        color: #fff;
        opacity: .8;
    }
    .item-row {border-top:1px solid #ccc;padding:.5em;font-size:small}
    .media-tools {
        display:none;
        position: absolute;
        top: 0px;
        left: 0;
    }
    .grid-item.checked .media-tools {
        display:block;
    }

    .wxuser-content .wxuser_item {
        width: 100px;
        float: left;
        margin-right: 10px;
        font-size: smaller;
        word-break: break-all;
    }

    .wxuser-content .wxuser_item.checked {
        border:1px solid green;
    }
    .customed-confirm {display:none;}
    .modal-user-list {list-style: none}
    .modal-user-list li {float:left;margin-right:20px;}

    .bootstrap-tagsinput {
        position:absolute;
        margin-top:20px;
        left:0px;
        width:200px;
    }

</style>
<link  rel="stylesheet" href="application/views/css/jquery.datetimepicker.css" />
<link  rel="stylesheet" href="application/views/css/tinyselect.css" />
<link  rel="stylesheet" href="application/views/css/bootstrap-tagsinput.css" />
<style>
    .bootstrap-tagsinput input {
        padding:0;
    }
</style>
<div class="row">
    <div class="col-xs-12">
        <form class="admin-query" onsubmit="checkQuery()">
            <input type="text" placeholder="查询条件" name="query" value="<?=$query?>">
            <input type="hidden" name="id" id="query_id" value="<?=$id?>" />
            <input type="submit" value="查询">
        </form>
    </div>
    <div class="col-xs-12">
        <div class="m-head">
            <select id="number_id" class="btn">
                <?php foreach ($numbers as $number) : ?>
                <option value="<?=$number['id']?>" <?php if ($number['id'] == $id):?>selected<?php endif;?>><?=$number['app_name']?></option>
                <?php endforeach;?>
            </select>
            <button class="btn btn-default" onclick="postNewsMessages('<?=$id?>',$('#material_container .grid-item.checked').attr('data-id'))">推送图文消息</button>
            <button class="btn btn-default" onclick="syncNewsMessages()">同步图文消息</button>
            <button class="btn btn-default " onclick="addNewsMessages()">新建图文消息</button>
            <button class="btn btn-default" onclick="editNewsMessage('<?=$id?>',$('#material_container .grid-item.checked').attr('data-id'))">编辑图文</button>
            <button class="btn btn-default" onclick="deleteMessage($('#material_container .grid-item.checked').attr('data-id'))">删除图文</button>
            <button class="btn btn-default" id="job_btn" disabled onclick="add_job()">加入到推送任务</button>
            <button class="btn btn-default" id="copy_btn" disabled data-toggle="modal" data-target="#newsModal">复制图文</button>
        </div>
    </div>
    <div class="col-xs-12">
        <?=$links?>
        <div class="grid">
            <div class="container-fluid" id="material_container">
            <?php foreach ($materials as $m) :?>
                <?php $cover = array_shift($m);?>
                <div class="grid-item <?php if ($cover['synchronized']) {echo 'item-synched';} ?>" data-id="<?=$cover['media_id']?>">
               
                <figure>
                    <img src="<?=$cover['picurl']?>" class="img-responsive">
                    <figcaption><a <?php if($cover['url']) {?> href="<?=$cover['url']?>" <?php } else { echo "href=\"index.php/AdminMaterial/preview/$cover[id]\"";}?> target="_blank"><?=$cover['title']?></a></figcaption>
                </figure>
                <?php foreach ($m as $item): ?>
                    <div class="row item-row">
                        <div class="col-xs-8"><a <?php if($item['url']) {?> href="<?=$item['url']?>" <?php } else { echo "href=\"index.php/AdminMaterial/preview/$item[id]\"";}?> target="_blank"><?=$item['title']?></a></div>
                        <div class="col-xs-4"><img src="<?=$item['picurl']?>" class="img-responsive"></div>
                    </div>
                <?php endforeach; ?>
                <?php if ($cover['synchronized']) {?>
                <div class="media-tools">
                    <button class="btn btn-default" id="mobile-preview"  data-toggle="modal" data-target="#prevModal" onclick="view_users('<?=$cover["app_id"]?>')">手机预览</button>
                </div>
                <?php }?>
                <input type="text" value="<?=$cover['tags']?>" data-role="tagsinput" />
            </div>
            <?php endforeach;?>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="jobModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close"
                        data-dismiss="modal" aria-hidden="true">
                    &times;
                </button>
                <h4 class="modal-title" id="myModalLabel">
                    设置推送时间
                </h4>
            </div>
            <div class="modal-body">
                推送时间：<input type="text" id="job_time" data-toggle="microtime" >
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">关闭
                </button>
                <button type="button" class="btn btn-primary" onclick="postNewsJob()">
                    提交
                </button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal -->
</div>

<div class="modal fade" id="prevModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close"
                        data-dismiss="modal" aria-hidden="true">
                    &times;
                </button>
                <h4 class="modal-title" id="myModalLabel">
                    选择接受者
                </h4>
            </div>
            <div class="modal-body">

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">关闭
                </button>
                <button type="button" class="btn btn-primary" onclick="mt_preview()">
                    提交
                </button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal -->
</div>


<div class="modal fade" id="newsModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close"
                        data-dismiss="modal" aria-hidden="true">
                    &times;
                </button>
                <h4 class="modal-title" >
                    复制给公众号:
                </h4>
            </div>
            <div class="modal-body">
                <ul class="modal-user-list clearfix">
                    <?php foreach ($numbers as $number) : ?>
                        <?php if ($number['id'] != $id):?>
                        <li>
                            <input type="radio" name="number_id" id="n_<?=$number['id']?>" value="<?=$number['id']?>">
                            <label for="n_<?=$number['id']?>"><?=$number['app_name']?></label>
                        </li>
                        <?php endif;?>
                    <?php endforeach;?>
                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">关闭
                </button>
                <button type="button" class="btn btn-primary" onclick="cp_to()">
                    提交
                </button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal -->
</div>

<script>

    function add_job () {
        var dataid = $(".grid-item.checked").attr("data-id");
        if (dataid.match(/^\d*$/)) {
            alert("这条图文还没有同步,如果已经同步的话,请刷新页面再重新添加任务");
            return ;
        }
        $("#jobModal").modal();
    }
    function view_users(id) {
        $.ajax({
            url:"index.php/AdminOfficialNumber/ajaxShowUsers",
            dataType:"text",
            data: { id: id},
            success:function (data) {
                $("#prevModal .modal-body").html(data);
                window.setTimeout(function(){
                    $(".wxuser-list").masonry({
                        itemSelector : '.wxuser_item'
                    });
                },500);
            }
        });
    }

    function mt_preview () {
        var id = $("#selectedId").val();
        var media_id = $(".grid-item.checked").attr("data-id");
        if (id != null && id != '' ) {
            $.ajax({
                url:"index.php/AdminMaterial/mobilePreview",
                dataType:"json",
                data:{"uid":id,"mid":media_id},
                success:function(data) {
                    if (data.errcode) {
                        alert(data.errmsg);
                    } else {
                        $("#prevModal").modal("hide");
                        alert("发送成功");
                    }
                }
            });
        }
    }
    function customed_page (nid,start) {
        $.ajax({
            url:"index.php/AdminOfficialNumber/ajaxShowUsers",
            dataType:"text",
            data: {'id':nid,start:start},
            success:function(data) {
                $("#prevModal .modal-body").html(data);
                window.setTimeout(function(){
                    $(".wxuser-list").masonry({
                        itemSelector : '.wxuser_item'
                    });
                },500);
            }
        });
    }

    function checkQuery() {
        $("#query_id").val($("#number_id").val());
        return true;
    }
</script>