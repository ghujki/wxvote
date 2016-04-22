$(function(){
    $(".panel-body a").click(function() {
        var a = this;
        var id = $(a).attr('data-id');
        $.ajax({
            url:"AdminOfficialNumber/ajaxMenuPage",
            type:"get",
            dataType:"text",
            data:{page:$(a).attr("data-src"),id:id},
            success:function(data) {
                $($(a).attr("target")).html(data);
            }
        });
    });
});