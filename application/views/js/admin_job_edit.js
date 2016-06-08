/**
 * Created by ghujk on 2016/6/8.
 */
function del_job (id){
    if (confirm("你确定要删除这条任务吗?")) {
        $.ajax({
            url:"/index.php/AdminJobController/deleteJob/"+id,
            dataType:"json",
            success:function(data) {
                $(".query-result tbody").empty();
                if (data.jobs) {
                    $(data.jobs).each(function(i,d) {
                        var str = "<tr><td>" + d.id +" </td><td>" + d.command + "</td><td><a href=\"javascript:;\" onclick=\"del_job('" + d.id + "')\">删除</a></td></tr>";
                        $(".query-result tbody").append(str);
                    });
                }
            }
        });
    }
}