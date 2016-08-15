<?php
/**
 * Created by PhpStorm.
 * User: ghujk
 * Date: 2016/4/22
 * Time: 17:56
 */
?>
<link href="application/views/css/admin-menu.css" rel="stylesheet" />
<div ng-app="navApp" ng-controller="navController">
<div class="nav-panel " id="nav-panel" >
<nav class="navbar navbar-default super" role="navigation" >
    <div class="menu-edit-panel">
        <form class="form-horizontal">
            <h4>更新菜单</h4>
            <div class="form-group">
                <label class="col-xs-4 control-label">菜单名称</label>
                <div class="col-xs-8"><input type="text" class="form-control" name="menu-name" id="menu_name"></div>
            </div>
            <div class="form-group">
                <label class="col-xs-4 control-label">类型</label>
                <div class="col-xs-8"><select class="form-control" name="menu-type" id="menu_type">
                    <option value="view">链接</option>
                    <option value="click">事件</option>
                </select>
                </div>
            </div>
            <div class="form-group">
                <label class="col-xs-4 control-label">地址或键值</label>
                <div class="col-xs-8"><input type="text" class="form-control" name="menu-value" id="menu_value"></div>
            </div>
            <div class="form-group">
                <input type="hidden" name="menu-parent" id="menu_parent" />
                <input type="button" class="btn" value="确认" onclick="update_menu()">
            </div>
        </form>
    </div>
    <div>
        <ul class="nav navbar-nav" >

            <li class="dropdown open" >
                <div class="dropdown-toggle" onclick="focus_item(this)">{{ item.name }}
                    <a href="javascript:;" onclick="remove_item(this)" class="fa fa-minus item-op"></a>
                </div>
                <ul class="dropdown-menu one" >
                    <li class="text-center" ><a href="javascript:;" class="fa fa-plus" onclick="new_item(this)"></a></li>
                    <li class="sub-menu-item" onclick="focus_item(this)"><span>{{ sub_button.name }}</span>
                        <a href="javascript:;" class="fa fa-minus item-op" onclick="remove_item(this)"></a>
                    </li>
                </ul>
            </li>
            <li class='dropdown open' >
                <div class='dropdown-toggle' onclick='focus_item(this)' ><a href='javascript:;' class='fa fa-plus'></a></div>
            </li>
        </ul>
    </div>
</nav>
</div>
<div class="form-group" id="menu-code-div" style="display:none">
    <textarea name="menu-code" id="menu-code" class="form-control" rows="10"><?=$menustr?></textarea>
</div>
<button class="btn btn-default" onclick="format()">格式化</button>
<button class="btn btn-default" onclick="preview()">预览</button>
<button class="btn btn-default" onclick="updateWxMenu('<?=$id?>')">更新微信菜单</button>
</div>

<script>
    preview();
    var menus = JSON.parse('<?=$menustr?>');
    if (menus == null) {
        menus = {};
        menus.button = [];
    }
    var target = null;
    function format () {
        var str = $("#menu-code").val();
        $("#menu-code").val(formatJson(str));
        $("#menu-code-div").toggle();
    }

    function updateWxMenu(id) {
        if (window.confirm("本次操作将直接更新微信菜单,是否继续?")) {
            var str = $("#menu-code").val();
            $.ajax({
                url: "index.php/AdminOfficialNumber/ajaxUpdateMenu",
                dataType: "json",
                data: {'id': id, 'menu_str': str},
                success: function (data) {
                    if (data.errcode == 0) {
                        alert('更新成功，一段时间后微信将更新菜单，或者重新关注公众号可立刻更新菜单');
                    } else {
                        alert(data.errmsg);
                    }
                },
                failure: function (e) {
                    alert(e);
                },
                error: function (e) {
                    alert(e);
                }
            });
        }
    }

    function preview() {
        str_to_menu($("#menu-code").val());
    }

    function str_to_menu (str) {
        menus = JSON.parse(str);
        if (menus == null ) {
            menus = {};
            menus.button = [];
        }
        var navbar = $(".nav.navbar-nav").empty();
        for (var id = 0;id < menus.button.length ;id ++) {
            var menu = menus.button[id];
            var menu_dom = $("<li class='dropdown open' ><div class='dropdown-toggle' onclick='focus_item(this)'>" + menu.name
                + "<a href='javascript:;' onclick='remove_item(this)' class='fa fa-minus item-op'></a></div></li>");
            navbar.append(menu_dom);

            var sub_dom = $("<ul class='dropdown-menu one' ></ul>");

            if (menu.sub_button == null || menu.sub_button.length < 5) {
                $(sub_dom).append("<li class='text-center' ><a href='javascript:;' class='fa fa-plus' onclick='new_item(this)'></a></li>");
            }
            $(menu_dom).append(sub_dom);
            if (menu.sub_button != null && menu.sub_button.length > 0) {
                for (var i = 0;i< menu.sub_button.length;i++) {
                    var sub_button = menu.sub_button[i];
                    var sub_button_dom = $("<li class='sub-menu-item' onclick='focus_item(this)'><span>" + sub_button.name
                        + "</span><a href='javascript:;' class='fa fa-minus item-op' onclick='remove_item(this)'></a> </li>");
                    $(sub_dom).append(sub_button_dom);
                }
            }
        }

        if (menus.button.length < 3 ) {
            navbar.append($("<li class='dropdown open' > <div class='dropdown-toggle' onclick='focus_item(this)'>" +
                "<a href='javascript:;' class='fa fa-plus' onclick='new_item(this)'></a></div></li>"));
        }
    }

    function focus_item (obj) {
        $(".nav.navbar-nav").find(".current").removeClass("current");
        $(obj).addClass("current");

        var sub_index = find_sub_index();
        var root_index = find_root_index();
        var item = null;
        if (sub_index < 0) {
            item = menus.button[root_index];
        } else {
            item = menus.button[root_index].sub_button[sub_index];
        }
        if (item != null) {
            $("#menu_name").val(item.name);
            $("#menu_type").val(item.type);
            $("#menu_value").val(item.key ? item.key : item.url);
        }
        target = obj;
        $(".menu-edit-panel").show();
    }

    function new_item(obj) {
        $(".menu-edit-panel").show();
        $(".menu-edit-panel input[name]").val("");
        $(".nav.navbar-nav").find(".current").removeClass("current");
        $(obj).parent().addClass("current");
        target = obj;
    }

    function update_menu() {
        var name = $("#menu_name").val();
        var type = $("#menu_type").val();
        var value = $("#menu_value").val();

        var root_index = find_root_index();
        var sub_index = find_sub_index();
        var item = build_menu_item(name,type,value)
        if (sub_index >= 0) {
            menus.button[root_index].sub_button[sub_index] = item;
        } else if (sub_index == -1) {
            if (menus.button[root_index].sub_button == null) {
                menus.button[root_index].sub_button = [];
            }
            menus.button[root_index].sub_button.splice(0,0,item);
        } else if (sub_index == -2) {
            if (root_index == menus.button.length) {
                menus.button[root_index] = item;
            } else {
                menus.button[root_index].name = name;
                menus.button[root_index].type = type;
                if (type == 'view') {
                    menus.button[root_index].url = value;
                } else {
                    menus.button[root_index].key = value;
                }
            }
        }

        var jsonstr = JSON.stringify(menus)
        $("#menu-code").val(jsonstr);
        str_to_menu(jsonstr);
    }

    function build_menu_item (name,type,value) {
        if (type == 'view') {
            return {"name":name,"type":type,"url":value};
        } else {
            return {"name":name,"type":type,"key":value};
        }
    }

    function find_root_index() {
        return $(".navbar-nav").children().index($(".current").parents("li"));
    }

    function find_sub_index() {
        return $(".current").parents(".dropdown-menu").children().index($(".current")) - 1;
    }

    function remove_item (obj) {
        var root_index = find_root_index();
        var sub_index = find_sub_index();
        if (sub_index >= 0) {
            menus.button[root_index].sub_button.remove(sub_index);
        } else {
            menus.button.remove(root_index);
        }
        var jsonstr = JSON.stringify(menus)
        $("#menu-code").val(jsonstr);
        str_to_menu(jsonstr);
    }

    Array.prototype.remove=function(dx)
    {
        if(isNaN(dx)||dx>this.length){return false;}
        for(var i=0,n=0;i<this.length;i++)
        {
            if(this[i]!=this[dx])
            {
                this[n++]=this[i]
            }
        }
        this.length-=1
    }
</script>