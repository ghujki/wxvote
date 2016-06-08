/**
 * Created by ghujk on 2016/6/6.
 */
var clipArea = new bjj.PhotoClip("#clipArea", {
    size: [260, 260],
    outputSize: [240, 240],
    file: "#headimage",
    view: "#view",
    ok: "#clipBtn",
    loadStart: function() {
        //console.log("照片读取中");
        $('#modal_exam').modal();
    },
    loadComplete: function() {
        //console.log("照片读取完成");
    },
    clipFinish: function(dataURL) {
        //console.log(dataURL);
        var data = dataURL.split(',')[1];
        // data = window.atob(data);
        // var ia = new Uint8Array(data.length);
        // for (var i = 0; i < data.length; i++) {
        //     ia[i] = data.charCodeAt(i);
        // };
        // var blob = new Blob([ia], {type:"image/png"});
        $("#file").val(data);
    }
});