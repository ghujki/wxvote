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
        margin:0px;
    }
    .news-sub-item {
        padding:1em 0;
    }
    .news-sub-item .title {margin-left:10px;}
    .news-sub-item img{
        border:1px solid #ccc;
        height: 50px;
        float: right;
        margin-right: 20px;
    }
    .news-current {
        border:1px solid green;
    }
    .news-cover .title {
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
        text-decoration: none;
        margin-left:20px;
    }

    .edit-tool {
        font-size:x-large;
        text-align: center;
        line-height: 20px;
        cursor:pointer;
    }
</style>
<script>
    var newslist = <?=json_encode($news_materials)?>;
    if (newslist == null) {
        newslist = [];
    }
</script>
<div class="row">
    <div class="col-xs-12">
        <form class="admin-query">
            <input type="text" placeholder="查询条件" name="query">
            <input type="submit" value="查询">
        </form>
    </div>
    <div class="col-xs-12">
        <div class="m-head">
            <span><a href="javascript:history.go(-1);"><?=$number['app_name']?></a></span>/<span>新建图文消息</span>
        </div>
    </div>
    <div class="col-xs-12">
        <div class="container-fluid form-panel">
            <div class="row">

                <div class="col-xs-4">
                   <p>图文列表</p>

                    <?php $cover = $news_materials ? array_shift($news_materials) : null;?>
                    <?php if ($cover) {?>
                        <div class="row news-item news-cover news-current" data-content-id="<?=$cover['id']?>" onclick="editNewsMessage(this)">
                            <img src="<?=$cover['picurl']?>" class="img-responsive" />
                            <div class="title"><?=$cover['title']?></div>
                        </div>
                    <?php } else {?>
                        <figure class="news-item news-cover news-current" data-content-id="" onclick="editNewsMessage(this)">
                            <img src="application/views/images/picture.jpg" class="img-responsive" />
                            <figcaption class="title">标题</figcaption>
                        </figure>
                    <?php }?>

                    <?php if ($news_materials) :?>
                    <?php foreach($news_materials as $material):?>
                    <div class="row news-item news-sub-item" onclick="editNewsMessage(this)" data-content-id="<?=$material['id']?>">
                        <img src="<?=$material['picurl']?>" class="img-responsive">
                        <div class="title"><?=$material['title']?></div>
                    </div>
                    <?php endforeach;?>
                    <?php endif;?>

                    <div class="news-item news-add text-center">
                        <a href="javascript:;" onclick="addNewsMessage()" title="增加" class="fa fa-plus edit-tool"></a>
                        <a href="javascript:;" onclick="removeNewsMessage()" title="删除" class="fa fa-times edit-tool"></a>
                        <a href="javascript:;" onclick="upNewsMessage()" title="向上移" class="fa  fa-level-up edit-tool"></a>
                        <a href="javascript:;" onclick="downNewsMessage()" title="向下移" class="fa  fa-level-down edit-tool"></a>
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
                                <button class="btn btn-default float-right" >提交</button>
                                <input type="file" name="pic" />
                            </div>

                            <div class="form-group">
                                <label for="check_url">地址</label>
                                <input type="text" class="form-control" name="url" value="<?=$cover['url']?>" />
                            </div>

                            <div class="form-group">
                                <label for="check_content">内容</label>
                                <input type="radio" id="check_content" value="content" name="radio" >
                                <div >
                                    <!-- 加载编辑器的容器 -->
                                    <script id="container" name="content" type="text/plain"><?=$cover['content']?></script>
                                    <!-- 配置文件 -->
                                    <script type="text/javascript" src="/application/third_party/uedit/ueditor.config.js"></script>
                                    <!-- 编辑器源码文件 -->
                                    <script type="text/javascript" src="/application/third_party/uedit/ueditor.all.js"></script>
                                    <!-- 实例化编辑器 -->
                                    <script type="text/javascript">
                                        var ue = UE.getEditor('container');
                                        var contentChanged = false;
                                        ue.addListener("ready",function() {
                                            ue.addInputRule(function(root){
                                                $.each(root.getNodesByTagName('img'),function(i,node){
                                                    var src = $(node).attr("data-src");
                                                    if (src != null) {
                                                        node.src = src;
                                                    }
                                                });
                                            });

                                            ue.addListener( 'contentChange', function( editor ) {
                                                contentChanged = true;
                                            });
                                        });

                                    </script>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="check_url">原文地址</label>
                                <input type="text" class="form-control" name="content_source_url" value="<?=$cover['content_source_url']?>" />
                            </div>

                            <div class="form-group">

                                <input type="hidden" name="number_id" value="<?=$number['id']?>" id="number_id"/>
                                <input type="hidden" name="material_id" id="material_id" value="<?=$cover['id']?>"/>
                                <input type="hidden" name="media_id" id="media_id" value="<?=$cover['media_id']?>"/>
                                <input type="hidden" name="sort" id="sort" value="1" />
                                <button class="btn btn-default" >提交</button>
                                <a class="btn btn-default" onclick="preview_mt(this)" target="_blank" href="javascript:;">预览</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

