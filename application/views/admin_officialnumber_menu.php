<?php
/**
 * Created by PhpStorm.
 * User: ghujk
 * Date: 2016/4/22
 * Time: 17:56
 */
?>
<div class="nav-panel panel-collapse collapse" id="nav-panel">
<!--<button class="btn btn-default">增加菜单</button>-->
<!--<div class="nav-op-panel">-->
<!--    <select>-->
<!--        <option>一级菜单</option>-->
<!--        <option>二级菜单</option>-->
<!--    </select>-->
<!--    <input type="radio" name="type" /><label>菜单</label>-->
<!--</div>-->
<nav class="navbar navbar-default" role="navigation">
    <div>
        <ul class="nav navbar-nav">
            <li class="dropdown open">
                <a class="dropdown-toggle"  aria-expanded="true">SVN
                    <i class="nav-item fa fa-edit" title="编辑" /><i class="nav-item fa fa-times" title="删除"/>
                </a>
                <ul class="dropdown-menu">
                    <li><span>jmeter</span><i class="nav-item fa fa-edit" title="编辑" /><i class="nav-item fa fa-times" title="删除"/></li>
                    <li><span>jmeter</span><i class="nav-item fa fa-edit" title="编辑" /><i class="nav-item fa fa-times" title="删除"/></li>
                    <li><span>jmeter</span><i class="nav-item fa fa-edit" title="编辑" /><i class="nav-item fa fa-times" title="删除"/></li>
                </ul>
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
                <ul class="dropdown-menu">
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
<style>
    .nav-panel {
        padding-bottom:160px;
    }
    .navbar {
        margin-top:10px;
    }
    .navbar-nav>li {
        width:160px;
        border-left:1px solid #ccc;
        border-right:1px solid #ccc;
    }
    .nav-item {
        float:right;
        display:none;
        padding-right:10px;
        cursor:pointer;
        line-height: 1.42857143;
    }

    .navbar-nav>li span{
        padding: 3px 20px;
        clear: both;
        font-weight: 400;
        line-height: 1.42857143;
        color: #333;
        white-space: nowrap;
    }
    .dropdown-menu>li {
        padding:0.5em 0em;
    }
    .dropdown-menu>li:hover {
        background-color:#00a0e9;
    }
    .dropdown-menu>li:hover i {
        display:block;
    }
    .dropdown-toggle:hover i {
        display:block;
    }
</style>

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
                 li += "<ul class=\"dropdown-menu\">"
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