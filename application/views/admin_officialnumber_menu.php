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
                        <input type="button" class="btn" value="确认" onclick="update_menu('<?=$id?>')">
                    </div>
                </form>
            </div>
            <div>
                <ul class="nav navbar-nav" >
                    <li class="dropdown open" >
                        <div class="dropdown-toggle" onclick="focus_item('<?=$id?>',this)">{{ item.name }}
                            <a href="javascript:;" onclick="remove_item('<?=$id?>',this)" class="fa fa-minus item-op"></a>
                        </div>
                        <ul class="dropdown-menu one" >
                            <li class="text-center" ><a href="javascript:;" class="fa fa-plus" onclick="new_item('<?=$id?>',this)"></a></li>
                            <li class="sub-menu-item" onclick="focus_item('<?=$id?>',this)"><span>{{ sub_button.name }}</span>
                                <a href="javascript:;" class="fa fa-minus item-op" onclick="remove_item('<?=$id?>',this)"></a>
                            </li>
                        </ul>
                    </li>
                    <li class='dropdown open' >
                        <div class='dropdown-toggle' onclick="focus_item('<?=$id?>',this)" >
                            <a href='javascript:;' class='fa fa-plus'></a>
                        </div>
                    </li>
                </ul>
            </div>
        </nav>
    </div>
    <div class="form-group" id="menu-code-div" style="display:none">
        <textarea name="menu-code" id="menu-code" class="form-control" rows="10"><?=$menustr?></textarea>
    </div>
    <button class="btn btn-default" onclick="format('<?=$id?>')">格式化</button>
    <button class="btn btn-default" onclick="preview('<?=$id?>')">预览</button>
    <button class="btn btn-default" onclick="updateWxMenu('<?=$id?>')">更新微信菜单</button>
</div>

<script>
    if (window.menus == null) {
        window.menus = [];
    }
    menus[<?=$id?>] = JSON.parse('<?=$menustr?>');
    console.log(menus);
    preview(<?=$id?>);
    console.log(menus);
    var target = null;
    function format (id) {
        var str = $("#collapse" + id +" #menu-code").val();
        $("#collapse" + id + " #menu-code").val(formatJson(str));
        $("#collapse" + id + " #menu-code-div").toggle();
    }

    function updateWxMenu(id) {
        if (window.confirm("本次操作将直接更新微信菜单,是否继续?")) {
            var str = $("#collapse" + id + " #menu-code").val();
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

    function preview(id) {
        str_to_menu(id,'<?=$menustr?>');
    }

    function str_to_menu (id,str) {
        menus[id] = JSON.parse(str);

        var this_menu = menus[id];

        if (this_menu == null) {
            this_menu = {};
            this_menu.button = [];
        }
        var navbar = $("#collapse<?=$id?> .nav.navbar-nav").empty();
        for (var i = 0;i < this_menu.button.length ;i ++) {
            var menu = this_menu.button[i];
            var menu_dom = $("<li class='dropdown open' ><div class='dropdown-toggle' onclick='focus_item(" + id + ",this)'>" + menu.name
                + "<a href='javascript:;' onclick='remove_item(" + id + ",this)' class='fa fa-minus item-op'></a></div></li>");
            navbar.append(menu_dom);

            var sub_dom = $("<ul class='dropdown-menu one' ></ul>");

            if (menu.sub_button == null || menu.sub_button.length < 5) {
                $(sub_dom).append("<li class='text-center' ><a href='javascript:;' class='fa fa-plus' onclick='new_item(" + id + ",this)'></a></li>");
            }
            $(menu_dom).append(sub_dom);
            if (menu.sub_button != null && menu.sub_button.length > 0) {
                for (var j = 0;j< menu.sub_button.length;j++) {
                    var sub_button = menu.sub_button[j];
                    var sub_button_dom = $("<li class='sub-menu-item' onclick='focus_item(" + id + ",this)'><span>" + sub_button.name
                        + "</span><a href='javascript:;' class='fa fa-minus item-op' onclick='remove_item(" + id + ",this)'></a> </li>");
                    $(sub_dom).append(sub_button_dom);
                }
            }
        }

        if (this_menu.button.length < 3 ) {
            navbar.append($("<li class='dropdown open' > <div class='dropdown-toggle' onclick='focus_item(" + id + ",this)'>" +
                "<a href='javascript:;' class='fa fa-plus' onclick='new_item(" + id + ",this)'></a></div></li>"));
        }
    }

    function focus_item (id,obj) {
        $("#collapse" + id + " .nav.navbar-nav").find(".current").removeClass("current");
        $(obj).addClass("current");

        var sub_index = find_sub_index(id);
        var root_index = find_root_index(id);
        var item = null;
        if (sub_index < 0) {
            item = menus[id].button[root_index];
        } else {
            item = menus[id].button[root_index].sub_button[sub_index];
        }
        if (item != null) {
            $("#collapse" + id + " #menu_name").val(item.name);
            $("#collapse" + id + " #menu_type").val(item.type);
            $("#collapse" + id + " #menu_value").val(item.key ? item.key : item.url);
        }
        target = obj;
        $("#collapse" + id + " .menu-edit-panel").show();
    }

    function new_item(id,obj) {
        $("#collapse" + id + " .menu-edit-panel").show();
        $("#collapse" + id +  " .menu-edit-panel input[name]").val("");
        $("#collapse" + id + " .nav.navbar-nav").find(".current").removeClass("current");
        $(obj).parent().addClass("current");
        target = obj;
    }

    function update_menu(id) {
        var name = $("#menu_name").val();
        var type = $("#menu_type").val();
        var value = $("#menu_value").val();

        var root_index = find_root_index(id);
        var sub_index = find_sub_index(id);
        var item = build_menu_item(name,type,value);
        if (sub_index >= 0) {
            menus[id].button[root_index].sub_button[sub_index] = item;
        } else if (sub_index == -1) {
            if (menus[id].button[root_index].sub_button == null) {
                menus[id].button[root_index].sub_button = [];
            }
            menus.button[root_index].sub_button.splice(0,0,item);
        } else if (sub_index == -2) {
            if (root_index == menus[id].button.length) {
                menus[id].button[root_index] = item;
            } else {
                menus[id].button[root_index].name = name;
                menus[id].button[root_index].type = type;
                if (type == 'view') {
                    menus[id].button[root_index].url = value;
                } else {
                    menus[id].button[root_index].key = value;
                }
            }
        }

        var jsonstr = JSON.stringify(menus[id]);
        $("#collapse" + id + " #menu-code").val(jsonstr);
        str_to_menu(id,jsonstr);
    }

    function build_menu_item (name,type,value) {
        if (type == 'view') {
            return {"name":name,"type":type,"url":value};
        } else {
            return {"name":name,"type":type,"key":value};
        }
    }

    function find_root_index(id) {
        return $("#collapse" + id + " .navbar-nav").children().index($("#collapse" + id + " .current").parents("li"));
    }

    function find_sub_index(id) {
        return $("#collapse" + id + " .current").parents(".dropdown-menu").children().index($("#collapse" + id + " .current")) - 1;
    }

    function remove_item (id,obj) {
        var root_index = find_root_index(id);
        var sub_index = find_sub_index(id);
        if (sub_index >= 0) {
            menus[id].button[root_index].sub_button.remove(sub_index);
        } else {
            menus[id].button.remove(root_index);
        }
        var jsonstr = JSON.stringify(menus[id]);
        $("#collapse" + id + " #menu-code").val(jsonstr);
        str_to_menu(id,jsonstr);
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