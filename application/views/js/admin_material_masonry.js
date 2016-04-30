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

function addNewsMessages() {
    var nid = $("#number_id").val();
    window.location.href = "index.php/AdminMaterial/add?number_id=" + nid;
}

