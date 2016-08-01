/**
 * Created by ghujk on 2016/4/25.
 */
$(function() {
    window.setTimeout(function(){
        $('.grid').masonry({
            // options
            itemSelector: '.grid-item',
            gutter:10
        });
    },200);

    $("#number_id").tinyselect();
});



$("#number_id").change(function() {
    window.location.href = "index.php/AdminMaterial/index?id="+ $(this).val();
});

function syncNewsMessages() {
    var nid = $("#number_id").val();
    var mid = $(".grid-item.checked").attr("data-id");
    if (mid == null || mid == '') {
        alert('请选择一条图文');
        return;
        if (!confirm("该操作将同步微信服务器上的所有图文消息到平台,可能会造成服务器一段时间的卡顿,是否继续?")) {
            return;
        }
    }
    $.ajax({
            url: 'index.php/AdminMaterial/ajaxSync',
            dataType: 'json',
            data: {"number_id": nid,"media_id":mid},
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
            url:"index.php/RunJobController/post",
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

function cp_to() {
    var number_id = $(".modal-user-list input:checked").val();
    var media_id = $('#material_container .grid-item.checked').attr('data-id');
    $.ajax({
        url:"index.php/AdminMaterial/copy_to",
        dataType:"json",
        type:"get",
        data:{"number_id":number_id,"media_id":media_id},
        success:function(data) {
            window.location.href = "/index.php/AdminMaterial/index?id="+ number_id;
        }
    });
}

$("#material_container .grid-item").click(function(){
    $("#material_container .grid-item").removeClass("checked");
    $(this).addClass("checked");
    $("#job_btn").prop("disabled",false);
    $("#copy_btn").prop("disabled",false);
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