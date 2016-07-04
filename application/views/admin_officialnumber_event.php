<?php
?>
<link rel="stylesheet" href="application/views/css/admin-keywords-event.css" >
<p><button data-target="#rule_panel" data-toggle="collapse">添加新规则</button></p>
<div class="container-fluid">
    <form class="form-horizontal" role="form" id="resp_form">
        <div id="rule_panel" class="row panel-collapse collapse">
            <div class="form-group">
                <label class="col-sm-2 control-label">类型</label>
                <div class="col-sm-10">
                    <ul class="event-type-list">
                        <li>
                            <label for="subscribe">新用户关注</label>
                            <input type="radio" id="subscribe" name="event" value="subscribe" />
                        </li>
                        <li>
                            <label for="unsubscribe">用户取消关注</label>
                            <input type="radio" id="unsubscribe" name="event" value="unsubscribe" />
                        </li>
                        <li>
                            <label for="location">用户发送地理位置</label>
                            <input type="radio" id="location" name="event" value="location" />
                        </li>
                        <li>
                            <label for="image">用户发送图片</label>
                            <input type="radio" id="image" name="event" value="image" />
                        </li>
                        <li>
                            <label for="voice">用户发送语音</label>
                            <input type="radio" id="voice" name="event" value="voice" />
                        </li>
                        <li>
                            <label for="video">用户发送视频</label>
                            <input type="radio" id="video" name="event" value="video" />
                        </li>
                    </ul>
                </div>
            </div>
            <div class="form-group">
                <label for="keywords" class="col-sm-2 control-label">回复</label>
                <div class="col-sm-10">
                    <ul class="resp-ul">
                        <li id="resp_txt"><a href="#resp_text" data-toggle="modal">文本回复</a></li>
                        <li id="resp_new"><a href="#resp_news" data-toggle="modal">图文回复</a></li>
                        <li id="resp_pro"><a href="#resp_program" data-toggle="modal">程序映射</a></li>
                    </ul>
                </div>
            </div>
            <div class="form-group col-sm-12">
                <input type="hidden" name="type" id="type"/>
                <input type="hidden" name="content" id="content"/>
                <input type="hidden" name="number_id" value="<?=$id?>" />
                <input type="button" class="btn btn-default" value="保存" onclick="saveResponse()"/>
            </div>
        </div>
    </form>
</div>
<div class="modal fade" id="resp_text" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="close" ></div>
            <div class="modal-body" >
                <div>
                    <p>输入回复内容</p>
                    <textarea name="resp" id="resp_content_txt" class="form-control"></textarea>
                </div>
                <div class="margin-top-10">
                    <button class="btn btn-default" onclick="respText()">确定</button>
                </div>
            </div>
        </div><!-- /.modal-content -->
    </div>
</div>

<div class="modal fade" id="resp_news" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="close" ></div>
            <div class="modal-body" >
                <div>
                    <p>选择回复素材</p>
                    <div class="grid clearfix">
                        <?php foreach ($materials['data'] as $m) :?>
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
                <div class="margin-top-10">
                    <button class="btn btn-default" onclick="respNews()">确定</button>
                </div>
            </div>
        </div><!-- /.modal-content -->
    </div>
</div>

<div class="modal fade" id="resp_program" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="close" ></div>
            <div class="modal-body" >
                <div>
                    <p>输入路径:<span style="font-size:small;">如resp/VoteForHandle,需要放在librarie下,继承ResponseHandle.对应的关键字不能写多个,只能诸如TP%这样的形式</span></p>
                    <textarea name="resp" id="resp_content_pro" class="form-control"></textarea>
                </div>
                <div class="margin-top-10">
                    <button class="btn btn-default" onclick="respProgram()">确定</button>
                </div>
            </div>
        </div><!-- /.modal-content -->
    </div>
</div>

<div class="container-fluid" id="resp-tbl">
    <div class="row thead">
        <div class="col-xs-3">
            已有关键字
        </div>
        <div class="col-xs-3">
            回复类型
        </div>
        <div class="col-xs-3">
            回复内容
        </div>
        <div class="col-xs-3">
            操作
        </div>
    </div>
    <div class="row tbody">
        <?php foreach($keywords as $k):?>
            <div class="col-xs-3" data-id="<?=$k['id']?>">
                <?=$k['event']?>
            </div>
            <div class="col-xs-3" data-id="<?=$k['id']?>">
                <?php if ($k['type'] == 0) {echo "文本回复";} elseif ($k["type"] == '1') {echo "图文回复";} elseif ($k['type'] == '2') {echo "程序处理";}?>
            </div>
            <div class="col-xs-3" data-id="<?=$k['id']?>">
                <?=$k['content']?>
            </div>
            <div class="col-xs-3" data-id="<?=$k['id']?>">
                <a href="javascript:;" onclick="removeResp('<?=$k['id']?>',this)">删除</a>
            </div>
        <?php endforeach;?>
    </div>
</div>
<script>
    function respText() {
        var txt = $("#resp_content_txt").val();
        $("#type").val("0");//text
        $("#content").val(txt);
        $("li.checked").removeClass("checked");
        $("i.fa").remove();
        $("#resp_txt").addClass("checked").children("a").before("<i class=\"fa fa-check\"></i>");
        $("#resp_text").modal("hide");
    }

    function saveResponse() {
        $("#resp_form").ajaxSubmit({
            url:'index.php/AdminOfficialNumber/saveResponse',
            type:'get',
            dataType:"json",
            success:function(data) {
                if (data.errcode == "ok") {
                    var type = "文本回复";
                    if(data.type == 1) {
                        type = "图文回复";
                    }else if (data.type == 2) {
                        type = "程序处理";
                    }
                    var str = "<div class=\"col-xs-3\" data-id=\"" + data.id + "\">" + data.keywords + "</div> " +
                        "<div class=\"col-xs-3\" data-id=\"" + data.id + "\">" + type + "</div> " +
                        "<div class=\"col-xs-3\" data-id=\"" + data.id + "\">" + data.content + "</div> " +
                        "<div class=\"col-xs-3\" data-id=\"" + data.id + "\"> " +
                        "<a href=\"javascript:;\" onclick=\"removeResp(" + data.id + ",this)\">删除</a> ";
                    $("#resp-tbl .tbody").append(str);
                } else {
                    alert(data.errinfo);
                }
                $("#rule_panel").collapse("hide");
            }
        });
    }

    function removeResp (id,obj) {
        $.ajax({
            url:"index.php/AdminOfficialNumber/removeKeywords",
            dataType:"json",
            data:{id:id},
            success:function(data) {
                $("div[data-id=" + id + "]").remove();
            }
        });
    }

    function respNews() {
        var data_id = $(".grid-item.checked").attr("data-id");
        $("#type").val("1");//news
        $("#content").val(data_id);
        $("li.checked").removeClass("checked");
        $("i.fa").remove();
        $("#resp_new").addClass("checked").children("a").before("<i class=\"fa fa-check\"></i>");
        $("#resp_news").modal("hide");
    }

    function respProgram() {
        var txt = $("#resp_content_pro").val();
        $("#type").val("2");//program
        $("#content").val(txt);
        $("li.checked").removeClass("checked");
        $("i.fa").remove();
        $("#resp_pro").addClass("checked").children("a").before("<i class=\"fa fa-check\"></i>");
        $("#resp_program").modal("hide");
    }
</script>
