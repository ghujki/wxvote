<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
?>
<style>
    .form-panel {
        margin-top:10px;
    }
    .form-panel p {
        font-weight:bold;
        font-size:small;
    }
    .news-item {
        border:1px dashed #ccc;
        cursor:pointer;
        margin:0px;
    }
    .news-sub-item {
        padding:1em 0;
    }
    .news-sub-item img{
        border:1px solid #ccc;
    }
    .news-current {
        border:1px solid green;
    }
    .news-cover figcaption {
        background-color:#000000;
        color:#fff;
        padding:.5em;
        opacity: .5;
        margin-top: -34px;
    }
    .news-add {
        font-size:xx-large;
    }
    .news-add a {
        cursor:pointer;
        text-decoration: none;;
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
        <div class="m-head">
            <span><?=$number['app_name']?></span>/<span>新建图文消息</span>
        </div>
    </div>
    <div class="col-xs-12">
        <div class="container-fluid form-panel">
            <div class="row">

                <div class="col-xs-4">
                   <p>图文列表</p>

                    <?php $cover = array_shift($news_materials);?>
                    <?php if ($cover) {?>
                        <figure class="news-item news-cover news-current" data-content-id="<?=$cover['id']?>" onclick="editNewsMessage(this)">
                            <img src="<?=$cover['picurl']?>" class="img-responsive" />
                            <figcaption class="title"><?=$cover['title']?></figcaption>
                        </figure>
                    <?php } else {?>
                        <figure class="news-item news-cover news-current" data-content-id="" onclick="editNewsMessage(this)">
                            <img src="application/views/images/picture.jpg" class="img-responsive" />
                            <figcaption class="title">标题</figcaption>
                        </figure>
                    <?php }?>

                    <?php foreach($news_materials as $material):?>
                    <div class="row news-item news-sub-item" onclick="editNewsMessage(this)">
                        <div class="col-xs-8 title"><?=$material['title']?></div>
                    <div class="col-xs-4"><img src="<?=$material['picurl']?>" class="img-responsive"></div>
                    </div>
                    <?php endforeach;?>

                    <div class="news-item news-add text-center">
                        <a href="javascript:;" onclick="addNewsMessage()">+</a>
                    </div>
                </div>

                <div class="col-xs-8">
                    <div class="news-content">
                        <?php echo form_open_multipart("AdminMaterial/doEdit",array("id"=>"materialForm"));?>
                            <div class="form-group">
                                <label>标题</label>
                                <input type="text" class="form-control" name="title" value="<?=$cover['title']?>"/>
                            </div>
                            <div class="form-group">
                                <label>描述</label>
                                <input type="text" class="form-control" name="desc" value="<?=$cover['desc']?>"/>
                            </div>

                            <div class="form-group">
                                <label>图片</label>
                                <input type="file" name="pic" />
                            </div>

                            <div class="form-group">
                                <label>url</label>
                                <input type="text" class="form-control" name="url" value="<?=$cover['url']?>"/>
                            </div>

                            <div class="form-group">

                                <input type="hidden" name="number_id" value="<?=$number['id']?>" id="number_id"/>
                                <input type="hidden" name="material_id" id="material_id" value="<?=$cover['id']?>"/>
                                <input type="hidden" name="media_id" id="media_id" value="<?=$cover['media_id']?>"/>
                                <button class="btn btn-default" >提交</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>