$(function(){
    if ($("").datetimepicker) {
        $("*[data-toggle=time]").datetimepicker({
            lang: "ch",           //语言选择中文
            format: "Y-m-d H:i",      //格式化日期
            timepicker: true,    //关闭时间选项
            yearStart: 2000,     //设置最小年份
            yearEnd: 2050,        //设置最大年份
            todayButton: false    //关闭选择今天按钮
        });
    }

    $(".default-img").on("error",function(){
        $(this).attr("src",$(this).attr("data-src"));
    });

    $(".config").click(function(){
        var content = $(this).attr("data-content");
        var id = $(this).attr("data-id");
        $.ajax({
            url:"index.php/AdminVoteController/configPage",
            dataType:"json",
            data:{"group":content,"vote_id":id},
            success:function(data) {
                if (data['error'] == null) {
                    $("#sub_content").html(data["content"]);
                }
            }
        });
    });
});