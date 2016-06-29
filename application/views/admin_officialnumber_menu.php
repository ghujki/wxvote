<?php
/**
 * Created by PhpStorm.
 * User: ghujk
 * Date: 2016/4/22
 * Time: 17:56
 */
?>
<link href="application/views/css/admin-menu.css" rel="stylesheet" />
<div class="nav-panel panel-collapse collapse" id="nav-panel">
<nav class="navbar navbar-default super" role="navigation" >
    <div>
        <ul class="nav navbar-nav" >
            <li class="dropdown open">
                  <ul class="dropdown-menu one" id="one">
                    <li><span>jmeter</span><i class="nav-item fa fa-edit" title="编辑" /><i class="nav-item fa fa-times" title="删除"/></li>
                    <li><span>jmeter</span><i class="nav-item fa fa-edit" title="编辑" /><i class="nav-item fa fa-times" title="删除"/></li>
                    <li><span>jmeter</span><i class="nav-item fa fa-edit" title="编辑" /><i class="nav-item fa fa-times" title="删除"/></li>
                </ul>
                <a class="dropdown-toggle"  aria-expanded="true">SVN
                    <i class="nav-item fa fa-edit" title="编辑" /><i class="nav-item fa fa-times" title="删除"/>
                 </a>
            </li>
            <li class="dropdown open">
                <a class="dropdown-toggle"  aria-expanded="true">SVN
                    <i class="nav-item fa fa-edit" title="编辑" /><i class="nav-item fa fa-times" title="删除"/>
                </a>
            </li>
            <li class="dropdown open">
                <A class="dropdown-toggle"  aria-expanded="true">
                    Java
                    <i class="nav-item fa fa-edit" title="编辑" /><i class="nav-item fa fa-times" title="删除"/>
                </A>
                <ul class="dropdown-menu one">
                    <li><span>jmeterddddd</span><i class="nav-item fa fa-edit" title="编辑" /><i class="nav-item fa fa-times" title="删除"/></li>
                    <li><span>jmeter121</span><i class="nav-item fa fa-edit" title="编辑" /><i class="nav-item fa fa-times" title="删除"/></li>
                    <li><span>jmeter22</span><i class="nav-item fa fa-edit" title="编辑" /><i class="nav-item fa fa-times" title="删除"/></li>
                    <li><span>jmeter121</span><i class="nav-item fa fa-edit" title="编辑" /><i class="nav-item fa fa-times" title="删除"/></li>
                    <li><span>jmeter22</span><i class="nav-item fa fa-edit" title="编辑" /><i class="nav-item fa fa-times" title="删除"/></li>
                </ul>
            </li>
        </ul>
    </div>
</nav>
</div>

<div class="form-group">
    <textarea name="menu-code" id="menu-code" class="form-control" rows="10"><?=$menustr?></textarea>
</div>
<button class="btn btn-default" onclick="format()">格式化</button>
<button class="btn btn-default" data-toggle="collapse" data-parent="#accordion" href="#nav-panel" onclick="preview()">预览</button>
<button class="btn btn-default" onclick="updateWxMenu('<?=$id?>')">更新微信菜单</button>
<script>
    function format () {
        var str = $("#menu-code").val();
        $("#menu-code").val(formatJson(str));
    }
    function preview() {
        var str = $("#menu-code").val();
        var obj = JSON.parse(str);
        $(".navbar-nav").empty();
        for(var i = 0 ;i < obj.button.length;i++) {
            var btn = obj.button[i];
            var li = "<li class=\"dropdown open\"> " +
                     " <a class=\"dropdown-toggle\"  aria-expanded=\"true\">" + btn.name +
                     " </a> " ;
            if (btn.sub_button && btn.sub_button.length > 0) {
                 li += "<ul class=\"dropdown-menu one\">";
                 for (var j = 0; j < btn.sub_button.length; j++) {
                     var sub = btn.sub_button[j];
                     li += "<li><span>" + sub.name + "</span></li>";
                 }
                li += "</ul>";
            }
            li += "</li>";
            $(".navbar-nav").append($(li));
        }
    }

    function updateWxMenu(id) {
        var str = $("#menu-code").val();
        $.ajax({
            url:"index.php/AdminOfficialNumber/ajaxUpdateMenu",
            dataType:"json",
            data:{'id':id,'menu_str':str},
            success:function(data) {
                if (data.errcode == 0) {
                    alert('更新成功，一段时间后微信将更新菜单，或者重新关注公众号可立刻更新菜单');
                } else {
                    alert(data.errmsg);
                }
            },
            failure:function(e) {
                alert(e);
            },
            error:function (e) {
                alert(e);
            }
        });
    }
</script>