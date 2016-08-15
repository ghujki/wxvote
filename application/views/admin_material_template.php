<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
?>

<div class="row news-toolbar clearfix" style="position:relative;width:auto;">
        <div class="col-xs-3 margin-top-10">
            <ul class="toolbar-nav text-center">
                <li><a href="javascript::"  onclick="show_page(this,'/ueditor/template/1.out.html')">关注</a></li>
                <li><a href="javascript:;"  onclick="show_page(this,'/ueditor/template/2.out.html')">标题</a></li>
                <li><a href="javascript:;"  onclick="show_page(this,'/ueditor/template/3.out.html')">正文</a></li>
                <li><a href="javascript:;"  onclick="show_page(this,'/ueditor/template/4.out.html')">图文</a></li>
                <li><a href="javascript:;"  onclick="show_page(this,'/ueditor/template/5.out.html')">分割线</a></li>
                <li><a href="javascript:;"  onclick="show_page(this,'/ueditor/template/6.out.html')">二维码</a></li>
                <li><a href="javascript:;"  onclick="show_page(this,'/ueditor/template/7.out.html')">阅读原文</a></li>
                <li><a href="javascript:;"  onclick="show_page(this,'/ueditor/template/8.out.html')">分享</a></li>
                <li><a href="javascript:;"  onclick="show_page(this,'/ueditor/template/9.out.html')">互推</a></li>
                <li><a href="javascript:;"  onclick="show_page(this,'/ueditor/template/10.out.html')">节日</a></li>
                <li><a href="javascript:;"  onclick="show_page(this,'/ueditor/template/11.out.html')">自定义</a></li>
            </ul>
            <div class="alert">
                <h4>编辑须知</h4>
                <p>
                    1、如果对于html不熟悉，请不要进行删除或者增加的操作，只做文字修改。
                </p>
                <p>
                    2、每一个item都需要 标记 class='tag-item'，否则无法使用。
                </p>
            </div>
        </div>

        <div class="col-xs-9 toolbar-content" style="overflow:auto;max-height: none;">
            <?php echo form_open("AdminMaterial/saveTemplate"); ?>
            <div >
                <?php if ($error) {echo $error."<br/>";} ?>
                <input type="submit" class="btn btn-default" value="保存" >
            </div>
            <div class="margin-top-10">
                <!-- 加载编辑器的容器 -->
                <script id="container" name="content" type="text/plain"><?=$content?></script>
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
            <div class="margin-top-10">
                <input type="hidden" id="url" name="url" value="<?=url?>" />
                <input type="submit" class="btn btn-default" value="保存" >
            </div>
            </form>
        </div>
</div>
<script>
    function show_page(obj,url) {
        $.get(url + "?" + (+ new Date()),function(data){
            ue.setContent(data);
            $("#url").val(url);
        })
    }
</script>
