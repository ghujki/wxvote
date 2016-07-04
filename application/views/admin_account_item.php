<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
?>
<style>
    .menu-item {line-height:25px;height:25px;}
    .menu-item-1 {background:#ddd;text-indent:1em;}
    .menu-item-2 {background:#fff;text-indent:2em;}
</style>
<form action="index.php/AdminAccountController/dispatch" id="form1">
    <div class="row">
        <div class="col-xs-12">
            <h5>为<?=$account['username']?>分配权限</h5>
            <input type="hidden" name="account_id" value="<?=$account['id']?>" >
        </div>
    </div>
    <div class="row">
        <div class="col-xs-4">
            <h5>菜单访问权限分配</h5>
            <?php if ($menus) {
            foreach ($menus as $menu) {?>
             <div class="menu-item menu-item-<?=$menu['level']?>"><label for="menu_<?=$menu['id']?>">
                 <?=$menu['menu_name']?></label>
              <input type="checkbox" name="menu_id[]" id="menu_<?=$menu['id']?>" value="<?=$menu['id']?>" <?php if ($menu['account_id']) {echo "checked";}?> onchange="changed(this)" ></div>
            <?php }}?>
        </div>
        <div class="col-xs-8">
            <h5>公众号分配</h5>
            <?php if ($numbers) {?>
            <?php foreach ($numbers as $number) {?>
            <label for="number_<?=$number['id']?>"><?=$number['app_name']?></label><input type="checkbox" name="number_id[]" value="<?=$number['id']?>" id="number_<?=$number['id']?>"  <?php if ($number['account_id']) {echo "checked";}?> />
            <?php }}?>
        </div>
    </div>
</form>

<script>
    function changed(obj) {
        if (obj.checked) {
            var parent = null;
            obj = $(obj).parent(".menu-item-2");
            if ($(obj).prev(".menu-item-1").length > 0) {
                parent = $(obj).prev(".menu-item-1");
            } else if ($(obj).prevUntil(".menu-item-1").length > 0) {
                parent = $($(obj).prevUntil(".menu-item-1").get(0)).prev(".menu-item-1");
            }
            $(parent).find("input").attr("checked",true);
        }
    }
</script>