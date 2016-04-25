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
                </div>
            </header>
            <div class="row">
                <div class="col-xs-12 col-lg-3">
                    <div class="admin-menu">
                        <dl>
                            <dt>公众号管理</dt>
                            <dd><a href="index.php/AdminOfficialNumber">公众号维护</a></dd>
                            <dd><a href="index.php/AdminOfficialNumber/add">增加公众号</a></dd>
                        </dl>
                        <dl>
                            <dt>素材管理</dt>
                            <dd><a href="index.php/AdminMaterial">素材列表</a></dd>
                        </dl>
                        <dl>
                            <dt>投票活动</dt>
                            <dd><a href="index.php/AdminVoteController">活动列表</a></dd>
                            <dd><a href="index.php/AdminVoteController/add">增加活动</a></dd>
                        </dl>
                    </div>
                </div>
                <div class="col-xs-12 col-lg-9">
                    <?=$content?>
                </div>
            </div>
            <footer class="footer">
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
