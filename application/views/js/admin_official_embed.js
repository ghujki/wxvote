$(function(){
    $(".panel-body a").click(function() {
        var a = this;
        if ($("").datetimepicker) {
            $("*[data-toggle=time]").datetimepicker('destroy');
        }
        var id = $(a).attr('data-id');
        var src = $(a).attr("data-src");
        if (src) {
            $.ajax({
                url: src,
                type: "get",
                dataType: "text",
                data: { id: id},
                success: function (data) {
                    $($(a).attr("target")).html(data);
                    $("#resp_news .grid-item").click(function() {
                        $("#resp_news .grid-item").removeClass("checked");
                        $(this).addClass("checked");
                    });


                    $("ul.event-type-list li input").change(function() {
                        $("ul.event-type-list li").removeClass("checked");
                        $(this).parents("li").addClass("checked");
                    });

                    if ($("").datetimepicker) {
                        $("*[data-toggle=time]").datetimepicker({
                            lang: "ch",           //语言选择中文
                            format: "Y-m-d H:i",      //格式化日期
                            timepicker: true,    //关闭时间选项
                            yearStart: 2000,     //设置最小年份
                            yearEnd: 2050,        //设置最大年份
                            todayButton: true    //关闭选择今天按钮
                        });
                    }

                    window.setTimeout(function(){
                        $(".wxuser-list").masonry({
                            itemSelector : '.wxuser_item'
                        });
                    },500);
                }
            });
        }
    });



});

function repeat(s, count) {
    return new Array(count + 1).join(s);
}

function formatJson(json) {
    var i           = 0,
        len          = 0,
        tab         = "    ",
        targetJson     = "",
        indentLevel = 0,
        inString    = false,
        currentChar = null;


    for (i = 0, len = json.length; i < len; i += 1) {
        currentChar = json.charAt(i);

        switch (currentChar) {
            case '{':
            case '[':
                if (!inString) {
                    targetJson += currentChar + "\n" + repeat(tab, indentLevel + 1);
                    indentLevel += 1;
                } else {
                    targetJson += currentChar;
                }
                break;
            case '}':
            case ']':
                if (!inString) {
                    indentLevel -= 1;
                    targetJson += "\n" + repeat(tab, indentLevel) + currentChar;
                } else {
                    targetJson += currentChar;
                }
                break;
            case ',':
                if (!inString) {
                    targetJson += ",\n" + repeat(tab, indentLevel);
                } else {
                    targetJson += currentChar;
                }
                break;
            case ':':
                if (!inString) {
                    targetJson += ": ";
                } else {
                    targetJson += currentChar;
                }
                break;
            case ' ':
            case "\n":
            case "\t":
                if (inString) {
                    targetJson += currentChar;
                }
                break;
            case '"':
                if (i > 0 && json.charAt(i - 1) !== '\\') {
                    inString = !inString;
                }
                targetJson += currentChar;
                break;
            default:
                targetJson += currentChar;
                break;
        }
    }
    return targetJson;
}

function syncMembers(id,obj) {
    if (confirm("该操作会将微信服务器上的用户同步到本平台,数据量大时会造成服务器几分钟的卡顿,确认要继续操作吗?")) {
        $.ajax({
            url: 'index.php/AdminOfficialNumber/ajaxSyncMember',
            dataType: 'json',
            data: {id: id},
            success: function (data) {
                if (data['errinfo']) {
                    alert(data['errinfo']);
                } else {
                    $(obj).parent().children("span").text(data);
                }
            }
        });
    }
}