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
                alert(data.error.error);
            }
            $("input[name='token_wxvote']").val(data.hash);
            $(".news-current img").attr("src", data.picurl);
            $(".news-current").attr("data-content-id", data.id);
            $(".news-current .title").text(data.title);
            $("#material_id").val(data.material_id);
            $("#media_id").val(data.media_id);
        }
    });
    return false;
});

function addNewsMessage() {
    var str = "<div class=\"row news-item news-sub-item news-current \" onclick=\"editNewsMessage(this)\"> " +
        " <div class=\"col-xs-8 title\">标题</div> " +
        " <div class=\"col-xs-4\"><img src=\"application/views/images/picture.jpg\" class=\"img-responsive\"></div></div> ";
    $(".news-current").after($(str)).removeClass("news-current");
    $("input[name=title]").val('');
    $("input[name=desc]").val('');
    $("input[name=pic]").val('');
    $("input[name=url]").val('');
}

function editNewsMessage(obj) {
    $(".news-current").removeClass("news-current");
    $(obj).addClass("news-current");
}