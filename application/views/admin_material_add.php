<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport">
    <title><?=$title?></title>
    <base href="<?=base_url()?>" />
    <link rel="stylesheet" href="application/views/css/bootstrap-3.3.5.min.css">
    <link rel="stylesheet" href="application/views/css/admin.css" >
    <link rel="stylesheet" href="application/views/css/font-awesome.min.css" >
    <link rel="stylesheet" href="application/views/css/font-awesome-ie7.min.css">
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
            background:#fff;
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
</head>
<body>
<div class="container main-content" >
    <header class="row">
        <div class="col-xs-12">
            <div><h1>微时光微信管理系统</h1></div>
            <div class="account-info">你好，<?php echo $this->session->userdata("wsg_user_name");?>&nbsp;
                <a href="index.php/AdminAccountController/editAccount?id=<?php echo $this->session->userdata("wsg_user_id");?>">修改密码</a>&nbsp;
                <a href="index.php/AdminController/logout">退出</a>
            </div>
            <form class="search-query">
                <input type="text" placeholder="" name="query">
                <button type="submit" value="查询">查询</button>
            </form>
        </div>
    </header>

    <div class="row">
        <ol class="breadcrumb">
            <li><a href="/index.php/AdminMaterial">所有图文</a></li>
            <li><a href="/index.php/AdminMaterial/index?id=<?=$number['id']?>"><?=$number['app_name']?></a></li>
            <li class="active">十一月</li>
        </ol>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <form class="admin-query">
                <input type="text" placeholder="查询条件" name="query">
                <input type="submit" value="查询">
            </form>
        </div>

        <div class="col-xs-3">
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

        <div class="col-xs-5">
            <div class="news-content">
                <?php echo form_open_multipart("AdminMaterial/doEdit",array("id"=>"materialForm"));?>

                <div class="form-group">
                    <label>导入url</label>
                    <input type="text" class="form-control" id="url" onkeypress="importUrl()" placeholder="填写导入页面的链接,只能以http://mp.weixin.qq开头.按回车."/>
                </div>
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

                    <a class="btn btn-default float-right" onclick="preview_mt(this)" target="_blank" href="javascript:;">预览</a>
                    <button class="btn btn-default float-right" >提交</button>
                    <input type="file" name="pic" />
                </div>

                <div class="form-group">
                    <label for="check_url">地址</label><input type="radio" id="check_url" value="url" name="radio" >
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
                            window.UEDITOR_HOME_URL = "/application/third_party/ueditor";
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
        <div class="col-xs-4">
            <div class="news-toolbar clearfix">
                <ul class="col-xs-3 toolbar-nav">
                    <li class="active">
                        <a href="#up-arrows" data-toggle="tab" onclick="show_page(this,'/ueditor/template/1.out.html')">
                            关注
                        </a>
                    </li>
                    <li><a href="#title" data-toggle="tab" onclick="show_page(this,'/ueditor/template/2.out.html')">标题</a></li>
                    <li><a href="#content" data-toggle="tab" onclick="show_page(this,'/ueditor/template/3.out.html')">正文</a></li>
                    <li><a href="#pic" data-toggle="tab" onclick="show_page(this,'/ueditor/template/4.out.html')">图文</a></li>
                    <li><a href="#seperator" data-toggle="tab" onclick="show_page(this,'/ueditor/template/5.out.html')">分割线</a></li>
                    <li><a href="#qrcode" data-toggle="tab" onclick="show_page(this,'/ueditor/template/6.out.html')">二维码</a></li>
                    <li><a href="#read" data-toggle="tab" onclick="show_page(this,'/ueditor/template/7.out.html')">阅读原文</a></li>
                    <li><a href="#share" data-toggle="tab" onclick="show_page(this,'/ueditor/template/8.out.html')">分享</a></li>
                    <li><a href="#prompt" data-toggle="tab" onclick="show_page(this,'/ueditor/template/9.out.html')">互推</a></li>
                    <li><a href="#holiday" data-toggle="tab" onclick="show_page(this,'/ueditor/template/10.out.html')">节日</a></li>
                    <li><a href="#customed" data-toggle="tab" onclick="show_page(this,'/ueditor/template/11.out.html')">自定义</a></li>
                </ul>

                <div class="tab-content col-xs-9 toolbar-content">
                    <div class="tab-pane fade in active" id="up-arrows"></div>
                    <div class="tab-pane fade" id="title"></div>
                    <div class="tab-pane fade" id="content"></div>
                    <div class="tab-pane fade" id="pic"></div>
                    <div class="tab-pane fade" id="seperator"></div>
                    <div class="tab-pane fade" id="qrcode"></div>
                    <div class="tab-pane fade" id="read"></div>
                    <div class="tab-pane fade" id="share"></div>
                    <div class="tab-pane fade" id="prompt"></div>
                    <div class="tab-pane fade" id="holiday"></div>
                    <div class="tab-pane fade" id="customed"></div>
                </div>
            </div>
        </div>
    </div>

    <footer class="footer">
        <div class="scroll-tool">
            <a href="javascript:window.scrollTo(0,0);" class="fa fa-angle-double-up" title="回到顶部">&nbsp;</a>
            <a href="javascript:window.scrollTo(0,$(document.body).height());" class="fa fa-angle-double-down" title="到底部">&nbsp;</a>
        </div>
        <div class="text-center">
            微时光微信管理系统
        </div>
    </footer>
</div>
<script src="application/views/js/jquery-1.11.3.min.js"></script>
<script src="application/views/js/bootstrap-3.3.5.min.js"></script>
<script src="application/views/js/admin_vote_edit.js"></script>
<?php if ($jspaths):?>
    <?php foreach ($jspaths as $path):?>
        <script type="text/javascript" src="<?=$path?>"></script>
    <?php endforeach;?>
<?php endif;?>

<script>
    $(function(){
        $("input[name='radio']").change(function() {
            if ($(this).val() == 'url') {
                ue.setHide();
                $("input[name=url]").show();
            } else {
                ue.setShow();
                $("input[name=url]").hide();
            }
        });

        $("#up-arrows").load("/ueditor/template/1.out.html",function(){
            $("#up-arrows .tag-item").click(function(){
                ue.execCommand( 'inserthtml', $(this).html(),true);
            });
        });
        $("#up-arrows").addClass("loaded");
    });

    function show_page(obj,url) {
        $(obj).tab('show');
        var id  = $(obj).attr("href");
        if (!$(id).hasClass("loaded")) {
            $(id).load(url,function(){
                $(id + " .tag-item").click(function(){
                    ue.execCommand( 'inserthtml', $(this).html(),true);
                });
            });
            $(id).addClass("loaded");
        }
    }
    function importUrl(e) {
        var e = e || window.event;
        var url = $("#url").val()+ "?" + (+ new Date());
        if(e.keyCode == 13 && url) {
            $.ajax({
                url:"/index.php/AdminMaterial/curl",
                dataType:"json",
                data:{url:url},
                success:function(data) {
                    $("input[name=title]").val(data.title.trim());
                    ue.setContent(data.content);
                }
            })
        }
    }
</script>
</body>
</html>

