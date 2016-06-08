/**
 * Created by ghujk on 2016/4/25.
 */
$('.grid').masonry({
    // options
    itemSelector: '.grid-item',
    gutter:10
});


$("#number_id").change(function() {
    window.location.href = "index.php/AdminMaterial/index?id="+ $(this).val();
});

function syncNewsMessages() {

    var nid = $("#number_id").val();
    $.ajax({
            url: 'index.php/AdminMaterial/ajaxSync',
            dataType: 'json',
            data: {"number_id": nid},
            success: function (data) {
                if (data.errcode) {
                    alert(data.errmsg);
                } else {
                    window.location.href = window.location.href;
                }
            }
        }
    );
}

function editNewsMessage(nid,id) {
    window.location.href = "index.php/AdminMaterial/edit?number_id=" + nid + "&media_id=" + id;
}

function deleteMessage(id) {
    if (confirm('确认要删除这条消息吗?')) {
        $.ajax({
            url:"index.php/AdminMaterial/ajaxRemove",
            dataType:"json",
            data:{media_id:id},
            success:function(data) {
                $('#material_container .grid-item.checked').remove();
            },
            error:function(data) {
                alert(data.responseText);
            }
        });
    }
}

function addNewsMessages() {
    var nid = $("#number_id").val();
    window.location.href = "index.php/AdminMaterial/add?number_id=" + nid;
}

function postNewsMessages(nid,id) {
    if (window.confirm("确定要推送这一条图文吗?")) {
        $.ajax({
            url:"index.php/AdminMaterial/post",
            dataType:"json",
            type:"get",
            data:{"number_id":nid,"media_id":id},
            success:function(data) {
                if (data.errcode != 0) {
                    alert(data.errmsg);
                } else {
                    alert("消息推送成功");
                }
            }
        });
    }
}

function postNewsJob () {
    var number_id = $("#number_id").val();
    var media_id = $('#material_container .grid-item.checked').attr('data-id');
    var time = $("#job_time").val();
    $.ajax({
        url:"index.php/AdminMaterial/addJob",
        dataType:"json",
        type:"get",
        data:{"number_id":number_id,"media_id":media_id,"time":time},
        success:function(data) {
            $("#jobModal").modal("hide");
        }
    });
}

$("#material_container .grid-item").click(function(){
    $("#material_container .grid-item").removeClass("checked");
    $(this).addClass("checked");
    $("#job_btn").prop("disabled",false);
});

$(function(){
    if ($("").datetimepicker) {
        $("*[data-toggle=microtime]").datetimepicker({
            lang:"ch",
            format: "Y-m-d H:i:s",
            minView:"second"
        });
    }
});