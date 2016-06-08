<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
?>
<style>
    .m-head {
        margin-top:20px;
        border-bottom:1px solid #ccc;
        padding:1em;
    }
    .grid {
        margin-top:10px;
    }
    .grid-item { width: 200px;margin-bottom:10px; border:1px solid #ccc;padding:1em;}
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
</style>
<link  rel="stylesheet" href="application/views/css/jquery.datetimepicker.css" />
<div class="row">
    <div class="col-xs-12">
        <form class="admin-query">
            <input type="text" placeholder="查询条件" name="query">
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
            <button class="btn btn-default" onclick="postNewsMessages('<?=$number['id']?>',$('#material_container .grid-item.checked').attr('data-id'))">推送图文消息</button>
            <button class="btn btn-default" onclick="syncNewsMessages()">同步图文消息</button>
            <button class="btn btn-default " disabled onclick="addNewsMessages()">新建图文消息</button>
            <button class="btn btn-default" disabled onclick="editNewsMessage('<?=$number['id']?>',$('#material_container .grid-item.checked').attr('data-id'))">编辑图文</button>
            <button class="btn btn-default" onclick="deleteMessage($('#material_container .grid-item.checked').attr('data-id'))">删除图文</button>
            <button class="btn btn-default" id="job_btn" disabled data-toggle="modal" data-target="#jobModal">加入到推送任务</button>
        </div>
    </div>
    <div class="col-xs-12">
        <div class="grid">
            <div class="container-fluid" id="material_container">
            <?php foreach ($materials as $m) :?>
            <div class="grid-item" data-id="<?=$m[0]['media_id']?>">
                <?php $cover = array_shift($m);?>
                <figure>
                    <img src="<?=$cover['picurl']?>" class="img-responsive">
                    <figcaption><a href="<?=$cover['url']?>" target="_blank"><?=$cover['title']?></a></figcaption>
                </figure>
                <?php foreach ($m as $item): ?>
                    <div class="row item-row">
                        <div class="col-xs-8"><a href="<?=$item['url']?>" target="_blank"><?=$item['title']?></a></div>
                        <div class="col-xs-4"><img src="<?=$item['picurl']?>" class="img-responsive"></div>
                    </div>
                <?php endforeach; ?>
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
