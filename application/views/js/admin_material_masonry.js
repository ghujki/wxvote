/**
 * Created by ghujk on 2016/4/25.
 */
$('.grid').masonry({
    // options
    itemSelector: '.grid-item',
    gutter:10
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
                }
            }
        }
    );
}

function addNewsMessages() {
    var nid = $("#number_id").val();
    window.location.href = "index.php/AdminMaterial/add?number_id=" + nid;
}

