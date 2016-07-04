<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
?>
<style>
    .margin-top {margin-top:30px;}
    .menu-item {line-height: 25px;
        background: #ccc;
        padding: 5px;
    }
    .menu-item:hover {
        background:#ddd;
    }
    .menu-item-1 {
        background:#eee;
        text-indent:1em;
    }
    .menu-item-2 {
        background:#fff;
        text-indent:2em;
    }
</style>
<div class="row margin-top">
    <div class="col-xs-6">
        <div class="menu-item" data-content-id="0">微时光微信平台</div>
        <?php if ($menus) {?>
            <?php foreach ($menus as $menu) {?>
                <div  class="menu-item menu-item-<?=$menu['level']?>" data-content-id="<?=$menu['id']?>" onclick="edit_me('<?=$menu["id"]?>')"><?=$menu['menu_name']?></div>
            <?php }?>
        <?php }?>
    </div>
    <div class="col-xs-6 margin-top">
        <div class="form-group">
            <label>标题</label>
            <input type="text" class="form-control" id="name" />
        </div>

        <div class="form-group">
            <label>链接</label>
            <input type="text" class="form-control" id="url"/>
        </div>

        <div class="form-group">
            <label>上级目录</label>
            <select class="form-control" id="parent">
                <option value="0">微时光平台</option>
                <?php if ($menus) {?><?php foreach ($menus as $menu) {?>
                <option value="<?=$menu['id']?>"><?=$menu['menu_name']?></option>
                <?php }}?>
            </select>
        </div>

        <div class="form-group">
            <input type="hidden" id="id" />
            <input type="button" class="btn btn-default" value="确认" onclick="save_menu()">
        </div>
    </div>
</div>

<script>
    function save_menu() {
        var parent = $("#parent").val();
        var id = $("#id").val();
        var name = $("#name").val();
        var url = $("#url").val();
        $.ajax({
            url:"index.php/AdminNavController/save",
            dataType:"json",
            data:{id:id,name:name,parent:parent,url:url},
            success:function(data) {
                if (data.err) {
                    alert(data.err);
                } else {
                   window.location.href=window.location.href;
                }
            }
        });
    }

    function edit_me (id) {
        $.ajax({
            url:"index.php/AdminNavController/get",
            dataType:"json",
            data: {"id":id},
            success:function(data) {
                if (data.id) {
                    $("#parent").val(data.parent_id);
                    $("#id").val(data.id);
                    $("#name").val(data.menu_name);
                    $("#url").val(data.url);
                }
            }
        });
    }
</script>