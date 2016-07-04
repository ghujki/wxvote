/**
 * Created by ghujk on 2016/4/25.
 */

$("#materialForm").submit(function() {
    $("#materialForm").ajaxSubmit({
        url: "index.php/AdminMaterial/doEdit",
        type: "post",
        dataType: "json",
        success: function (data) {
            if (data.error) {
                $("input[name='token_wxvote']").val(data.hash);
                alert(data.error);
                return;
            }
            $("input[name='token_wxvote']").val(data.hash);
            $(".news-current img").attr("src", data.picurl);
            $(".news-current").attr("data-content-id", data.id);
            $(".news-current .title").text(data.title);
            $("#material_id").val(data.id);
            $("#media_id").val(data.media_id);
            $("#sort").val(data.sort);
            newslist.push(data);
        },
        error:function(e) {
            alert(e.responseText);
        }
    });
    return false;
});

function preview_mt (obj) {
    if ($("#material_id").val() == "") {
        alert("请先提交再预览");
        return false;
    } else {
        $(obj).attr("href","index.php/AdminMaterial/preview/" + $("#material_id").val());
    }

    if ($("input[name=url]").val() != "") {
        $(obj).attr("href",$("input[name=url]").val());
    }
}


function addNewsMessage() {
    var str = "<div class=\"row news-item news-sub-item news-current \" onclick=\"editNewsMessage(this)\"> " +
        " <div class=\"title\">标题</div> " +
        " <img src=\"application/views/images/picture.jpg\" class=\"img-responsive\"></div> ";
    $(".news-current").after($(str)).removeClass("news-current");
    $("input[name=title]").val('');
    $("input[name=desc]").val('');
    $("input[name=pic]").val('');
    $("input[name=url]").val('');
    $("#check_url").prop("checked",true);
    $("#material_id").val('');
    ue.setContent('');
    $("input[name=content_source_url]").val("");
    $("#sort").val(parseFloat($("#sort").val()) + 1);
}

function removeNewsMessage() {
    var id = $(".news-current").attr("data-content-id");
    if (id > 0) {
        if (!confirm("确定要删除这条消息吗?")) {
            return ;
        }
    }

    $.ajax({
        url:"index.php/AdminMaterial/remove",
        type:"get",
        dataType:"json",
        data:{material_id:id},
        success:function(data) {
            if (data.error) {
                alert(data.error);
                return;
            } else {
                var obj = $(".news-current");
                try {
                    editNewsMessage($(".news-current").next());
                } catch (e) {
                    console.log(e);
                }
                $(obj).remove();
            }
        }
    });
}

function getNews (id) {
    if (newslist) {
        for (var i = 0;i < newslist.length;i++) {
            var o = newslist[i];
            if (o != null && o.id == id) {
                return o;
            }
        }
    }
    return null;
}

function upNewsMessage () {
    var dom =  $(".news-current");
    if ($(dom).attr("data-content-id") == "" || $(dom).attr("data-content-id") == null) {
        alert("保存后才可以移动");
        return;
    }
    var obj = getNews($(dom).attr('data-content-id'));
    if (newslist) {
        var i = newslist.indexOf(obj);
        if (i == 1) {
            obj.sort = parseFloat(newslist[0].sort) / 2;
            var first = $(dom).prev();
            $(first).insertAfter(dom);
            $(first).removeClass("news-cover").addClass("news-sub-item");
            $(dom).removeClass("news-sub-item").addClass("news-cover");
            newslist = newslist.sort(function(a,b){
                return a.sort - b.sort;
            });
        } else if (i > 1 && i < newslist.length ) {
            var prev = $(dom).prev();
            var prevObj = newslist[i-1];
            var prevObj2 = newslist[i-2];
            obj.sort = ( parseFloat(prevObj.sort) + parseFloat(prevObj2.sort)) / 2 ;
            $(prev).insertAfter(dom);
            newslist = newslist.sort(function(a,b){
                return a.sort - b.sort;
            });
        }
        $.ajax({
            url:"index.php/AdminMaterial/updateSort",
            type:"get",
            dataType:"json",
            data:{id:obj.id,sort:obj.sort},
            success:function(data) {
                if (data.error) {
                    alert(data.error);
                }
            }
        });
    }
}

function downNewsMessage () {
    var dom =  $(".news-current");
    if ($(dom).attr("data-content-id") == "" || $(dom).attr("data-content-id") == null) {
        alert("保存后才可以移动");
        return;
    }
    var obj = getNews($(dom).attr('data-content-id'));
    if (newslist) {
        var i = newslist.indexOf(obj);
        if (i == newslist.length - 2) {
            obj.sort = parseFloat(newslist[ i + 1 ].sort) + 1;
            var last = $(dom).next();
            $(dom).insertAfter(last);
            newslist = newslist.sort(function(a,b){
                return a.sort - b.sort;
            });
            if (i == 0 && newslist.length > 1) {
                $(dom).removeClass("news-cover").addClass("news-sub-item");
                $(last).removeClass("news-sub-item").addClass("news-cover");
            }
        } else if (i < newslist.length - 2) {
            var next = $(dom).next();
            var nextObj = newslist[i + 1];
            var nextObj2 = newslist[i + 2];
            obj.sort = ( parseFloat(nextObj.sort) + parseFloat(nextObj2.sort)) / 2 ;
            $(dom).insertAfter(next);
            newslist = newslist.sort(function(a,b){
                return a.sort - b.sort;
            });

            if (i == 0 && newslist.length > 1) {
                $(dom).removeClass("news-cover").addClass("news-sub-item");
                $(next).removeClass("news-sub-item").addClass("news-cover");
            }
        }

        $.ajax({
            url:"index.php/AdminMaterial/updateSort",
            type:"get",
            dataType:"json",
            data:{id:obj.id,sort:obj.sort},
            success:function(data) {
                if (data.error) {
                    alert(data.error);
                }
            }
        });
    }
}


function editNewsMessage(obj) {
    var lid = $(".news-current").attr("data-content-id");
    var id  = $(obj).attr("data-content-id");
    if ((lid == null || lid == '') && lid != id && id != '' && id != null) {
        if (!confirm("上一条编辑的内容尚未保存,确定放弃吗?")) {
            return ;
        };
    }
    $(".news-current").removeClass("news-current");
    $(obj).addClass("news-current");

    if (newslist) {
        var length = newslist.length;
        var matched = false;
        for (var i = 0;i < length;i++)  {
            var o = newslist[i];
            if (o != null && o.id == id) {
                $("input[name=title]").val(o.title);
                $("input[name=desc]").val(o.desc);
                $("input[name=url]").val(o.url);
                $("input[name=content_source_url]").val(o.content_source_url);
                $("#material_id").val(o.id);
                $("#sort").val(o.sort);
                ue.setContent(o.content);
                matched = true;
                break;
            }
        }

        if (!matched) {
            $("input[name=title]").val('');
            $("input[name=desc]").val('');
            $("input[name=pic]").val('');
            $("input[name=url]").val('');
            $("#material_id").val('');
            ue.setContent('');
            $("input[name=content_source_url]").val("");
            $("#sort").val(parseFloat($("#sort").val()) + 1);
        }
    }
}


