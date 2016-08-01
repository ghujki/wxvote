<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport">
        <title><?=$title?></title>
        <base href="<?=base_url()?>" />
        <link rel="stylesheet" href="application/views/css/bootstrap-3.3.5.min.css">
        <link rel="stylesheet" href="application/views/css/admin.css" >
        <link rel="stylesheet" href="application/views/css/font-awesome.min.css" >
        <link rel="stylesheet" href="application/views/css/font-awesome-ie7.min.css">
    </head>
    <body>
        <div class="container">
            <header class="row">
                <div class="col-xs-12">
                    <div><h1>微时光微信管理系统</h1></div>
                    <div class="account-info">你好，<?php echo $this->session->userdata("wsg_user_name");?>&nbsp;
                        <a href="index.php/AdminAccountController/editAccount?id=<?php echo $this->session->userdata("wsg_user_id");?>">修改密码</a>&nbsp;
                        <a href="index.php/AdminController/logout">退出</a>
                    </div>
                    <form class="search-query">
                        <input type="text" placeholder="" name="query">
                        <button type="submit" value="查询">查询</button>
                    </form>                                     
                </div>
            </header>
            <div class="row">
                <div class="col-xs-12 col-lg-3 left-sidebar">
                    <div class="admin-menu">
                        <?php $menus = get_menu(); ?>
                        <?php foreach ($menus as $menu) {
                            if ($menu['account_id']) {
                            if ($menu['level'] == 1) {?>
                            <dt><?=$menu['menu_name']?></dt>
                            <?php } elseif ($menu['level'] == 2) {?>
                            <dd>
                                <a href="<?=$menu['url']?>"><?=$menu['menu_name']?></a>
                            </dd>
                            <?php }?>
                        <?php }}?>
                        <dl>
                            <dd><a href="index.php/AdminController/logout">退出</a></dd>
                        </dl>
                    </div>
                </div>
                <div class="col-xs-12 col-lg-9">
                    <?=$content?>
                </div>
            </div>
            <footer class="footer">
                <div class="scroll-tool">
                    <a href="javascript:window.scrollTo(0,0);" class="fa fa-angle-double-up" title="回到顶部">&nbsp;</a>
                    <a href="javascript:window.scrollTo(0,$(document.body).height());" class="fa fa-angle-double-down" title="到底部">&nbsp;</a>
                </div>
                <div class="text-center">
                    微时光微信管理系统
                </div>
            </footer>
        </div>
        <script src="application/views/js/jquery-1.11.3.min.js"></script>
        <script src="application/views/js/bootstrap-3.3.5.min.js"></script>
        <script src="application/views/js/admin_vote_edit.js"></script>
        <?php if ($jspaths):?>
        <?php foreach ($jspaths as $path):?>
            <script type="text/javascript" src="<?=$path?>"></script>
        <?php endforeach;?>
        <?php endif;?>
    </body>
</html>
