$("input").click(function(){
    if ($("#name").val() && $("#phone").val() && $("#captcha").val() && $("#user_id").val()
        && ($("#file1_path").val() ||  $("#file2_path").val()||  $("#file3_path").val()||  $("#file4_path").val())) {
        $("button[type=submit]").prop("disabled",false);
    } else {
        $("button[type=submit]").prop("disabled",true);
    }
});